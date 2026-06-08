<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class MedicoController extends Controller
{
    /**
     * Muestra una lista de recursos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $medicos = Medico::where('nombre', 'like', '%' . $request->buscarpor . '%')->paginate(10);
        $especialidades = Especialidad::all();
        return view('medicos.index', compact('medicos', 'especialidades'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* $especialidades = Especialidad::all();
        return view('medicos.create', compact('especialidades')); */
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* $rules = [
            'nombre' => 'required|string',
            'ci' => 'required|string|unique:medicos',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'registro' => 'required|string',
            'especialidad_id' => 'required'
        ];

        $mensaje = [
            'required' => 'El :attribute es requerido',
            'nombre.required' => 'El nombre del doctor es requerido',
            'ci.required' => 'El ci del doctor es requerido',
            'ci.unique' => 'El ci del doctor ya existe',
            'email.required' => 'El email del doctor es requerido',
            'telefono.required' => 'El teléfono del doctor es requerido',
            'registro.required' => 'El registro del doctor es requerido',
            'especialidad_id.required' => 'Debe seleccionar una especialidad'
        ];
        $this->validate($request, $rules, $mensaje); */

        $medicos = request()->except('_token');
        Medico::insert($medicos);
        Flash::success('Creado correctamente');
        return redirect(route('medicos.index'));
    }

    /**
     * Muestra el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* $medicos = Medico::findOrFail($id); //buscar los registros según id y los actualiza
        return view('medicos.show', compact('medicos')); */
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* $medicos = Medico::findOrFail($id); //buscar los registros segun id y los actualiza
        $especialidades = Especialidad::all();
        return view('medicos.edit', compact('medicos', 'especialidades')); */
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //si el id existe lo actualiza y muestra un mensaje de que el proceso se completo correctamente
        $medicos = request()->except(['_token', '_method']);
        $especialidades = Especialidad::all();
        Medico::where('id_medico', '=', $id)->update($medicos);
        Flash::success('Editado correctamente');
        return redirect(route('medicos.index'));
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Medico::destroy($id); //eliminar registro de la base de datos.
        Flash::error('Eliminado correctamente');
        return redirect('medicos'); // al eliminar, redirecciona a la pantalla de inicio.
    }
}
