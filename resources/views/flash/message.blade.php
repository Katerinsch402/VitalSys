@if (session('flash_message'))
<style>
    .success-panel {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 2px solid #28a745;
        color: #155724;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        position: relative;
        overflow: hidden;
        animation: slideInSuccess 0.5s ease-out;
        margin-bottom: 20px;
    }
    .success-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #28a745, #1e7e34);
    }
    .success-panel .success-icon {
        font-size: 2rem;
        color: #28a745;
        margin-bottom: 12px;
        animation: bounceIn 0.6s ease-out;
    }
    .success-panel .success-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #155724;
    }
    .success-panel .success-message {
        font-size: 0.95rem;
        margin-bottom: 12px;
    }
    .error-panel {
        background: linear-gradient(135deg, #ffe6e6, #ffcccc);
        border: 2px solid #ff6b6b;
        color: #d63031;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
        position: relative;
        overflow: hidden;
        animation: slideInError 0.5s ease-out;
        margin-bottom: 20px;
    }
    .error-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #ff6b6b, #e74c3c);
    }
    .error-panel .error-icon {
        font-size: 2rem;
        color: #e74c3c;
        margin-bottom: 12px;
        animation: bounceIn 0.6s ease-out;
    }
    .error-panel .error-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #d63031;
    }
    .error-panel .error-message {
        font-size: 0.95rem;
        margin-bottom: 12px;
    }
    .warning-panel {
        background: linear-gradient(135deg, #fff3cd, #ffe69c);
        border: 2px solid #ffc107;
        color: #856404;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
        position: relative;
        overflow: hidden;
        animation: slideInWarning 0.5s ease-out;
        margin-bottom: 20px;
    }
    .warning-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #ffc107, #ff9800);
    }
    .warning-panel .warning-icon {
        font-size: 2rem;
        color: #ff9800;
        margin-bottom: 12px;
        animation: bounceIn 0.6s ease-out;
    }
    .warning-panel .warning-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #856404;
    }
    .warning-panel .warning-message {
        font-size: 0.95rem;
        margin-bottom: 12px;
    }
    .info-panel {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb);
        border: 2px solid #17a2b8;
        color: #0c5460;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.2);
        position: relative;
        overflow: hidden;
        animation: slideInInfo 0.5s ease-out;
        margin-bottom: 20px;
    }
    .info-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #17a2b8, #138496);
    }
    .info-panel .info-icon {
        font-size: 2rem;
        color: #17a2b8;
        margin-bottom: 12px;
        animation: bounceIn 0.6s ease-out;
    }
    .info-panel .info-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #0c5460;
    }
    .info-panel .info-message {
        font-size: 0.95rem;
        margin-bottom: 12px;
    }
    @keyframes slideInSuccess { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes slideInError { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes slideInWarning { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes slideInInfo { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); opacity: 1; } }
    .alert-close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .alert-close-btn:hover {
        opacity: 1;
    }
</style>

<div class="alert-container" role="alert">
    @if (session('flash_message.message'))
        @php
            $level = session('flash_message.level', 'info');
        @endphp

        @if ($level === 'success')
            <div class="success-panel alert alert-success alert-dismissible fade show" role="alert">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="success-title">¡Éxito!</div>
                <div class="success-message">
                    {{ session('flash_message.message') }}
                </div>
                <button type="button" class="alert-close-btn" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif ($level === 'error')
            <div class="error-panel alert alert-danger alert-dismissible fade show" role="alert">
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="error-title">¡Error!</div>
                <div class="error-message">
                    {{ session('flash_message.message') }}
                </div>
                <button type="button" class="alert-close-btn" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif ($level === 'warning')
            <div class="warning-panel alert alert-warning alert-dismissible fade show" role="alert">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="warning-title">¡Atención!</div>
                <div class="warning-message">
                    {{ session('flash_message.message') }}
                </div>
                <button type="button" class="alert-close-btn" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @else
            <div class="info-panel alert alert-info alert-dismissible fade show" role="alert">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-title">Información</div>
                <div class="info-message">
                    {{ session('flash_message.message') }}
                </div>
                <button type="button" class="alert-close-btn" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @endif
</div>

<script>
    // Auto-dismiss alerts after 40 seconds
    document.querySelectorAll('.success-panel, .error-panel, .warning-panel, .info-panel').forEach(function(alert) {
        setTimeout(function() {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 40000);
    });
</script>
@endif
