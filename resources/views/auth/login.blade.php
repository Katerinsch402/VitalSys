<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- CSS -->
    @vite(['resources/css/login-style.css'])
    <!-- Inline temporal para forzar estilo de login (eliminar después) -->
    <style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #e9f4fb 0%, #fefefe 100%);
        font-family: 'Inter', sans-serif;
    }
    .login-wrapper {
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 36px 14px;
    }
    .login-card {
        width: 440px;
        max-width: 95vw;
        background: #ffffff;
        border-radius: 28px;
        box-shadow: 0 28px 70px rgba(15, 102, 165, 0.14);
        position: relative;
        overflow: hidden;
    }
    .card-top {
        height: 104px;
        background: linear-gradient(135deg, #0f6fa8 0%, #17a2b8 100%);
        border-radius: 28px 28px 0 0;
    }
    .avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: 44px;
        background: #f8fcff;
        box-shadow: 0 22px 44px rgba(15, 111, 168, 0.14);
        border: none;
    }
    .avatar img {
        width: 95%;
        height: 95%;
        object-fit: contain;
        border-radius: 50%;
    }
    .card-body {
        padding: 112px 28px 28px;
        text-align: left;
    }
    .project-title {
        text-align: center;
        margin: 0 0 8px;
        font-weight: 800;
        color: #0f6fa8;
        font-size: 1.35rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }
    .main-title {
        text-align: center;
        margin-top: 0;
        margin-bottom: 18px;
        font-weight: 800;
        color: #123f53;
        font-size: 1.05rem;
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-group label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #5e7182;
        display: block;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .form-control {
        height: 48px;
        border-radius: 16px;
        border: 1px solid #e6edf4;
        padding: 0 14px;
        background: #f8fbff;
        box-shadow: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus {
        box-shadow: 0 10px 30px rgba(15,111,168,0.1);
        border-color: #0f6fa8;
        outline: none;
    }
    .login-btn {
        background: linear-gradient(135deg, #17a2b8, #0f6fa8);
        border: none;
        color: #fff;
        font-weight: 700;
        height: 50px;
        font-size: 1rem;
        border-radius: 16px;
        box-shadow: 0 10px 28px rgba(15,111,168,0.18);
    }
    .login-btn:hover {
        background: linear-gradient(135deg, #0f6fa8, #0b5f8a);
    }
    .forgot-link {
        font-size: 0.92rem;
        color: #4c6276;
        text-decoration: none;
    }
    .forgot-link:hover {
        color: #0f6fa8;
    }
    @media (max-width: 480px) {
        .login-card { width: 92%; }
        .card-top { height: 84px; }
        .avatar { width: 96px; height: 96px; top: 50px; }
        .card-body { padding: 90px 18px 22px; }
        .main-title { font-size: 1.2rem; }
    }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="card-top"></div>

            <div class="avatar">
                <img src="{{ asset('vendor/adminlte/dist/img/logo.png') }}" alt="CSO">
            </div>

            <div class="card-body">
                <div class="project-title">VitalSys</div>
                <h2 class="main-title">Iniciar Sesión</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Usuario</label>
                        <input id="email" type="email" name="email" class="form-control" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="form-check mb-3">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label" for="remember_me">{{ __('Recordarme') }}</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary login-btn">{{ __('Entrar') }}</button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a class="forgot-link" href="{{ route('password.request') }}">{{ __('Olvidé mi contraseña') }}</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session("error") }}',
                icon: 'error',
            })
        </script>
    @endif
</body>
</html>
