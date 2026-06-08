@extends('adminlte::page')
@section('title', 'Usuarios')
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-users-cog fa-lg mr-2" style="color:#17a2b8;"></i>
        <h1 class="font-weight-bold mb-0">Gestión de Usuarios</h1>
    </div>
@stop

@section('content')
<style>
    .card-main { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
    .card-main .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; padding:16px 24px; font-weight:600; }
    .form-control { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .form-label { font-weight:600; font-size:0.85rem; color:#555; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; display:block; }
    .table thead th { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border:none; padding:12px 16px; font-size:0.85rem; text-transform:uppercase; }
    .table tbody tr:hover { background:#f0fafc; }
    .table tbody td { padding:12px 16px; vertical-align:middle; border-color:#e9f5f8; }
    .modal-header-custom { background:linear-gradient(135deg,#17a2b8,#138496); color:white; }
    .modal-header-custom .close { color:white; opacity:1; }
    .modal-content { border-radius:12px; overflow:hidden; border:none; }
    .btn-nuevo { background:linear-gradient(135deg,#17a2b8,#138496); border:none; border-radius:8px; padding:8px 20px; color:white; font-weight:600; }
    .badge-rol { padding:4px 12px; border-radius:10px; font-size:0.78rem; font-weight:600; }
</style>

<div class="card card-main">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list mr-2"></i> Lista de Usuarios</span>
        <div class="d-flex">
            <form action="{{ route('usuarios.index') }}" method="GET" class="mr-2">
                <div class="input-group input-group-sm">
                    <input type="search" name="buscarpor" class="form-control" placeholder="Buscar usuario..." style="border-radius:8px 0 0 8px; border:none;">
                    <div class="input-group-append">
                        <button class="btn btn-light btn-sm" type="submit" style="border-radius:0 8px 8px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn-nuevo" data-toggle="modal" data-target="#nuevoUsuario">
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
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td><i class="fas fa-user-circle mr-1" style="color:#17a2b8;"></i> {{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            @php
                                $colores = [
                                    'admin' => 'background:#f3e8ff;color:#6f42c1;',
                                    'medico' => 'background:#e8f4ff;color:#0d6efd;',
                                    'recepcionista' => 'background:#e8f8fb;color:#17a2b8;',
                                    'administrativo' => 'background:#fff3e0;color:#fd7e14;',
                                ];
                                $color = $colores[$usuario->rol] ?? 'background:#e2e3e5;color:#383d41;';
                            @endphp
                            <span class="badge-rol" style="{{ $color }}">{{ ucfirst($usuario->rol) }}</span>
                        </td>
                        <td>
                            @if($usuario->estado == 'activo')
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning mr-1" data-toggle="modal" data-target="#editarUsuario{{ $usuario->id }}" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#cambiarContra{{ $usuario->id }}" title="Cambiar Contraseña">
                                <i class="fas fa-key"></i>
                            </button>
                            <form action="{{ route('usuarios.cambiarEstado') }}" method="POST" style="display:inline-block;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $usuario->id }}">
                                <button type="submit" class="btn btn-sm btn-{{ $usuario->estado == 'activo' ? 'danger' : 'success' }}" title="{{ $usuario->estado == 'activo' ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas fa-{{ $usuario->estado == 'activo' ? 'ban' : 'check' }}"></i>
                                    {{ $usuario->estado == 'activo' ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarUsuario{{ $usuario->id }}" tabindex="-1" data-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header modal-header-custom">
                                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Editar Usuario</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('usuarios.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $usuario->id }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="name" value="{{ $usuario->name }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="{{ $usuario->email }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Rol</label>
                                                <select class="form-control" id="rolEdit{{ $usuario->id }}" name="rol">
                                                    <option value="">-- Seleccionar --</option>
                                                    <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="administrativo" {{ $usuario->rol == 'administrativo' ? 'selected' : '' }}>Administrador</option>
                                                    <option value="recepcionista" {{ $usuario->rol == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
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

                    <!-- Modal Cambiar Contraseña -->
                    <div class="modal fade" id="cambiarContra{{ $usuario->id }}" tabindex="-1" data-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header modal-header-custom">
                                    <h5 class="modal-title"><i class="fas fa-key mr-2"></i> Cambiar Contraseña</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('usuarios.passChange') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $usuario->id }}">
                                        <div class="mb-3">
                                            <label class="form-label">Nueva Contraseña</label>
                                            <input type="password" class="form-control" name="password" placeholder="Nueva contraseña" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Confirmar Contraseña</label>
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmar contraseña" required>
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
            {{ $usuarios->links() }}
        </div>
    </div>
</div>

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="nuevoUsuario" tabindex="-1" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> Nuevo Usuario</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name" placeholder="Nombre completo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="correo@ejemplo.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rol</label>
                            <select class="form-control" id="rol" name="rol" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="admin">Admin</option>
                                <option value="administrativo">Administrador</option>
                                <option value="recepcionista">Recepcionista</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmar contraseña" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px;">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#17a2b8,#138496);border:none;border-radius:8px;">
                            <i class="fas fa-save mr-1"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')

@if(session('passChangeError'))
    <script>Swal.fire({ icon:'error', title:'Error!', text:'{{ session("passChangeError") }}' });</script>
@endif
@if(session('userCreateUpdate'))
    <script>Swal.fire({ icon:'info', title:'Atención!', text:'{{ session("userCreateUpdate") }}' });</script>
@endif
@if(session('passChangeSuccess'))
    <script>Swal.fire({ title:'Guardado!', text:'{{ session("passChangeSuccess") }}' });</script>
@endif
@if(session('cambioEstado'))
    <script>Swal.fire({ icon:'success', title:'Estado actualizado', text:'{{ session("cambioEstado") }}' });</script>
@endif
@stop