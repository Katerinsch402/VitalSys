@extends('adminlte::page')
@section('title', 'Editar Sala')

@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-door-open fa-lg mr-2" style="color:#17a2b8;"></i>
        <h1 class="font-weight-bold mb-0">Editar Sala</h1>
    </div>
@stop

@section('content')
<style>
    .card-form { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
    .card-form .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border-radius:16px 16px 0 0; padding:16px 24px; font-weight:600; }
    .form-control { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .form-label { font-weight:600; font-size:0.85rem; color:#555; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; display:block; }
</style>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-form">
            <div class="card-header">
                <i class="fas fa-edit mr-2"></i> Editar Sala
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('salas.update', $sala->id_sala) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Tipo de Sala</label>
                        <input type="text" class="form-control @error('tipo_sala') is-invalid @enderror" name="tipo_sala" value="{{ $sala->tipo_sala }}" placeholder="Ej: Consulta General" required>
                        @error('tipo_sala')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Número de Sala</label>
                        <input type="text" class="form-control @error('num_sala') is-invalid @enderror" name="num_sala" value="{{ $sala->num_sala }}" placeholder="Ej: 101" required>
                        @error('num_sala')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('salas.index') }}" class="btn btn-secondary" style="border-radius:8px;">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#17a2b8,#138496);border:none;border-radius:8px;">
                            <i class="fas fa-save mr-1"></i> Actualizar Sala
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop