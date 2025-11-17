<?php $__env->startSection('title', 'YACHAY - Grupos de Tutoría'); ?>

<?php $__env->startSection('content'); ?>

<?php
// Cargar datos JSON
$grupos_json = file_get_contents(storage_path('app/grupos_tutoria.json'));
$grupos = json_decode($grupos_json, true);

$miembros_json = file_get_contents(storage_path('app/miembros_grupo.json'));
$miembros = json_decode($miembros_json, true);

$cursos_json = file_get_contents(storage_path('app/cursos.json'));
$cursos = json_decode($cursos_json, true);

// Obtener filtros
$search = $_GET['search'] ?? '';
$tipo_filter = $_GET['tipo'] ?? '';
$curso_filter = $_GET['curso'] ?? '';

// Filtrar grupos
$grupos_filtrados = array_filter($grupos, function($grupo) use ($search, $tipo_filter, $curso_filter, $miembros) {
    $match_search = empty($search) ||
        stripos($grupo['nombre_grupo'], $search) !== false ||
        stripos($grupo['descripcion'], $search) !== false;

    $match_tipo = empty($tipo_filter) || $grupo['tipo'] === $tipo_filter;

    $match_curso = empty($curso_filter) || $grupo['id_curso'] == $curso_filter;

    return $match_search && $match_tipo && $match_curso && $grupo['esta_activo'];
});

// Contar miembros por grupo
function contarMiembros($id_grupo, $miembros) {
    return count(array_filter($miembros, fn($m) => $m['id_grupo'] == $id_grupo));
}
?>

