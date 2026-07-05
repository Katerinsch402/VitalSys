@extends('adminlte::page')
@section('title', 'Nuevo Paciente')
@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-hospital-user fa-lg mr-2" style="color:#17a2b8;"></i>
        <h1 class="font-weight-bold mb-0">Registrar Paciente</h1>
    </div>
@stop

@section('content')
<style>
    .card-form { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
    .card-form .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border-radius:16px 16px 0 0; padding:16px 24px; font-weight:600; font-size:1rem; }
    .form-control, .custom-select { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus, .custom-select:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .form-label { font-weight:600; font-size:0.85rem; color:#555; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; display:block; }
    .section-divider { border:none; border-top:2px solid #e9f5f8; margin:20px 0; }
    .section-title { font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#17a2b8; margin-bottom:15px; }
    .btn-guardar { background:linear-gradient(135deg,#17a2b8,#138496); border:none; border-radius:8px; padding:10px 30px; font-weight:600; }
    .btn-guardar:hover { background:linear-gradient(135deg,#138496,#0f6674); }
    .form-control.is-invalid, .custom-select.is-invalid { border-color:#e74c3c; box-shadow:0 0 0 0.2rem rgba(231,76,60,0.25); }
    .invalid-feedback { display:block; color:#e74c3c; font-size:0.85rem; margin-top:4px; }
    .form-group.has-error .form-label::after { content:' *'; color:#e74c3c; font-weight:bold; }
    .alert-panel { background:linear-gradient(135deg,#ffe6e6,#ffcccc); border:2px solid #ff6b6b; color:#d63031; border-radius:16px; padding:24px; box-shadow:0 4px 12px rgba(255,107,107,0.2); position:relative; overflow:hidden; animation: slideInError 0.5s ease-out; }
    .alert-panel::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; background:linear-gradient(to bottom,#ff6b6b,#e74c3c); }
    .alert-panel .alert-icon { font-size:2rem; color:#e74c3c; margin-bottom:12px; animation: bounceIn 0.6s ease-out; }
    .alert-panel .alert-title { font-size:1.1rem; font-weight:700; margin-bottom:8px; color:#d63031; }
    .alert-panel .alert-message { font-size:0.95rem; margin-bottom:12px; }
    .alert-panel ul { list-style:none; padding:0; margin:0; }
    .alert-panel ul li { padding:6px 0; border-bottom:1px solid rgba(255,107,107,0.2); animation: fadeInUp 0.4s ease-out; animation-fill-mode: both; }
    .alert-panel ul li:nth-child(1) { animation-delay: 0.1s; }
    .alert-panel ul li:nth-child(2) { animation-delay: 0.2s; }
    .alert-panel ul li:nth-child(3) { animation-delay: 0.3s; }
    .alert-panel ul li:nth-child(4) { animation-delay: 0.4s; }
    .alert-panel ul li:nth-child(5) { animation-delay: 0.5s; }
    .alert-panel ul li:last-child { border-bottom:none; }
    .alert-panel ul li::before { content:'⚠️'; margin-right:8px; }
    @keyframes slideInError { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); opacity: 1; } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="card card-form">
    <div class="card-header">
        <i class="fas fa-plus-circle mr-2"></i> Nuevo Paciente
    </div>
    <div class="card-body p-4">
            @if($errors->any() || session('mensaje'))
                <div class="alert alert-danger alert-panel mb-4" role="alert">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle alert-icon"></i>
                    </div>
                    <div class="text-center">
                        <div class="alert-title">¡Atención! Hay errores en el formulario</div>
                        <div class="alert-message">Por favor, revisa los siguientes campos antes de continuar:</div>
                    </div>
                    @if(session('mensaje'))
                        <div class="mt-3 p-3 bg-white bg-opacity-50 rounded">
                            <i class="fas fa-info-circle mr-2 text-warning"></i>
                            {{ session('mensaje') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <ul class="mt-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="text-center mt-3">
                        <div class="d-flex justify-content-center align-items-center">
                            <small class="text-muted mr-2">
                                <i class="fas fa-lightbulb text-warning"></i>
                                Completa todos los campos marcados como obligatorios (*)
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Los campos marcados con (*) son obligatorios. Revisa cada campo y asegúrate de que la información sea correcta.">
                                <i class="fas fa-question-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        <form method="POST" action="/guardar-paciente">
            @csrf

            {{-- IDENTIFICACIÓN --}}
            <p class="section-title"><i class="fas fa-id-card mr-1"></i> Identificación</p>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Código del Paciente</label>
                    <input type="text" name="cod_paciente" id="cod_paciente" value="{{ old('cod_paciente') }}" placeholder="Ej: PAC-001" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <div class="ultimo-codigo w-100">
                        <i class="fas fa-info-circle mr-1"></i>
                        Último código: <strong>@if($paciente != null){{ $paciente->cod_paciente }}@else Sin registros @endif</strong>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cédula de Identidad</label>
                    <input type="text" name="num_doc" id="num_doc" value="{{ old('num_doc') }}" placeholder="Ej: 1234567" class="form-control" required>
                </div>
            </div>

            <hr class="section-divider">

            {{-- DATOS PERSONALES --}}
            <p class="section-title"><i class="fas fa-user mr-1"></i> Datos Personales</p>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nombre(s)</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Nombre(s)" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Apellido(s)</label>
                    <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}" placeholder="Apellido(s)" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Edad</label>
                    <input type="text" name="edad" id="edad" value="{{ old('edad') }}" placeholder="Ej: 35" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Sexo</label>
                    <select name="sexo" id="sexo" class="form-control" required>
                        <option value="">Seleccionar</option>
                        <option value="masculino" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ old('sexo') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
            </div>

            <hr class="section-divider">

            {{-- UBICACIÓN --}}
            <p class="section-title"><i class="fas fa-map-marker-alt mr-1"></i> Ubicación</p>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Departamento</label>
                    <select name="departamento" id="departamento" class="form-control" required>
                        <option value="">Seleccionar departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id_departamento }}" {{ old('departamento') == $departamento->id_departamento ? 'selected' : '' }}>{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Ciudad</label>
                    <select name="ciudad" id="ciudad" class="form-control" {{ old('departamento') ? '' : 'disabled' }} required>
                        <option value="">Seleccionar ciudad</option>
                        @if(old('departamento'))
                            {{-- Considerar recargar ciudades en el servidor si se quiere mantener la selección completa --}}
                        @endif
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" placeholder="Dirección completa" class="form-control" required>
                </div>
            </div>

            <hr class="section-divider">

            {{-- INFORMACIÓN MÉDICA (resumida) --}}
            <p class="section-title"><i class="fas fa-heartbeat mr-1"></i> Información Médica</p>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tiene IPS</label>
                    <select name="tiene_IPS" id="tiene_IPS" class="form-control" required>
                        <option value="">Seleccionar</option>
                        <option value="SI" {{ old('tiene_IPS') == 'SI' ? 'selected' : '' }}>SI</option>
                        <option value="NO" {{ old('tiene_IPS') == 'NO' ? 'selected' : '' }}>NO</option>
                    </select>
                </div>
                <div class="col-md-9 mb-3">
                    <label class="form-label">Comentario (opcional)</label>
                    <textarea name="comentario" id="comentario" rows="4" class="form-control" placeholder="Comentarios adicionales...">{{ old('comentario') }}</textarea>
                    <small class="form-text text-muted">Este campo es opcional; puede completarse más tarde si lo desea.</small>
                </div>
            </div>

            <input type="hidden" name="estado" value="activo">

            <hr class="section-divider">

            <div class="d-flex justify-content-end">
                <a href="/pacientes" class="btn btn-secondary mr-2" style="border-radius:8px; padding:10px 24px;">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-guardar text-white">
                    <i class="fas fa-save mr-1"></i> Guardar Paciente
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@if(session('mensaje'))
    <script>
        Swal.fire({ title: "Error", text: "{{ session('mensaje') }}", icon: "error" });
    </script>
@endif
<script>
    $(document).ready(function() {
        // Select2 removed for this view (not used)
        $('[data-toggle="tooltip"]').tooltip();

        // Resaltar campos con errores
        @if($errors->any())
            @foreach($errors->keys() as $field)
                $('[name="{{ $field }}"]').addClass('is-invalid');
                $('[name="{{ $field }}"]').closest('.mb-3').find('.form-label').addClass('text-danger');
            @endforeach
        @endif

        $('#departamento').change(function () {
            var departamentoId = $(this).val();
            if (departamentoId) {
                $.ajax({
                    type: 'GET',
                    url: '/obtener-ciudades/' + departamentoId,
                    success: function (data) {
                        $('#ciudad').html('<option value="">Seleccione una ciudad</option>');
                        $.each(data, function (i, value) {
                            $('#ciudad').append('<option value="' + value.id_ciudad + '">' + value.nombre + '</option>');
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