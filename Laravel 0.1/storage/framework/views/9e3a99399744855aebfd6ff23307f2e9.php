<?php $__env->startSection('title', 'YACHAY - Comedores Universitarios'); ?>

<?php $__env->startSection('content'); ?>

<?php
// --- CONFIGURACIÓN Y CARGA DE DATOS ---
// Obtener la clave de Google Maps de forma segura (usa la clave del .env, o la clave antigua como fallback)
$google_api_key = env('GOOGLE_MAPS_KEY', 'AIzaSyDHTjrZa8tIMQoTVCJIbPPW1nu5ivxfFKM');

// ------------------------------------------------
// 1. DEFINICIÓN DE COORDENADAS Y CENTRO POR DEFECTO (¡CORREGIDO CON TU COORDENADA!)
// ------------------------------------------------
$coordsUniversidades = [
    // Coordenada PRECISA para la Universidad Continental
    'continental' => ['lat' => -13.555983492874608, 'lng' => -71.85663647964834, 'zoom' => 16],

    'unsaac' => ['lat' => -13.518600, 'lng' => -71.977400, 'zoom' => 15],
    'andina' => ['lat' => -13.504200, 'lng' => -71.936000, 'zoom' => 15],
    'general' => ['lat' => -13.5319, 'lng' => -71.9675, 'zoom' => 14]
];

// Carga de JSON
$comedores_json = @file_get_contents(storage_path('app/comedores.json'));
$comedores = ($comedores_json && !is_null(json_decode($comedores_json))) ? json_decode($comedores_json, true) : [];

// Funciones Auxiliares
function display_stars($rating) {
    $full = floor($rating);
    $half = $rating - $full >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    $html = str_repeat('<i class="fas fa-star" style="color: var(--accent-yellow);"></i>', $full);
    if ($half) $html .= '<i class="fas fa-star-half-alt" style="color: var(--accent-yellow);"></i>';
    $html .= str_repeat('<i class="far fa-star" style="color: var(--accent-yellow);"></i>', $empty);
    return $html;
}

function get_distance_approx($id) {
    // Simulación de distancia a pie desde la UC
    $distances = [1 => '3 min', 2 => '2 min', 3 => '4 min', 4 => '5 min', 5 => '4 min', 6 => '6 min'];
    return $distances[$id] ?? 'N/A';
}

// --- LÓGICA DE FILTROS ---
$universidad_filter = $_GET['universidad'] ?? 'continental'; // INICIO EN CONTINENTAL
$precio_filter = $_GET['precio'] ?? '';
$comida_filter = $_GET['comida'] ?? '';
$valoracion_filter = $_GET['valoracion'] ?? '';
$search_query = $_GET['search'] ?? '';

// Seleccionar el centro del mapa y el zoom
$centroMapa = $coordsUniversidades[$universidad_filter] ?? $coordsUniversidades['general'];
$centroMapaJSON = json_encode($centroMapa);
$universidadFiltroJSON = json_encode($universidad_filter); // Para JS

$comedores_filtrados = array_filter($comedores, function($comedor) use ($precio_filter, $comida_filter, $valoracion_filter, $search_query, $universidad_filter) {
    // Filtro por Universidad
    $match_universidad = empty($universidad_filter) || strtolower($comedor['universidad_cercana'] ?? '') === $universidad_filter;

    // Filtro por Precio
    $match_precio = true;
    if (!empty($precio_filter)) {
        $min = $comedor['precio_menu_min'];
        if ($precio_filter === 'economico' && $min >= 5) $match_precio = false;
        if ($precio_filter === 'moderado' && ($min < 5 || $min > 10)) $match_precio = false;
        if ($precio_filter === 'premium' && $min <= 10) $match_precio = false;
    }

    // Filtro por Tipo de Comida
    $match_comida = empty($comida_filter) || strtolower($comedor['tipo_comida']) === strtolower($comida_filter);

    // Filtro por Valoración
    $match_valoracion = true;
    if (!empty($valoracion_filter)) {
        $rating = floatval($valoracion_filter);
        if ($valoracion_filter === '4+' && ($comedor['valoracion_promedio'] ?? 0) < 4) $match_valoracion = false;
        if ($valoracion_filter === '3+' && ($comedor['valoracion_promedio'] ?? 0) < 3) $match_valoracion = false;
        if ($valoracion_filter === '5' && ($comedor['valoracion_promedio'] ?? 0) < 5) $match_valoracion = false;
    }

    // Filtro por Búsqueda (nombre o descripción)
    $match_search = empty($search_query) ||
        stripos($comedor['nombre'], $search_query) !== false ||
        stripos($comedor['descripcion'], $search_query) !== false;

    // CORRECCIÓN: Quitamos la condición de 'activo', que hacía que la lista saliera vacía.
    return $match_universidad && $match_precio && $match_comida && $match_valoracion && $match_search;
});

