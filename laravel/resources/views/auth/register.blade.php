@php
    // Procesar registro
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        $usersFile = storage_path('app/users.json');
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        $email = $_POST['email'] ?? '';
        $error = '';
        $success = '';

        // Verificar email duplicado
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $error = 'El email ya está registrado';
                break;
            }
        }

        if (!$error) {
            $newUser = [
                'id' => count($users) + 1,
                'nombre_completo' => $_POST['nombre_completo'],
                'email' => $email,
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'telefono' => $_POST['telefono'],
                'universidad' => $_POST['universidad'],
                'carrera' => $_POST['carrera'],
                'ciclo' => $_POST['ciclo'],
                'rol' => 'estudiante',
                'fecha_registro' => date('Y-m-d H:i:s')
            ];

            $users[] = $newUser;
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

            $success = '¡Registro exitoso! Redirigiendo...';
            echo "<script>setTimeout(() => window.location.href = '/login', 2000);</script>";
        }
    }
@endphp

@extends('layouts.app')
@section('title', 'Registro - YACHAY')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--primary-50), var(--secondary-50)); padding: var(--spacing-2xl);">
    <div class="card" style="max-width: 550px; width: 100%; padding: var(--spacing-2xl);">

        <div style="text-align: center; margin-bottom: var(--spacing-2xl);">
            <div style="width: 80px; height: 80px; margin: 0 auto var(--spacing-lg);
                        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                        border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-plus" style="font-size: 2.5rem; color: white;"></i>
            </div>
            <h2>Crear Cuenta</h2>
            <p style="color: var(--text-secondary);">Únete a la comunidad YACHAY</p>
        </div>

        @if(isset($success))
            <div style="padding: var(--spacing-md); background: #10b981; color: white;
                        border-radius: var(--radius-md); margin-bottom: var(--spacing-lg); text-align: center;">
                <i class="fas fa-check-circle"></i> {{ $success }}
            </div>
        @endif

        @if(isset($error))
            <div style="padding: var(--spacing-md); background: #ef4444; color: white;
                        border-radius: var(--radius-md); margin-bottom: var(--spacing-lg); text-align: center;">
                <i class="fas fa-exclamation-circle"></i> {{ $error }}
            </div>
        @endif

        <form method="POST" action="{{ url('/registro') }}">
            @csrf

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-user"></i> Nombre Completo
                </label>
                <input type="text" name="nombre_completo" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" name="email" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" name="password" required minlength="6"
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-phone"></i> Teléfono
                </label>
                <input type="tel" name="telefono" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-university"></i> Universidad
                </label>
                <input type="text" name="universidad" value="Universidad Continental" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-book"></i> Carrera
                </label>
                <select name="carrera" required
                        style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                               border-radius: var(--radius-md); font-size: var(--text-base);">
                    <option value="">Selecciona tu carrera</option>
                    <option value="Ing. de Sistemas">Ing. de Sistemas e Informática</option>
                    <option value="Ing. Industrial">Ing. Industrial</option>
                    <option value="Ing. Civil">Ing. Civil</option>
                    <option value="Administración">Administración</option>
                    <option value="Contabilidad">Contabilidad</option>
                </select>
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-graduation-cap"></i> Ciclo
                </label>
                <select name="ciclo" required
                        style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                               border-radius: var(--radius-md); font-size: var(--text-base);">
                    <option value="">Selecciona tu ciclo</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">Ciclo {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" name="register" class="btn btn-primary" style="width: 100%; margin-bottom: var(--spacing-md);">
                <i class="fas fa-rocket"></i> Crear Cuenta
            </button>
        </form>

        <div style="text-align: center; margin-top: var(--spacing-lg);">
            <p style="color: var(--text-secondary);">
                ¿Ya tienes cuenta?
                <a href="{{ url('/login') }}" style="color: var(--primary-600); font-weight: 600;">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
