<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class CiudadController extends Controller
{
    /**
     * Muestra una lista de las ciudades.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $ciudades = Ciudad::where('nombre', 'like', '%' . $request->buscarpor . '%')->paginate(10);
        $departamentos = Departamento::all();
        return view('ciudades.index', compact('ciudades', 'departamentos'));
    }

    /**
     * Muestra el formulario para crear una nueva ciudad.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        //return view('ciudades.create');
    }

    /**
     * Almacena una nueva ciudad en la base de datos.
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

        $ciudades = request()->except('_token');
        Ciudad::insert($ciudades);
        Flash::success('Creado correctamente');
        return redirect(route('ciudades.index'));
    }

    /**
     * Muestra la Ciudad especificada.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        /* $ciudades = Ciudad::findOrFail($id);
        return view('ciudades.show', compact('ciudades')); */
    }

    /**
     * Muestra el formulario para editar la ciudad especificada.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        /* $ciudades = Ciudad::findOrFail($id);
        return view('ciudades.edit', compact('ciudades')); */
    }

    /**
     * Actualiza la ciudad especificada en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $ciudades = request()->except(['_token', '_method']);
        Ciudad::where('id_ciudad', '=', $id)->update($ciudades);
        Flash::success('Actualizado correctamente');
        return redirect(route('ciudades.index'));
    }

    /**
     * Elimina la Ciudad especificada de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Ciudad::destroy($id);
        Flash::error('Eliminado correctamente');
        return redirect('ciudades');
    }
}
