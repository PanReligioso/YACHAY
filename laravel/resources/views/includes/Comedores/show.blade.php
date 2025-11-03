@extends('layouts.app')

@section('title', 'YACHAY - Detalle Comedor')

@section('content')

<?php
// --- CONFIGURACIÓN Y CARGA DE DATOS ---
$id = $id ?? 0; // Viene de la ruta web.php
// Obtener la clave de Google Maps de forma segura (usa la clave del .env, o la clave antigua como fallback)
$google_api_key = env('GOOGLE_MAPS_KEY', 'AIzaSyDHTjrZa8tIMQoTVCJIbPPW1nu5ivxfFKM');

// Carga de JSON
$comedores_json = @file_get_contents(storage_path('app/comedores.json'));
$comedores = ($comedores_json && !is_null(json_decode($comedores_json))) ? json_decode($comedores_json, true) : [];

$resenas_json = @file_get_contents(storage_path('app/resenas_comedores.json'));
$resenas = ($resenas_json && !is_null(json_decode($resenas_json))) ? json_decode($resenas_json, true) : [];

$usuarios_json = @file_get_contents(storage_path('app/usuarios.json'));
$usuarios = json_decode($usuarios_json, true) ?: [];

// Busca el comedor actual
$comedor = array_values(array_filter($comedores, fn($c) => $c['id'] == $id))[0] ?? null;

if (!$comedor) {
    // Si no se encuentra el comedor, redirige al listado
    header('Location: /comedores');
    exit;
}

// --- LÓGICA DE RESEÑAS Y ESTADÍSTICAS ---
$resenas_comedor = array_filter($resenas, fn($r) => $r['id_comedor'] == $id);
$num_resenas = count($resenas_comedor);
$resenas_por_estrella = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$total_calificaciones = 0;

foreach ($resenas_comedor as $resena) {
    $resenas_por_estrella[$resena['calificacion']]++;
    $total_calificaciones += $resena['calificacion'];
}

// Recalcular el promedio para la vista
$promedio_calculado = $num_resenas > 0 ? $total_calificaciones / $num_resenas : 0;
// Actualiza el promedio en el objeto (aunque no lo guarda en el JSON, simula el resultado)
$comedor['valoracion_promedio'] = number_format($promedio_calculado, 1);

// Lógica de Formulario de Reseña
$mensaje_reseña = '';
$error_reseña = '';
// NOTA: Uso 'id_usuario' 1 de usuarios.json para simular un usuario logueado.
$user_id = 1; // session('user_id'); // Usuario logueado (simulación)
$user_logged_in = !empty($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') {
    if (!$user_logged_in) {
        $error_reseña = 'Debes iniciar sesión para dejar una reseña.';
    } else {
        $calificacion = intval($_POST['calificacion'] ?? 0);
        $comentario = trim($_POST['comentario'] ?? '');
        $fecha_visita = trim($_POST['fecha_visita'] ?? date('Y-m-d'));

        // Validaciones
        if ($calificacion < 1 || $calificacion > 5) {
            $error_reseña = 'La calificación debe ser entre 1 y 5 estrellas.';
        } elseif (empty($comentario)) {
            $error_reseña = 'El comentario no puede estar vacío.';
        } else {
            // Generar nuevo ID de reseña (simulación de autoincremental)
            $nuevo_id_resena = max(array_column($resenas, 'id_resena') ?: [0]) + 1;

            $nueva_reseña = [
                'id_resena' => $nuevo_id_resena,
                'id_comedor' => $comedor['id'],
                'id_usuario' => $user_id,
                'calificacion' => $calificacion,
                'comentario' => $comentario,
                'fecha_visita' => $fecha_visita,
                'fecha_resena' => date('Y-m-d H:i:s')
            ];

            // Simulación de guardado: añadir al array y guardar JSON
            $resenas[] = $nueva_reseña;
            $resenas_json_updated = json_encode($resenas, JSON_PRETTY_PRINT);

            $mensaje_reseña = '¡Gracias! Tu reseña se ha guardado con éxito.';

            // Recarga para recalcular estadísticas
            // header('Location: ' . url('/comedores/' . $comedor['id']));
            // exit;
        }
    }
}

// Funciones Auxiliares (duplicadas para Show, ya que no estamos usando un Helper)
function display_stars_show($rating) {
    $full = floor($rating);
    $half = $rating - $full >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    $html = str_repeat('<i class="fas fa-star" style="color: var(--accent-yellow);"></i>', $full);
    if ($half) $html .= '<i class="fas fa-star-half-alt" style="color: var(--accent-yellow);"></i>';
    $html .= str_repeat('<i class="far fa-star" style="color: var(--accent-yellow);"></i>', $empty);
    return $html;
}