$total_comedores = count($comedores_filtrados);

// Obtener tipos de comida únicos para el filtro
$tipos_comida = array_unique(array_column($comedores, 'tipo_comida'));
?>

<section style="background: linear-gradient(180deg, var(--primary-50), var(--primary-100));">
    <div class="container" style="padding: var(--spacing-2xl) 0;">
        <h1 style="text-align: center; color: var(--text-primary); margin-bottom: var(--spacing-md); font-size: var(--text-4xl);">
            <i class="fas fa-utensils" style="color: var(--accent-orange);"></i> Comedores Universitarios
        </h1>
        <p style="text-align: center; color: var(--text-secondary); font-size: var(--text-lg); max-width: 600px; margin: 0 auto;">
            Encuentra los mejores lugares para comer cerca de la Universidad Continental Cusco, filtrados por precio y valoración.
        </p>
    </div>
</section>

<div id="map" style="width: 100%; height: 500px;"></div>

<section class="section">
    <div class="container">
        <form method="GET" action="<?php echo e(url('/comedores')); ?>" style="margin-bottom: var(--spacing-2xl); padding: var(--spacing-xl);
                                                                    background: var(--bg-secondary); border-radius: var(--radius-lg);
                                                                    box-shadow: var(--shadow-md);">
            <div class="grid grid-4" style="gap: var(--spacing-xl); align-items: flex-end;">
                <div>
                    <label for="search" style="font-weight: bold; color: var(--text-primary); font-size: var(--text-sm);">Buscar:</label>
                    <input type="text" name="search" id="search" placeholder="Nombre o descripción"
                           value="<?php echo e(htmlspecialchars($search_query)); ?>"
                           style="width: 100%; padding: var(--spacing-sm); border: 1px solid var(--text-tertiary); border-radius: var(--radius-md);">
                </div>

                <div>
                    <label for="universidad" style="font-weight: bold; color: var(--text-primary); font-size: var(--text-sm);">Universidad Cercana:</label>
                    <select name="universidad" id="universidad" style="width: 100%; padding: var(--spacing-sm); border: 1px solid var(--text-tertiary); border-radius: var(--radius-md);">
                        <option value="continental" <?= $universidad_filter === 'continental' ? 'selected' : '' ?>>Universidad Continental</option>
                        <option value="unsaac" <?= $universidad_filter === 'unsaac' ? 'selected' : '' ?>>UNSAAC</option>
                        <option value="andina" <?= $universidad_filter === 'andina' ? 'selected' : '' ?>>Universidad Andina</option>
                        <option value="">Todas</option>
                    </select>
                </div>

                <div>
                    <label for="precio" style="font-weight: bold; color: var(--text-primary); font-size: var(--text-sm);">Precio:</label>
                    <select name="precio" id="precio" style="width: 100%; padding: var(--spacing-sm); border: 1px solid var(--text-tertiary); border-radius: var(--radius-md);">
                        <option value="">Todos</option>
                        <option value="economico" <?php echo e($precio_filter == 'economico' ? 'selected' : ''); ?>>Económico (< S/ 5)</option>
                        <option value="moderado" <?php echo e($precio_filter == 'moderado' ? 'selected' : ''); ?>>Moderado (S/ 5 - 10)</option>
                        <option value="premium" <?php echo e($precio_filter == 'premium' ? 'selected' : ''); ?>>Premium (> S/ 10)</option>
                    </select>
                </div>

                <div>
                    <label for="comida" style="font-weight: bold; color: var(--text-primary); font-size: var(--text-sm);">Tipo de Comida:</label>
                    <select name="comida" id="comida" style="width: 100%; padding: var(--spacing-sm); border: 1px solid var(--text-tertiary); border-radius: var(--radius-md);">
                        <option value="">Todos los Tipos</option>
                        <?php foreach($tipos_comida as $tipo): ?>
                            <option value="<?php echo e(strtolower($tipo)); ?>" <?php echo e(strtolower($comida_filter) == strtolower($tipo) ? 'selected' : ''); ?>>
                                <?php echo e($tipo); ?>

                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: var(--spacing-lg);">
                 <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Aplicar Filtros
                </button>
                <a href="<?php echo e(url('/comedores')); ?>" class="btn btn-outline" style="margin-left: var(--spacing-sm);">
                    <i class="fas fa-undo"></i> Limpiar
                </a>
            </div>
        </form>

        <h2 style="font-size: var(--text-2xl); color: var(--text-primary); margin-bottom: var(--spacing-xl);">
            Resultados (<?php echo e($total_comedores); ?> Comedores encontrados)
        </h2>

        <?php if ($total_comedores > 0): ?>
            <div class="grid grid-3" style="gap: var(--spacing-2xl);">
                <?php foreach($comedores_filtrados as $comedor): ?>
                    <div class="card" style="box-shadow: var(--shadow-md); transition: transform 0.2s; border: 1px solid var(--bg-secondary);">
                        <img src="<?php echo e($comedor['foto']); ?>" alt="<?php echo e($comedor['nombre']); ?>"
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: var(--radius-lg) var(--radius-lg) 0 0;">

                        <div style="padding: var(--spacing-lg);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-sm);">
                                <h3 style="font-size: var(--text-xl); color: var(--primary-700); margin: 0;">
                                    <?php echo e($comedor['nombre']); ?>

                                </h3>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: var(--text-sm); font-weight: bold; color: var(--accent-yellow);">
                                    <?php echo display_stars($comedor['valoracion_promedio']); ?>

                                    <span style="color: var(--text-secondary); margin-left: var(--spacing-xs);">(<?php echo e(number_format($comedor['valoracion_promedio'], 1)); ?>)</span>
                                </div>
                            </div>

                            <ul style="list-style: none; padding: 0; margin-bottom: var(--spacing-lg); font-size: var(--text-sm);">
                                <li style="margin-bottom: var(--spacing-xs); color: var(--text-secondary);">
                                    <i class="fas fa-money-bill-wave" style="color: var(--accent-green);"></i>
                                    Rango Precio: **S/ <?php echo e(number_format($comedor['precio_menu_min'], 2)); ?> - S/ <?php echo e(number_format($comedor['precio_menu_max'], 2)); ?>**
                                </li>
                                <li style="margin-bottom: var(--spacing-xs); color: var(--text-secondary);">
                                    <i class="fas fa-clock" style="color: var(--accent-orange);"></i>
                                    Horario: <?php echo e($comedor['horario_apertura']); ?> - <?php echo e($comedor['horario_cierre']); ?> (<?php echo e($comedor['dias_atencion']); ?>)
                                </li>
                                <li style="margin-bottom: var(--spacing-xs); color: var(--text-secondary);">
                                    <i class="fas fa-concierge-bell" style="color: var(--secondary-600);"></i>
                                    Tipo: <?php echo e($comedor['tipo_comida']); ?>

                                </li>
                                <li style="margin-bottom: var(--spacing-xs); color: var(--text-secondary);">
                                    <i class="fas fa-walking" style="color: var(--primary-600);"></i>
                                    Distancia Aprox: **<?php echo e(get_distance_approx($comedor['id'])); ?> caminando**
                                </li>
                            </ul>

                            <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-lg);">
                                <a href="<?php echo e(url('/comedores/' . $comedor['id'])); ?>" class="btn btn-primary" style="flex: 1;">
                                    <i class="fas fa-info-circle"></i> Ver Detalles
                                </a>
                                <a href="https://www.google.com/maps/dir/?api=1&destination=LAT,LNG?daddr=<?php echo e($comedor['latitud']); ?>,<?php echo e($comedor['longitud']); ?>"
                                   target="_blank" class="btn btn-outline" style="flex: 1;">
                                    <i class="fas fa-route"></i> Cómo llegar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: var(--spacing-3xl); background: var(--bg-secondary); border-radius: var(--radius-xl);">
                <i class="fas fa-search-minus" style="font-size: var(--text-5xl); color: var(--text-tertiary); margin-bottom: var(--spacing-lg);"></i>
                <p style="font-size: var(--text-lg); color: var(--text-secondary);">
                    No se encontraron comedores que coincidan con los filtros aplicados.
                </p>
                <a href="<?php echo e(url('/comedores')); ?>" class="btn btn-outline" style="margin-top: var(--spacing-xl);">
                    <i class="fas fa-redo"></i> Mostrar Todos
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
    // Se usa el nombre comedoresData para ser compatible con el maps.js que me pasaste
    const comedoresData = <?= json_encode(array_map(function($c) {
        $c['latitud'] = (float)($c['latitud'] ?? 0);
        $c['longitud'] = (float)($c['longitud'] ?? 0);
        return $c;
    }, $comedores_filtrados)) ?>;

    // Coordenadas y zoom de la universidad seleccionada (UC por defecto)
    const centroMapa = <?= $centroMapaJSON ?>;
    const universidadSeleccionada = <?= $universidadFiltroJSON ?>;
</script>


<script src="<?php echo e(asset('js/maps.js')); ?>"></script>



<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($google_api_key); ?>&callback=initMap"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\resources\views/includes/Comedores/index.blade.php ENDPATH**/ ?>