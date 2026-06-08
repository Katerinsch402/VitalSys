<?php $__env->startSection('title', 'Pacientes'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-user-injured fa-lg mr-2" style="color:#17a2b8;"></i>
            <h1 class="font-weight-bold mb-0">Gestión de Pacientes</h1>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .card-main { border:none; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
        .card-main .card-header { background:linear-gradient(135deg,#17a2b8,#138496); color:white; padding:16px 24px; font-weight:600; }
        .form-control, .custom-select { border-radius:8px; border:1.5px solid #dee2e6; padding:10px 14px; font-size:0.92rem; }
        .form-control:focus, .custom-select:focus { border-color:#17a2b8; box-shadow:0 0 0 0.2rem rgba(23,162,184,0.2); }
        .table thead th { background:linear-gradient(135deg,#17a2b8,#138496); color:white; border:none; padding:14px 16px; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.05em; }
        .table tbody tr:hover { background:#f0fafc; }
        .table tbody td { padding:12px 16px; vertical-align:middle; border-color:#e9f5f8; }
        .btn-nuevo { background:linear-gradient(135deg,#17a2b8,#138496); border:none; border-radius:8px; padding:10px 20px; color:white; font-weight:600; }
        .section-search { min-width:320px; }
    </style>

    <div class="card card-main mb-4">
        <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
            <div class="mb-3 mb-md-0">
                <span><i class="fas fa-list mr-2"></i> Lista de Pacientes</span>
            </div>
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                <form action="<?php echo e(route('pacientes.index')); ?>" method="GET" class="form-inline mr-sm-3 mb-2 mb-sm-0 section-search">
                    <div class="input-group input-group-sm w-100">
                        <input type="search" name="buscarpor" class="form-control" placeholder="Buscar por nombre" aria-label="search">
                        <div class="input-group-append">
                            <button class="btn btn-light" type="submit" style="border-radius:0 8px 8px 0;">Buscar</button>
                        </div>
                    </div>
                </form>
                <a href="/registro-paciente" class="btn btn-nuevo">Nuevo</a>
            </div>
        </div>

        <div class="card-body p-0">
            <?php echo $__env->make('flash::message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="table-responsive">
                <table class="table table-border mb-0" id="tabla">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>C.I</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Sexo</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Departamento</th>
                            <th>IPS</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! (empty($pacientes))): ?>
                            <?php $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($paciente->cod_paciente); ?></td>
                                    <td><?php echo e($paciente->num_doc); ?></td>
                                    <td><?php echo e($paciente->nombre); ?></td>
                                    <td><?php echo e($paciente->apellido); ?></td>
                                    <td><?php echo e($paciente->sexo); ?></td>
                                    <td><?php echo e($paciente->direccion); ?></td>
                                    <td><?php echo e($paciente->ciudad); ?></td>
                                    <td><?php echo e($paciente->departamento); ?></td>
                                    <td><?php echo e($paciente->tiene_ips); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('pacientes.edit', $paciente->id_paciente)); ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="<?php echo e(route('pacientes.historial', $paciente->id_paciente)); ?>" class="btn btn-sm btn-info" target="_blank" title="Descargar Historial PDF">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                        <form action="<?php echo e(route('pacientes.delete', $paciente->id_paciente)); ?>" method="POST" style="display: inline-block;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">No hay registros</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(session('mensaje')): ?>
    <script>
        Swal.fire({
            title: "¡Guardado con éxito!",
            text: "El paciente se creó correctamente",
            icon: "success"
        });
    </script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\USUARIO\Desktop\TFG\VitalSys-main\resources\views/pacientes/index.blade.php ENDPATH**/ ?>