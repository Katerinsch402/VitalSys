<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Sala;
use App\Models\TipoConsulta;
use Laracasts\Flash\Flash;

class CitaController extends Controller
{
    public function index() {

        Cita::markOverdueAsConcluded();

        $medicos = Medico::all();
        $pacientes = Paciente::all();
        $salas = Sala::all();
        $tipoConsultas = TipoConsulta::all();
        $citas = Cita::with(['medico', 'paciente', 'sala', 'tipoConsulta'])->orderBy('fec_inicio', 'desc')->get();
        
        return view('citas.index', compact('medicos', 'pacientes', 'salas', 'tipoConsultas', 'citas'));
    }

    function show(Request $request) {

        $id = $request->query('medico_id');

        Cita::markOverdueAsConcluded();

        if($id){
            $citas = Cita::where('medico_id', $id)->get();
        }else{
            $citas = Cita::where('medico_id', 1)->get();
        }

        $citasNuevo = [];

        foreach ($citas as $clave => $valor) {
            $doctor = Medico::find($valor->medico_id);
            $paciente = Paciente::find($valor->paciente_id);
            $sala = Sala::find($valor->sala_id);
            $tipoConsulta = TipoConsulta::find($valor->tipo_consulta_id);

            // Si algún dato relacionado no existe, saltar esta cita
            if (!$doctor || !$paciente || !$sala || !$tipoConsulta) {
                continue;
            }

            $fec_ini = date_format(new \DateTime($valor->fec_inicio),"d/m/Y H:i");
            $fec_fin = date_format(new \DateTime($valor->fec_fin),"d/m/Y H:i");
            
            $citasNuevo[$clave] = [
                'title' => $valor->id_cita.' - '.$paciente->nombre . ' ' . $paciente->apellido,
                'start' => $valor->fec_inicio,
                'end' => $valor->fec_fin,
                'description' => "<span style='text-align: start;'>
                    <b>Estado: </b>".$valor->estado."<br><br>
                    <b>Fecha inicio: </b>".$fec_ini."<br>
                    <b>Fecha fin: </b>".$fec_fin."<br>
                    <b>Doctor:</b> ".$doctor->nombre."<br>
                    <b>N° de Sala:</b> ".$sala->tipo_sala." - ".$sala->num_sala."<br>
                    <b>Tipo de Consulta:</b> ".$tipoConsulta->descripcion."<br>
                    <b>Observaciones:</b> ".$valor->observaciones."
                </span>",
                'id' => $valor->id_cita,
                'estado' => $valor->estado
            ];
        }

        if(empty($citasNuevo)){
            $citasNuevo = [];
        }

        return response()->json($citasNuevo);
    }

    public function store(Request $request) {
        try {
            \Log::info('Recibido store de cita:', $request->all());
            
            // Validación simplificada
            $validated = $request->validate([
                'medico_id' => 'required|numeric',
                'paciente_id' => 'required|numeric',
                'fec_inicio' => 'required|date_format:Y-m-d\TH:i',
                'fec_fin' => 'required|date_format:Y-m-d\TH:i',
                'sala_id' => 'required|numeric',
                'tipo_consulta_id' => 'required|numeric',
                'observaciones' => 'required|string|max:255',
            ], [
                'medico_id.required' => 'Debes seleccionar un médico para la cita.',
                'medico_id.numeric' => 'El médico debe ser válido.',
                'paciente_id.required' => 'Debes seleccionar un paciente para la cita.',
                'paciente_id.numeric' => 'El paciente debe ser válido.',
                'fec_inicio.required' => 'La fecha y hora de inicio son obligatorias.',
                'fec_inicio.date_format' => 'El formato de fecha de inicio es inválido.',
                'fec_fin.required' => 'La fecha y hora de fin son obligatorias.',
                'fec_fin.date_format' => 'El formato de fecha de fin es inválido.',
                'sala_id.required' => 'Debes seleccionar una sala para la cita.',
                'sala_id.numeric' => 'La sala debe ser válida.',
                'tipo_consulta_id.required' => 'Debes seleccionar un tipo de consulta.',
                'tipo_consulta_id.numeric' => 'El tipo de consulta debe ser válido.',
                'observaciones.required' => 'Las observaciones son obligatorias.',
                'observaciones.string' => 'Las observaciones deben ser texto válido.',
                'observaciones.max' => 'Las observaciones no pueden superar los 255 caracteres.',
            ]);

            // Guardar siempre como cita pendiente al registrar
            $validated['estado'] = 'Pendiente';
            $validated['fec_fin'] = \Carbon\Carbon::parse($validated['fec_inicio'])->copy()->addMinutes(15)->format('Y-m-d\TH:i');

            $cita = Cita::create($validated);

            if($cita){
                \Log::info('Cita creada:', $cita->toArray());

                if ($request->expectsJson()) {
                    return response()->json('Cita creada correctamente', 200);
                }

                Flash::success('¡Cita creada correctamente! La cita se ha registrado en el sistema.');
                return redirect()->route('citas.index');
            }else{
                \Log::error('Error al crear cita');

                if ($request->expectsJson()) {
                    return response()->json('Error al crear la cita.', 500);
                }

                Flash::error('Error al crear la cita.');
                return redirect()->back();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Errores de validación:', $e->errors());

            if ($request->expectsJson()) {
                return response()->json($e->errors(), 422);
            }

            Flash::error('Datos incompletos o inválidos. Por favor, completa todos los campos.');
            return redirect()->back()->withInput();
        } catch (\Throwable $th) {
            \Log::error('Error al crear cita:', ['message' => $th->getMessage(), 'file' => $th->getFile(), 'line' => $th->getLine()]);

            if ($request->expectsJson()) {
                return response()->json($th->getMessage(), 500);
            }

            Flash::error('Error inesperado al crear la cita: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id) {
        $cita = Cita::find($id);

        return response()->json($cita);
    }

    public function atendido($id) {
        $cita = Cita::find($id);

        if(!$cita){
            return response()->json('Cita no encontrada', 404);
        }

        $cita->estado = 'atendido';
        $cita->save();

        return response()->json('Cita actualizada correctamente');
    }

    public function update(Request $request) {
        try {
            $status = 0;
            $result = '';

            $cita = Cita::find($request->id_cita);

            if(!$cita){
                return response()->json('Cita no encontrada', 404);
            }

            $data = $request->all();
            if (!empty($data['fec_inicio'])) {
                $data['fec_fin'] = \Carbon\Carbon::parse($data['fec_inicio'])->copy()->addMinutes(15)->format('Y-m-d\TH:i');
            }

            $cita->fill($data);

            if($cita->save()){
                $status = 200;
                $result = 'Cita actualizada correctamente';
            }else{
                $status = 500;
                $result = 'Error al actualizar la cita';
            }
            return response()->json($result, $status);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function destroy() {

    }

    public function concluir($id) {
        $cita = Cita::find($id);

        if(!$cita){
            return response()->json('Cita no encontrada', 404);
        }

        $cita->estado = 'Concluido';
        $cita->save();

        return response()->json('Cita marcada como concluida correctamente');
    }

    public function cancelar($id) {
        $cita = Cita::find($id);

        if(!$cita){
            return response()->json('Cita no encontrada', 404);
        }

        $cita->estado = 'Cancelado';
        $cita->save();

        return response()->json('Cita cancelada correctamente');
    }
}