function find_user($id, $users) {
    return array_values(array_filter($users, fn($u) => $u['id_usuario'] == $id))[0] ?? ['nombre_completo' => 'Usuario Desconocido'];
}

function get_comedores_cercanos($current_id, $all_comedores) {
    // Implementación simple para obtener 3 cercanos
    $cercanos = array_filter($all_comedores, fn($c) => $c['id'] != $current_id);
    return array_slice($cercanos, 0, 3);
}

// Obtener 3 comedores cercanos (simulación)
$comedores_cercanos = get_comedores_cercanos($comedor['id'], $comedores);
?>

<section class="section">
    <div class="container">
        <p style="font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: var(--spacing-lg);">
            <a href="{{ url('/') }}" style="color: var(--primary-600); text-decoration: none;">Inicio</a> /
            <a href="{{ url('/comedores') }}" style="color: var(--primary-600); text-decoration: none;">Comedores</a> /
            {{ $comedor['nombre'] }}
        </p>

        <h1 style="font-size: var(--text-4xl); color: var(--text-primary); margin-bottom: var(--spacing-sm);">
            {{ $comedor['nombre'] }}
        </h1>
        <div style="display: flex; align-items: center; gap: var(--spacing-md); margin-bottom: var(--spacing-2xl);">
            <div style="font-size: var(--text-xl); color: var(--accent-yellow); display: flex;">
                {!! display_stars_show($comedor['valoracion_promedio']) !!}
            </div>
            <span style="font-size: var(--text-lg); color: var(--text-primary); font-weight: bold;">
                {{ $comedor['valoracion_promedio'] }} / 5
            </span>
            <span style="color: var(--text-secondary);">({{ $num_resenas }} Reseñas)</span>
        </div>

        <div class="grid grid-3" style="gap: var(--spacing-3xl);">
            <div style="grid-column: span 2;">

                <div style="margin-bottom: var(--spacing-2xl);">
                    <img src="{{ $comedor['foto'] }}" alt="Foto de {{ $comedor['nombre'] }}"
                         style="width: 100%; height: 450px; object-fit: cover; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg);">
                </div>

                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-2xl);">
                    <h2 style="font-size: var(--text-2xl); color: var(--primary-700); margin-bottom: var(--spacing-lg);">
                        Descripción y Menú del Día
                    </h2>
                    <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
                        {{ $comedor['descripcion'] }}
                    </p>

                    <h3 style="font-size: var(--text-xl); color: var(--text-primary); margin-top: var(--spacing-xl);">
                        <i class="fas fa-list-alt" style="color: var(--accent-green);"></i> Menú Destacado
                    </h3>
                    <p style="font-style: italic; color: var(--text-secondary); border-left: 3px solid var(--accent-green); padding-left: var(--spacing-md); margin-top: var(--spacing-sm);">
                        {{ $comedor['menu_dia'] }}
                    </p>
                </div>

                <div style="margin-top: var(--spacing-3xl);">
                    <h2 style="font-size: var(--text-2xl); color: var(--text-primary); margin-bottom: var(--spacing-xl);">
                        Reseñas de la Comunidad ({{ $num_resenas }})
                    </h2>

                    </div>
            </div>

            <div style="grid-column: span 1;">
                <div class="card" style="padding: var(--spacing-xl); margin-bottom: var(--spacing-2xl);">
                    <h3 style="font-size: var(--text-xl); color: var(--text-primary); margin-bottom: var(--spacing-lg);">
                        Datos del Comedor
                    </h3>
                    <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=LAT,LNG?daddr={{ $comedor['latitud'] }},{{ $comedor['longitud'] }}"
                           target="_blank" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-route"></i> Cómo llegar
                        </a>
                        <a href="tel:{{ $comedor['telefono'] }}" class="btn btn-outline" style="flex: 1;">
                            <i class="fas fa-phone-alt"></i> Llamar
                        </a>
                    </div>
                </div>

                <div style="margin-bottom: var(--spacing-2xl);">
                    <h3 style="font-size: var(--text-xl); color: var(--text-primary); margin-bottom: var(--spacing-lg);">
                        Ubicación Exacta
                    </h3>
                    <div id="map-small" style="width: 100%; height: 250px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);"></div>
                </div>

                </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
{{-- 1. Pasa las variables PHP necesarias a JavaScript --}}
<script>
    const comedorLoc = { lat: {{ (float)($comedor['latitud'] ?? 0) }}, lng: {{ (float)($comedor['longitud'] ?? 0) }} };
</script>

{{-- 2. Carga la LÓGICA del mapa (maps.js) --}}
<script src="{{ asset('js/maps.js') }}"></script>

{{-- 3. Carga la API de Google Maps y especifica la función de callback --}}
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $google_api_key }}&callback=initMapSmall"></script>
@endpush
