@extends('adminlte::page')
@section('title', 'Citas')
@section('plugins.Select2', true)

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-calendar-check fa-lg mr-2" style="color:#17a2b8;"></i>
            <h1 class="font-weight-bold mb-0">Gestión de Citas</h1>
        </div>
    </div>
@stop

@section('content')
<style>
    .card-form { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
    .card-header-citas { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border-radius:16px 16px 0 0; padding:16px 24px; }
    .form-control { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
    .form-control:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
    .form-label { font-weight:600; font-size:0.85rem; color:#555; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; }
    .modal-header-custom { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border-radius:8px 8px 0 0; }
    .modal-header-custom .close { color:white; opacity:1; }
    .btn-nueva-cita { background:linear-gradient(135deg,#28a745,#1e7e34); border:none; border-radius:8px; padding:10px 20px; font-weight:600; color:white; }
    .section-title { font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#17a2b8; margin-bottom:12px; margin-top:5px; }
    .error-panel { background:linear-gradient(135deg,#ffe6e6,#ffcccc); border:2px solid #ff6b6b; color:#d63031; border-radius:16px; padding:20px; box-shadow:0 4px 12px rgba(255,107,107,0.2); position:relative; overflow:hidden; animation: slideInError 0.5s ease-out; }
    .error-panel::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; background:linear-gradient(to bottom,#ff6b6b,#e74c3c); }
    .error-panel .error-icon { font-size:2rem; color:#e74c3c; margin-bottom:12px; animation: bounceIn 0.6s ease-out; }
    .error-panel .error-title { font-size:1.1rem; font-weight:700; margin-bottom:8px; color:#d63031; }
    .error-panel .error-message { font-size:0.95rem; margin-bottom:12px; }
    .error-panel ul { list-style:none; padding:0; margin:0; }
    .error-panel ul li { padding:6px 0; border-bottom:1px solid rgba(255,107,107,0.2); animation: fadeInUp 0.4s ease-out; animation-fill-mode: both; }
    .error-panel ul li:nth-child(1) { animation-delay: 0.1s; }
    .error-panel ul li:nth-child(2) { animation-delay: 0.2s; }
    .error-panel ul li:nth-child(3) { animation-delay: 0.3s; }
    .error-panel ul li:nth-child(4) { animation-delay: 0.4s; }
    .error-panel ul li:nth-child(5) { animation-delay: 0.5s; }
    .error-panel ul li:last-child { border-bottom:none; }
    .error-panel ul li::before { content:'⚠️'; margin-right:8px; }
    .error-highlight { border-color:#e74c3c !important; box-shadow:0 0 0 0.2rem rgba(231,76,60,0.25) !important; }
    @keyframes slideInError { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); opacity: 1; } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="card card-form mb-4">
    <div class="card-header-citas d-flex align-items-center justify-content-between">
        <div>
            <i class="fas fa-list mr-2"></i>
            <span class="font-weight-bold">Listado de Citas</span>
        </div>
        @can('crear citas')
            <button type="button" class="btn-nueva-cita btn-sm" data-toggle="modal" data-target="#cargarCita" style="padding: 6px 12px; font-size: 0.85rem;">
                <i class="fas fa-plus mr-1"></i> Nueva Cita
            </button>
        @endcan
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tablaCitas">
                <thead style="background: #f4f6f9;">
                    <tr>
                        <th>#</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($citas) && count($citas) > 0)
                        @foreach($citas as $cita)
                        <tr data-medico-id="{{ $cita->medico_id }}">
                            <td>{{ $cita->id_cita }}</td>
                            <td>{{ $cita->paciente->nombre ?? '-' }} {{ $cita->paciente->apellido ?? '' }}</td>
                            <td>{{ $cita->medico->nombre ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->fec_inicio)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->fec_fin)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($cita->estado == 'atendido')
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Atendido</span>
                                @elseif($cita->estado == 'Pendiente')
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i>Pendiente</span>
                                @elseif($cita->estado == 'Cancelado')
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i>Cancelado</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">{{ $cita->estado }}</span>
                                @endif
                            </td>
                            <td>
                                @if($cita->estado == 'Pendiente')
                                    <button class="btn btn-sm btn-info" title="Reagendar" onclick="reagendarCita({{ $cita->id_cita }})">
                                        <i class="fas fa-calendar-day"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Cancelar" onclick="cancelarCita({{ $cita->id_cita }})">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">No hay citas registradas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nueva Cita -->
<div class="modal fade" id="cargarCita" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-calendar-plus mr-2"></i> Cargar Cita
                </h5>
            </div>
            <div class="modal-body p-4 position-relative">
                <div class="position-absolute d-flex justify-content-center align-items-center" style="top:0;left:0;right:0;bottom:0;z-index:5;display:none!important;background:rgba(255,255,255,0.9);backdrop-filter:blur(2px);" id="cargando">
                    <div class="text-center">
                        <div class="spinner-border text-info mb-3" style="width:3rem;height:3rem;" role="status"></div>
                        <div class="text-info font-weight-bold">Guardando cita...</div>
                        <small class="text-muted">Por favor espera un momento</small>
                    </div>
                </div>

                <form id="formNuevo" action="{{ route('citas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="idx" value="-1" id="idx">
                    <input type="hidden" name="estado" id="estado" value="Pendiente">

                    <p class="section-title"><i class="fas fa-user-md mr-1"></i> Información Médica</p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Doctor</label>
                            <input type="hidden" name="medico_id" id="medico_id" value="">
                            <input type="text" class="form-control" name="doctor_nombre" id="doctor_nombre" placeholder="Matrícula o nombre del doctor" autocomplete="off" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sala</label>
                            <input type="hidden" name="sala_id" id="sala_id" value="">
                            <input type="text" class="form-control" name="sala_nombre" id="sala_nombre" placeholder="Tipo o número de sala" autocomplete="off" required>
                        </div>
                    </div>

                    <p class="section-title"><i class="fas fa-clock mr-1"></i> Fecha y Hora</p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha y Hora de Inicio</label>
                            <input type="datetime-local" class="form-control" name="fec_inicio" id="fechaHoraIni" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha y Hora de Fin</label>
                            <input type="datetime-local" class="form-control" name="fec_fin" id="fechaHoraFin" required>
                            <small class="form-text text-muted">Se calcula automáticamente al cambiar la fecha de inicio, pero puedes editarla manualmente</small>
                        </div>
                    </div>

                    <p class="section-title"><i class="fas fa-hospital-user mr-1"></i> Paciente</p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Paciente</label>
                            <input type="hidden" name="paciente_id" id="paciente_id" value="">
                            <input type="text" class="form-control" name="paciente_codigo" id="paciente_codigo" placeholder="Código o nombre de paciente" autocomplete="off" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Consulta</label>
                            <input type="hidden" name="tipo_consulta_id" id="tipo_consulta_id" value="">
                            <input type="text" class="form-control" name="tipo_consulta_nombre" id="tipo_consulta_nombre" placeholder="Descripción del tipo" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="entreCitas" id="entreCitas">
                                <label class="custom-control-label font-weight-600" for="entreCitas">Es consulta entre citas</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="notas" rows="3" placeholder="Observaciones de la cita..." required></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e9f5f8;">
                <button type="button" class="btn btn-secondary" id="btnCancelar" data-dismiss="modal" style="border-radius:8px;">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="submit" id="btnGuardar" class="btn text-white" onclick="this.disabled=true; this.form.submit();" style="background:linear-gradient(135deg,#17a2b8,#138496);border-radius:8px;border:none;">
                    <i class="fas fa-save mr-1"></i> Guardar Cita
                </button>
            </div>
                </form>
        </div>
    </div>
</div>

<!-- Modal búsqueda Doctor -->
<div class="modal fade" id="buscarDoctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-search mr-2"></i> Buscar Doctor</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Buscar nombre</label>
                    <input type="text" id="doctorSearchInput" class="form-control" placeholder="Escribe nombre o especialidad...">
                </div>
                <div id="doctorSearchResults" class="list-group"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal búsqueda Paciente -->
<div class="modal fade" id="buscarPacienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-search mr-2"></i> Buscar Paciente</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Buscar paciente</label>
                    <input type="text" id="pacienteSearchInput" class="form-control" placeholder="Escribe código, nombre o documento...">
                </div>
                <div id="pacienteSearchResults" class="list-group"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal búsqueda Sala -->
<div class="modal fade" id="buscarSalaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-search mr-2"></i> Buscar Sala</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Buscar sala</label>
                    <input type="text" id="salaSearchInput" class="form-control" placeholder="Escribe tipo o número de sala...">
                </div>
                <div id="salaSearchResults" class="list-group"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal búsqueda Tipo de Consulta -->
<div class="modal fade" id="buscarTipoConsultaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-search mr-2"></i> Buscar Tipo de Consulta</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Buscar tipo</label>
                    <input type="text" id="tipoConsultaSearchInput" class="form-control" placeholder="Escribe descripción o duración...">
                </div>
                <div id="tipoConsultaSearchResults" class="list-group"></div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const medicosData = {!! json_encode($medicos->map(fn($m) => ['id' => $m->id_medico, 'nombre' => $m->nombre, 'especialidad' => $m->especialidad_id, 'registro' => $m->registro])->values()->all()) !!};

    const pacientesData = {!! json_encode($pacientes->map(fn($p) => ['id' => $p->id_paciente, 'nombre' => $p->nombre, 'apellido' => $p->apellido, 'num_doc' => $p->num_doc])->values()->all()) !!};

    const salasData = {!! json_encode($salas->map(fn($s) => ['id' => $s->id_sala, 'tipo' => $s->tipo_sala, 'numero' => $s->num_sala])->values()->all()) !!};

    const tipoConsultasData = {!! json_encode($tipoConsultas->map(fn($t) => ['id' => $t->id_tipo_consulta, 'descripcion' => $t->descripcion, 'duracion' => $t->duracion])->values()->all()) !!};

    document.addEventListener('DOMContentLoaded', function() {
        let formulario = document.querySelector('#formNuevo');
        const btnGuardar = document.getElementById('btnGuardar');
        const btnCancelar = document.getElementById('btnCancelar');
        const doctorInput = document.getElementById('doctor_nombre');
        const doctorIdInput = document.getElementById('medico_id');
        const pacienteInput = document.getElementById('paciente_codigo');
        const pacienteIdInput = document.getElementById('paciente_id');
        const salaInput = document.getElementById('sala_nombre');
        const salaIdInput = document.getElementById('sala_id');
        const tipoConsultaInput = document.getElementById('tipo_consulta_nombre');
        const tipoConsultaIdInput = document.getElementById('tipo_consulta_id');
        const doctorSearchInput = document.getElementById('doctorSearchInput');
        const pacienteSearchInput = document.getElementById('pacienteSearchInput');
        const salaSearchInput = document.getElementById('salaSearchInput');
        const tipoConsultaSearchInput = document.getElementById('tipoConsultaSearchInput');

        console.log('Datos cargados:');
        console.log('medicosData:', medicosData);
        console.log('pacientesData:', pacientesData);
        console.log('salasData:', salasData);
        console.log('tipoConsultasData:', tipoConsultasData);

        if (!formulario || !btnGuardar || !btnCancelar) {
            console.error('No se encontraron elementos del formulario de cita.');
            return;
        }

        btnGuardar.addEventListener('click', function() {
            console.log('Botón guardar presionado');
            
            // Limpiar errores anteriores
            document.querySelectorAll('.error-highlight').forEach(el => el.classList.remove('error-highlight'));
            const existingErrorPanel = document.querySelector('.error-panel');
            if (existingErrorPanel) existingErrorPanel.remove();

            let url;
            let id = document.getElementById('idx').value;
            let doctor = doctorIdInput.value;
            let doctorNombre = doctorInput.value.trim();
            let fechaIni = document.getElementById('fechaHoraIni').value;
            let fechaFin = document.getElementById('fechaHoraFin').value;
            let sala = salaIdInput.value;
            let paciente = pacienteIdInput.value;
            let pacienteCodigo = pacienteInput.value.trim();
            let tipo = tipoConsultaIdInput.value;
            let entreCitas = document.getElementById('entreCitas').checked ? 1 : 0;
            let notas = document.getElementById('notas').value;

            if (!doctor && doctorNombre) {
                const doctorEncontrado = buscarDoctorPorMatricula(doctorNombre);
                if (doctorEncontrado) {
                    doctor = doctorEncontrado.id;
                    doctorIdInput.value = doctorEncontrado.id;
                }
            }

            if (!paciente && pacienteCodigo) {
                const pacienteEncontrado = buscarPacientePorCodigo(pacienteCodigo);
                if (pacienteEncontrado) {
                    paciente = pacienteEncontrado.id;
                    pacienteIdInput.value = pacienteEncontrado.id;
                }
            }

            if (!sala && salaInput.value) {
                const salaEncontrada = buscarSalaPorNombre(salaInput.value);
                if (salaEncontrada) {
                    sala = salaEncontrada.id;
                    salaIdInput.value = salaEncontrada.id;
                }
            }

            if (!tipo && tipoConsultaInput.value) {
                const tipoEncontrado = buscarTipoConsultaPorNombre(tipoConsultaInput.value);
                if (tipoEncontrado) {
                    tipo = tipoEncontrado.id;
                    tipoConsultaIdInput.value = tipoEncontrado.id;
                }
            }

            console.log('Valores del formulario:', {doctor, fechaIni, fechaFin, sala, paciente, tipo, notas, pacienteCodigo});

            // Validación del lado del cliente
            const erroresCliente = validarFormularioCliente(doctor, fechaIni, fechaFin, sala, paciente, tipo, notas, pacienteCodigo);
            console.log('Errores de validación:', erroresCliente);
            
            if (erroresCliente.length > 0) {
                console.log('Mostrando errores de validación');
                mostrarErroresValidacion({
                    error: true,
                    message: 'Datos incompletos',
                    details: erroresCliente
                });
                return;
            }

            let datos = {
                medico_id: doctor,
                paciente_id: paciente,
                fec_inicio: fechaIni,
                fec_fin: fechaFin,
                estado: 'Pendiente',
                sala_id: sala,
                tipo_consulta_id: tipo,
                entreCitas: entreCitas,
                observaciones: notas,
            };

            url = id == '-1' ? '/citas' : '/citas/actualizar';
            if(id != '-1') datos.id_cita = id;

            mostrarCargando(true);

            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenElement) {
                mostrarCargando(false);
                Swal.fire({
                    title: 'Error interno',
                    html: 'No se encontró el token CSRF. Recarga la página e intenta de nuevo.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    background: '#fff5f5',
                    iconColor: '#dc3545',
                    customClass: {
                        popup: 'rounded-4 shadow-sm',
                        confirmButton: 'btn btn-danger px-4'
                    }
                });
                return;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenElement.getAttribute('content'),
                },
                body: JSON.stringify(datos),
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                mostrarCargando(false);

                // Verificar si es una respuesta de éxito
                if (typeof data === 'string' && (data.includes('correctamente'))) {
                    Swal.fire({
                        title: '¡Cita registrada!',
                        html: `<p>${data}</p><p>La cita se guardó correctamente en el sistema.</p>`,
                        icon: 'success',
                        confirmButtonText: 'Perfecto',
                        background: '#f4fcf6',
                        iconColor: '#28a745',
                        customClass: {
                            popup: 'rounded-4 shadow-sm',
                            confirmButton: 'btn btn-success px-4'
                        }
                    }).then(() => {
                        location.reload();
                    });
                } else if (data.error && data.details) {
                    // Mostrar errores de validación del servidor
                    mostrarErroresValidacion(data);
                } else {
                    // Error genérico
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Error',
                        text: typeof data === 'string' ? data : 'Ha ocurrido un error inesperado',
                        showConfirmButton: false,
                        timer: 3000,
                        background: '#f8d7da',
                        color: '#721c24'
                    });
                }
            })
            .catch(err => {
                mostrarCargando(false);
                console.error('Error:', err);
                Swal.fire({
                    title: 'No se pudo guardar la cita',
                    html: 'Por favor, revisa los datos e intenta nuevamente.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar',
                    background: '#fff5f5',
                    iconColor: '#dc3545',
                    customClass: {
                        popup: 'rounded-4 shadow-sm',
                        confirmButton: 'btn btn-danger px-4'
                    }
                });
            });
        });

        btnCancelar.addEventListener('click', function() {
            formulario.reset();
        });

        // Limpiar errores cuando el usuario interactúa con los campos
        document.querySelectorAll('#formNuevo input, #formNuevo select, #formNuevo textarea').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('error-highlight');
            });
            element.addEventListener('change', function() {
                this.classList.remove('error-highlight');
            });
        });

        let fechaHoraIni = document.getElementById('fechaHoraIni');
        let fechaHoraFin = document.getElementById('fechaHoraFin');

        if (fechaHoraIni) {
            fechaHoraIni.addEventListener('change', () => {
                let fechaHoraFinElement = document.getElementById('fechaHoraFin');
                if (fechaHoraIni.value) {
                    // Solo autocompletar si el campo de fin está vacío
                    if (fechaHoraFinElement && !fechaHoraFinElement.value) {
                        fechaHoraFinElement.value = sum15Minutes(fechaHoraIni.value);
                    }
                }
            });
        }

        if (fechaHoraFin) {
            fechaHoraFin.addEventListener('change', () => {
                const fechaIni = new Date(fechaHoraIni.value);
                const fechaFin = new Date(fechaHoraFin.value);

                if (fechaIni && fechaFin && fechaFin <= fechaIni) {
                    // Mostrar error y resetear
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de fin debe ser posterior a la fecha de inicio',
                        confirmButtonText: 'Entendido'
                    });
                    fechaHoraFin.value = sum15Minutes(fechaHoraIni.value);
                }
            });
        }

        if (doctorInput) {
            doctorInput.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 'Enter') {
                    event.preventDefault();
                    abrirBusquedaDoctor();
                }
            });

            doctorInput.addEventListener('input', function() {
                doctorIdInput.value = '';
            });

            doctorInput.addEventListener('blur', function() {
                const valor = doctorInput.value.trim();
                if (!valor) {
                    doctorIdInput.value = '';
                    return;
                }

                const doctorEncontrado = buscarDoctorPorMatricula(valor);
                if (doctorEncontrado) {
                    doctorIdInput.value = doctorEncontrado.id;
                } else {
                    doctorIdInput.value = '';
                }
            });
        }

        if (pacienteInput) {
            pacienteInput.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 'Enter') {
                    event.preventDefault();
                    abrirBusquedaPaciente();
                }
            });

            pacienteInput.addEventListener('input', function() {
                pacienteIdInput.value = '';
            });

            pacienteInput.addEventListener('blur', function() {
                const valor = pacienteInput.value.trim();
                if (!valor) {
                    pacienteIdInput.value = '';
                    return;
                }

                const pacienteEncontrado = buscarPacientePorCodigo(valor);
                if (pacienteEncontrado) {
                    pacienteIdInput.value = pacienteEncontrado.id;
                } else {
                    pacienteIdInput.value = '';
                }
            });
        }

        if (salaInput) {
            salaInput.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 'Enter') {
                    event.preventDefault();
                    abrirBusquedaSala();
                }
            });

            salaInput.addEventListener('input', function() {
                salaIdInput.value = '';
            });

            salaInput.addEventListener('blur', function() {
                const valor = salaInput.value.trim();
                if (!valor) {
                    salaIdInput.value = '';
                    return;
                }

                const salaEncontrada = buscarSalaPorNombre(valor);
                if (salaEncontrada) {
                    salaIdInput.value = salaEncontrada.id;
                } else {
                    salaIdInput.value = '';
                }
            });
        }

        if (tipoConsultaInput) {
            tipoConsultaInput.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 'Enter') {
                    event.preventDefault();
                    abrirBusquedaTipoConsulta();
                }
            });

            tipoConsultaInput.addEventListener('input', function() {
                tipoConsultaIdInput.value = '';
            });

            tipoConsultaInput.addEventListener('blur', function() {
                const valor = tipoConsultaInput.value.trim();
                if (!valor) {
                    tipoConsultaIdInput.value = '';
                    return;
                }

                const tipoEncontrado = buscarTipoConsultaPorNombre(valor);
                if (tipoEncontrado) {
                    tipoConsultaIdInput.value = tipoEncontrado.id;
                } else {
                    tipoConsultaIdInput.value = '';
                }
            });
        }

        if (doctorSearchInput) {
            doctorSearchInput.addEventListener('input', function() {
                actualizarResultadosDoctores(this.value);
            });
        }

        if (pacienteSearchInput) {
            pacienteSearchInput.addEventListener('input', function() {
                actualizarResultadosPacientes(this.value);
            });
        }

        if (salaSearchInput) {
            salaSearchInput.addEventListener('input', function() {
                actualizarResultadosSalas(this.value);
            });
        }

        if (tipoConsultaSearchInput) {
            tipoConsultaSearchInput.addEventListener('input', function() {
                actualizarResultadosTipoConsulta(this.value);
            });
        }
    });

    function sum15Minutes(fecha) {
        let d = new Date(fecha);
        d.setMinutes(d.getMinutes() + 15);
        let year = d.getFullYear();
        let mes  = ("0" + (d.getMonth() + 1)).slice(-2);
        let dia  = ("0" + d.getDate()).slice(-2);
        let hora = ("0" + d.getHours()).slice(-2);
        let min  = ("0" + d.getMinutes()).slice(-2);
        return `${year}-${mes}-${dia}T${hora}:${min}`;
    }

    function validarFormularioCliente(doctor, fechaIni, fechaFin, sala, paciente, tipo, notas, pacienteCodigo) {
        const errores = [];

        if (!doctor || doctor === '') {
            errores.push('Debes seleccionar un médico para la cita.');
        }

        if ((!paciente || paciente === '') && (!pacienteCodigo || pacienteCodigo === '')) {
            errores.push('Debes seleccionar un paciente para la cita.');
        }

        if (pacienteCodigo && (!paciente || paciente === '')) {
            errores.push('El código o nombre del paciente no corresponde a ningún registro.');
        }

        if (!fechaIni) {
            errores.push('La fecha y hora de inicio son obligatorias.');
        }
        if (!fechaFin) {
            errores.push('La fecha y hora de fin son obligatorias.');
        }
        if (!sala || sala === '') {
            errores.push('Debes seleccionar una sala para la cita.');
        }
        if (!tipo || tipo === '') {
            errores.push('Debes seleccionar un tipo de consulta.');
        }
        if (!notas || notas.trim().length < 5) {
            errores.push('Las observaciones son obligatorias y deben tener al menos 5 caracteres.');
        }

        return errores;
    }

    function mostrarErroresValidacion(data) {
        console.log('mostrarErroresValidacion llamado con:', data);
        
        // Limpiar errores anteriores
        document.querySelectorAll('.error-highlight').forEach(el => el.classList.remove('error-highlight'));
        const existingErrorPanel = document.querySelector('.error-panel');
        if (existingErrorPanel) existingErrorPanel.remove();

        const modalBody = document.querySelector('#cargarCita .modal-body');
        if (!modalBody) {
            console.error('No se encontró modal-body');
            return;
        }

        // Crear panel de error atractivo
        const errorPanel = document.createElement('div');
        errorPanel.className = 'alert error-panel mb-4';
        
        const detalles = data.details || [];
        let listaHTML = '';
        if (Array.isArray(detalles)) {
            listaHTML = detalles.map(error => `<li>${error}</li>`).join('');
        }
        
        errorPanel.innerHTML = `
            <div class="text-center mb-3">
                <i class="fas fa-exclamation-triangle error-icon"></i>
            </div>
            <div class="text-center">
                <div class="error-title">¡Oops! Faltan algunos datos</div>
                <div class="error-message">Por favor, revisa y completa la siguiente información antes de guardar:</div>
            </div>
            ${listaHTML ? `<ul class="mt-3">${listaHTML}</ul>` : ''}
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-lightbulb text-warning"></i>
                    Los campos marcados en rojo necesitan tu atención
                </small>
            </div>
        `;
        
        console.log('Insertando panel de error');
        // Insertar el panel de error al inicio del modal body
        const firstChild = modalBody.querySelector('.position-absolute');
        if (firstChild) {
            modalBody.insertBefore(errorPanel, firstChild);
        } else {
            modalBody.insertBefore(errorPanel, modalBody.firstChild);
        }

        // Resaltar campos con errores
        resaltarCamposConErrores(data.details);

        // Auto-remover el panel después de 8 segundos
        setTimeout(() => {
            if (errorPanel.parentNode) {
                errorPanel.remove();
            }
        }, 8000);
    }

    function resaltarCamposConErrores(errors) {
        if (!errors || !Array.isArray(errors)) return;
        
        errors.forEach(error => {
            console.log('Procesando error:', error);
            const errorLower = error.toLowerCase();
            
            if (errorLower.includes('médico')) {
                document.getElementById('doctor_nombre').classList.add('error-highlight');
            }
            if (errorLower.includes('paciente')) {
                document.getElementById('paciente_codigo').classList.add('error-highlight');
            }
            if (errorLower.includes('inicio')) {
                document.getElementById('fechaHoraIni').classList.add('error-highlight');
            }
            if (errorLower.includes('fin')) {
                document.getElementById('fechaHoraFin').classList.add('error-highlight');
            }
            if (errorLower.includes('sala')) {
                document.getElementById('sala_nombre').classList.add('error-highlight');
            }
            if (errorLower.includes('consulta')) {
                document.getElementById('tipo_consulta_nombre').classList.add('error-highlight');
            }
            if (errorLower.includes('observaciones')) {
                document.getElementById('notas').classList.add('error-highlight');
            }
        });
    }

    function abrirBusquedaDoctor() {
        console.log('abrirBusquedaDoctor() llamado');
        console.log('medicosData:', medicosData);
        $('#buscarDoctorModal').modal('show');
        const input = document.getElementById('doctorSearchInput');
        if (input) {
            input.value = '';
            input.focus();
            actualizarResultadosDoctores('');
        }
    }

    function abrirBusquedaPaciente() {
        $('#buscarPacienteModal').modal('show');
        const input = document.getElementById('pacienteSearchInput');
        if (input) {
            input.value = '';
            input.focus();
            actualizarResultadosPacientes('');
        }
    }

    function abrirBusquedaSala() {
        $('#buscarSalaModal').modal('show');
        const input = document.getElementById('salaSearchInput');
        if (input) {
            input.value = '';
            input.focus();
            actualizarResultadosSalas('');
        }
    }

    function abrirBusquedaTipoConsulta() {
        $('#buscarTipoConsultaModal').modal('show');
        const input = document.getElementById('tipoConsultaSearchInput');
        if (input) {
            input.value = '';
            input.focus();
            actualizarResultadosTipoConsulta('');
        }
    }

    function actualizarResultadosDoctores(query) {
        console.log('actualizarResultadosDoctores() - query:', query);
        console.log('medicosData:', medicosData);
        const container = document.getElementById('doctorSearchResults');
        if (!container) {
            console.error('No se encontró container doctorSearchResults');
            return;
        }
        const texto = query.trim().toLowerCase();
        const resultados = medicosData.filter(medico => {
            return medico.nombre.toLowerCase().includes(texto) 
                || medico.registro.toLowerCase().includes(texto)
                || medico.especialidad.toString().toLowerCase().includes(texto);
        });

        console.log('Resultados encontrados:', resultados);

        container.innerHTML = resultados.length > 0 ? resultados.map(medico => {
            return `<button type="button" class="list-group-item list-group-item-action" onclick="seleccionarDoctor(${medico.id})">${medico.registro} - ${medico.nombre}</button>`;
        }).join('') : '<div class="text-muted">No se encontraron doctores.</div>';
    }

    function actualizarResultadosPacientes(query) {
        console.log('actualizarResultadosPacientes() - query:', query);
        console.log('pacientesData:', pacientesData);
        const container = document.getElementById('pacienteSearchResults');
        if (!container) {
            console.error('No se encontró container pacienteSearchResults');
            return;
        }
        const texto = query.trim().toLowerCase();
        const resultados = pacientesData.filter(paciente => {
            return paciente.num_doc.toLowerCase().includes(texto)
                || paciente.nombre.toLowerCase().includes(texto)
                || paciente.apellido.toLowerCase().includes(texto);
        });

        console.log('Resultados encontrados:', resultados);

        container.innerHTML = resultados.length > 0 ? resultados.map(paciente => {
            return `<button type="button" class="list-group-item list-group-item-action" onclick="seleccionarPaciente(${paciente.id})">${paciente.num_doc} - ${paciente.nombre} ${paciente.apellido}</button>`;
        }).join('') : '<div class="text-muted">No se encontraron pacientes.</div>';
    }

    function actualizarResultadosSalas(query) {
        console.log('actualizarResultadosSalas() - query:', query);
        console.log('salasData:', salasData);
        const container = document.getElementById('salaSearchResults');
        if (!container) {
            console.error('No se encontró container salaSearchResults');
            return;
        }
        const texto = query.trim().toLowerCase();
        const resultados = salasData.filter(sala => {
            return sala.tipo.toLowerCase().includes(texto) || sala.numero.toLowerCase().includes(texto);
        });

        console.log('Resultados encontrados:', resultados);

        container.innerHTML = resultados.length > 0 ? resultados.map(sala => {
            return `<button type="button" class="list-group-item list-group-item-action" onclick="seleccionarSala(${sala.id})">${sala.tipo} - ${sala.numero}</button>`;
        }).join('') : '<div class="text-muted">No se encontraron salas.</div>';
    }

    function actualizarResultadosTipoConsulta(query) {
        console.log('actualizarResultadosTipoConsulta() - query:', query);
        console.log('tipoConsultasData:', tipoConsultasData);
        const container = document.getElementById('tipoConsultaSearchResults');
        if (!container) {
            console.error('No se encontró container tipoConsultaSearchResults');
            return;
        }
        const texto = query.trim().toLowerCase();
        const resultados = tipoConsultasData.filter(tipo => {
            return tipo.descripcion.toLowerCase().includes(texto) || tipo.duracion.toLowerCase().includes(texto);
        });

        console.log('Resultados encontrados:', resultados);

        container.innerHTML = resultados.length > 0 ? resultados.map(tipo => {
            return `<button type="button" class="list-group-item list-group-item-action" onclick="seleccionarTipoConsulta(${tipo.id})">${tipo.descripcion} - ${tipo.duracion}</button>`;
        }).join('') : '<div class="text-muted">No se encontraron tipos.</div>';
    }

    function buscarPacientePorCodigo(texto) {
        if (!texto) return null;
        const busqueda = texto.trim().toLowerCase();
        return pacientesData.find(paciente =>
            paciente.num_doc.toLowerCase() === busqueda
            || `${paciente.nombre} ${paciente.apellido}`.toLowerCase() === busqueda
            || paciente.id.toString() === busqueda
        ) || null;
    }

    function buscarDoctorPorMatricula(texto) {
        console.log('buscarDoctorPorMatricula() - texto:', texto);
        console.log('medicosData:', medicosData);
        if (!texto) return null;
        const busqueda = texto.trim().toLowerCase();
        const resultado = medicosData.find(medico =>
            medico.registro.toLowerCase() === busqueda
            || medico.nombre.toLowerCase() === busqueda
            || `${medico.registro} ${medico.nombre}`.toLowerCase() === busqueda
        ) || null;
        console.log('Resultado encontrado:', resultado);
        return resultado;
    }

    function buscarSalaPorNombre(texto) {
        if (!texto) return null;
        const busqueda = texto.trim().toLowerCase();
        return salasData.find(sala =>
            `${sala.tipo} ${sala.numero}`.toLowerCase() === busqueda
            || sala.tipo.toLowerCase() === busqueda
            || sala.numero.toLowerCase() === busqueda
        ) || null;
    }

    function buscarTipoConsultaPorNombre(texto) {
        if (!texto) return null;
        const busqueda = texto.trim().toLowerCase();
        return tipoConsultasData.find(tipo =>
            tipo.descripcion.toLowerCase() === busqueda
            || `${tipo.descripcion} ${tipo.duracion}`.toLowerCase() === busqueda
        ) || null;
    }

    function seleccionarDoctor(id) {
        const doctorInput = document.getElementById('doctor_nombre');
        const doctorIdInput = document.getElementById('medico_id');
        const doctor = medicosData.find(item => item.id === id);
        if (!doctor || !doctorInput || !doctorIdInput) return;

        doctorInput.value = `${doctor.registro} - ${doctor.nombre}`;
        doctorIdInput.value = doctor.id;
        $('#buscarDoctorModal').modal('hide');
    }

    function seleccionarPaciente(id) {
        const pacienteInput = document.getElementById('paciente_codigo');
        const pacienteIdInput = document.getElementById('paciente_id');
        const paciente = pacientesData.find(item => item.id === id);
        if (!paciente || !pacienteInput || !pacienteIdInput) return;

        pacienteInput.value = `${paciente.num_doc} - ${paciente.nombre} ${paciente.apellido}`;
        pacienteIdInput.value = paciente.id;
        $('#buscarPacienteModal').modal('hide');
    }

    function seleccionarSala(id) {
        const salaInput = document.getElementById('sala_nombre');
        const salaIdInput = document.getElementById('sala_id');
        const sala = salasData.find(item => item.id === id);
        if (!sala || !salaInput || !salaIdInput) return;

        salaInput.value = `${sala.tipo} - ${sala.numero}`;
        salaIdInput.value = sala.id;
        $('#buscarSalaModal').modal('hide');
    }

    function seleccionarTipoConsulta(id) {
        const tipoInput = document.getElementById('tipo_consulta_nombre');
        const tipoIdInput = document.getElementById('tipo_consulta_id');
        const tipo = tipoConsultasData.find(item => item.id === id);
        if (!tipo || !tipoInput || !tipoIdInput) return;

        tipoInput.value = `${tipo.descripcion} - ${tipo.duracion}`;
        tipoIdInput.value = tipo.id;
        $('#buscarTipoConsultaModal').modal('hide');
    }


    // Función para Cancelar Cita
    function cancelarCita(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se marcará esta cita como Cancelada.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cancelar cita',
            cancelButtonText: 'No, mantener'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/citas/cancelar/' + id)
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire(
                            'Cancelada!',
                            'La cita ha sido cancelada correctamente.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Ocurrió un problema al cancelar la cita.', 'error');
                    });
            }
        });
    }

    // Función para Reagendar (editar) Cita
    function reagendarCita(id) {
        // Obtenemos los datos de la cita vía AJAX
        fetch('/citas/editar/' + id)
            .then(response => response.json())
            .then(data => {
                // Rellenamos el formulario del modal
                document.getElementById('idx').value = data.id_cita;
                document.getElementById('doctor').value = data.medico_id;
                
                // Buscar sala
                const sala = salasData.find(s => s.id === data.sala_id);
                if (sala) {
                    document.getElementById('sala_nombre').value = `${sala.tipo} - ${sala.numero}`;
                    document.getElementById('sala_id').value = data.sala_id;
                }
                
                document.getElementById('fechaHoraIni').value = data.fec_inicio;
                document.getElementById('fechaHoraFin').value = data.fec_fin;
                
                // Buscar paciente
                const paciente = pacientesData.find(p => p.id === data.paciente_id);
                if (paciente) {
                    document.getElementById('paciente_codigo').value = `${paciente.num_doc} - ${paciente.nombre} ${paciente.apellido}`;
                    document.getElementById('paciente_id').value = data.paciente_id;
                }
                
                // Buscar tipo de consulta
                const tipo = tipoConsultasData.find(t => t.id === data.tipo_consulta_id);
                if (tipo) {
                    document.getElementById('tipo_consulta_nombre').value = `${tipo.descripcion} - ${tipo.duracion}`;
                    document.getElementById('tipo_consulta_id').value = data.tipo_consulta_id;
                }
                
                document.getElementById('entreCitas').checked = data.entreCitas == 1;
                document.getElementById('notas').value = data.observaciones;

                // Abrimos el modal
                $('#cargarCita').modal('show');
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudieron cargar los datos de la cita.', 'error');
            });
    }
</script>
@if(session('flash_notification'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flashNotifications = @json(session('flash_notification'));
        flashNotifications.forEach(message => {
            let icon = 'info';
            if (message.level === 'success') icon = 'success';
            if (message.level === 'error' || message.level === 'danger') icon = 'error';
            if (message.level === 'warning') icon = 'warning';

            Swal.fire({
                title: message.title || (icon === 'success' ? '¡Listo!' : icon === 'warning' ? 'Atención' : 'Error'),
                html: `<p>${message.message}</p>`,
                icon: icon,
                confirmButtonText: 'Aceptar',
                showCloseButton: true,
                width: 460,
                background: icon === 'success' ? '#f4fcf6' : '#fff5f5',
                iconColor: icon === 'success' ? '#28a745' : '#dc3545',
                customClass: {
                    popup: 'rounded-4 shadow-sm',
                    confirmButton: icon === 'success' ? 'btn btn-success px-4' : 'btn btn-danger px-4'
                }
            });
        });
    });
</script>
@endif
@stop