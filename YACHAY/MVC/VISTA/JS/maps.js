// ==========================================
// GOOGLE MAPS - Para módulo de Comedores
// ==========================================

let map;
let markers = [];

// Inicializar mapa
function initMap() {
    // Centro en Cusco
    const cusco = { lat: -13.5319, lng: -71.9675 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: cusco,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
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
    const marker = new google.maps.Marker({
        position: { lat: parseFloat(comedor.latitud), lng: parseFloat(comedor.longitud) },
        map: map,
        title: comedor.nombre,
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }
    });
    
    // InfoWindow
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div class="map-info-window">
                <h4>${comedor.nombre}</h4>
                <p><i class="fas fa-map-marker-alt"></i> ${comedor.direccion}</p>
                <p><i class="fas fa-money-bill-wave"></i> Menú desde S/ ${comedor.precio_menu_min}</p>
                <a href="comedor-detalle.php?id=${comedor.id}" class="btn btn-sm">Ver Detalles</a>
            </div>
        `
    });
    
    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });
    
    markers.push(marker);
}

// Filtrar marcadores por universidad
function filterMarkers(universidad) {
    markers.forEach(marker => {
        marker.setMap(null);
    });
    markers = [];
    
    if (universidad === 'todas') {
        comedoresData.forEach(comedor => addMarker(comedor));
    } else {
        comedoresData
            .filter(c => c.universidad_cercana === universidad)
            .forEach(comedor => addMarker(comedor));
    }
}
