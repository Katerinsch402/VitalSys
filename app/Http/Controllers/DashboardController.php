<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Sala;

class DashboardController extends Controller
{
    public function index()
    {
        Cita::markOverdueAsConcluded();

        $totalPacientes  = Paciente::count();
        $totalMedicos    = Medico::count();
        $totalSalas      = Sala::count();
        $citasHoy        = Cita::whereDate('fec_inicio', today())->count();
        $citasPendientes = Cita::where('estado', 'Pendiente')->count();
        $citasAtendidas  = Cita::where('estado', 'atendido')->count();
        $citasRecientes  = Cita::with(['medico', 'paciente'])
                            ->orderBy('fec_inicio', 'desc')
                            ->take(8)
                            ->get();

        return view('dashboard', compact(
            'totalPacientes',
            'totalMedicos',
            'totalSalas',
            'citasHoy',
            'citasPendientes',
            'citasAtendidas',
            'citasRecientes'
        ));
    }

}