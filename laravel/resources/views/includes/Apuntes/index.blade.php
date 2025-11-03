@extends('layouts.app')

@section('title', 'Apuntes - YACHAY')

@section('content')

@php
$apuntesData = json_decode(file_get_contents(storage_path('app/apuntes.json')), true) ?? [];
$cursosData = json_decode(file_get_contents(storage_path('app/cursos.json')), true) ?? [];

$buscar = $_GET['buscar'] ?? '';
$cicloFiltro = $_GET['ciclo'] ?? '';
$tipoFiltro = $_GET['tipo'] ?? '';

$apuntesFiltrados = array_filter($apuntesData, function($apunte) use ($buscar, $cicloFiltro, $tipoFiltro, $cursosData) {
    $curso = array_values(array_filter($cursosData, fn($c) => $c['id_curso'] == $apunte['id_curso']))[0] ?? null;

    $matchBuscar = empty($buscar) || stripos($apunte['titulo'], $buscar) !== false || stripos($apunte['descripcion'], $buscar) !== false;
    $matchCiclo = empty($cicloFiltro) || ($curso && $curso['ciclo'] == $cicloFiltro);
    $matchTipo = empty($tipoFiltro) || $apunte['tipo_material'] == $tipoFiltro;

    return $matchBuscar && $matchCiclo && $matchTipo && $apunte['estado_validacion'] == 'aprobado';
});

$tiposIconos = [
    'apuntes' => ['icon' => 'fa-file-alt', 'color' => 'var(--primary-600)'],
    'guia' => ['icon' => 'fa-book-open', 'color' => 'var(--secondary-600)'],
    'ejercicios' => ['icon' => 'fa-pen', 'color' => 'var(--accent-orange)'],
    'examenes' => ['icon' => 'fa-file-signature', 'color' => 'var(--accent-red)'],
    'proyecto' => ['icon' => 'fa-project-diagram', 'color' => 'var(--accent-green)'],
    'otro' => ['icon' => 'fa-file', 'color' => 'var(--text-tertiary)']
];
@endphp

