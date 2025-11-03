/**
 * Lógica JavaScript unificada para la sección de Comedores (index y show)
 * * Las funciones se asignan a 'window' para ser globales y corregir el error:
 * * InvalidValueError: initMap is not a function.
 */

// ==========================================
// GOOGLE MAPS - Variables y Helpers
// ==========================================

let map;
let markers = [];

/**
 * Función auxiliar que genera el HTML de estrellas.
 */
function displayStarsHtml(rating) {
    const full = Math.floor(rating);
    const half = rating - full >= 0.5 ? 1 : 0;
    const empty = 5 - full - half;
    let html = '';

    // Color amarillo (#fbbf24)
    html += '<i class="fas fa-star" style="color: #fbbf24;"></i>'.repeat(full);
    if (half) {
        html += '<i class="fas fa-star-half-alt" style="color: #fbbf24;"></i>';
    }
    html += '<i class="far fa-star" style="color: #fbbf24;"></i>'.repeat(empty);

    return html;
}

// ==========================================
// MAPA PRINCIPAL (Función para index.blade.php)
// ==========================================

// CRÍTICO: Asignar a 'window' para que Google lo encuentre
window.initMap = function() {
    // Las variables 'comedoresData' y 'centroMapa' se asumen definidas en el scope global por PHP.

    // 1. Usar las coordenadas y zoom de la universidad (UC por defecto)
    const centro = { lat: centroMapa.lat, lng: centroMapa.lng };
    const nivelZoom = centroMapa.zoom;

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: nivelZoom,
        center: centro,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

    // Marcador de la Universidad Continental (Referencia)
    new google.maps.Marker({
        position: centro,
        map: map,
        title: "Universidad Continental Cusco",
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
        }
    });

    // Cargar comedores desde data
    if (typeof comedoresData !== 'undefined') {
        comedoresData.forEach(comedor => {
            addMarker(comedor);
        });
    }
}

// Agregar marcador
function addMarker(comedor) {
    // CRÍTICO: Conversión a Float para Google Maps y verificación
    const lat = parseFloat(comedor.latitud);
    const lng = parseFloat(comedor.longitud);

    if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) {
         console.warn(`Marcador omitido para ${comedor.nombre}. Coordenadas inválidas.`);
         return;
    }

    const marker = new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map: map,
        title: comedor.nombre,
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }
    });

    // InfoWindow con formato mejorado
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="font-family: sans-serif; max-width: 250px;">
                <h4 style="margin: 0 0 5px 0; font-size: 16px;">${comedor.nombre}</h4>
                <p style="margin: 0; font-size: 14px; line-height: 1.4;">
                    Valoración: <b>${comedor.valoracion_promedio.toFixed(1)}</b> (${displayStarsHtml(comedor.valoracion_promedio)})
                </p>
                <p style="margin: 5px 0 10px 0; font-size: 14px;">
                    Menú desde S/ ${comedor.precio_menu_min.toFixed(2)}
                </p>
                <a href="/comedores/${comedor.id}" style="color: #ff6b35; font-weight: bold; text-decoration: none;">Ver Detalles</a>
            </div>
        `
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });

    markers.push({ marker: marker, data: comedor });
}


// ==========================================
// MAPA PEQUEÑO (Función para show.blade.php)
// ==========================================

/**
 * Inicializa el mapa pequeño en la vista de detalle del comedor.
 * Usa la variable 'comedorLoc' definida en el Blade.
 */
window.initMapSmall = function() {
    // La variable 'comedorLoc' se asume definida en el scope global por PHP.

    const mapSmall = new google.maps.Map(document.getElementById("map-small"), {
        zoom: 17,
        center: comedorLoc,
        disableDefaultUI: true // Oculta controles para un mapa pequeño
    });

    // Marcador del comedor
    new google.maps.Marker({
        position: comedorLoc,
        map: mapSmall,
        title: "Ubicación del Comedor",
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }
    });
}
