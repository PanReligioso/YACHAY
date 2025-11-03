<?php
require_once __DIR__ . '/../../../config/funciones.php'; 
?>
<header class="header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                
                <!-- Logo -->
                <div class="navbar-brand">
                    <a href="<?= BASE_URL ?>index.php" class="logo">
                        <i class="fas fa-graduation-cap"></i>
                        <span>YACHAY</span>
                    </a>
                </div>
                
                <!-- Menu Toggle (Mobile) -->
                <button class="menu-toggle" id="menuToggle" aria-label="Menú">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <!-- Navigation Menu -->
                <ul class="navbar-menu" id="navbarMenu">
                    <li><a href="<?= BASE_URL ?>MVC/VISTA/PRINCIPAL/LIBROS/libros.php" class="nav-link">
                        <i class="fas fa-book"></i> <span>Libros</span>
                    </a></li>
                    <li><a href="<?= BASE_URL ?>MVC/VISTA/PRINCIPAL/APUNTES/apuntes.php" class="nav-link">
                        <i class="fas fa-file-pdf"></i> <span>Apuntes</span>
                    </a></li>
                    <li><a href="<?= BASE_URL ?>MVC/VISTA/PRINCIPAL/TUTORIAS/tutoria.php" class="nav-link">
                        <i class="fas fa-chalkboard-teacher"></i> <span>Tutorías</span>
                    </a></li>
                    <li><a href="<?= BASE_URL ?>MVC/VISTA/PRINCIPAL/COMEDORES/comedores.php" class="nav-link">
                        <i class="fas fa-utensils"></i> <span>Comedores</span>
                    </a></li>
                </ul>
                
                <!-- User Actions -->
                <div class="navbar-actions">
                    <!-- Theme Toggle Button -->
                    <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema" data-tooltip="Cambiar tema">
                        <div class="theme-toggle-slider">
                            <i class="fas fa-moon"></i>
                        </div>
                    </button>
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="user-dropdown">
                            <button class="user-btn" aria-label="Menú de usuario">
                                <i class="fas fa-user-circle"></i>
                                <span><?= getUserName() ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="<?= BASE_URL ?>MVC/VISTA/PRINCIPAL/perfil.php">
                                    <i class="fas fa-user"></i> Mi Perfil
                                </a>
                                <a href="<?= BASE_URL ?>MVC/CONTROLADOR/AuthController.php?action=logout">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>MVC/VISTA/PRINCIPAL/login.php" class="btn btn-outline">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Ingresar</span>
                        </a>
                        <a href="<?= BASE_URL ?>MVC/VISTA/PRINCIPAL/registro.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            <span>Registrarse</span>
                        </a>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </nav>
    
    <!-- Mensaje Flash -->
    <?php 
    $flash = getFlash();
    if ($flash): 
    ?>
        <div class="flash-message flash-<?= $flash['tipo'] ?>">
            <div class="container">
                <span><?= $flash['mensaje'] ?></span>
                <button class="flash-close" aria-label="Cerrar mensaje">&times;</button>
            </div>
        </div>
    <?php endif; ?>
</header>

<style>
    /* Estilos adicionales para el tema toggle en el header */
    .theme-toggle {
        order: -1; /* Poner el toggle antes de los botones en mobile */
    }
    
    [data-theme="dark"] .theme-toggle-slider i:before {
        content: "\f185"; /* Icono de sol */
    }
    
    @media (max-width: 1024px) {
        .navbar-actions {
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
            margin-top: var(--spacing-lg);
        }
        
        .theme-toggle {
            order: 0;
        }
    }
</style>