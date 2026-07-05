<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Paciente;
use App\Models\Ciudad;
use App\Models\TipoDeEnfermedad;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class PacientesController extends Controller
{
    private function buildPacientePayload(array $data): array
    {
        $availableColumns = Schema::getColumnListing('pacientes');
        $payload = [];
        $aliases = [
            'tiene_IPS' => 'tiene_ips',
        ];

        foreach ($data as $key => $value) {
            $column = $aliases[$key] ?? $key;

            if (in_array($column, $availableColumns, true)) {
                $payload[$column] = $value;
            }
        }

        return $payload;
    }

    private function getTipoEnfermedades()
    {
        try {
            if (!class_exists(TipoDeEnfermedad::class)) {
                return collect([]);
            }

            $candidateTables = ['tipo_de_enfermedades', 'tipo_enfermedades', 'tipos_de_enfermedad', 'tipos_enfermedad'];
            if (!collect($candidateTables)->contains(fn ($table) => Schema::hasTable($table))) {
                return collect([]);
            }

            return TipoDeEnfermedad::query()->get();
        } catch (\Throwable $e) {
            return collect([]);
        }
    }

    public function index()
    {
        $pacientes = Paciente::all();
        return view('pacientes.index', compact('pacientes'));
    }

    public function nuevo(){
        $paciente = DB::table("pacientes")->latest()->first();
        $departamentos = Departamento::all();
        $enfermedad = $this->getTipoEnfermedades();
        return view('pacientes.RegistroPaciente', compact('paciente','departamentos','enfermedad'));
    }

    public function error(){
        $datos = session('datos');
        $ciudad = Ciudad::where('departamento_id', $datos['departamento'])->get();
        $depa = $datos["departamento"];
        $departamentos = Departamento::all();
        $enfermedad = $this->getTipoEnfermedades();
        $paciente = DB::table("pacientes")->latest()->first();
        return view('pacientes.Registroerror', compact('paciente','datos','departamentos','enfermedad','depa','ciudad'));
    }

    public function obtenerCiudades($departamentoId)
    {
        try {
            $ciudades = Ciudad::where('departamento_id', $departamentoId)->get();
            return response()->json($ciudades);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener ciudades: ' . $e->getMessage()], 500);
        }
    }

    public function crear(Request $request){
        $request->validate([
            'cod_paciente' => 'required|string',
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'num_doc' => 'required|string',
            'departamento' => 'required',
            'ciudad' => 'required',
            'direccion' => 'required|string',
            'edad' => 'required|numeric',
            'sexo' => 'required',
            'tiene_IPS' => 'required',
        ], [
            'cod_paciente.required' => 'El código del paciente es obligatorio.',
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'num_doc.required' => 'La cédula es obligatoria.',
            'departamento.required' => 'El departamento es obligatorio.',
            'ciudad.required' => 'La ciudad es obligatoria.',
            'direccion.required' => 'La dirección es obligatoria.',
            'edad.required' => 'La edad es obligatoria.',
            'edad.numeric' => 'La edad debe ser un número.',
            'sexo.required' => 'El sexo es obligatorio.',
            'tiene_IPS.required' => 'El campo IPS es obligatorio.',
        ]);

        $datos = $request->all();
        $paciente = DB::table("pacientes")->latest()->first();
        $docu = $request->input('num_doc');
        $codi = $request->input('cod_paciente');
        $doc = DB::table("pacientes")->where('num_doc', '=', $docu)->get()->first();
        $cod = DB::table("pacientes")->where('cod_paciente', '=', $codi)->get()->first();

        if($doc != null && $cod != null){
            return redirect()->route('registro-paciente-e')->with(['mensaje' => 'El numero de documento y el codigo ya existen, por favor reviselo', 'datos' => $datos]);
        } else if($doc){
            return redirect()->route('registro-paciente-e')->with(['mensaje' => 'El numero de documento ya existe, por favor reviselo', 'datos' => $datos]);
        } else if($cod){
            return redirect()->route('registro-paciente-e')->with(['mensaje' => 'El codigo de paciente ya existe, por favor reviselo', 'datos' => $datos]);
        } else {
            $payload = $this->buildPacientePayload($request->all());
            $pacienteCreado = Paciente::create($payload);

            return redirect()->route('pacientes.index')->with('mensaje', 'Se guardo correctamente!');
        }
    }

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    }

    public function show(string $id)
    {
        //
    }

    public function actualizar(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $payload = $this->buildPacientePayload($request->all());
        $paciente->update($payload);
        return redirect()->route('pacientes.index')->with('mensaje', 'Se guardo correctamente!');
    }

    public function eliminar($id)
    {
        $paciente = Paciente::find($id);
        $paciente->delete();
        return redirect()->route('pacientes.index');
    }

    public function reportes(){
        $pacientes = DB::table('pacientes')->get();
        return view('pacientes.reportes', compact('pacientes'));
    }

    public function historialPdf($id)
    {
        $paciente = Paciente::with([
            'citas.medico',
            'citas.sala',
            'citas.tipoConsulta'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pacientes.historial-pdf', compact('paciente'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('historial-'.$paciente->num_doc.'.pdf');
    }
}