<!-- Hero Section -->
<section class="hero" style="min-height: 60vh; padding-bottom: var(--spacing-3xl);">
    <div class="container">
        <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
            <div class="hero-badge">
                <i class="fas fa-file-alt"></i>
                <span>Biblioteca de Apuntes</span>
            </div>

            <h1 class="hero-title">
                Encuentra <span class="highlight">Apuntes</span> de Calidad
            </h1>

            <p class="hero-description">
                Accede a apuntes, guías, ejercicios y material de estudio compartido por estudiantes de todos los ciclos
            </p>

            <!-- Buscador -->
            <form method="GET" style="margin-top: var(--spacing-2xl);">
                <div style="display: flex; gap: var(--spacing-md); max-width: 600px; margin: 0 auto; flex-wrap: wrap;">
                    <input type="text" name="buscar" placeholder="Buscar por título o descripción..."
                           value="<?= htmlspecialchars($buscar) ?>"
                           style="flex: 1; min-width: 250px; padding: var(--spacing-md) var(--spacing-lg);
                                  border: 2px solid var(--primary-200); border-radius: var(--radius-lg);
                                  font-size: var(--text-base);">
                    <button type="submit" class="btn btn-primary">
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
        <!-- Filtro por Ciclo -->
        <div style="margin-bottom: var(--spacing-2xl);">
            <h3 style="font-size: var(--text-lg); margin-bottom: var(--spacing-md); color: var(--text-primary);">
                <i class="fas fa-filter"></i> Filtrar por Ciclo
            </h3>
            <div style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap;">
                <a href="?" class="btn <?= empty($cicloFiltro) ? 'btn-primary' : 'btn-secondary' ?>"
                   style="padding: var(--spacing-sm) var(--spacing-lg);">Todos</a>
                <?php for($i = 1; $i <= 10; $i++): ?>
                    <a href="?ciclo=<?= $i ?><?= $tipoFiltro ? '&tipo='.$tipoFiltro : '' ?><?= $buscar ? '&buscar='.$buscar : '' ?>"
                       class="btn <?= $cicloFiltro == $i ? 'btn-primary' : 'btn-secondary' ?>"
                       style="padding: var(--spacing-sm) var(--spacing-lg);">Ciclo <?= $i ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Filtro por Tipo -->
        <div style="margin-bottom: var(--spacing-2xl);">
            <h3 style="font-size: var(--text-lg); margin-bottom: var(--spacing-md); color: var(--text-primary);">
                <i class="fas fa-tags"></i> Filtrar por Tipo de Material
            </h3>
            <div style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap;">
                <a href="?<?= $cicloFiltro ? 'ciclo='.$cicloFiltro : '' ?><?= $buscar ? '&buscar='.$buscar : '' ?>"
                   class="btn <?= empty($tipoFiltro) ? 'btn-primary' : 'btn-secondary' ?>"
                   style="padding: var(--spacing-sm) var(--spacing-lg);">Todos</a>
                <?php foreach($tiposIconos as $tipo => $config): ?>
                    <a href="?tipo=<?= $tipo ?><?= $cicloFiltro ? '&ciclo='.$cicloFiltro : '' ?><?= $buscar ? '&buscar='.$buscar : '' ?>"
                       class="btn <?= $tipoFiltro == $tipo ? 'btn-primary' : 'btn-secondary' ?>"
                       style="padding: var(--spacing-sm) var(--spacing-lg);">
                        <i class="fas <?= $config['icon'] ?>"></i>
                        <?= ucfirst($tipo) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Resultados -->
        <div style="margin-bottom: var(--spacing-lg);">
            <p style="color: var(--text-secondary); font-size: var(--text-lg);">
                <strong><?= count($apuntesFiltrados) ?></strong> apuntes encontrados
            </p>
        </div>

        <!-- Grid de Apuntes -->
        <?php if(count($apuntesFiltrados) > 0): ?>
            <div class="grid grid-3">
                <?php foreach($apuntesFiltrados as $apunte): ?>
                    <?php
                    $curso = array_values(array_filter($cursosData, fn($c) => $c['id_curso'] == $apunte['id_curso']))[0] ?? null;
                    $tipoConfig = $tiposIconos[$apunte['tipo_material']] ?? $tiposIconos['otro'];
                    ?>
                    <div class="card" style="padding: var(--spacing-xl); display: flex; flex-direction: column;">
                        <!-- Badge Tipo -->
                        <div style="display: inline-flex; align-items: center; gap: var(--spacing-sm);
                                    padding: var(--spacing-xs) var(--spacing-md);
                                    background: <?= $tipoConfig['color'] ?>20;
                                    border-radius: var(--radius-full); width: fit-content;
                                    margin-bottom: var(--spacing-md);">
                            <i class="fas <?= $tipoConfig['icon'] ?>" style="color: <?= $tipoConfig['color'] ?>;"></i>
                            <span style="color: <?= $tipoConfig['color'] ?>; font-weight: 600; font-size: var(--text-sm);">
                                <?= ucfirst($apunte['tipo_material']) ?>
                            </span>
                        </div>

                        <!-- Título -->
                        <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-sm);
                                   color: var(--text-primary); line-height: 1.3;">
                            <?= htmlspecialchars($apunte['titulo']) ?>
                        </h3>

                        <!-- Curso y Ciclo -->
                        <?php if($curso): ?>
                            <p style="color: var(--text-secondary); font-size: var(--text-sm); margin-bottom: var(--spacing-md);">
                                <i class="fas fa-graduation-cap"></i>
                                <?= htmlspecialchars($curso['nombre_curso']) ?> - Ciclo <?= $curso['ciclo'] ?>
                            </p>
                        <?php endif; ?>

                        <!-- Descripción -->
                        <p style="color: var(--text-secondary); font-size: var(--text-sm);
                                  margin-bottom: var(--spacing-lg); flex-grow: 1;
                                  display: -webkit-box; -webkit-line-clamp: 3;
                                  -webkit-box-orient: vertical; overflow: hidden;">
                            <?= htmlspecialchars($apunte['descripcion']) ?>
                        </p>

                        <!-- Estadísticas -->
                        <div style="display: flex; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);
                                    padding-top: var(--spacing-md); border-top: 1px solid var(--primary-100);">
                            <span style="color: var(--text-tertiary); font-size: var(--text-sm);">
                                <i class="fas fa-eye"></i> <?= $apunte['vistas'] ?>
                            </span>
                            <span style="color: var(--text-tertiary); font-size: var(--text-sm);">
                                <i class="fas fa-download"></i> <?= $apunte['descargas'] ?>
                            </span>
                        </div>

                        <!-- Botones -->
                        <div style="display: flex; gap: var(--spacing-sm);">
                            <a href="/apuntes/<?= $apunte['id_apunte'] ?>" class="btn btn-primary"
                               style="flex: 1; font-size: var(--text-sm); padding: var(--spacing-sm) var(--spacing-md);">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </a>
                            <a href="<?= htmlspecialchars($apunte['url_drive']) ?>" target="_blank"
                               class="btn btn-secondary"
                               style="font-size: var(--text-sm); padding: var(--spacing-sm) var(--spacing-md);">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card" style="padding: var(--spacing-3xl); text-align: center;">
                <i class="fas fa-search" style="font-size: var(--text-5xl); color: var(--text-tertiary);
                                                margin-bottom: var(--spacing-lg);"></i>
                <h3 style="color: var(--text-primary); margin-bottom: var(--spacing-md);">
                    No se encontraron apuntes
                </h3>
                <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
                    Prueba ajustando los filtros o realiza una nueva búsqueda
                </p>
                <a href="/apuntes" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Limpiar Filtros
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Subir Apuntes -->
<?php if(session('logged_in', false)): ?>
<section class="section" style="background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                  color: var(--text-white); text-align: center;">
    <div class="container">
        <h2 style="color: var(--text-white); font-size: var(--text-3xl); margin-bottom: var(--spacing-md);">
            ¿Tienes material de estudio para compartir?
        </h2>
        <p style="font-size: var(--text-lg); margin-bottom: var(--spacing-xl); opacity: 0.9;">
            Ayuda a otros estudiantes compartiendo tus apuntes, guías y material de estudio
        </p>
        <a href="/apuntes/subir" class="btn" style="background: var(--text-white); color: var(--primary-600);
                                                     padding: var(--spacing-lg) var(--spacing-2xl);">
            <i class="fas fa-upload"></i>
            Subir Apuntes
        </a>
    </div>
</section>
<?php endif; ?>

@endsection
