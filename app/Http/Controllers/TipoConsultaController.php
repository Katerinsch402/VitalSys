<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TipoConsulta;
use Laracasts\Flash\Flash;

class TipoConsultaController extends Controller
{
    /**
     * Muestra una lista de las tipos_de_consulta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $tipos_de_consulta = TipoConsulta::where('descripcion', 'like', '%' . $request->buscarpor . '%')->paginate(5);
        return view('tipos-consulta.index', compact('tipos_de_consulta'));
    }

    public function create()
    {
        //return view('tipos_de_enfermedad.create')
    }

    public function store(Request $request)
    {
        $tipos_de_consulta = request()->except('_token');
        TipoConsulta::insert($tipos_de_consulta);
        Flash::success('Creado correctamente');
        return redirect(route('tipos-consulta.index'));
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
            $tipos_de_consulta = request()->except(['_token', '_method']);
            TipoConsulta::where('id_tipo_consulta', '=', $id)->update($tipos_de_consulta);
            Flash::success('Actualizado correctamente');
            return redirect(route('tipos-consulta.index'));
        }

        /**
         * Elimina la especialidad especificada de la base de datos.
         *
         * @param  int  $id
         * @return \Illuminate\Http\RedirectResponse
         */
        public function destroy($id)
        {
            TipoConsulta::destroy($id);
            Flash::error('Eliminado correctamente');
            return redirect('tipos-consulta');
        }
}
