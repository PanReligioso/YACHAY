@php
    $isLoggedIn = session('logged_in', false);
    $userName = session('user_name', '');
@endphp

<header class="header" id="header">
    <div class="container">
        <div class="header-container">

            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span class="logo-text">YACHAY</span>
            </a>

            <nav class="nav" id="navMenu">
                <ul class="nav-menu">
                    <li>
                        <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/libros') }}" class="nav-link {{ request()->is('libros*') ? 'active' : '' }}">
                            Libros
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/apuntes') }}" class="nav-link {{ request()->is('apuntes*') ? 'active' : '' }}">
                            Apuntes
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/tutorias') }}" class="nav-link {{ request()->is('tutorias*') ? 'active' : '' }}">
                            Tutorías
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/comedores') }}" class="nav-link {{ request()->is('comedores*') ? 'active' : '' }}">
                            Comedores
                        </a>
                    </li>
                </ul>

                <div class="nav-actions">

                    {{-- BOTÓN DE MODO OSCURO (AÑADIDO AQUÍ) --}}
                    <button id="theme-toggle" class="btn btn-icon" aria-label="Cambiar Tema"
                            style="width: 45px; height: 45px; border-radius: var(--radius-full);
                                   background: var(--bg-secondary); border: 2px solid var(--primary-200);
                                   display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-moon" id="theme-icon" style="color: var(--text-primary); font-size: var(--text-lg);"></i>
                    </button>

                    @if(!$isLoggedIn)
                        <a href="{{ url('/login') }}" class="btn btn-secondary hide-mobile">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </a>
                        <a href="{{ url('/registro') }}" class="btn btn-primary hide-mobile">
                            <i class="fas fa-user-plus"></i>
                            Registrarse
                        </a>
                    @else
                        <span style="margin-right: var(--spacing-md); color: var(--primary-600); font-weight: 600;">
                            <i class="fas fa-user-circle"></i> {{ $userName }}
                        </span>
                        <a href="{{ url('/login?logout=1') }}" class="btn btn-outline"
                           onclick="return confirm('¿Seguro que quieres cerrar sesión?')">
                            <i class="fas fa-sign-out-alt"></i>
                            Salir
                        </a>
                    @endif
                </div>
            </nav>

            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </div>
</header>
