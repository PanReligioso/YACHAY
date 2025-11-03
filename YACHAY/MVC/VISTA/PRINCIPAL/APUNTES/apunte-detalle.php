<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';
require_once __DIR__ . '/../../MODELO/Apunte.php';

// Obtener ID del apunte
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    setFlash('error', 'Apunte no encontrado');
    redirect('MVC/VISTA/APUNTES/apuntes.php');
}

// Obtener datos del apunte
$apunteModel = new Apunte();
$apunte = $apunteModel->obtenerPorId($id);

if (!$apunte) {
    setFlash('error', 'Apunte no encontrado');
    redirect('MVC/VISTA/APUNTES/apuntes.php');
}

$pageTitle = $apunte['titulo'] . ' - YACHAY';
$esPropio = isLoggedIn() && getUserId() == $apunte['user_id'];
?>
<!DOCTYPE html>
<html lang="es">
<?php include '../INCLUDE/head.php'; ?>
<body>
    
    <?php include '../INCLUDE/header.php'; ?>
    
    <main class="page-detalle">
        <div class="container">
            
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="<?= BASE_URL ?>index.php">Inicio</a>
                <i class="fas fa-chevron-right"></i>
                <a href="apuntes.php">Apuntes</a>
                <i class="fas fa-chevron-right"></i>
                <span><?= htmlspecialchars($apunte['titulo']) ?></span>
            </nav>
            
            <div class="apunte-detalle-container">
                
                <!-- Columna Principal -->
                <div class="apunte-detalle-main">
                    
                    <div class="apunte-detalle-card">
                        
                        <!-- Header -->
                        <div class="apunte-detalle-header">
                            <div class="apunte-detalle-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div>
                                <h1 class="apunte-detalle-title"><?= htmlspecialchars($apunte['titulo']) ?></h1>
                                <p class="apunte-detalle-subtitle">
                                    <i class="fas fa-book-open"></i>
                                    <?= htmlspecialchars($apunte['curso']) ?>
                                    <?php if($apunte['tema']): ?>
                                        • <?= htmlspecialchars($apunte['tema']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Stats Bar -->
                        <div class="apunte-stats-bar">
                            <div class="stat-item">
                                <i class="fas fa-download"></i>
                                <span><?= $apunte['descargas'] ?> descargas</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-calendar"></i>
                                <span><?= date('d/m/Y', strtotime($apunte['created_at'])) ?></span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-file"></i>
                                <span><?= number_format($apunte['tamano_archivo'] / 1048576, 2) ?> MB</span>
                            </div>
                        </div>
                        
                        <!-- Descripción -->
                        <?php if($apunte['descripcion']): ?>
                            <div class="apunte-detalle-section">
                                <h3 class="section-title">
                                    <i class="fas fa-align-left"></i>
                                    Descripción
                                </h3>
                                <p class="section-text"><?= nl2br(htmlspecialchars($apunte['descripcion'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Información Académica -->
                        <div class="apunte-detalle-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Información Académica
                            </h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Universidad:</span>
                                    <span class="info-value"><?= ucfirst($apunte['universidad']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Carrera:</span>
                                    <span class="info-value"><?= htmlspecialchars($apunte['carrera']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Curso:</span>
                                    <span class="info-value"><?= htmlspecialchars($apunte['curso']) ?></span>
                                </div>
                                <?php if($apunte['tema']): ?>
                                    <div class="info-item">
                                        <span class="info-label">Tema:</span>
                                        <span class="info-value"><?= htmlspecialchars($apunte['tema']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if($apunte['ciclo']): ?>
                                    <div class="info-item">
                                        <span class="info-label">Ciclo:</span>
                                        <span class="info-value"><?= $apunte['ciclo'] ?>° Ciclo</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Autor -->
                        <div class="apunte-detalle-section">
                            <h3 class="section-title">
                                <i class="fas fa-user"></i>
                                Subido por
                            </h3>
                            <div class="autor-card">
                                <div class="autor-avatar">
                                    <?= strtoupper(substr($apunte['autor_nombre'], 0, 1)) ?>
                                </div>
                                <div class="autor-info">
                                    <h4><?= htmlspecialchars($apunte['autor_nombre']) ?> <?= htmlspecialchars($apunte['autor_apellidos']) ?></h4>
                                    <p>
                                        <i class="fas fa-graduation-cap"></i>
                                        <?= htmlspecialchars($apunte['autor_carrera']) ?>
                                    </p>
                                    <?php if(!$esPropio): ?>
                                        <p>
                                            <i class="fas fa-envelope"></i>
                                            <?= htmlspecialchars($apunte['autor_email']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preview PDF -->
                        <div class="apunte-detalle-section">
                            <h3 class="section-title">
                                <i class="fas fa-eye"></i>
                                Vista Previa
                            </h3>
                            <div class="pdf-preview">
                                <embed 
                                    src="<?= BASE_URL ?>MVC/VISTA/IMG/uploads/apuntes/<?= $apunte['archivo_pdf'] ?>#toolbar=0&navpanes=0" 
                                    type="application/pdf" 
                                    width="100%" 
                                    height="600px"
                                >
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
                <!-- Sidebar -->
                <div class="apunte-detalle-sidebar">
                    
                    <!-- Acciones -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Acciones</h3>
                        
                        <div class="sidebar-actions">
                            <a href="../../CONTROLADOR/ApunteController.php?action=descargar&id=<?= $apunte['id'] ?>" 
                               class="btn btn-primary btn-lg btn-block"
                               target="_blank">
                                <i class="fas fa-download"></i>
                                Descargar PDF
                            </a>
                            
                            <?php if ($esPropio): ?>
                                <a href="../../CONTROLADOR/ApunteController.php?action=eliminar&id=<?= $apunte['id'] ?>" 
                                   class="btn btn-outline btn-lg btn-block"
                                   onclick="return confirm('¿Eliminar este apunte?')">
                                    <i class="fas fa-trash"></i>
                                    Eliminar
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline btn-lg btn-block">
                                    <i class="fas fa-heart"></i>
                                    Guardar
                                </button>
                                <button class="btn btn-outline btn-lg btn-block">
                                    <i class="fas fa-share-alt"></i>
                                    Compartir
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Información del Archivo -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Información del Archivo</h3>
                        <div class="file-info">
                            <div class="file-info-item">
                                <i class="fas fa-file-pdf"></i>
                                <div>
                                    <span class="file-info-label">Formato</span>
                                    <span class="file-info-value">PDF</span>
                                </div>
                            </div>
                            <div class="file-info-item">
                                <i class="fas fa-database"></i>
                                <div>
                                    <span class="file-info-label">Tamaño</span>
                                    <span class="file-info-value"><?= number_format($apunte['tamano_archivo'] / 1048576, 2) ?> MB</span>
                                </div>
                            </div>
                            <div class="file-info-item">
                                <i class="fas fa-download"></i>
                                <div>
                                    <span class="file-info-label">Descargas</span>
                                    <span class="file-info-value"><?= $apunte['descargas'] ?> veces</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tags -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Etiquetas</h3>
                        <div class="tags-cloud">
                            <span class="tag"><?= ucfirst($apunte['universidad']) ?></span>
                            <span class="tag"><?= htmlspecialchars($apunte['curso']) ?></span>
                            <?php if($apunte['tema']): ?>
                                <span class="tag"><?= htmlspecialchars($apunte['tema']) ?></span>
                            <?php endif; ?>
                            <?php if($apunte['ciclo']): ?>
                                <span class="tag"><?= $apunte['ciclo'] ?>° Ciclo</span>
                            <?php endif; ?>
                            <span class="tag">PDF</span>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
    </main>
    
    <?php include '../INCLUDE/footer.php'; ?>
    
    <script src="../JS/main.js"></script>
    
</body>
</html>