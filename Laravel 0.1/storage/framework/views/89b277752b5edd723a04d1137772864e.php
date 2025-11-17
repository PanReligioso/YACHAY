<?php $__env->startSection('title', 'Biblioteca Digital - YACHAY'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                padding: 60px 0 var(--spacing-3xl); color: white;">
    <div class="container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <h1 style="color: white; font-size: var(--text-5xl); margin-bottom: var(--spacing-lg);">
                <i class="fas fa-book-open"></i> Biblioteca Digital
            </h1>
            <p style="font-size: var(--text-xl); opacity: 0.9; margin-bottom: var(--spacing-2xl);">
                Explora nuestra colección de más de 500 libros digitales para tu carrera
            </p>

            <!-- Buscador -->
            <form method="GET" action="<?php echo e(url('/libros')); ?>"
                  style="display: flex; gap: var(--spacing-md); max-width: 600px; margin: 0 auto;">
                <input type="text" name="buscar" placeholder="Buscar por título o autor..."
                       value="<?php echo e($busqueda); ?>"
                       style="flex: 1; padding: var(--spacing-lg); border: none;
                              border-radius: var(--radius-lg); font-size: var(--text-base);">
                <button type="submit" class="btn"
                        style="background: white; color: var(--primary-600); padding: var(--spacing-lg) var(--spacing-2xl);">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Filtros por Categoría -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <h3 style="text-align: center; margin-bottom: var(--spacing-xl);">
            <i class="fas fa-filter"></i> Filtrar por Categoría
        </h3>

        <div style="display: flex; gap: var(--spacing-md); flex-wrap: wrap; justify-content: center;">
            <a href="<?php echo e(url('/libros')); ?>"
               class="btn <?php echo e(empty($categoriaFiltro) ? 'btn-primary' : 'btn-secondary'); ?>"
               style="padding: var(--spacing-sm) var(--spacing-lg);">
                <i class="fas fa-th"></i> Todas
            </a>
            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(url('/libros?categoria=' . $cat['id'])); ?>"
                   class="btn <?php echo e($categoriaFiltro == $cat['id'] ? 'btn-primary' : 'btn-secondary'); ?>"
                   style="padding: var(--spacing-sm) var(--spacing-lg);">
                    <i class="fas <?php echo e($cat['icono']); ?>"></i> <?php echo e($cat['nombre']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<!-- Grid de Libros -->
<section class="section">
    <div class="container">

        <!-- Contador -->
        <div style="margin-bottom: var(--spacing-2xl); text-align: center;">
            <h2 style="color: var(--primary-600);">
                <?php echo e(count($librosFiltrados)); ?> libro(s) encontrado(s)
            </h2>
        </div>

        <?php if(count($librosFiltrados) > 0): ?>
            <div class="grid grid-3" style="gap: var(--spacing-xl);">
                <?php $__currentLoopData = $librosFiltrados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $libro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card" style="overflow: hidden; transition: var(--transition);">
                        <!-- Portada -->
                        <div style="height: 300px; overflow: hidden; background: var(--primary-100);">
                            <img src="<?php echo e($libro['portada']); ?>" alt="<?php echo e($libro['titulo']); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <!-- Contenido -->
                        <div style="padding: var(--spacing-xl);">
                            <!-- Categoría Badge -->
                            <?php
                                $catNombre = '';
                                foreach($categorias as $cat) {
                                    if($cat['id'] == $libro['categoria_id']) {
                                        $catNombre = $cat['nombre'];
                                        break;
                                    }
                                }
                            ?>
                            <div style="display: inline-block; padding: var(--spacing-xs) var(--spacing-md);
                                        background: var(--primary-100); color: var(--primary-600);
                                        border-radius: var(--radius-full); font-size: var(--text-sm);
                                        font-weight: 600; margin-bottom: var(--spacing-md);">
                                <?php echo e($catNombre); ?>

                            </div>

                            <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-sm);
                                       display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
                                       overflow: hidden;">
                                <?php echo e($libro['titulo']); ?>

                            </h3>

                            <p style="color: var(--text-secondary); font-size: var(--text-sm); margin-bottom: var(--spacing-xs);">
                                <i class="fas fa-user"></i> <?php echo e($libro['autor']); ?>

                            </p>

                            <p style="color: var(--text-tertiary); font-size: var(--text-sm); margin-bottom: var(--spacing-md);">
                                <i class="fas fa-building"></i> <?php echo e($libro['editorial']); ?> (<?php echo e($libro['anio']); ?>)
                            </p>

                            <p style="color: var(--text-secondary); font-size: var(--text-sm);
                                      display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
                                      overflow: hidden; margin-bottom: var(--spacing-lg);">
                                <?php echo e($libro['descripcion']); ?>

                            </p>

                            <!-- Estadísticas -->
                            <div style="display: flex; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);
                                        padding-top: var(--spacing-md); border-top: 1px solid var(--primary-200);">
                                <span style="color: var(--text-tertiary); font-size: var(--text-sm);">
                                    <i class="fas fa-eye"></i> <?php echo e($libro['vistas']); ?>

                                </span>
                                <span style="color: var(--text-tertiary); font-size: var(--text-sm);">
                                    <i class="fas fa-download"></i> <?php echo e($libro['descargas']); ?>

                                </span>
                            </div>

                            <!-- Botones -->
                            <div style="display: flex; gap: var(--spacing-sm);">
                                <a href="<?php echo e(url('/libros/' . $libro['id'])); ?>" class="btn btn-primary"
                                   style="flex: 1; justify-content: center; font-size: var(--text-sm);">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="<?php echo e($libro['url_drive']); ?>" target="_blank" class="btn btn-secondary"
                                   style="flex: 1; justify-content: center; font-size: var(--text-sm);">
                                    <i class="fas fa-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <!-- Sin Resultados -->
            <div style="text-align: center; padding: var(--spacing-3xl);">
                <i class="fas fa-search" style="font-size: 5rem; color: var(--text-tertiary); margin-bottom: var(--spacing-lg);"></i>
                <h3 style="color: var(--text-secondary); margin-bottom: var(--spacing-md);">
                    No se encontraron libros
                </h3>
                <p style="color: var(--text-tertiary);">
                    Intenta con otros términos de búsqueda o categoría
                </p>
                <a href="<?php echo e(url('/libros')); ?>" class="btn btn-primary" style="margin-top: var(--spacing-lg);">
                    <i class="fas fa-refresh"></i> Ver todos los libros
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- CTA Subir Libro -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-50), var(--secondary-50)); <?php if(!auth()->check()): ?> margin: var(--spacing-3xl) 0; padding: var(--spacing-3xl) 0; <?php endif; ?>">
    <div class="container" style="text-align: center;">
        <h2 style="margin-bottom: var(--spacing-lg);">
            ¿Tienes un libro que compartir?
        </h2>
        <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl); font-size: var(--text-lg);">
            Ayuda a la comunidad subiendo material educativo de calidad
        </p>
        <?php if(auth()->check()): ?>
            <a href="<?php echo e(url('/libros/subir')); ?>" class="btn btn-primary" style="padding: var(--spacing-lg) var(--spacing-2xl);">
                <i class="fas fa-cloud-upload-alt"></i> Subir Libro
            </a>
        <?php else: ?>
            <a href="<?php echo e(url('/registro')); ?>" class="btn btn-primary" style="padding: var(--spacing-lg) var(--spacing-2xl);">
                <i class="fas fa-user-plus"></i> Registrarse para Subir
            </a>
        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\resources\views/includes/Libros/index.blade.php ENDPATH**/ ?>