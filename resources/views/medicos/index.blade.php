@extends('adminlte::page')
@section('title', 'Doctores')

@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-user-md fa-lg mr-2" style="color:#17a2b8;"></i>
        <h1 class="font-weight-bold mb-0">Gestión de Doctores</h1>
    </div>
@stop

@section('content')
<style>
    .card-main { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
    .card-main .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; padding:16px 24px; font-weight:600; }
    .form-control, .custom-select { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus, .custom-select:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .form-label { font-weight:600; font-size:0.85rem; color:#555; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; display:block; }
    .table thead th { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border:none; padding:12px 16px; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.05em; }
    .table tbody tr:hover { background:#f0fafc; }
    .table tbody td { padding:12px 16px; vertical-align:middle; border-color:#e9f5f8; }
    .modal-header-custom { background:linear-gradient(135deg,#17a2b8,#138496); color:white; }
    .modal-header-custom .close { color:white; opacity:1; }
    .modal-content { border-radius:12px; overflow:hidden; border:none; }
    .btn-nuevo { background:linear-gradient(135deg,#17a2b8,#138496); border:none; border-radius:8px; padding:8px 20px; color:white; font-weight:600; }
</style>

<div class="card card-main">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list mr-2"></i> Lista de Doctores</span>
        <div class="d-flex">
            <form action="{{ route('medicos.index') }}" method="GET" class="mr-2">
                <div class="input-group input-group-sm">
                    <input type="search" name="buscarpor" class="form-control" placeholder="Buscar doctor..." style="border-radius:8px 0 0 8px; border:none;">
                    <div class="input-group-append">
                        <button class="btn btn-light btn-sm" type="submit" style="border-radius:0 8px 8px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn-nuevo" data-toggle="modal" data-target="#nuevoMedicoModal">
                <i class="fas fa-plus mr-1"></i> Nuevo
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @include('flash::message')
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Teléfono</th>
                        <th>Registro</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicos as $m)
                    <tr>
                        <td><i class="fas fa-user-md mr-1" style="color:#17a2b8;"></i> {{ $m->nombre }}</td>
                        <td>{{ $m->ci }}</td>
                        <td>{{ $m->telefono }}</td>
                        <td>{{ $m->registro }}</td>
                        <td><span style="background:#e8f8fb;color:#17a2b8;padding:3px 10px;border-radius:10px;font-size:0.8rem;font-weight:600;">{{ $m->especialidad->nombre }}</span></td>
                        <td>
                            @if($m->estado == 1)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#verMedicoModal{{ $m->id_medico }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning mr-1" data-toggle="modal" data-target="#editarMedicoModal{{ $m->id_medico }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('medicos.update', $m->id_medico) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="{{ $m->estado ? '0' : '1' }}">
                                <button type="submit" class="btn btn-sm btn-{{ $m->estado ? 'danger' : 'success' }}">
                                    <i class="fas fa-{{ $m->estado ? 'ban' : 'check' }}"></i>
                                    {{ $m->estado ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Ver -->
                    <div class="modal fade" id="verMedicoModal{{ $m->id_medico }}" tabindex="-1" data-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header modal-header-custom">
                                    <h5 class="modal-title"><i class="fas fa-user-md mr-2"></i> Detalle del Doctor</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" class="form-control" value="{{ $m->nombre }}" disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cédula</label>
                                            <input type="text" class="form-control" value="{{ $m->ci }}" disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" value="{{ $m->telefono }}" disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Registro</label>
                                            <input type="text" class="form-control" value="{{ $m->registro }}" disabled>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Especialidad</label>
                                            <input type="text" class="form-control" value="{{ $m->especialidad->nombre }}" disabled>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px;">
                                            <i class="fas fa-times mr-1"></i> Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarMedicoModal{{ $m->id_medico }}" tabindex="-1" data-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header modal-header-custom">
                                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Editar Doctor</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('medicos.update', $m) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="nombre" value="{{ $m->nombre }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Cédula</label>
                                                <input type="text" class="form-control" name="ci" value="{{ $m->ci }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Teléfono</label>
                                                <input type="text" class="form-control" name="telefono" value="{{ $m->telefono }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Registro</label>
                                                <input type="text" class="form-control" name="registro" value="{{ $m->registro }}" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Especialidad</label>
                                                <select class="form-control" name="especialidad_id" required>
                                                    @foreach($especialidades as $e)
                                                        <option value="{{ $e->id_especialidad }}" {{ $m->especialidad_id == $e->id_especialidad ? 'selected' : '' }}>
                                                            {{ $e->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px;">
                                                <i class="fas fa-times mr-1"></i> Cancelar
                                            </button>
                                            <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#17a2b8,#138496);border:none;border-radius:8px;">
                                                <i class="fas fa-save mr-1"></i> Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end p-3">
            {{ $medicos->links() }}
        </div>
    </div>
</div>

<!-- Modal Nuevo Médico -->
<div class="modal fade" id="nuevoMedicoModal" tabindex="-1" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> Nuevo Doctor</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('medicos.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre completo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cédula</label>
                            <input type="text" class="form-control" name="ci" placeholder="Cédula" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" placeholder="Teléfono" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registro</label>
                            <input type="text" class="form-control" name="registro" placeholder="N° Registro" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Especialidad</label>
                            <select class="form-control" name="especialidad_id" required>
                                <option value="">Seleccione una especialidad</option>
                                @foreach($especialidades as $e)
                                    <option value="{{ $e->id_especialidad }}">{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px;">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#17a2b8,#138496);border:none;border-radius:8px;">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop