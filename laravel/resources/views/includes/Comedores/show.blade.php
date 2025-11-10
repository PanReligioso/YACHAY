@extends('layouts.app') //extiende de la plantilla principal

@section('title', 'YACHAY - Detalle Comedor') //define el titulo de la pagina

@section('content') //inicia la seccion de contenido

<?php
// --- CONFIGURACION Y CARGA DE DATOS ---
$id = $id ?? 0; // Viene de la ruta web.php //obtiene el ID del comedor
// Obtener la clave de Google Maps de forma segura (usa la clave del .env, o la clave antigua como fallback)
$google_api_key = env('GOOGLE_MAPS_KEY', 'AIzaSyDHTjrZa8tIMQoTVCJIbPPW1nu5ivxfFKM'); //obtiene la clave de Google Maps

// Carga de JSON
$comedores_json = @file_get_contents(storage_path('app/comedores.json')); //carga el JSON de comedores
$comedores = ($comedores_json && !is_null(json_decode($comedores_json))) ? json_decode($comedores_json, true) : []; //decodifica comedores

$resenas_json = @file_get_contents(storage_path('app/resenas_comedores.json')); //carga el JSON de reseñas
$resenas = ($resenas_json && !is_null(json_decode($resenas_json))) ? json_decode($resenas_json, true) : []; //decodifica reseñas

$usuarios_json = @file_get_contents(storage_path('app/usuarios.json')); //carga el JSON de usuarios
$usuarios = json_decode($usuarios_json, true) ?: []; //decodifica usuarios

// Busca el comedor actual
$comedor = array_values(array_filter($comedores, fn($c) => $c['id'] == $id))[0] ?? null; //busca el comedor por ID

if (!$comedor) { //si el comedor no existe
    // Si no se encuentra el comedor, redirige al listado
    header('Location: /comedores'); //redirige a la lista
    exit; //termina la ejecucion
}

// --- LOGICA DE RESEÑAS Y ESTADISTICAS ---
$resenas_comedor = array_filter($resenas, fn($r) => $r['id_comedor'] == $id); //filtra reseñas del comedor actual
$num_resenas = count($resenas_comedor); //cuenta el numero de reseñas
$resenas_por_estrella = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]; //inicializa contadores por estrella
$total_calificaciones = 0; //inicializa total de calificaciones

foreach ($resenas_comedor as $resena) { //itera sobre las reseñas
    $resenas_por_estrella[$resena['calificacion']]++; //cuenta por calificacion
    $total_calificaciones += $resena['calificacion']; //suma para el promedio
}

// Recalcular el promedio para la vista
$promedio_calculado = $num_resenas > 0 ? $total_calificaciones / $num_resenas : 0; //calcula el promedio
// Actualiza el promedio en el objeto (aunque no lo guarda en el JSON, simula el resultado)
$comedor['valoracion_promedio'] = number_format($promedio_calculado, 1); //formatea el promedio

// Logica de Formulario de Reseña
$mensaje_reseña = ''; //inicializa mensaje de exito
$error_reseña = ''; //inicializa mensaje de error
// NOTA: Uso 'id_usuario' 1 de usuarios.json para simular un usuario logueado.
$user_id = 1; // session('user_id'); // Usuario logueado (simulacion)
$user_logged_in = !empty($user_id); //verifica si hay usuario logueado (simulado)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') { //maneja el envio del formulario de reseña
    if (!$user_logged_in) { //si no esta logueado
        $error_reseña = 'Debes iniciar sesion para dejar una reseña.'; //mensaje de error
    } else { //si esta logueado
        $calificacion = intval($_POST['calificacion'] ?? 0); //obtiene calificacion
        $comentario = trim($_POST['comentario'] ?? ''); //obtiene comentario
        $fecha_visita = trim($_POST['fecha_visita'] ?? date('Y-m-d')); //obtiene fecha de visita

        // Validaciones
        if ($calificacion < 1 || $calificacion > 5) { //valida calificacion
            $error_reseña = 'La calificacion debe ser entre 1 y 5 estrellas.';
        } elseif (empty($comentario)) { //valida comentario
            $error_reseña = 'El comentario no puede estar vacio.';
        } else {
            // Generar nuevo ID de reseña (simulacion de autoincremental)
            $nuevo_id_resena = max(array_column($resenas, 'id_resena') ?: [0]) + 1; //calcula nuevo ID

            $nueva_reseña = [ //crea la nueva reseña
                'id_resena' => $nuevo_id_resena,
                'id_comedor' => $comedor['id'],
                'id_usuario' => $user_id,
                'calificacion' => $calificacion,
                'comentario' => $comentario,
                'fecha_visita' => $fecha_visita,
                'fecha_resena' => date('Y-m-d H:i:s')
            ];

            // Simulacion de guardado: añadir al array y guardar JSON
            $resenas[] = $nueva_reseña; //añade al array
            $resenas_json_updated = json_encode($resenas, JSON_PRETTY_PRINT); //codifica para guardar

            $mensaje_reseña = '¡Gracias! Tu reseña se ha guardado con exito.'; //mensaje de exito

            // Recarga para recalcular estadisticas
            // header('Location: ' . url('/comedores/' . $comedor['id']));
            // exit;
        }
    }
}

// Funciones Auxiliares (duplicadas para Show, ya que no estamos usando un Helper)
function display_stars_show($rating) { //funcion para mostrar estrellas
    $full = floor($rating); //estrellas completas
    $half = $rating - $full >= 0.5 ? 1 : 0; //media estrella
    $empty = 5 - $full - $half; //estrellas vacias
    $html = str_repeat('<i class="fas fa-star" style="color: var(--accent-yellow);"></i>', $full); //genera HTML de estrellas
    if ($half) $html .= '<i class="fas fa-star-half-alt" style="color: var(--accent-yellow);"></i>';
    $html .= str_repeat('<i class="far fa-star" style="color: var(--accent-yellow);"></i>', $empty);
    return $html; //retorna el HTML
}

function find_user($id, $users) { //funcion para buscar usuario
    return array_values(array_filter($users, fn($u) => $u['id_usuario'] == $id))[0] ?? ['nombre_completo' => 'Usuario Desconocido']; //retorna usuario o desconocido
}

function get_comedores_cercanos($current_id, $all_comedores) { //funcion para obtener comedores cercanos
    // Implementacion simple para obtener 3 cercanos
    $cercanos = array_filter($all_comedores, fn($c) => $c['id'] != $current_id); //filtra el comedor actual
    return array_slice($cercanos, 0, 3); //retorna los primeros 3
}

// Obtener 3 comedores cercanos (simulacion)
$comedores_cercanos = get_comedores_cercanos($comedor['id'], $comedores); //obtiene comedores cercanos
?>

<section class="section"> //seccion principal
    <div class="container"> //contenedor de ancho limitado
        <p style="font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: var(--spacing-lg);"> //migas de pan
            <a href="{{ url('/') }}" style="color: var(--primary-600); text-decoration: none;">Inicio</a> /
            <a href="{{ url('/comedores') }}" style="color: var(--primary-600); text-decoration: none;">Comedores</a> /
            {{ $comedor['nombre'] }} //nombre del comedor actual
        </p>

        <h1 style="font-size: var(--text-4xl); color: var(--text-primary); margin-bottom: var(--spacing-sm);"> //titulo del comedor
            {{ $comedor['nombre'] }}
        </h1>
        <div style="display: flex; align-items: center; gap: var(--spacing-md); margin-bottom: var(--spacing-2xl);"> //valoracion
            <div style="font-size: var(--text-xl); color: var(--accent-yellow); display: flex;">
                {!! display_stars_show($comedor['valoracion_promedio']) !!} //muestra las estrellas
            </div>
            <span style="font-size: var(--text-lg); color: var(--text-primary); font-weight: bold;">
                {{ $comedor['valoracion_promedio'] }} / 5 //promedio numerico
            </span>
            <span style="color: var(--text-secondary);">({{ $num_resenas }} Reseñas)</span> //numero de reseñas
        </div>

        <div class="grid grid-3" style="gap: var(--spacing-3xl);"> //grid principal
            <div style="grid-column: span 2;"> //columna de contenido principal

                <div style="margin-bottom: var(--spacing-2xl);">
                    <img src="{{ $comedor['foto'] }}" alt="Foto de {{ $comedor['nombre'] }}" //foto del comedor
                         style="width: 100%; height: 450px; object-fit: cover; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg);">
                </div>

                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-2xl);"> //tarjeta de descripcion
                    <h2 style="font-size: var(--text-2xl); color: var(--primary-700); margin-bottom: var(--spacing-lg);">
                        Descripcion y Menu del Dia //titulo
                    </h2>
                    <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
                        {{ $comedor['descripcion'] }} //descripcion
                    </p>

                    <h3 style="font-size: var(--text-xl); color: var(--text-primary); margin-top: var(--spacing-xl);">
                        <i class="fas fa-list-alt" style="color: var(--accent-green);"></i> Menu Destacado //titulo menu
                    </h3>
                    <p style="font-style: italic; color: var(--text-secondary); border-left: 3px solid var(--accent-green); padding-left: var(--spacing-md); margin-top: var(--spacing-sm);">
                        {{ $comedor['menu_dia'] }} //menu del dia
                    </p>
                </div>

                <div style="margin-top: var(--spacing-3xl);">
                    <h2 style="font-size: var(--text-2xl); color: var(--text-primary); margin-bottom: var(--spacing-xl);">
                        Reseñas de la Comunidad ({{ $num_resenas }}) //titulo de reseñas
                    </h2>

                    </div>
            </div>

            <div style="grid-column: span 1;"> //columna lateral
                <div class="card" style="padding: var(--spacing-xl); margin-bottom: var(--spacing-2xl);"> //tarjeta de datos
                    <h3 style="font-size: var(--text-xl); color: var(--text-primary); margin-bottom: var(--spacing-lg);">
                        Datos del Comedor //titulo
                    </h3>
                    <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);"> //botones de accion
                        <a href="https://www.google.com/maps/dir/?api=1&destination=LAT,LNG?daddr={{ $comedor['latitud'] }},{{ $comedor['longitud'] }}"
                           target="_blank" class="btn btn-primary" style="flex: 1;"> //boton como llegar
                            <i class="fas fa-route"></i> Como llegar
                        </a>
                        <a href="tel:{{ $comedor['telefono'] }}" class="btn btn-outline" style="flex: 1;"> //boton llamar
                            <i class="fas fa-phone-alt"></i> Llamar
                        </a>
                    </div>
                </div>

                <div style="margin-bottom: var(--spacing-2xl);"> //mapa pequeño
                    <h3 style="font-size: var(--text-xl); color: var(--text-primary); margin-bottom: var(--spacing-lg);">
                        Ubicacion Exacta //titulo del mapa
                    </h3>
                    <div id="map-small" style="width: 100%; height: 250px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);"></div> //contenedor del mapa
                </div>

                </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
{{-- 1. Pasa las variables PHP necesarias a JavaScript --}}
<script>
    const comedorLoc = { lat: {{ (float)($comedor['latitud'] ?? 0) }}, lng: {{ (float)($comedor['longitud'] ?? 0) }} }; //pasa coordenadas a JS
</script>

{{-- 2. Carga la LOGICA del mapa (maps.js) --}}
<script src="{{ asset('js/maps.js') }}"></script> //carga el script de logica de mapas

{{-- 3. Carga la API de Google Maps y especifica la funcion de callback --}}
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $google_api_key }}&callback=initMapSmall"></script> //carga la API y llama a initMapSmall
@endpush
