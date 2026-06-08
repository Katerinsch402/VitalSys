<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class DepartamentoController extends Controller
{
    /**
     * Muestra una lista de las departamentos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $departamentos = Departamento::where('nombre', 'like', '%' . $request->buscarpor . '%')->paginate(10);
        return view('departamentos.index', compact('departamentos'));
    }

    /**
     * Muestra el formulario para crear una nueva departamento.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        //return view('departamentos.create');
    }

    /**
     * Almacena una nueva departamento en la base de datos.
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

        $departamentos = request()->except('_token');
        Departamento::insert($departamentos);
        Flash::success('Creado correctamente');
        return redirect(route('departamentos.index'));
    }

    /**
     * Muestra la departamento especificada.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        /* $departamentos = Departamento::findOrFail($id);
        return view('departamentos.show', compact('departamentos')); */
    }

    /**
     * Muestra el formulario para editar la departamento especificada.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        /* $departamentos = Departamento::findOrFail($id);
        return view('departamentos.edit', compact('departamentos')); */
    }

    /**
     * Actualiza la departamento especificada en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $departamentos = request()->except(['_token', '_method']);
        Departamento::where('id_departamento', '=', $id)->update($departamentos);
        Flash::success('Actualizado correctamente');
        return redirect(route('departamentos.index'));
    }

    /**
     * Elimina la departamento especificada de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Departamento::destroy($id);
        Flash::error('Eliminado correctamente');
        return redirect('departamentos');
    }
}
