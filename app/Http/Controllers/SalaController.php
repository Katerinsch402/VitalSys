<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Sala;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class SalaController extends Controller
{
    public function index()
    {
        $salas = Sala::all();
        return view('salas.index', compact('salas'));
    }
    public function crear()
    {
        return view('salas.create');
    }
    public function store(Request $request)
    {
        Sala::create([
            'tipo_sala' => $request->input('tipo_sala'),
            'num_sala' => $request->input('num_sala'),
        ]);

        Flash::success('Se guardó correctamente!');
        return redirect()->route('salas.index');
    }

    public function editar($id)
    {
        $sala = Sala::find($id);
        return view('salas.edit', compact('sala'));
    }
    public function show($id)
    {
        $sala = Sala::find($id);
        return view('salas.show', compact('sala'));
    }

    public function actualizar(Request $request, $id)
    {
        $sala = Sala::find($id);
        $sala->update([
            'tipo_sala' => $request->input('tipo_sala'),
            'num_sala' => $request->input('num_sala'),
        ]);

        Flash::success('Se actualizó correctamente!');
        return redirect()->route('salas.show', ['id' => $sala->id_sala]);

    }

    public function eliminar($id)
    {
        $sala = Sala::find($id);
        $sala->delete();

        Flash::success('Se eliminó correctamente!');
        return redirect()->route('salas.index');
    }
}

