@extends('adminlte::page')
@section('title', 'Salas')

@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-door-open fa-lg mr-2" style="color:#17a2b8;"></i>
        <h1 class="font-weight-bold mb-0">Gestión de Salas</h1>
    </div>
@stop

@section('content')
<style>
    .card-main { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
    .card-main .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; padding:16px 24px; font-weight:600; }
    .form-control { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .table thead th { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border:none; padding:12px 16px; font-size:0.85rem; text-transform:uppercase; }
    .table tbody tr:hover { background:#f0fafc; }
    .table tbody td { padding:12px 16px; vertical-align:middle; border-color:#e9f5f8; }
    .btn-nuevo { background:linear-gradient(135deg,#17a2b8,#138496); border:none; border-radius:8px; padding:8px 20px; color:white; font-weight:600; }
</style>

<div class="card card-main">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list mr-2"></i> Lista de Salas</span>
        <a href="{{ route('salas.create') }}" class="btn-nuevo">
            <i class="fas fa-plus mr-1"></i> Nueva Sala
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo de Sala</th>
                        <th>Número de Sala</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salas as $sala)
                    <tr>
                        <td>{{ $sala->id_sala }}</td>
                        <td><i class="fas fa-door-open mr-1" style="color:#17a2b8;"></i> {{ $sala->tipo_sala }}</td>
                        <td><span style="background:#e8f8fb;color:#17a2b8;padding:3px 10px;border-radius:10px;font-size:0.8rem;font-weight:600;">{{ $sala->num_sala }}</span></td>
                        <td>
                            <a class="btn btn-sm btn-warning mr-1" href="{{ route('salas.edit', $sala->id_sala) }}">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('salas.delete', $sala->id_sala) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta sala?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop