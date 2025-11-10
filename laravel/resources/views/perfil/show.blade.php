// resources/views/perfil/show.blade.php
@extends('layouts.app')
@section('title', 'Mi Perfil - YACHAY')

@section('content')
<div style="min-height: 100vh; background: var(--bg-primary); padding: var(--spacing-2xl) 0;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">

        {{-- Header del Perfil --}}
        <div class="card" style="margin-bottom: var(--spacing-xl); text-align: center; padding: var(--spacing-2xl);">
            <div style="margin-bottom: var(--spacing-lg);">
                @if($usuario->avatar)
                    <img src="{{ $usuario->avatar }}" alt="Avatar"
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
                                border: 4px solid var(--primary-500); margin: 0 auto;">
                @else
                    <div style="width: 120px; height: 120px; margin: 0 auto; border-radius: 50%;
                                background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user" style="font-size: 3rem; color: white;"></i>
                    </div>
                @endif
            </div>

            <h1 style="margin-bottom: var(--spacing-sm);">
                {{ $usuario->nombre }} {{ $usuario->apellido }}
            </h1>
            <p style="color: var(--text-secondary); font-size: var(--text-lg);">
                <i class="fas fa-envelope"></i> {{ $usuario->email }}
            </p>
            @if($usuario->codigo_universitario)
                <p style="color: var(--text-secondary);">
                    <i class="fas fa-id-card"></i> Código: {{ $usuario->codigo_universitario }}
                </p>
            @endif
            <div style="margin-top: var(--spacing-md);">
                <span class="badge" style="background: var(--primary-100); color: var(--primary-600);
                                           padding: var(--spacing-xs) var(--spacing-md); border-radius: var(--radius-full);">
                    <i class="fas fa-user-tag"></i> {{ $usuario->rol->nombre ?? 'Estudiante' }}
                </span>
            </div>
        </div>

        {{-- Información Detallada --}}
        <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-xl);">
            <h2 style="margin-bottom: var(--spacing-xl); display: flex; align-items: center; gap: var(--spacing-sm);">
                <i class="fas fa-info-circle" style="color: var(--primary-600);"></i>
                Información de la Cuenta
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-lg);">
                <div style="padding: var(--spacing-lg); background: var(--bg-secondary); border-radius: var(--radius-md);">
                    <div style="display: flex; align-items: center; gap: var(--spacing-sm); margin-bottom: var(--spacing-sm);">
                        <i class="fas fa-user" style="color: var(--primary-600);"></i>
                        <strong>Nombre Completo</strong>
                    </div>
                    <p style="color: var(--text-secondary);">{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                </div>

                <div style="padding: var(--spacing-lg); background: var(--bg-secondary); border-radius: var(--radius-md);">
                    <div style="display: flex; align-items: center; gap: var(--spacing-sm); margin-bottom: var(--spacing-sm);">
                        <i class="fas fa-envelope" style="color: var(--primary-600);"></i>
                        <strong>Correo Electrónico</strong>
                    </div>
                    <p style="color: var(--text-secondary);">{{ $usuario->email }}</p>
                </div>

                @if($usuario->codigo_universitario)
                <div style="padding: var(--spacing-lg); background: var(--bg-secondary); border-radius: var(--radius-md);">
                    <div style="display: flex; align-items: center; gap: var(--spacing-sm); margin-bottom: var(--spacing-sm);">
                        <i class="fas fa-id-card" style="color: var(--primary-600);"></i>
                        <strong>Código Universitario</strong>
                    </div>
                    <p style="color: var(--text-secondary);">{{ $usuario->codigo_universitario }}</p>
                </div>
                @endif

                <div style="padding: var(--spacing-lg); background: var(--bg-secondary); border-radius: var(--radius-md);">
                    <div style="display: flex; align-items: center; gap: var(--spacing-sm); margin-bottom: var(--spacing-sm);">
                        <i class="fas fa-calendar-alt" style="color: var(--primary-600);"></i>
                        <strong>Miembro desde</strong>
                    </div>
                    <p style="color: var(--text-secondary);">{{ $usuario->created_at->format('d/m/Y') }}</p>
                </div>

                @if($usuario->google_id)
                <div style="padding: var(--spacing-lg); background: var(--bg-secondary); border-radius: var(--radius-md);">
                    <div style="display: flex; align-items: center; gap: var(--spacing-sm); margin-bottom: var(--spacing-sm);">
                        <i class="fab fa-google" style="color: var(--primary-600);"></i>
                        <strong>Cuenta vinculada</strong>
                    </div>
                    <p style="color: var(--text-secondary);">Google Account</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Formulario de Edición --}}
        <div class="card" style="padding: var(--spacing-2xl);">
            <h2 style="margin-bottom: var(--spacing-xl); display: flex; align-items: center; gap: var(--spacing-sm);">
                <i class="fas fa-edit" style="color: var(--primary-600);"></i>
                Editar Perfil
            </h2>

            @if(session('success'))
                <div style="padding: var(--spacing-md); background: #10b981; color: white;
                            border-radius: var(--radius-md); margin-bottom: var(--spacing-lg); text-align: center;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="padding: var(--spacing-md); background: #ef4444; color: white;
                            border-radius: var(--radius-md); margin-bottom: var(--spacing-lg);">
                    <ul style="margin: 0; padding-left: var(--spacing-lg);">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('perfil.update') }}">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
                    <div>
                        <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                            <i class="fas fa-user"></i> Nombre
                        </label>
                        <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base);">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                            <i class="fas fa-user"></i> Apellido
                        </label>
                        <input type="text" name="apellido" value="{{ old('apellido', $usuario->apellido) }}" required
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base);">
                    </div>
                </div>

                <div style="margin-bottom: var(--spacing-lg);">
                    <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required
                           style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                  border-radius: var(--radius-md); font-size: var(--text-base);">
                </div>

                <div style="margin-bottom: var(--spacing-lg);">
                    <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                        <i class="fas fa-id-card"></i> Código Universitario (opcional)
                    </label>
                    <input type="text" name="codigo_universitario" value="{{ old('codigo_universitario', $usuario->codigo_universitario) }}"
                           style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                  border-radius: var(--radius-md); font-size: var(--text-base);">
                </div>

                <hr style="margin: var(--spacing-xl) 0; border: none; border-top: 2px solid var(--primary-100);">

                <h3 style="margin-bottom: var(--spacing-lg);">Cambiar Contraseña (opcional)</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
                    <div>
                        <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                            <i class="fas fa-lock"></i> Nueva Contraseña
                        </label>
                        <input type="password" name="password" minlength="6"
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base);">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600;">
                            <i class="fas fa-lock"></i> Confirmar Contraseña
                        </label>
                        <input type="password" name="password_confirmation" minlength="6"
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base);">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
