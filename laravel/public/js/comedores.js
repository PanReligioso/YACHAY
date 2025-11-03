/**
 * Lógica JavaScript para la sección de Comedores (index y show)
 * * NOTA: Las variables como 'comedores', 'mapCenter', 'comedorLoc' y 'google_api_key'
 * se asumen definidas en la vista Blade antes de que se cargue este script.
 */

// --- Funciones Auxiliares ---

/**
 * Genera el HTML para los iconos de estrella basado en la calificación.
 * @param {number} rating
 * @returns {string} HTML de los iconos de estrella
 */
function displayStarsHtml(rating) {
    const full = Math.floor(rating);
    const half = rating - full >= 0.5 ? 1 : 0;
    const empty = 5 - full - half;
    let html = '';

    // Icono de estrella sólida (fas fa-star)
    html += '<i class="fas fa-star" style="color: var(--accent-yellow);"></i>'.repeat(full);

    // Icono de media estrella (fas fa-star-half-alt)
    if (half) {
        html += '<i class="fas fa-star-half-alt" style="color: var(--accent-yellow);"></i>';
    }

    // Icono de estrella vacía (far fa-star)
    html += '<i class="far fa-star" style="color: var(--accent-yellow);"></i>'.repeat(empty);

    return html;
}


// --- Inicialización del Mapa Principal (para index.blade.php) ---

/**
 * Inicializa el mapa principal de Google Maps con marcadores de comedores.
 * Usa las variables 'comedores' y 'mapCenter' definidas en el Blade.
 */
function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: mapCenter,
        mapId: "DEMO_MAP_ID"
    });

    // 1. Marcador de la Universidad Continental (Referencia)
    new google.maps.Marker({
        position: mapCenter,
        map: map,
        title: "Universidad Continental Cusco",
        icon: {
            url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png" // Icono de UC
        }
    });

    // 2. Agregar marcadores para cada comedor filtrado
    comedores.forEach(comedor => {
        const marker = new google.maps.Marker({
            position: { lat: comedor.latitud, lng: comedor.longitud },
            map: map,
            title: comedor.nombre,
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png" // Icono de Comedor
            }
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `<div style="color: var(--text-primary); font-family: var(--font-primary);">
                        <h5 style="margin: 0 0 5px 0; font-size: var(--text-base); color: var(--primary-700);">${comedor.nombre}</h5>
                        <p style="margin: 0; font-size: var(--text-sm);">
                            ⭐ <b>${comedor.valoracion_promedio.toFixed(1)}</b> (${displayStarsHtml(comedor.valoracion_promedio)})
                        </p>
                        <p style="margin: 5px 0 10px 0; font-size: var(--text-sm);">
                            Precio: S/ ${comedor.precio_menu_min.toFixed(2)} - ${comedor.precio_menu_max.toFixed(2)}
                        </p>
                        <a href="/comedores/${comedor.id}" style="color: var(--accent-orange); font-weight: bold; text-decoration: none;">Ver detalles</a>
                      </div>`
        });

        marker.addListener("click", () => {
            infoWindow.open(map, marker);
        });
    });
}


// --- Inicialización del Mapa Pequeño (para show.blade.php) ---

/**
 * Inicializa el mapa pequeño en la vista de detalle del comedor.
 * Usa la variable 'comedorLoc' definida en el Blade.
 */
function initMapSmall() {
    const mapSmall = new google.maps.Map(document.getElementById("map-small"), {
        zoom: 17,
        center: comedorLoc,
        disableDefaultUI: true // Oculta controles para un mapa pequeño
    });

    // Marcador del comedor
    new google.maps.Marker({
        position: comedorLoc,
        map: mapSmall,
        title: "Comedor",
        icon: {
            url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        }
    });
}
