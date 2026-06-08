@extends('adminlte::page')
@section('title', 'Departamentos')

@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-map fa-lg mr-2" style="color:#17a2b8;"></i>
        <h1 class="font-weight-bold mb-0">Gestión de Departamentos</h1>
    </div>
@stop

@section('content')
<style>
    .card-main { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
    .card-main .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; padding:16px 24px; font-weight:600; }
    .form-control { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
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
        <span><i class="fas fa-list mr-2"></i> Lista de Departamentos</span>
        <div class="d-flex">
            <form action="{{ route('departamentos.index') }}" method="GET" class="mr-2">
                <div class="input-group input-group-sm">
                    <input type="search" name="buscarpor" class="form-control" placeholder="Buscar departamento..." style="border-radius:8px 0 0 8px; border:none;">
                    <div class="input-group-append">
                        <button class="btn btn-light btn-sm" type="submit" style="border-radius:0 8px 8px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn-nuevo" data-toggle="modal" data-target="#nuevoDepartamentoModal">
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
                        <th>Departamento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departamentos as $d)
                    <tr>
                        <td><i class="fas fa-map-marker-alt mr-1" style="color:#17a2b8;"></i> {{ $d->nombre }}</td>
                        <td>
                            <button class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#verDepartamentoModal{{ $d->id_departamento }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarDepartamentoModal{{ $d->id_departamento }}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Ver -->
                    <div class="modal fade" id="verDepartamentoModal{{ $d->id_departamento }}" tabindex="-1" data-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header modal-header-custom">
                                    <h5 class="modal-title"><i class="fas fa-map mr-2"></i> Detalle</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body p-4">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" value="{{ $d->nombre }}" disabled>
                                    <div class="text-right mt-3">
                                        <button class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px;">
                                            <i class="fas fa-times mr-1"></i> Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarDepartamentoModal{{ $d->id_departamento }}" tabindex="-1" data-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header modal-header-custom">
                                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Editar Departamento</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('departamentos.update', $d) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control mb-3" name="nombre" value="{{ $d->nombre }}" required>
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
            {{ $departamentos->links() }}
        </div>
    </div>
</div>

<!-- Modal Nuevo Departamento -->
<div class="modal fade" id="nuevoDepartamentoModal" tabindex="-1" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> Nuevo Departamento</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('departamentos.store') }}" method="POST">
                    @csrf
                    <label class="form-label">Nombre del Departamento</label>
                    <input type="text" class="form-control mb-3" name="nombre" placeholder="Ej: Alto Paraná" required>
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