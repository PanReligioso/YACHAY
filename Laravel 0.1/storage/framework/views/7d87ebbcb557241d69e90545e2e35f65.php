<?php $__env->startSection('title', 'YACHAY - Inicio'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section -->
<?php echo $__env->make('partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Main Content Sections -->
<div style="padding-top: 60px;"></div>

<!-- About Section -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-title">
            <h2>¿Qué es YACHAY?</h2>
            <p>
                Una plataforma integral diseñada para facilitar el aprendizaje
                y la colaboración entre estudiantes universitarios
            </p>
        </div>

        <div class="grid grid-3" style="margin-top: var(--spacing-3xl);">
            <div class="card" style="padding: var(--spacing-2xl); text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto var(--spacing-lg);
                            background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
                            border-radius: var(--radius-lg); display: flex; align-items: center;
                            justify-content: center; font-size: 2.5rem; color: var(--primary-600);">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-md);">
                    Aprendizaje Colaborativo
                </h3>
                <p style="color: var(--text-secondary);">
                    Comparte y accede a material de estudio creado por la comunidad estudiantil
                </p>
            </div>

            <div class="card" style="padding: var(--spacing-2xl); text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto var(--spacing-lg);
                            background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
                            border-radius: var(--radius-lg); display: flex; align-items: center;
                            justify-content: center; font-size: 2.5rem; color: var(--primary-600);">
                    <i class="fas fa-users"></i>
                </div>
                <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-md);">
                    Comunidad Activa
                </h3>
                <p style="color: var(--text-secondary);">
                    Conecta con otros estudiantes, forma grupos de estudio y recibe tutorías
                </p>
            </div>

            <div class="card" style="padding: var(--spacing-2xl); text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto var(--spacing-lg);
                            background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
                            border-radius: var(--radius-lg); display: flex; align-items: center;
                            justify-content: center; font-size: 2.5rem; color: var(--primary-600);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-md);">
                    Recursos Ilimitados
                </h3>
                <p style="color: var(--text-secondary);">
                    Accede a una biblioteca digital con libros y apuntes de todas las materias
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-4" style="text-align: center;">
            <div>
                <div style="font-size: var(--text-5xl); font-weight: 800;
                            background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                            margin-bottom: var(--spacing-sm);">
                    500+
                </div>
                <p style="font-size: var(--text-lg); color: var(--text-secondary); font-weight: 600;">
                    Libros Digitales
                </p>
            </div>

            <div>
                <div style="font-size: var(--text-5xl); font-weight: 800;
                            background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                            margin-bottom: var(--spacing-sm);">
                    1000+
                </div>
                <p style="font-size: var(--text-lg); color: var(--text-secondary); font-weight: 600;">
                    Apuntes Compartidos
                </p>
            </div>

            <div>
                <div style="font-size: var(--text-5xl); font-weight: 800;
                            background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                            margin-bottom: var(--spacing-sm);">
                    200+
                </div>
                <p style="font-size: var(--text-lg); color: var(--text-secondary); font-weight: 600;">
                    Estudiantes Activos
                </p>
            </div>

            <div>
                <div style="font-size: var(--text-5xl); font-weight: 800;
                            background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                            margin-bottom: var(--spacing-sm);">
                    50+
                </div>
                <p style="font-size: var(--text-lg); color: var(--text-secondary); font-weight: 600;">
                    Tutorías Disponibles
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                  color: var(--text-white); text-align: center; <?php if(!auth()->check()): ?> margin: var(--spacing-3xl) 0; padding: var(--spacing-3xl) 0; <?php endif; ?>">
    <div class="container">
        <h2 style="color: var(--text-white); font-size: var(--text-4xl); margin-bottom: var(--spacing-xl);">
            ¿Listo para comenzar tu viaje de aprendizaje?
        </h2>
        <p style="font-size: var(--text-xl); margin-bottom: var(--spacing-2xl); opacity: 1; color:white">
            Únete a cientos de estudiantes que ya están usando YACHAY para mejorar su experiencia universitaria
        </p>
        <div style="display: flex; gap: <?php if(!auth()->check()): ?> var(--spacing-lg) <?php else: ?> var(--spacing-md) <?php endif; ?>; justify-content: center; flex-wrap: wrap;">
            <?php if(auth()->check()): ?>
                <a href="<?php echo e(url('/libros/subir')); ?>" class="btn" style="background: var(--text-white);
                   color: var(--primary-600); padding: var(--spacing-lg) var(--spacing-2xl);
                   font-size: var(--text-lg);">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Compartir Contenido
                </a>
            <?php else: ?>
                <a href="<?php echo e(url('/registro')); ?>" class="btn" style="background: var(--text-white);
                   color: var(--primary-600); padding: var(--spacing-lg) var(--spacing-2xl);
                   font-size: var(--text-lg);">
                    <i class="fas fa-rocket"></i>
                    Registrarse Gratis
                </a>
            <?php endif; ?>
            <a href="<?php echo e(url('/libros')); ?>" class="btn btn-outline" style="padding: var(--spacing-lg) var(--spacing-2xl);
               font-size: var(--text-lg);">
                <i class="fas fa-book"></i>
                Explorar Libros
            </a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\resources\views/index.blade.php ENDPATH**/ ?>