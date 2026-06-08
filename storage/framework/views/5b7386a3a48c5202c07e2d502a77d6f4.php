

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .card-stat {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    .card-stat:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    .card-stat .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .card-stat .card-body {
        padding: 20px 24px;
    }
    .card-stat .stat-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }
    .card-stat .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }
    .table-modern thead th {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
        border: none;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px;
    }
    .table-modern tbody tr {
        transition: background 0.15s;
    }
    .table-modern tbody tr:hover {
        background-color: #f0fafc;
    }
    .table-modern tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-color: #e9f5f8;
        font-size: 0.9rem;
    }
    .badge-estado {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .card-table {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .card-table .card-header {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
        border: none;
        padding: 16px 24px;
        font-weight: 600;
        font-size: 1rem;
    }
    .dashboard-page {
        margin: 0 auto;
        padding: 0 20px 40px;
        max-width: 1300px;
        transition: all 0.2s ease;
    }
    @media (max-width: 1200px) {
        .dashboard-page {
            padding: 0 16px 40px;
        }
    }
    .accesos-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
    }
    @media (max-width: 992px) {
        .accesos-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 768px) {
        .accesos-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
    .acceso-rapido {
        border: none;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-decoration: none;
        display: block;
        overflow: hidden;
        position: relative;
    }
    .acceso-rapido::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: currentColor;
        opacity: 0.8;
    }
    .acceso-rapido:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    .acceso-rapido .card-body {
        min-height: 190px;
        padding: 30px 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .acceso-rapido i {
        font-size: 4.5rem;
        margin-bottom: 16px;
        transition: transform 0.3s ease;
    }
    .acceso-rapido:hover i {
        transform: scale(1.15);
    }
    .acceso-rapido p {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 0.02em;
    }
    .bienvenida-card {
        background: linear-gradient(135deg, #17a2b8 0%, #0d6efd 100%);
        border: none;
        border-radius: 16px;
        color: white;
        box-shadow: 0 4px 20px rgba(23,162,184,0.3);
    }
</style>

<div class="dashboard-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-dark font-weight-bold" style="font-size: 2rem;">
                <i class="fas fa-heartbeat" style="color:#17a2b8;"></i>
                Panel Principal
            </h1>
        </div>
        <span class="text-muted"><?php echo e(\Carbon\Carbon::now()->format('d/m/Y')); ?></span>
    </div>

    <!-- Bienvenida -->
    <div class="card bienvenida-card mb-4">
        <div class="card-body d-flex align-items-center justify-content-between" style="padding: 24px 30px;">
            <div>
                <h4 class="mb-1 font-weight-bold">¡Bienvenido, <?php echo e(auth()->user()->name); ?>! 👋</h4>
                <p class="mb-0" style="opacity:0.85;">VitalSys — Panel de administración</p>
            </div>
            <i class="fas fa-hospital" style="font-size: 3.5rem; opacity:0.3;"></i>
        </div>
    </div>

    <!-- Accesos Rápidos -->
<div class="accesos-rapidos-section mb-4">
    <div class="mb-2">
        <h5 class="font-weight-bold text-dark">
            <i class="fas fa-th" style="color:#17a2b8;"></i> Accesos Rápidos
        </h5>
    </div>
    <div class="accesos-grid">
        <a href="/citas" class="acceso-rapido card" style="color:#17a2b8;">
            <div class="card-body">
                <i class="fas fa-calendar-alt"></i>
                <p>Citas</p>
            </div>
        </a>
        <a href="/pacientes" class="acceso-rapido card" style="color:#0d6efd;">
            <div class="card-body">
                <i class="fas fa-users"></i>
                <p>Pacientes</p>
            </div>
        </a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver medicos')): ?>
        <a href="/medicos" class="acceso-rapido card" style="color:#28a745;">
            <div class="card-body">
                <i class="fas fa-user-md"></i>
                <p>Médicos</p>
            </div>
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver salas')): ?>
        <a href="/registro-sala" class="acceso-rapido card" style="color:#fd7e14;">
            <div class="card-body">
                <i class="fas fa-door-open"></i>
                <p>Salas</p>
            </div>
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver especialidades')): ?>
        <a href="/especialidades" class="acceso-rapido card" style="color:#6f42c1;">
            <div class="card-body">
                <i class="fas fa-stethoscope"></i>
                <p>Especialidades</p>
            </div>
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver usuarios')): ?>
        <a href="/usuarios" class="acceso-rapido card" style="color:#dc3545;">
            <div class="card-body">
                <i class="fas fa-user-cog"></i>
                <p>Usuarios</p>
            </div>
        </a>
        <?php endif; ?>
    </div>
</div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\USUARIO\Desktop\TFG\VitalSys-main\resources\views/dashboard.blade.php ENDPATH**/ ?>