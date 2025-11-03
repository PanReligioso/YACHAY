<?php
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../../config/funciones.php';
require_once __DIR__ . '/../../../MODELO/Apunte.php';

$pageTitle = 'Apuntes Universitarios - YACHAY';

// Obtener filtros
$universidad = $_GET['universidad'] ?? '';
$curso = $_GET['curso'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';

// Instanciar modelo
$apunteModel = new Apunte();

// Obtener apuntes con filtros
$apuntes = $apunteModel->obtenerTodos($universidad, $curso, $busqueda);

// Inicializar como array vacÃ­o si es null
$apuntes = $apuntes ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<?php include __DIR__ . '/../../INCLUDE/head.php'; ?>
<body>
    
    <?php include __DIR__ . '/../../INCLUDE/header.php'; ?>
    
    <main class="page-apuntes">
        <div class="container">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="fas fa-file-pdf"></i>
                        Banco de Apuntes Universitarios
                    </h1>
                    <p class="page-subtitle">
                        Accede a apuntes compartidos por estudiantes de tu universidad
                    </p>
                </div>
                <?php if (isLoggedIn()): ?>
                    <a href="apunte-subir.php" class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Subir Apunte
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Filtros -->
            <div class="filters-section">
                <form method="GET" action="apuntes.php" class="filters-form">
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-university"></i>
                            Universidad
                        </label>
                        <select name="universidad" class="filter-select">
                            <option value="">Todas las universidades</option>
                            <option value="Continental" <?= $universidad === 'Continental' ? 'selected' : '' ?>>Universidad Continental</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-book"></i>
                            Curso
                        </label>
                        <input type="text" name="curso" class="filter-input" 
                               placeholder="Ej: CÃ¡lculo, QuÃ­mica..." 
                               value="<?= htmlspecialchars($curso) ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i>
                            Buscar
                        </label>
                        <input type="text" name="busqueda" class="filter-input" 
                               placeholder="Buscar en tÃ­tulo o tema..." 
                               value="<?= htmlspecialchars($busqueda) ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary filter-btn">
                        <i class="fas fa-filter"></i>
                        <span>Filtrar</span>
                    </button>
                    
                    <?php if ($universidad || $curso || $busqueda): ?>
                        <a href="apuntes.php" class="btn btn-outline filter-btn">
                            <i class="fas fa-times"></i>
                            <span>Limpiar</span>
                        </a>
                    <?php endif; ?>
                    
                </form>
                
                <div class="filters-results">
                    <p><?= count($apuntes) ?> apunte(s) encontrado(s)</p>
                </div>
            </div>
            
            <!-- Lista de Apuntes -->
            <div class="apuntes-grid">
                <?php if (empty($apuntes)): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-pdf"></i>
                        <h3>No se encontraron apuntes</h3>
                        <p>Intenta con otros filtros o sÃ© el primero en compartir apuntes</p>
                        <?php if (isLoggedIn()): ?>
                            <a href="apunte-subir.php" class="btn btn-primary">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Subir Apunte
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($apuntes as $apunte): ?>
                        <div class="apunte-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="card-badges">
                                    <span class="badge-universidad"><?= htmlspecialchars($apunte['universidad']) ?></span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h3 class="apunte-titulo">
                                    <a href="apunte-detalle.php?id=<?= $apunte['id'] ?>">
                                        <?= htmlspecialchars($apunte['titulo']) ?>
                                    </a>
                                </h3>
                                
                                <div class="apunte-info">
                                    <div class="info-item">
                                        <i class="fas fa-book"></i>
                                        <span><?= htmlspecialchars($apunte['curso']) ?></span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-user"></i>
                                        <span><?= htmlspecialchars($apunte['autor_nombre']) ?></span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-download"></i>
                                        <span><?= $apunte['descargas'] ?> descargas</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-calendar"></i>
                                        <span><?= date('d/m/Y', strtotime($apunte['created_at'])) ?></span>
                                    </div>
                                </div>
                                
                                <?php if (!empty($apunte['descripcion'])): ?>
                                    <p class="apunte-descripcion">
                                        <?= strlen($apunte['descripcion']) > 100 
                                            ? htmlspecialchars(substr($apunte['descripcion'], 0, 100)) . '...' 
                                            : htmlspecialchars($apunte['descripcion']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer">
                                <a href="apunte-detalle.php?id=<?= $apunte['id'] ?>" class="btn btn-primary btn-sm btn-block">
                                    <i class="fas fa-eye"></i>
                                    Ver Apunte
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
        </div>
    </main>
    
    <?php include __DIR__ . '/../../INCLUDE/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>MVC/VISTA/JS/main.js"></script>
    
</body>
</html>