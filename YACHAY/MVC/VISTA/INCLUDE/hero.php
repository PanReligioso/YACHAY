<section class="hero">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            
            <span class="hero-badge">
                <i class="fas fa-star"></i>
                Plataforma #1 en Cusco
            </span>
            
            <h1 class="hero-title">
                Recursos Educativos
                <span class="gradient-text">Compartidos</span>
            </h1>
            
            <p class="hero-subtitle">
                La plataforma que conecta a estudiantes universitarios de Cusco.
                Encuentra libros, apuntes, tutorías y comedores económicos en un solo lugar.
            </p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-content">
                        <h3>500+</h3>
                        <p>Libros</p>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stat-content">
                        <h3>1,200+</h3>
                        <p>Apuntes</p>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-content">
                        <h3>150+</h3>
                        <p>Tutores</p>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="stat-content">
                        <h3>80+</h3>
                        <p>Comedores</p>
                    </div>
                </div>
            </div>
            
            <div class="hero-cta">
                <?php if (!isLoggedIn()): ?>
                    <a href="MVC/VISTA/PRINCIPAL/registro.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket"></i>
                        <span>Comenzar Gratis</span>
                    </a>
                    <a href="#libros" class="btn btn-outline-white btn-lg">
                        <i class="fas fa-compass"></i>
                        <span>Explorar</span>
                    </a>    
                <?php else: ?>
                    <a href="MVC/VISTA/PRINCIPAL/LIBROS/libros.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i>
                        <span>Explorar Recursos</span>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="hero-features">
                <div class="feature-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>100% Gratis</span>
                </div>
                <div class="feature-badge">
                    <i class="fas fa-users"></i>
                    <span>Comunidad Verificada</span>
                </div>
                <div class="feature-badge">
                    <i class="fas fa-lock"></i>
                    <span>Datos Seguros</span>
                </div>
            </div>
            
        </div>
    </div>
</section>