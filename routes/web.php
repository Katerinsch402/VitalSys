<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\DepaCiudadController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\TipoConsultaController;
use App\Http\Controllers\UsuarioController;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---- CITAS -----
Route::get('/citas', [CitaController::class, 'index'])->name('citas.index')->middleware(['auth', 'verified']);
Route::post('/citas', [CitaController::class, 'store'])->name('citas.store')->middleware(['auth', 'verified']);
Route::get('/citas/ver', [CitaController::class, 'show'])->middleware(['auth', 'verified']);
Route::get('/citas/editar/{id}', [CitaController::class, 'edit'])->middleware(['auth', 'verified']);
Route::get('/citas/atendido/{id}', [CitaController::class, 'atendido'])->middleware(['auth', 'verified']);
Route::get('/citas/concluir/{id}', [CitaController::class, 'concluir'])->middleware(['auth', 'verified']);
Route::get('/citas/cancelar/{id}', [CitaController::class, 'cancelar'])->middleware(['auth', 'verified']);
Route::post('/citas/actualizar', [CitaController::class, 'update'])->middleware(['auth', 'verified']);
Route::get('/citas/reportes', [PacientesController::class, 'reportes'])->name('citas.reportes')->middleware(['auth', 'verified']);

// ---- PACIENTES -----
Route::get('/pacientes', [PacientesController::class, 'index'])->middleware(['auth', 'verified'])->name('pacientes.index');
Route::get('/registro-paciente', [PacientesController::class, 'nuevo'])->middleware(['auth', 'verified'])->name('registro-paciente');
Route::get('/pacientes/historial/{id}', [PacientesController::class, 'historialPdf'])->middleware(['auth', 'verified'])->name('pacientes.historial');
Route::get('/pacientes/edit/{id}', [PacientesController::class, 'edit'])->middleware(['auth', 'verified'])->name('pacientes.edit');
Route::post('/guardar-paciente', [PacientesController::class, 'crear'])->middleware(['auth', 'verified'])->name('guardar-paciente');
Route::put('/pacientes/edit/{id}', [PacientesController::class, 'actualizar'])->middleware(['auth', 'verified'])->name('pacientes.actualizar');
Route::delete('/pacientes/delete/{id}', [PacientesController::class, 'eliminar'])->middleware(['auth', 'verified'])->name('pacientes.delete');
Route::get('/registro-paciente-e', [PacientesController::class, 'error'])->name('registro-paciente-e');
Route::get('/obtener-ciudades/{departamento}', [PacientesController::class, 'obtenerCiudades']);

// Reportes
Route::get('/reportes', [PacientesController::class, 'reportes'])->name('pacientes.reportes')->middleware(['auth', 'verified']);

// ----- MIDDLEWARE POR ROL DE USUARIO -----
Route::group(['middleware' => ['role:admin']], function () {

    // ---- SALAS -----
    Route::get('/registro-sala', [SalaController::class, 'index'])->name('salas.index')->middleware(['auth', 'verified']);
    Route::get('/salas/crear', [SalaController::class, 'crear'])->name('salas.create')->middleware(['auth', 'verified']);
    Route::post('/salas/store', [SalaController::class, 'store'])->name('salas.store')->middleware(['auth', 'verified']);
    Route::get('/salas/edit/{id}', [SalaController::class, 'editar'])->name('salas.edit')->middleware(['auth', 'verified']);
    Route::get('/salas/show/{id}', [SalaController::class, 'show'])->name('salas.show')->middleware(['auth', 'verified']);
    Route::put('/salas/edit/{id}', [SalaController::class, 'actualizar'])->name('salas.update')->middleware(['auth', 'verified']);
    Route::delete('/salas/delete/{id}', [SalaController::class, 'eliminar'])->name('salas.delete')->middleware(['auth', 'verified']);

    // ---- ESPECIALIDADES -----
    Route::resource('especialidades', EspecialidadController::class)->middleware(['auth', 'verified']);

    // ---- MEDICOS -----
    Route::resource('medicos', MedicoController::class)->middleware(['auth', 'verified']);

    // ---- DEPARTAMENTOS -----
    Route::resource('departamentos', DepartamentoController::class)->middleware(['auth', 'verified']);

    // ---- TIPOS DE CONSULTAS -----
    Route::resource('tipos-consulta', TipoConsultaController::class)->middleware(['auth', 'verified']);

    // ---- CIUDADES -----
    Route::resource('ciudades', CiudadController::class)->middleware(['auth', 'verified']);

    // ---- USUARIOS -----
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index')->middleware(['auth', 'verified']);
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store')->middleware(['auth', 'verified']);
    Route::post('/usuarios/update', [UsuarioController::class, 'update'])->name('usuarios.update')->middleware(['auth', 'verified']);
    Route::post('/usuarios/pass-change', [UsuarioController::class, 'passChange'])->name('usuarios.passChange')->middleware(['auth', 'verified']);
    Route::post('/usuarios/cambiarEstado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.cambiarEstado')->middleware(['auth', 'verified']);
});

require __DIR__ . '/auth.php';