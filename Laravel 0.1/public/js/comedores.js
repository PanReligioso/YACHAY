// --- Funciones Auxiliares ---

/**
 * Genera el HTML para los iconos de estrella basado en la calificacion.
 * @param {number} rating //valoracion numerica
 * @returns {string} HTML de los iconos de estrella
 */
function displayStarsHtml(rating) { //funcion para generar estrellas
    const full = Math.floor(rating); //calcula estrellas completas
    const half = rating - full >= 0.5 ? 1 : 0; //calcula media estrella
    const empty = 5 - full - half; //calcula estrellas vacias
    let html = ''; //inicializa el string html

    // Icono de estrella solida (fas fa-star)
    html += '<i class="fas fa-star" style="color: var(--accent-yellow);"></i>'.repeat(full); //agrega estrellas solidas

    // Icono de media estrella (fas fa-star-half-alt)
    if (half) { //si hay media estrella
        html += '<i class="fas fa-star-half-alt" style="color: var(--accent-yellow);"></i>'; //agrega media estrella
    }

    // Icono de estrella vacia (far fa-star)
    html += '<i class="far fa-star" style="color: var(--accent-yellow);"></i>'.repeat(empty); //agrega estrellas vacias

    return html; //retorna el HTML
}


// --- Inicializacion del Mapa Principal (para index.blade.php) ---

/**
 * Inicializa el mapa principal de Google Maps con marcadores de comedores.
 * Usa las variables 'comedores' y 'mapCenter' definidas en el Blade.
 */
function initMap() { //funcion para inicializar el mapa principal
    const map = new google.maps.Map(document.getElementById("map"), { //crea el objeto mapa
        zoom: 16, //nivel de zoom
        center: mapCenter, //centro del mapa
        mapId: "DEMO_MAP_ID" //id del mapa personalizado
    });

    // 1. Marcador de la Universidad Continental (Referencia)
    new google.maps.Marker({ //crea el marcador de la UC
        position: mapCenter, //posicion de la UC
        map: map, //asigna al mapa
        title: "Universidad Continental Cusco", //titulo
        icon: {
            url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png" // Icono de UC //url del icono
        }
    });

    // 2. Agregar marcadores para cada comedor filtrado
    comedores.forEach(comedor => { //itera sobre los comedores
        const marker = new google.maps.Marker({ //crea el marcador del comedor
            position: { lat: comedor.latitud, lng: comedor.longitud }, //posicion del comedor
            map: map, //asigna al mapa
            title: comedor.nombre, //titulo
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png" // Icono de Comedor //url del icono
            }
        });

        const infoWindow = new google.maps.InfoWindow({ //crea la ventana de informacion
            content: `<div style="color: var(--text-primary); font-family: var(--font-primary);">
                        <h5 style="margin: 0 0 5px 0; font-size: var(--text-base); color: var(--primary-700);">${comedor.nombre}</h5>
                        <p style="margin: 0; font-size: var(--text-sm);">
                            ⭐ <b>${comedor.valoracion_promedio.toFixed(1)}</b> (${displayStarsHtml(comedor.valoracion_promedio)})
                        </p>
                        <p style="margin: 5px 0 10px 0; font-size: var(--text-sm);">
                            Precio: S/ ${comedor.precio_menu_min.toFixed(2)} - ${comedor.precio_menu_max.toFixed(2)}
                        </p>
                        <a href="/comedores/${comedor.id}" style="color: var(--accent-orange); font-weight: bold; text-decoration: none;">Ver detalles</a>
                      </div>` //contenido HTML
        });

        marker.addListener("click", () => { //agrega listener de click
            infoWindow.open(map, marker); //abre la ventana de informacion
        });
    });
}


// --- Inicializacion del Mapa Pequeño (para show.blade.php) ---

/**
 * Inicializa el mapa pequeño en la vista de detalle del comedor.
 * Usa la variable 'comedorLoc' definida en el Blade.
 */
function initMapSmall() { //funcion para inicializar el mapa pequeño
    const mapSmall = new google.maps.Map(document.getElementById("map-small"), { //crea el mapa pequeño
        zoom: 17, //nivel de zoom
        center: comedorLoc, //posicion del comedor
        disableDefaultUI: true // Oculta controles para un mapa pequeño //oculta controles
    });

    // Marcador del comedor
    new google.maps.Marker({ //crea el marcador del comedor
        position: comedorLoc, //posicion
        map: mapSmall, //asigna al mapa pequeño
        title: "Comedor", //titulo
        icon: {
            url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png" //url del icono
        }
    });
}
