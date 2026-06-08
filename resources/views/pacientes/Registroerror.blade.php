@extends('adminlte::page')

@section('title', 'Pacientes')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-user-injured fa-lg mr-2" style="color:#17a2b8;"></i>
            <h1 class="font-weight-bold mb-0">Registrar Paciente</h1>
        </div>
    </div>
@stop

@section('content')
<style>
    .card-form { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
    .card-form .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border-radius:16px 16px 0 0; padding:16px 24px; font-weight:600; font-size:1rem; }
    .form-control, .custom-select { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus, .custom-select:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .form-label { font-weight:600; font-size:0.85rem; color:#555; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; display:block; }
    .alert-panel { background:linear-gradient(135deg,#ffe6e6,#ffcccc); border:2px solid #ff6b6b; color:#d63031; border-radius:16px; padding:24px; box-shadow:0 4px 12px rgba(255,107,107,0.2); position:relative; overflow:hidden; animation: slideInError 0.5s ease-out; }
    .alert-panel::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; background:linear-gradient(to bottom,#ff6b6b,#e74c3c); }
    .alert-panel .alert-icon { font-size:2rem; color:#e74c3c; margin-bottom:12px; animation: bounceIn 0.6s ease-out; }
    .alert-panel .alert-title { font-size:1.1rem; font-weight:700; margin-bottom:8px; color:#d63031; }
    .alert-panel .alert-message { font-size:0.95rem; margin-bottom:12px; }
    @keyframes slideInError { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); opacity: 1; } }
    .btn-guardar { background:linear-gradient(135deg,#17a2b8,#138496); border:none; border-radius:8px; padding:10px 24px; color:white; font-weight:600; }
    .btn-guardar:hover { background:linear-gradient(135deg,#138496,#0f6674); }
    .form-submit-group { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
</style>

<div class="card card-form">
    <div class="card-header">
        <i class="fas fa-exclamation-circle mr-2"></i> Error en el registro
    </div>
    <div class="card-body p-4">
        @if(session('mensaje'))
            <div class="alert alert-panel mb-4" role="alert">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle alert-icon"></i>
                </div>
                <div class="text-center">
                    <div class="alert-title">¡Error en el registro!</div>
                    <div class="alert-message">{{ session('mensaje') }}</div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-edit mr-1"></i>
                        Corrige los datos marcados y vuelve a intentar
                    </small>
                </div>
            </div>
        @endif

        <form method="POST" action="/guardar-paciente">
            @csrf
            <div class="row">
                <div class="col-3 mb-4">
                    <input type="text" name="cod_paciente" id="cod_paciente"  value="{{$datos['cod_paciente']}}" placeholder="Codigo del paciente" class="form-control" required> <br>
                </div>
                <div class="col-3 mb-4">
                    <span>Último codigo usado: </span>
                    <b>@if($paciente !=null){{$paciente->cod_paciente}} @else {no se encontaron registros} @endif</b>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <input type="text" name="nombre" id="nombre" value="{{$datos['nombre']}}" placeholder="Nombre(s)" class="form-control" required><br>
                </div>
                <div class="col-6">
                    <input type="text" name="apellido" id="apellido" value="{{$datos['apellido']}}" placeholder="Apellido(s)" class="form-control" required><br>
                </div >
            </div>

            <div class="row">
                <div class="col-3">
                    <input type="text" name="num_doc" id="num_doc" value="{{$datos['num_doc']}}" placeholder="Cedula de identidad" class="form-control"required><br>
                </div>
                <div class="col-3">
                    <select name="sexo" id="sexo" class="form-control" required>
                        <option value="">Sexo</option>
                        <option value="masculino" <?php if($datos['sexo'] == 'masculino'){print('selected');} ?>>Masculino</option>
                        <option value="femenino" <?php if($datos['sexo'] == 'femenino'){print('selected');} ?>>Femenino</option>
                    </select>
                </div>
                <div class="col-6">
                    <input type="text" name="direccion" id="direccion" value="{{$datos['direccion']}}" placeholder="Direccion" class="form-control" required> <br>
                </div>
            </div>

            <div class="row">
                <div class="col-6"> 
                    <input type="text" name="url_maps" id="url_maps" value="{{$datos['url_maps']}}" placeholder="URL Maps" class="form-control" required><br>
                </div>
                <div class="col-6"> 
                    <input type="text" name="edad" id="edad" value="{{$datos['edad']}}" placeholder="Edad" class="form-control" required><br>
                </div>

            </div>

            <div class="row">
                <div class="col-6">
                    <select name="departamento" id="departamento" class="form-control" required>
                        <option value="">Departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id_departamento}}" <?php if($datos['departamento'] == $departamento->id_departamento){print('selected');} ?> >{{ $departamento->nombre }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="col-6">
                    <select name="ciudad" id="ciudad" class="form-control">
                        @foreach($ciudad as $c)
                        <option value="{{ $c->id_ciudad}}" <?php if($datos['ciudad'] == $c->id_ciudad){print('selected');} ?>>{{ $c->nombre }}</option>
                        @endforeach
                    </select><br>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <input type="text" name="tiene_IPS" id="tiene_IPS" value="{{$datos['tiene_IPS']}}" placeholder="IPS" class="form-control" required>
                </div>
                <div class="col-4">
                    <label for="tipo_enfermedad">Tipo de Enfermedad:</label>
                    <select multiple name="tipo_enfermedad[]" id="tipo_enfermedad" class="form-control"  >
                        @foreach($enfermedad as $c)
                        <option value="{{ $c->id_tipo}}"<?php foreach ($datos['tipo_enfermedad'] as $d){if($d == $c->id_tipo){print('selected');} }?>  >{{ $c->tipo_enfermedad}} - {{ $c->etapa_enfermedad}}</option>
                        @endforeach
                    </select><br>
            </div>
         </div>
            <div class="form-group">
                <label for="diagnostico">Diagnostico: </label>
                <textarea name="diagnostico" id="diagnostico" cols="15" rows="5" class="form-control">{{$datos['diagnostico']}}</textarea>
            </div>

            <div class="form-group">
                <label for="comentario">Comentario: </label>
                <textarea name="comentario" id="comentario"  cols="15" rows="5" class="form-control">{{$datos['comentario']}}</textarea>
            </div>
            <div class="form-submit-group mb-4">
                <button type="submit" class="btn btn-guardar">Guardar</button>
                <a href="/pacientes" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        @if(session('mensaje'))
            <script>
                Swal.fire({
                    title: "Se ha producido un error :(",
                    text: "{{ session('mensaje') }}",
                    icon: "error"
                });
            </script>
        @endif
<script>
    $(document).ready(function () {
        $('#departamento').change(function () {
            var departamentoId = $(this).val();
            
            if (departamentoId) {
                $.ajax({
                    type: 'GET',
                    url: '/obtener-ciudades/' + departamentoId,
                    success: function (data) {
                        var selectCiudad = $('#ciudad');
                        console.log("datos:", data);
                        $('#ciudad').html('<option value="">Seleccione una ciudad</option>');
                        $.each(data, function (ciudad_id, value) {
                            $('#ciudad').append('<option  value="' + value.id_ciudad + '">'+ value.nombre + '</option>');
                        });
                        $('#ciudad').prop('disabled', false);
                    }
                });
            } else {
                $('#ciudad').html('<option value="">Seleccione una ciudad</option>');
                $('#ciudad').prop('disabled', true);
            }
        });
    });
</script>

@stop