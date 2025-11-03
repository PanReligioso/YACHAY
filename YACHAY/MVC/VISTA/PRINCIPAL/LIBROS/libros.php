<?php
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../../config/funciones.php';
require_once __DIR__ . '/../../../MODELO/Libro.php';

$pageTitle = 'Libros Universitarios - YACHAY';

// Obtener filtros
$universidad = $_GET['universidad'] ?? '';
$carrera = $_GET['carrera'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Instanciar modelo
$libroModel = new Libro();

// Obtener libros con filtros
$limit = 12;
$offset = ($page - 1) * $limit;
$libros = $libroModel->obtenerTodos($universidad, $carrera, $busqueda, $limit, $offset);
$total = $libroModel->contarTotal($universidad, $carrera, $busqueda);
$totalPages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html lang="es">
<?php include __DIR__ . '/../../INCLUDE/head.php'; ?>
<body>
    
    <?php include __DIR__ . '/../../INCLUDE/header.php'; ?>
    
    <main class="page-libros">
        <div class="container">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="fas fa-book"></i>
                        Marketplace de Libros Universitarios
                    </h1>
                    <p class="page-subtitle">
                        Encuentra libros usados de la comuidad. Ahorra hasta 100% del precio original.
                    </p>
                </div>
                <?php if (isLoggedIn()): ?>
                    <div class="page-header-actions">
                        <a href="libro-crear.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i>
                            <span>Publicar Libro</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Filtros -->
            <div class="filters-section">
                <form method="GET" action="libros.php" class="filters-form">
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-university"></i>
                            Universidad
                        </label>
                        <select name="universidad" class="filter-select">
                            <option value="">Todas las universidades</option>
                            <option value="continental" <?= $universidad === 'continental' ? 'selected' : '' ?>>Universidad Continental</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-graduation-cap"></i>
                            Carrera
                        </label>
                        <input 
                            type="text" 
                            name="carrera" 
                            class="filter-input" 
                            placeholder="Ej: IngenierÃ­a de Sistemas"
                            value="<?= htmlspecialchars($carrera) ?>"
                        >
                    </div>
                    
                    <div class="filter-group filter-search">
                        <label class="filter-label">
                            <i class="fas fa-search"></i>
                            Buscar
                        </label>
                        <input 
                            type="text" 
                            name="busqueda" 
                            class="filter-input" 
                            placeholder="TÃ­tulo, autor o curso..."
                            value="<?= htmlspecialchars($busqueda) ?>"
                        >
                    </div>
                    
                    <button type="submit" class="btn btn-primary filter-btn">
                        <i class="fas fa-filter"></i>
                        <span>Filtrar</span>
                    </button>
                    
                    <?php if ($universidad || $carrera || $busqueda): ?>
                        <a href="libros.php" class="btn btn-outline filter-btn">
                            <i class="fas fa-times"></i>
                            <span>Limpiar</span>
                        </a>
                    <?php endif; ?>
                    
                </form>
                
                <div class="filters-results">
                    <p><?= $total ?> libro(s) encontrado(s)</p>
                </div>
            </div>
            
            <!-- Grid de Libros -->
            <?php if (empty($libros)): ?>
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>No se encontraron libros</h3>
                    <p>Intenta con otros filtros o busca por tÃ­tulo, autor o curso</p>
                    <?php if (isLoggedIn()): ?>
                        <a href="libro-crear.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Publicar el Primer Libro
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="cards-grid">
                    <?php foreach($libros as $libro): ?>
                        <div class="card libro-card">
                            
                            <!-- Imagen -->
                            <div class="card-image">
                                <img src="<?= BASE_URL ?>MVC/VISTA/IMG/uploads/libros/<?= $libro['foto'] ?>" 
                                     alt="<?= htmlspecialchars($libro['titulo']) ?>"
                                     onerror="this.src='https://via.placeholder.com/300x400/4f46e5/ffffff?text=Sin+Imagen'">
                                <div class="card-badges">
                                    <span class="card-badge badge-price">
                                        S/ <?= number_format($libro['precio'], 2) ?>
                                    </span>
                                    <?php if($libro['precio_original']): ?>
                                        <span class="card-badge badge-discount">
                                            -<?= round((($libro['precio_original'] - $libro['precio']) / $libro['precio_original']) * 100) ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-overlay">
                                    <button class="btn-icon btn-favorite" title="Agregar a favoritos">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Contenido -->
                            <div class="card-content">
                                <h3 class="card-title">
                                    <a href="libros-detalles.php?id=<?= $libro['id'] ?>">
                                        <?= htmlspecialchars($libro['titulo']) ?>
                                    </a>
                                </h3>
                                
                                <p class="card-author">
                                    <i class="fas fa-user-edit"></i>
                                    <?= htmlspecialchars($libro['autor']) ?>
                                </p>
                                
                                <div class="card-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-university"></i>
                                        <?= ucfirst($libro['universidad']) ?>
                                    </span>
                                    <span class="meta-divider">â€¢</span>
                                    <span class="meta-item">
                                        <i class="fas fa-book-open"></i>
                                        <?= htmlspecialchars($libro['curso']) ?>
                                    </span>
                                </div>
                                
                                <div class="card-tags">
                                    <span class="tag tag-estado tag-<?= $libro['estado'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $libro['estado'])) ?>
                                    </span>
                                    <?php if($libro['ciclo']): ?>
                                        <span class="tag">
                                            <?= $libro['ciclo'] ?>Â° Ciclo
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($libro['descripcion']): ?>
                                    <p class="card-description">
                                        <?= htmlspecialchars(substr($libro['descripcion'], 0, 100)) ?>
                                        <?= strlen($libro['descripcion']) > 100 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if($libro['precio_original']): ?>
                                    <p class="card-price-original">
                                        Precio original: 
                                        <span class="price-strikethrough">S/ <?= number_format($libro['precio_original'], 2) ?></span>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="card-footer">
                                    <div class="user-info">
                                        <div class="avatar">
                                            <?= strtoupper(substr($libro['vendedor_nombre'], 0, 1)) ?>
                                        </div>
                                        <div class="user-details">
                                            <span class="user-name"><?= htmlspecialchars($libro['vendedor_nombre']) ?></span>
                                            <span class="user-stats">
                                                <i class="fas fa-eye"></i> <?= $libro['vistas'] ?> vistas
                                            </span>
                                        </div>
                                    </div>
                                    <a href="libros-detalles.php?id=<?= $libro['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i>
                                        <span>Ver</span>
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- PaginaciÃ³n -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>&universidad=<?= $universidad ?>&carrera=<?= $carrera ?>&busqueda=<?= $busqueda ?>" 
                               class="pagination-btn">
                                <i class="fas fa-chevron-left"></i>
                                Anterior
                            </a>
                        <?php endif; ?>
                        
                        <div class="pagination-numbers">
                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="pagination-number active"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?page=<?= $i ?>&universidad=<?= $universidad ?>&carrera=<?= $carrera ?>&busqueda=<?= $busqueda ?>" 
                                       class="pagination-number">
                                        <?= $i ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>&universidad=<?= $universidad ?>&carrera=<?= $carrera ?>&busqueda=<?= $busqueda ?>" 
                               class="pagination-btn">
                                Siguiente
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
            <?php endif; ?>
            
        </div>
    </main>
    
    <?php include __DIR__ . '/../../INCLUDE/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>MVC/VISTA/JS/main.js"></script>
    
</body>
</html>