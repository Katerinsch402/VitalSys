@extends('adminlte::page')
@section('title', 'Editar Paciente')

@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-hospital-user fa-lg mr-2" style="color:#17a2b8;"></i>
        <h1 class="font-weight-bold mb-0">Editar Paciente</h1>
    </div>
@stop

@section('content')
<style>
    .card-form { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
    .card-form .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border-radius:16px 16px 0 0; padding:16px 24px; font-weight:600; }
    .form-control, .custom-select { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus, .custom-select:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .form-label { font-weight:600; font-size:0.85rem; color:#555; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; display:block; }
    .section-divider { border:none; border-top:2px solid #e9f5f8; margin:20px 0; }
    .section-title { font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#17a2b8; margin-bottom:15px; }
    .btn-guardar { background:linear-gradient(135deg,#17a2b8,#138496); border:none; border-radius:8px; padding:10px 30px; font-weight:600; color:white; }
    .btn-guardar:hover { background:linear-gradient(135deg,#138496,#0f6674); }
</style>

<div class="card card-form">
    <div class="card-header">
        <i class="fas fa-edit mr-2"></i> Editando Paciente: <strong>{{ $paciente->nombre }} {{ $paciente->apellido }}</strong>
    </div>
    <div class="card-body p-4">

        @if(session('mensaje'))
            <div class="alert alert-success border-0" style="border-radius:8px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('mensaje') }}
            </div>
        @endif

        <form method="POST" action="{{ route('pacientes.actualizar', $paciente->id_paciente) }}">
            @method('PUT')
            @csrf

            {{-- IDENTIFICACIÓN --}}
            <p class="section-title"><i class="fas fa-id-card mr-1"></i> Identificación</p>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Código del Paciente</label>
                    <input type="text" name="cod_paciente" class="form-control" value="{{ $paciente->cod_paciente }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cédula de Identidad</label>
                    <input type="text" name="num_doc" class="form-control" value="{{ $paciente->num_doc }}" required>
                </div>
            </div>

            <hr class="section-divider">

            {{-- DATOS PERSONALES --}}
            <p class="section-title"><i class="fas fa-user mr-1"></i> Datos Personales</p>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nombre(s)</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $paciente->nombre }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Apellido(s)</label>
                    <input type="text" name="apellido" class="form-control" value="{{ $paciente->apellido }}" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Edad</label>
                    <input type="text" name="edad" class="form-control" value="{{ $paciente->edad }}" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Sexo</label>
                    <select name="sexo" class="form-control" required>
                        <option value="masculino" {{ $paciente->sexo == 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ $paciente->sexo == 'femenino' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
            </div>

            <hr class="section-divider">

            {{-- UBICACIÓN --}}
            <p class="section-title"><i class="fas fa-map-marker-alt mr-1"></i> Ubicación</p>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="departamento" class="form-control" value="{{ $paciente->departamento }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Ciudad</label>
                    <input type="text" name="ciudad" class="form-control" value="{{ $paciente->ciudad }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ $paciente->direccion }}" required>
                </div>
            </div>

            <hr class="section-divider">

            {{-- INFORMACIÓN MÉDICA --}}
            <p class="section-title"><i class="fas fa-heartbeat mr-1"></i> Información Médica</p>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tiene IPS</label>
                    <select name="tiene_IPS" class="form-control" required>
                        <option value="SI" {{ strtoupper($paciente->tiene_ips) == 'SI' ? 'selected' : '' }}>SI</option>
                        <option value="NO" {{ strtoupper($paciente->tiene_ips) == 'NO' ? 'selected' : '' }}>NO</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Comentario</label>
                    <textarea name="comentario" rows="3" class="form-control" placeholder="Comentarios adicionales...">{{ $paciente->comentario }}</textarea>
                </div>
            </div>

            <hr class="section-divider">

            <div class="d-flex justify-content-end">
                <a href="/pacientes" class="btn btn-secondary mr-2" style="border-radius:8px; padding:10px 24px;">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-guardar text-white">
                    <i class="fas fa-save mr-1"></i> Actualizar Paciente
                </button>
            </div>
        </form>
    </div>
</div>
@stop