<!-- Hero Section -->
<section class="hero" style="min-height: 60vh; padding-top: 120px;">
    <div class="container">
        <div class="hero-content" style="text-align: center;">
            <div class="hero-badge">
                <i class="fas fa-users"></i>
                <span>Aprende en Comunidad</span>
            </div>

            <h1 class="hero-title">
                Grupos de <span class="highlight">Tutoría</span>
            </h1>

            <p class="hero-description">
                Únete a grupos de estudio, recibe tutoría de compañeros y colabora en tu aprendizaje
            </p>

            <!-- Buscador -->
            <form method="GET" style="max-width: 600px; margin: var(--spacing-2xl) auto;">
                <div style="position: relative; display: flex; gap: var(--spacing-sm);">
                    <input type="text" name="search" placeholder="Buscar grupo por nombre..."
                           value="<?= htmlspecialchars($search) ?>"
                           style="flex: 1; padding: var(--spacing-lg) var(--spacing-xl); border: 2px solid var(--primary-200);
                                  border-radius: var(--radius-full); font-size: var(--text-base);">
                    <button type="submit" class="btn btn-primary" style="border-radius: var(--radius-full);">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Filtros -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <form method="GET" style="background: var(--bg-primary); padding: var(--spacing-xl);
                                   border-radius: var(--radius-xl); box-shadow: var(--shadow-md);
                                   margin-bottom: var(--spacing-2xl);">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-lg); align-items: end;">
                <!-- Filtro Tipo -->
                <div>
                    <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600; color: var(--text-primary);">
                        <i class="fas fa-filter"></i> Tipo de Grupo
                    </label>
                    <select name="tipo" style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                               border-radius: var(--radius-md); font-size: var(--text-base);">
                        <option value="">Todos</option>
                        <option value="publico" <?= $tipo_filter === 'publico' ? 'selected' : '' ?>>Público</option>
                        <option value="privado" <?= $tipo_filter === 'privado' ? 'selected' : '' ?>>Privado</option>
                    </select>
                </div>

                <!-- Filtro Curso -->
                <div>
                    <label style="display: block; margin-bottom: var(--spacing-sm); font-weight: 600; color: var(--text-primary);">
                        <i class="fas fa-book"></i> Curso
                    </label>
                    <select name="curso" style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                                border-radius: var(--radius-md); font-size: var(--text-base);">
                        <option value="">Todos los cursos</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id_curso'] ?>" <?= $curso_filter == $curso['id_curso'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Botón Filtrar -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i>
                    Aplicar Filtros
                </button>
            </div>
        </form>

        <!-- Contador -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-xl);">
            <h3 style="font-size: var(--text-xl); color: var(--text-secondary);">
                <i class="fas fa-list"></i>
                <?= count($grupos_filtrados) ?> grupos encontrados
            </h3>

            <?php if (session('logged_in')): ?>
                <a href="<?php echo e(url('/tutorias/crear')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Crear Grupo
                </a>
            <?php endif; ?>
        </div>

        <!-- Grid de Grupos -->
        <?php if (count($grupos_filtrados) > 0): ?>
            <div class="grid grid-3">
                <?php foreach ($grupos_filtrados as $grupo): ?>
                    <?php
                    $num_miembros = contarMiembros($grupo['id_grupo'], $miembros);
                    $esta_lleno = $num_miembros >= $grupo['max_participantes'];
                    $curso_nombre = '';
                    if ($grupo['id_curso']) {
                        $curso = array_values(array_filter($cursos, fn($c) => $c['id_curso'] == $grupo['id_curso']))[0] ?? null;
                        $curso_nombre = $curso ? $curso['nombre_curso'] : 'General';
                    }
                    ?>

                    <div class="card" style="padding: var(--spacing-xl); position: relative;">
                        <!-- Badge Tipo -->
                        <div style="position: absolute; top: var(--spacing-md); right: var(--spacing-md);">
                            <span style="padding: var(--spacing-xs) var(--spacing-md); border-radius: var(--radius-full);
                                        font-size: var(--text-xs); font-weight: 600;
                                        background: <?= $grupo['tipo'] === 'publico' ? 'var(--accent-green)' : 'var(--accent-orange)' ?>;
                                        color: var(--text-white);">
                                <i class="fas fa-<?= $grupo['tipo'] === 'publico' ? 'globe' : 'lock' ?>"></i>
                                <?= ucfirst($grupo['tipo']) ?>
                            </span>
                        </div>

                        <!-- Nombre -->
                        <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-md); color: var(--text-primary);
                                   padding-right: 80px;">
                            <?= htmlspecialchars($grupo['nombre_grupo']) ?>
                        </h3>

                        <!-- Descripción -->
                        <p style="color: var(--text-secondary); margin-bottom: var(--spacing-lg);
                                  display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
                                  overflow: hidden; line-height: 1.6;">
                            <?= htmlspecialchars($grupo['descripcion']) ?>
                        </p>

                        <!-- Info -->
                        <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);
                                    margin-bottom: var(--spacing-lg); padding-top: var(--spacing-md);
                                    border-top: 1px solid var(--primary-100);">
                            <?php if ($curso_nombre): ?>
                                <div style="display: flex; align-items: center; gap: var(--spacing-sm); color: var(--text-secondary);">
                                    <i class="fas fa-book" style="color: var(--primary-600);"></i>
                                    <span style="font-size: var(--text-sm);"><?= htmlspecialchars($curso_nombre) ?></span>
                                </div>
                            <?php endif; ?>

                            <div style="display: flex; align-items: center; gap: var(--spacing-sm); color: var(--text-secondary);">
                                <i class="fas fa-users" style="color: var(--primary-600);"></i>
                                <span style="font-size: var(--text-sm);">
                                    <?= $num_miembros ?> / <?= $grupo['max_participantes'] ?> participantes
                                </span>
                            </div>
                        </div>

                        <!-- Estado y Botón -->
                        <div style="display: flex; gap: var(--spacing-md); align-items: center;">
                            <?php if ($esta_lleno): ?>
                                <span style="padding: var(--spacing-sm) var(--spacing-md); border-radius: var(--radius-md);
                                            background: var(--accent-red); color: var(--text-white); font-size: var(--text-sm);
                                            font-weight: 600;">
                                    <i class="fas fa-ban"></i> Lleno
                                </span>
                            <?php else: ?>
                                <span style="padding: var(--spacing-sm) var(--spacing-md); border-radius: var(--radius-md);
                                            background: var(--accent-green); color: var(--text-white); font-size: var(--text-sm);
                                            font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            <?php endif; ?>

                            <a href="<?php echo e(url('/tutorias')); ?>/<?= $grupo['id_grupo'] ?>" class="btn btn-secondary"
                               style="flex: 1; justify-content: center;">
                                <i class="fas fa-eye"></i>
                                Ver Detalle
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Sin resultados -->
            <div style="text-align: center; padding: var(--spacing-3xl); background: var(--bg-secondary);
                        border-radius: var(--radius-xl);">
                <i class="fas fa-search" style="font-size: 4rem; color: var(--text-tertiary); margin-bottom: var(--spacing-lg);"></i>
                <h3 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-md);">No se encontraron grupos</h3>
                <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
                    Intenta ajustar los filtros o crea tu propio grupo de estudio
                </p>
                <?php if (session('logged_in')): ?>
                    <a href="<?php echo e(url('/tutorias/crear')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Crear Primer Grupo
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Final -->
<?php if (session('logged_in')): ?>
<section class="section" style="background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                color: var(--text-white); text-align: center;">
    <div class="container">
        <h2 style="color: var(--text-white); font-size: var(--text-3xl); margin-bottom: var(--spacing-lg);">
            ¿No encuentras el grupo perfecto?
        </h2>
        <p style="font-size: var(--text-lg); margin-bottom: var(--spacing-2xl); opacity: 0.9;">
            Crea tu propio grupo de estudio y conecta con estudiantes de tu carrera
        </p>
        <a href="<?php echo e(url('/tutorias/crear')); ?>" class="btn"
           style="background: var(--text-white); color: var(--primary-600);
                  padding: var(--spacing-lg) var(--spacing-2xl); font-size: var(--text-lg);">
            <i class="fas fa-plus-circle"></i>
            Crear Mi Grupo
        </a>
    </div>
</section>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\resources\views/includes/tutorias/index.blade.php ENDPATH**/ ?>