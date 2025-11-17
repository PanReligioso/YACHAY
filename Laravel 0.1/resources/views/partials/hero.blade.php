<section class="hero">
    <div class="container">
        <div class="hero-container">

            <!-- Hero Content -->
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i>
                    <span>Universidad Continental</span>
                </div>

                <h1 class="hero-title">
                    Aprende, Comparte y Crece con
                    <span class="highlight">YACHAY</span>
                </h1>

                <p class="hero-description">
                    La plataforma educativa diseñada por estudiantes para estudiantes.
                    Accede a libros, apuntes, tutorías y encuentra los mejores comedores
                    universitarios. Todo en un solo lugar.
                </p>

                <div class="hero-actions">
                    @if(auth()->check())
                        <a href="{{ url('/libros/subir') }}" class="btn btn-primary">
                            <i class="fas fa-upload"></i>
                            Compartir Libro
                        </a>
                    @else
                        <a href="{{ url('/registro') }}" class="btn btn-primary">
                            <i class="fas fa-rocket"></i>
                            Comenzar Ahora
                        </a>
                    @endif
                    <a href="#features" class="btn btn-outline">
                        <i class="fas fa-play-circle"></i>
                        Ver Más
                    </a>
                </div>

                <!-- Stats -->
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-number">500+</span>
                        <span class="hero-stat-label">Libros Digitales</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">1000+</span>
                        <span class="hero-stat-label">Apuntes Compartidos</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">200+</span>
                        <span class="hero-stat-label">Estudiantes Activos</span>
                    </div>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="hero-visual">
                <!-- Floating Cards -->
                <div class="floating-card floating-card-1">
                    <div class="floating-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h4 class="floating-card-title">Biblioteca Digital</h4>
                    <p class="floating-card-subtitle">Acceso ilimitado</p>
                </div>

                <div class="floating-card floating-card-2">
                    <div class="floating-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="floating-card-title">Comunidad Activa</h4>
                    <p class="floating-card-subtitle">Aprende en grupo</p>
                </div>
            </div>

        </div>

        <!-- Features Quick Access -->
        <div class="hero-features" id="features">
            <a href="{{ url('/libros') }}" class="feature-card" style="text-decoration: none; color: inherit;">
                <div class="feature-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="feature-title">Libros</h3>
                <p class="feature-description">
                    Biblioteca digital con cientos de libros de todas las materias
                </p>
            </a>

            <a href="{{ url('/apuntes') }}" class="feature-card" style="text-decoration: none; color: inherit;">
                <div class="feature-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="feature-title">Apuntes</h3>
                <p class="feature-description">
                    Comparte y descarga apuntes de tus cursos favoritos
                </p>
            </a>

            <a href="{{ url('/tutorias') }}" class="feature-card" style="text-decoration: none; color: inherit;">
                <div class="feature-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 class="feature-title">Tutorías</h3>
                <p class="feature-description">
                    Conecta con tutores y grupos de estudio colaborativos
                </p>
            </a>

            <a href="{{ url('/comedores') }}" class="feature-card" style="text-decoration: none; color: inherit;">
                <div class="feature-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="feature-title">Comedores</h3>
                <p class="feature-description">
                    Encuentra los mejores lugares para comer cerca del campus
                </p>
            </a>
        </div>
    </div>
</section>
