<?php
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../../config/funciones.php';
require_once __DIR__ . '/../../../MODELO/Tutoria.php';

$pageTitle = 'Tutorías Universitarias - YACHAY';

// Obtener filtros
$materia = $_GET['materia'] ?? '';
$universidad = $_GET['universidad'] ?? '';
$precio_max = $_GET['precio_max'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';

// Instanciar modelo
$tutoriaModel = new Tutoria();

// Obtener tutorías con filtros
$tutorias = $tutoriaModel->obtenerTodas($materia, $universidad, $precio_max, $busqueda);

// Inicializar como array vacío si es null
$tutorias = $tutorias ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<?php include __DIR__ . '/../../INCLUDE/head.php'; ?>
<body>
    
    <?php include __DIR__ . '/../../INCLUDE/header.php'; ?>
    
    <main class="page-tutorias">
        <div class="container">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Red de Tutorías Entre Estudiantes
                    </h1>
                    <p class="page-subtitle">
                        Encuentra grupos de estudio ó encuentra personas para estudiar.
                    </p>
                </div>
                <?php if (isLoggedIn()): ?>
                    <a href="tutoria-crear.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Ofrecer Tutoría
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Filtros -->
            <div class="filters-section">
                <form method="GET" action="tutoria.php" class="filters-form">
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-book"></i>
                            Materia
                        </label>
                        <input type="text" name="materia" class="filter-input" 
                               placeholder="Ej: Matemáticas, Física..." 
                               value="<?= htmlspecialchars($materia) ?>">
                    </div>
                    
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
                            <i class="fas fa-money-bill-wave"></i>
                            Precio Máximo/Hora
                        </label>
                        <select name="precio_max" class="filter-select">
                            <option value="">Cualquier precio</option>
                            <option value="15" <?= $precio_max === '15' ? 'selected' : '' ?>>Hasta S/ 15</option>
                            <option value="20" <?= $precio_max === '20' ? 'selected' : '' ?>>Hasta S/ 20</option>
                            <option value="30" <?= $precio_max === '30' ? 'selected' : '' ?>>Hasta S/ 30</option>
                            <option value="50" <?= $precio_max === '50' ? 'selected' : '' ?>>Hasta S/ 50</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i>
                            Buscar
                        </label>
                        <input type="text" name="busqueda" class="filter-input" 
                               placeholder="Buscar tutor o materia..." 
                               value="<?= htmlspecialchars($busqueda) ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary filter-btn">
                        <i class="fas fa-filter"></i>
                        <span>Filtrar</span>
                    </button>
                    
                    <?php if ($materia || $universidad || $precio_max || $busqueda): ?>
                        <a href="tutoria.php" class="btn btn-outline filter-btn">
                            <i class="fas fa-times"></i>
                            <span>Limpiar</span>
                        </a>
                    <?php endif; ?>
                    
                </form>
                
                <div class="filters-results">
                    <p><?= count($tutorias) ?> tutoría(s) encontrada(s)</p>
                </div>
            </div>
            
            <!-- Lista de Tutorías -->
            <div class="tutorias-grid">
                <?php if (empty($tutorias)): ?>
                    <div class="empty-state">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <h3>No se encontraron tutorías</h3>
                        <p>Intenta con otros filtros o sé el primero en ofrecer tutorías</p>
                        <?php if (isLoggedIn()): ?>
                            <a href="tutoria-crear.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Ofrecer Tutoría
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($tutorias as $tutoria): ?>
                        <div class="tutoria-card">
                            <div class="card-header">
                                <div class="tutor-avatar">
                                    <?= strtoupper(substr($tutoria['tutor_nombre'], 0, 1)) ?>
                                </div>
                                <div class="tutor-info">
                                    <h4><?= htmlspecialchars($tutoria['tutor_nombre']) ?> <?= htmlspecialchars($tutoria['tutor_apellidos']) ?></h4>
                                    <p><?= htmlspecialchars($tutoria['tutor_carrera']) ?></p>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h3 class="tutoria-materia">
                                    <?= htmlspecialchars($tutoria['materia']) ?>
                                </h3>
                                
                                <?php if (!empty($tutoria['descripcion'])): ?>
                                    <p class="tutoria-descripcion">
                                        <?= strlen($tutoria['descripcion']) > 100 
                                            ? htmlspecialchars(substr($tutoria['descripcion'], 0, 100)) . '...' 
                                            : htmlspecialchars($tutoria['descripcion']) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="tutoria-info">
                                    <div class="info-item">
                                        <i class="fas fa-university"></i>
                                        <span><?= htmlspecialchars($tutoria['tutor_universidad']) ?></span>
                                    </div>
                                    <?php if (!empty($tutoria['horario_disponible'])): ?>
                                        <div class="info-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?= htmlspecialchars($tutoria['horario_disponible']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="tutoria-precio">
                                    <span class="precio-label">Precio por hora:</span>
                                    <span class="precio-valor">S/ <?= number_format($tutoria['precio_hora'], 2) ?></span>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <a href="tutoria-detalle.php?id=<?= $tutoria['id'] ?>" class="btn btn-primary btn-sm btn-block">
                                    <i class="fas fa-info-circle"></i>
                                    Ver Detalles
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