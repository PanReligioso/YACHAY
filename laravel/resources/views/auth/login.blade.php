@php
    // Procesar login
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $usersFile = storage_path('app/users.json');
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';
        $found = false;

        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                session([
                    'user_id' => $user['id'],
                    'user_name' => $user['nombre_completo'],
                    'user_email' => $user['email'],
                    'logged_in' => true
                ]);
                $found = true;
                echo "<script>window.location.href = '/';</script>";
                exit;
            }
        }

        if (!$found) {
            $error = 'Credenciales incorrectas';
        }
    }

    // Procesar logout
    if (isset($_GET['logout'])) {
        session()->flush();
        echo "<script>window.location.href = '/login';</script>";
        exit;
    }
@endphp

@extends('layouts.app')
@section('title', 'Iniciar Sesión - YACHAY')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--primary-50), var(--secondary-50)); padding: var(--spacing-2xl);">
    <div class="card" style="max-width: 450px; width: 100%; padding: var(--spacing-2xl);">

        <div style="text-align: center; margin-bottom: var(--spacing-2xl);">
            <div style="width: 80px; height: 80px; margin: 0 auto var(--spacing-lg);
                        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                        border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-graduation-cap" style="font-size: 2.5rem; color: white;"></i>
            </div>
            <h2>Iniciar Sesión</h2>
            <p style="color: var(--text-secondary);">Bienvenido de vuelta a YACHAY</p>
        </div>

        @if(isset($error))
            <div style="padding: var(--spacing-md); background: #ef4444; color: white;
                        border-radius: var(--radius-md); margin-bottom: var(--spacing-lg); text-align: center;">
                <i class="fas fa-exclamation-circle"></i> {{ $error }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div style="margin-bottom: var(--spacing-lg);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" name="email" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" name="password" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; margin-bottom: var(--spacing-md);">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div style="text-align: center; margin-top: var(--spacing-lg);">
            <p style="color: var(--text-secondary);">
                ¿No tienes cuenta?
                <a href="{{ url('/registro') }}" style="color: var(--primary-600); font-weight: 600;">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
