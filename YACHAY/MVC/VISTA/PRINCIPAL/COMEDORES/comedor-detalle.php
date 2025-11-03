<?php
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../../config/funciones.php';
require_once __DIR__ . '/../../../MODELO/Comedor.php';

$pageTitle = 'Comedores Económicos - YACHAY';

// Obtener filtros
$universidad = $_GET['universidad'] ?? '';
$tipo_comida = $_GET['tipo_comida'] ?? '';
$precio_max = $_GET['precio_max'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';

// Instanciar modelo
$comedorModel = new Comedor();

// Obtener comedores con filtros
$comedores = $comedorModel->obtenerTodos($universidad, $tipo_comida, $precio_max, $busqueda);

// DEBUGGING TEMPORAL - Elimina esto después de verificar
echo "<pre style='background: #f0f0f0; padding: 20px; margin: 20px;'>";
echo "=== DEBUGGING ===\n\n";
echo "Filtros aplicados:\n";
echo "Universidad: " . ($universidad ?: 'ninguno') . "\n";
echo "Tipo comida: " . ($tipo_comida ?: 'ninguno') . "\n";
echo "Precio max: " . ($precio_max ?: 'ninguno') . "\n";
echo "Búsqueda: " . ($busqueda ?: 'ninguno') . "\n\n";
echo "Número de comedores encontrados: " . count($comedores) . "\n\n";
if (!empty($comedores)) {
    echo "Datos de comedores:\n";
    print_r($comedores);
} else {
    echo "❌ NO SE ENCONTRARON COMEDORES\n";
}
echo "</pre>";
// ELIMINA TODO HASTA AQUÍ después de verificar

// Inicializar como array vacío si es null
$comedores = $comedores ?? [];

// Preparar datos para JavaScript (Google Maps)
$comedoresJSON = json_encode(array_map(function($c) {
    return [
        'id' => $c['id'],
        'nombre' => $c['nombre'],
        'direccion' => $c['direccion'],
        'latitud' => $c['latitud'],
        'longitud' => $c['longitud'],
        'precio_menu_min' => $c['precio_menu_min'],
        'universidad_cercana' => $c['universidad_cercana']
    ];
}, $comedores));
?>
<!DOCTYPE html>
<html lang="es">
<?php include '/xampp/htdocs/YACHAY/MVC/VISTA/INCLUDE/head.php'; ?>
<body>
    
    <?php include '/xampp/htdocs/YACHAY/MVC/VISTA/INCLUDE/header.php'; ?>
    
    <main class="page-comedores">
        <div class="container">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="fas fa-utensils"></i>
                        Directorio de Comedores Económicos
                    </h1>
                    <p class="page-subtitle">
                        Encuentra los mejores lugares para comer cerca de tu universidad. Menús desde S/7.
                    </p>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="filters-section">
                <form method="GET" action="comedores.php" class="filters-form">
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-university"></i>
                            Universidad Cercana
                        </label>
                        <select name="universidad" class="filter-select">
                            <option value="">Todas las universidades</option>
                            <option value="unsaac" <?= $universidad === 'unsaac' ? 'selected' : '' ?>>UNSAAC</option>
                            <option value="continental" <?= $universidad === 'continental' ? 'selected' : '' ?>>Universidad Continental</option>
                            <option value="andina" <?= $universidad === 'andina' ? 'selected' : '' ?>>Universidad Andina</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-utensils"></i>
                            Tipo de Comida
                        </label>
                        <select name="tipo_comida" class="filter-select">
                            <option value="">Todos los tipos</option>
                            <option value="criolla" <?= $tipo_comida === 'criolla' ? 'selected' : '' ?>>Criolla</option>
                            <option value="internacional" <?= $tipo_comida === 'internacional' ? 'selected' : '' ?>>Internacional</option>
                            <option value="vegetariana" <?= $tipo_comida === 'vegetariana' ? 'selected' : '' ?>>Vegetariana</option>
                            <option value="variada" <?= $tipo_comida === 'variada' ? 'selected' : '' ?>>Variada</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Precio Máximo
                        </label>
                        <select name="precio_max" class="filter-select">
                            <option value="">Cualquier precio</option>
                            <option value="8" <?= $precio_max === '8' ? 'selected' : '' ?>>Hasta S/ 8</option>
                            <option value="10" <?= $precio_max === '10' ? 'selected' : '' ?>>Hasta S/ 10</option>
                            <option value="12" <?= $precio_max === '12' ? 'selected' : '' ?>>Hasta S/ 12</option>
                            <option value="15" <?= $precio_max === '15' ? 'selected' : '' ?>>Hasta S/ 15</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i>
                            Buscar
                        </label>
                        <input type="text" name="busqueda" class="filter-input" 
                               placeholder="Buscar por nombre o dirección..." 
                               value="<?= htmlspecialchars($busqueda) ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary filter-btn">
                        <i class="fas fa-filter"></i>
                        <span>Filtrar</span>
                    </button>
                    
                    <?php if ($universidad || $tipo_comida || $precio_max || $busqueda): ?>
                        <a href="comedores.php" class="btn btn-outline filter-btn">
                            <i class="fas fa-times"></i>
                            <span>Limpiar</span>
                        </a>
                    <?php endif; ?>
                    
                </form>
                
                <div class="filters-results">
                    <p><?= count($comedores) ?> comedor(es) encontrado(s)</p>
                </div>
            </div>
            
            <!-- Tabs: Mapa / Lista -->
            <div class="view-tabs">
                <button class="view-tab active" data-view="mapa">
                    <i class="fas fa-map-marked-alt"></i>
                    Ver Mapa
                </button>
                <button class="view-tab" data-view="lista">
                    <i class="fas fa-list"></i>
                    Ver Lista
                </button>
            </div>
            
            <!-- Vista Mapa -->
            <div id="vista-mapa" class="vista-content active">
                <div class="mapa-container">
                    <div id="map" class="google-map" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
            
            <!-- Vista Lista -->
            <div id="vista-lista" class="vista-content">
                <?php if (empty($comedores)): ?>
                    <div class="empty-state">
                        <i class="fas fa-utensils"></i>
                        <h3>No se encontraron comedores</h3>
                        <p>Intenta con otros filtros o busca por nombre o dirección</p>
                    </div>
                <?php else: ?>
                    <div class="comedores-grid">
                        <?php foreach ($comedores as $comedor): ?>
                            <div class="comedor-card">
                                
                                <div class="card-image">
                                    <img src="<?= BASE_URL ?>MVC/VISTA/IMG/uploads/comedores/<?= $comedor['foto'] ?>" 
                                         alt="<?= htmlspecialchars($comedor['nombre']) ?>"
                                         onerror="this.src='https://via.placeholder.com/400x300/f59e0b/ffffff?text=<?= urlencode($comedor['nombre']) ?>'">
                                    
                                    <?php if ($comedor['tipo_comida']): ?>
                                        <span class="badge-tipo-comida"><?= ucfirst($comedor['tipo_comida']) ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body">
                                    <h3 class="comedor-nombre">
                                        <?= htmlspecialchars($comedor['nombre']) ?>
                                    </h3>
                                    
                                    <?php if ($comedor['descripcion']): ?>
                                        <p class="comedor-descripcion">
                                            <?= strlen($comedor['descripcion']) > 100 
                                                ? htmlspecialchars(substr($comedor['descripcion'], 0, 100)) . '...' 
                                                : htmlspecialchars($comedor['descripcion']) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="comedor-info">
                                        <div class="info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?= htmlspecialchars($comedor['direccion']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-university"></i>
                                            <span>Cerca de <?= ucfirst($comedor['universidad_cercana']) ?></span>
                                        </div>
                                        <?php if($comedor['horario_apertura'] && $comedor['horario_cierre']): ?>
                                            <div class="info-item">
                                                <i class="fas fa-clock"></i>
                                                <span><?= $comedor['horario_apertura'] ?> - <?= $comedor['horario_cierre'] ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                     
                                    <div class="comedor-precio-range">
                                        <span class="precio-label">Menú del día:</span>
                                        <span class="precio-range">
                                            S/ <?= number_format($comedor['precio_menu_min'], 2) ?>
                                            <?php if($comedor['precio_menu_max']): ?>
                                                - S/ <?= number_format($comedor['precio_menu_max'], 2) ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <a href="comedor-detalle.php?id=<?= $comedor['id'] ?>" class="btn btn-primary btn-sm btn-block">
                                            <i class="fas fa-info-circle"></i>
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </main>
    
    <?php include '/xampp/htdocs/YACHAY/MVC/VISTA/INCLUDE/footer.php'; ?>
    
    <!-- Google Maps API con tu API KEY -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHTjrZa8tIMQoTVCJIbPPW1nu5ivxfFKM&callback=initMap" async defer></script>
    
    <!-- Define los datos ANTES de cargar maps.js -->
    <script>
        const comedoresData = <?= $comedoresJSON ?>;
        
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
            
            // Agregar marcadores
            comedoresData.forEach(comedor => {
                addMarker(comedor);
            });
        }
        
        // Agregar marcador
        function addMarker(comedor) {
            if (!comedor.latitud || !comedor.longitud) return;
            
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(comedor.latitud), 
                    lng: parseFloat(comedor.longitud) 
                },
                map: map,
                title: comedor.nombre,
                icon: {
                    url: 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png'
                }
            });
            
            // InfoWindow
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="map-info-window">
                        <h4>${comedor.nombre}</h4>
                        <p><i class="fas fa-map-marker-alt"></i> ${comedor.direccion}</p>
                        <p><i class="fas fa-money-bill-wave"></i> Desde S/ ${comedor.precio_menu_min}</p>
                        <a href="comedor-detalle.php?id=${comedor.id}" class="btn btn-sm btn-primary">Ver Detalles</a>
                    </div>
                `
            });
            
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
            
            markers.push({
                marker: marker,
                data: comedor
            });
        }
        
        // Filtrar marcadores por universidad
        function filterMarkers(universidad) {
            markers.forEach(item => {
                if (universidad === '' || item.data.universidad_cercana === universidad) {
                    item.marker.setMap(map);
                } else {
                    item.marker.setMap(null);
                }
            });
        }
        
        // Tabs de vista
        document.querySelectorAll('.view-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const view = this.dataset.view;
                
                // Actualizar tabs
                document.querySelectorAll('.view-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Actualizar vistas
                document.querySelectorAll('.vista-content').forEach(v => v.classList.remove('active'));
                document.getElementById('vista-' + view).classList.add('active');
                
                // Reinicializar mapa si es necesario
                if (view === 'mapa' && map) {
                    google.maps.event.trigger(map, 'resize');
                }
            });
        });
    </script>
    
</body>
</html>