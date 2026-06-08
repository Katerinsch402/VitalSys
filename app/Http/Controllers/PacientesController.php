<?php


namespace App\Http\Controllers;


use DB;
use App\Models\Paciente;
use App\Models\Ciudad;
use App\Models\TipoDeEnfermedad;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;


class PacientesController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::all();
        return view('pacientes.index', compact('pacientes'));
    }


    public function nuevo(){
        $paciente = DB::table("pacientes")->latest()->first();
        $departamentos = Departamento::all();
        $enfermedad = TipoDeEnfermedad::all();
        return view('pacientes.RegistroPaciente', compact('paciente','departamentos','enfermedad'));
    }


    public function error(){
        $datos = session('datos');
        $ciudad = Ciudad::where('departamento_id', $datos['departamento'])->get();
        $depa = $datos["departamento"];
        $departamentos = Departamento::all();
        $enfermedad = TipoDeEnfermedad::all();
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
            'tipo_enfermedad' => 'required|array|min:1',
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
            'tipo_enfermedad.required' => 'Debe seleccionar al menos un tipo de enfermedad.',
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
            $opciones = $request->input('tipo_enfermedad');
            Paciente::create([
                'cod_paciente' => $request->input('cod_paciente'),
                'nombre'       => $request->input('nombre'),
                'apellido'     => $request->input('apellido'),
                'num_doc'      => $request->input('num_doc'),
                'ciudad'       => $request->input('ciudad'),
                'departamento' => $request->input('departamento'),
                'direccion'    => $request->input('direccion'),
                'edad'         => $request->input('edad'),
                'sexo'         => $request->input('sexo'),
                'tiene_ips'    => $request->input('tiene_IPS'),
                'diagnostico'  => $request->input('diagnostico'),
                'comentario'   => $request->input('comentario'),
            ]);


            foreach ($opciones as $op){
                if($paciente){
                    DB::table('paciente_tipo_enfermedad')->insert([
                        'paciente_id'       => $paciente->id_paciente + 1,
                        'tipo_enfermedad_id' => $op
                    ]);
                } else {
                    DB::table('paciente_tipo_enfermedad')->insert([
                        'paciente_id'       => 1,
                        'tipo_enfermedad_id' => $op
                    ]);
                }
            }


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
        $paciente = Paciente::find($id);
        $paciente->update([
            'cod_paciente' => $request->input('cod_paciente'),
            'nombre'       => $request->input('nombre'),
            'apellido'     => $request->input('apellido'),
            'num_doc'      => $request->input('num_doc'),
            'ciudad'       => $request->input('ciudad'),
            'departamento' => $request->input('departamento'),
            'direccion'    => $request->input('direccion'),
            'edad'         => $request->input('edad'),
            'sexo'         => $request->input('sexo'),
            'tiene_ips'    => $request->input('tiene_IPS'),
            'comentario'   => $request->input('comentario'),
        ]);
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
            'tiposDeEnfermedad',
            'citas.medico',
            'citas.sala',
            'citas.tipoConsulta'
        ])->findOrFail($id);


        $pdf = Pdf::loadView('pacientes.historial-pdf', compact('paciente'));
        $pdf->setPaper('A4', 'portrait');


        return $pdf->download('historial-'.$paciente->num_doc.'.pdf');
    }
}