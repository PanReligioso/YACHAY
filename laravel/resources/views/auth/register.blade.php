
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
            <p style="color: var(--text-secondary);">Unete a la comunidad YACHAY</p>
        </div>

        @if($errors->any())
            <div style="padding: var(--spacing-md); background: #ef4444; color: white;
                        border-radius: var(--radius-md); margin-bottom: var(--spacing-lg); text-align: center;">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf //directiva de proteccion CSRF

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-user"></i> Nombre
                </label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-user"></i> Apellido
                </label>
                <input type="text" name="apellido" value="{{ old('apellido') }}" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <div style="margin-bottom: var(--spacing-md);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-id-card"></i> Codigo Universitario (opcional)
                </label>
                <input type="text" name="codigo_universitario" value="{{ old('codigo_universitario') }}"
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

            <div style="margin-bottom: var(--spacing-lg);">
                <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                    <i class="fas fa-lock"></i> Confirmar Contraseña
                </label>
                <input type="password" name="password_confirmation" required minlength="6"
                       style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                              border-radius: var(--radius-md); font-size: var(--text-base);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: var(--spacing-md);">
                <i class="fas fa-rocket"></i> Crear Cuenta
            </button>
        </form>

        <div style="text-align: center; margin: var(--spacing-lg) 0; color: var(--text-secondary);">
            <span>─────── O ───────</span>
        </div>

        <a href="{{ route('google.login') }}" class="btn"
           style="width: 100%; display: flex; align-items: center; justify-content: center; gap: var(--spacing-sm);
                  background: white; color: #333; border: 2px solid var(--primary-200); margin-bottom: var(--spacing-md);">
            <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuar con Google
        </a>

        <div style="text-align: center; margin-top: var(--spacing-lg);">
            <p style="color: var(--text-secondary);">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" style="color: var(--primary-600); font-weight: 600;">
                    Inicia sesion aqui
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
