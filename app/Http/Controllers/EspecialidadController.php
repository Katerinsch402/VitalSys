<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class EspecialidadController extends Controller
{
    /**
     * Muestra una lista de las especialidades.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $especialidades = Especialidad::where('nombre', 'like', '%' . $request->buscarpor . '%')->paginate(10);
        return view('especialidades.index', compact('especialidades'));
    }

    /**
     * Muestra el formulario para crear una nueva especialidad.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        //return view('especialidades.create');
    }

    /**
     * Almacena una nueva especialidad en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /* $rules = [
            'nombre' => 'required|string',
        ];

        $mensaje = [
            'required' => 'El :attribute es requerido',
        ];
        $this->validate($request, $rules, $mensaje); */

        $especialidades = request()->except('_token');
        Especialidad::insert($especialidades);
        Flash::success('Creado correctamente');
        return redirect(route('especialidades.index'));
    }

    /**
     * Muestra la especialidad especificada.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        /* $especialidades = Especialidad::findOrFail($id);
        return view('especialidades.show', compact('especialidades')); */
    }

    /**
     * Muestra el formulario para editar la especialidad especificada.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        /* $especialidades = Especialidad::findOrFail($id);
        return view('especialidades.edit', compact('especialidades')); */
    }

    /**
     * Actualiza la especialidad especificada en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $especialidades = request()->except(['_token', '_method']);
        Especialidad::where('id_especialidad', '=', $id)->update($especialidades);
        Flash::success('Actualizado correctamente');
        return redirect(route('especialidades.index'));
    }

    /**
     * Elimina la especialidad especificada de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Especialidad::destroy($id);
        Flash::error('Eliminado correctamente');
        return redirect('especialidades');
    }
}
