<header class="header" id="header">
    <div class="container">
        <div class="header-container">

            <a href="<?php echo e(url('/')); ?>" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span class="logo-text">YACHAY</span>
            </a>

            <nav class="nav" id="navMenu">
                <ul class="nav-menu">
                    <li>
                        <a href="<?php echo e(url('/')); ?>" class="nav-link <?php echo e(request()->is('/') ? 'active' : ''); ?>">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/libros')); ?>" class="nav-link <?php echo e(request()->is('libros*') ? 'active' : ''); ?>">
                            Libros
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/apuntes')); ?>" class="nav-link <?php echo e(request()->is('apuntes*') ? 'active' : ''); ?>">
                            Apuntes
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/tutorias')); ?>" class="nav-link <?php echo e(request()->is('tutorias*') ? 'active' : ''); ?>">
                            Tutorías
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/comedores')); ?>" class="nav-link <?php echo e(request()->is('comedores*') ? 'active' : ''); ?>">
                            Comedores
                        </a>
                    </li>
                </ul>

                <div class="nav-actions">

                    
                    <button id="theme-toggle" class="btn btn-icon" aria-label="Cambiar Tema"
                            style="width: 45px; height: 45px; border-radius: var(--radius-full);
                                   background: var(--bg-secondary); border: 2px solid var(--primary-200);
                                   display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-moon" id="theme-icon" style="color: var(--text-primary); font-size: var(--text-lg);"></i>
                    </button>

                    <?php if(auth()->guard()->check()): ?>
                        
                        <div class="user-menu" style="display: flex; align-items: center; gap: var(--spacing-sm);">
                            <a href="<?php echo e(route('perfil.show')); ?>"
                               style="display: flex; align-items: center; gap: var(--spacing-sm);
                                      padding: var(--spacing-sm) var(--spacing-md); border-radius: var(--radius-full);
                                      background: var(--primary-50); color: var(--primary-600); font-weight: 600;
                                      text-decoration: none; transition: all 0.3s;">
                                <?php if(auth()->user()->avatar): ?>
                                    <img src="<?php echo e(auth()->user()->avatar); ?>" alt="Avatar"
                                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                                <?php endif; ?>
                                <span class="hide-mobile"><?php echo e(auth()->user()->nombre); ?></span>
                            </a>

                            <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin: 0;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-secondary"
                                        style="padding: var(--spacing-sm) var(--spacing-md);
                                               display: flex; align-items: center; gap: var(--spacing-xs);"
                                        onclick="return confirm('¿Seguro que quieres cerrar sesión?')">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span class="hide-mobile">Salir</span>
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-secondary hide-mobile">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary hide-mobile">
                            <i class="fas fa-user-plus"></i>
                            Registrarse
                        </a>
                    <?php endif; ?>
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
<?php /**PATH C:\xampp\htdocs\laravel\resources\views/partials/header.blade.php ENDPATH**/ ?>