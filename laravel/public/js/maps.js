// ==========================================
// GOOGLE MAPS - Variables y Helpers
// ==========================================

let map; //variable global para el objeto map principal
let markers = []; //array para almacenar los marcadores

/**
 * Funcion auxiliar que genera el HTML de estrellas.
 */
function displayStarsHtml(rating) { //funcion para generar estrellas segun el rating
    const full = Math.floor(rating); //calcula estrellas completas
    const half = rating - full >= 0.5 ? 1 : 0; //calcula media estrella si el decimal es 0.5 o mas
    const empty = 5 - full - half; //calcula estrellas vacias
    let html = ''; //inicializa el string html

    // Color amarillo (#fbbf24)
    html += '<i class="fas fa-star" style="color: #fbbf24;"></i>'.repeat(full); //agrega estrellas completas
    if (half) { //si hay media estrella
        html += '<i class="fas fa-star-half-alt" style="color: #fbbf24;"></i>'; //agrega media estrella
    }
    html += '<i class="far fa-star" style="color: #fbbf24;"></i>'.repeat(empty); //agrega estrellas vacias

    return html; //retorna el HTML de las estrellas
}

// ==========================================
// MAPA PRINCIPAL (Funcion para index.blade.php)
// ==========================================

// CRITICO: Asignar a 'window' para que Google lo encuentre
window.initMap = function() { //funcion global de inicializacion del mapa principal
    // Las variables 'comedoresData' y 'centroMapa' se asumen definidas en el scope global por PHP.

    // 1. Usar las coordenadas y zoom de la universidad (UC por defecto)
    const centro = { lat: centroMapa.lat, lng: centroMapa.lng }; //obtiene coordenadas del centro
    const nivelZoom = centroMapa.zoom; //obtiene nivel de zoom

    map = new google.maps.Map(document.getElementById('map'), { //crea el nuevo mapa en el div con id 'map'
        zoom: nivelZoom, //asigna el nivel de zoom
        center: centro, //asigna el centro
        styles: [ //aplica estilos al mapa
            {
                featureType: 'poi', //oculta puntos de interes
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

    // Marcador de la Universidad Continental (Referencia)
    new google.maps.Marker({ //crea el marcador de la universidad
        position: centro, //posicion en el centro
        map: map, //lo asigna al mapa
        title: "Universidad Continental Cusco", //titulo del marcador
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png' //url del icono de referencia
        }
    });

    // Cargar comedores desde data
    if (typeof comedoresData !== 'undefined') { //verifica si la data de comedores existe
        comedoresData.forEach(comedor => { //itera sobre los datos
            addMarker(comedor); //agrega un marcador por cada comedor
        });
    }
}

// Agregar marcador
function addMarker(comedor) { //funcion para agregar un marcador de comedor
    // CRITICO: Conversion a Float para Google Maps y verificacion
    const lat = parseFloat(comedor.latitud); //convierte latitud a float
    const lng = parseFloat(comedor.longitud); //convierte longitud a float

    if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) { //valida las coordenadas
         console.warn(`Marcador omitido para ${comedor.nombre}. Coordenadas invalidas.`); //muestra advertencia
         return; //sale de la funcion si es invalido
    }

    const marker = new google.maps.Marker({ //crea el objeto marcador
        position: { lat: lat, lng: lng }, //posicion del comedor
        map: map, //lo asigna al mapa
        title: comedor.nombre, //titulo del comedor
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' //url del icono de comedor
        }
    });

    // InfoWindow con formato mejorado
    const infoWindow = new google.maps.InfoWindow({ //crea la ventana de informacion
        content: `
            <div style="font-family: sans-serif; max-width: 250px;">
                <h4 style="margin: 0 0 5px 0; font-size: 16px;">${comedor.nombre}</h4>
                <p style="margin: 0; font-size: 14px; line-height: 1.4;">
                    Valoracion: <b>${comedor.valoracion_promedio.toFixed(1)}</b> (${displayStarsHtml(comedor.valoracion_promedio)})
                </p>
                <p style="margin: 5px 0 10px 0; font-size: 14px;">
                    Menu desde S/ ${comedor.precio_menu_min.toFixed(2)}
                </p>
                <a href="/comedores/${comedor.id}" style="color: #ff6b35; font-weight: bold; text-decoration: none;">Ver Detalles</a>
            </div>
        ` //contenido HTML de la ventana
    });

    marker.addListener('click', () => { //agrega listener de click al marcador
        infoWindow.open(map, marker); //abre la ventana de informacion
    });

    markers.push({ marker: marker, data: comedor }); //almacena el marcador y los datos
}


// ==========================================
// MAPA PEQUEÑO (Funcion para show.blade.php)
// ==========================================

/**
 * Inicializa el mapa pequeño en la vista de detalle del comedor.
 * Usa la variable 'comedorLoc' definida en el Blade.
 */
window.initMapSmall = function() { //funcion global de inicializacion del mapa pequeño
    // La variable 'comedorLoc' se asume definida en el scope global por PHP.

    const mapSmall = new google.maps.Map(document.getElementById("map-small"), { //crea el mapa pequeño
        zoom: 17, //nivel de zoom fijo
        center: comedorLoc, //centro en la ubicacion del comedor
        disableDefaultUI: true //oculta los controles por defecto
    });

    // Marcador del comedor
    new google.maps.Marker({ //crea el marcador del comedor
        position: comedorLoc, //posicion del comedor
        map: mapSmall, //lo asigna al mapa pequeño
        title: "Ubicacion del Comedor", //titulo del marcador
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' //url del icono de comedor
        }
    });
}
