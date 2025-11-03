<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';
require_once __DIR__ . '/../../MODELO/Tutoria.php';

// Obtener ID de la tutoría
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    setFlash('error', 'Tutoría no encontrada');
    redirect('MVC/VISTA/TUTORIAS/tutorias.php');
}

// Obtener datos de la tutoría
$tutoriaModel = new Tutoria();
$tutoria = $tutoriaModel->obtenerPorId($id);

if (!$tutoria) {
    setFlash('error', 'Tutoría no encontrada');
    redirect('MVC/VISTA/TUTORIAS/tutorias.php');
}

$pageTitle = $tutoria['materia'] . ' - ' . $tutoria['tutor_nombre'] . ' - YACHAY';
$esPropio = isLoggedIn() && getUserId() == $tutoria['tutor_id'];
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
                <a href="tutorias.php">Tutorías</a>
                <i class="fas fa-chevron-right"></i>
                <span><?= htmlspecialchars($tutoria['materia']) ?></span>
            </nav>
            
            <div class="tutoria-detalle-container">
                
                <!-- Columna Principal -->
                <div class="tutoria-detalle-main">
                    
                    <!-- Perfil del Tutor -->
                    <div class="tutor-profile-card">
                        <div class="tutor-profile-header">
                            <div class="tutor-avatar-lg">
                                <?= strtoupper(substr($tutoria['tutor_nombre'], 0, 1)) ?>
                            </div>
                            <div class="tutor-profile-info">
                                <h1><?= htmlspecialchars($tutoria['tutor_nombre']) ?> <?= htmlspecialchars($tutoria['tutor_apellidos']) ?></h1>
                                <p class="tutor-title">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    Tutor de <?= htmlspecialchars($tutoria['materia']) ?>
                                </p>
                                <div class="tutor-meta">
                                    <span>
                                        <i class="fas fa-graduation-cap"></i>
                                        <?= htmlspecialchars($tutoria['tutor_carrera']) ?>
                                    </span>
                                    <span class="divider">•</span>
                                    <span>
                                        <i class="fas fa-university"></i>
                                        <?= ucfirst($tutoria['universidad']) ?>
                                    </span>
                                    <?php if($tutoria['valoracion_promedio'] > 0): ?>
                                        <span class="divider">•</span>
                                        <span class="rating-badge">
                                            <i class="fas fa-star"></i>
                                            <?= number_format($tutoria['valoracion_promedio'], 1) ?>
                                            (<?= $tutoria['num_valoraciones'] ?> opiniones)
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de la Tutoría -->
                    <div class="tutoria-info-card">
                        
                        <div class="tutoria-section">
                            <h2 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Sobre la Tutoría
                            </h2>
                            <?php if($tutoria['descripcion']): ?>
                                <p class="section-text"><?= nl2br(htmlspecialchars($tutoria['descripcion'])) ?></p>
                            <?php else: ?>
                                <p class="section-text text-muted">El tutor no ha proporcionado una descripción.</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="tutoria-section">
                            <h2 class="section-title">
                                <i class="fas fa-list-ul"></i>
                                Detalles
                            </h2>
                            <div class="details-grid">
                                <div class="detail-card">
                                    <div class="detail-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <span class="detail-label">Materia</span>
                                        <span class="detail-value"><?= htmlspecialchars($tutoria['materia']) ?></span>
                                    </div>
                                </div>
                                
                                <div class="detail-card">
                                    <div class="detail-icon">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    <div>
                                        <span class="detail-label">Modalidad</span>
                                        <span class="detail-value"><?= ucfirst($tutoria['modalidad']) ?></span>
                                    </div>
                                </div>
                                
                                <div class="detail-card">
                                    <div class="detail-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <span class="detail-label">Precio</span>
                                        <span class="detail-value">S/ <?= number_format($tutoria['precio_hora'], 2) ?>/hora</span>
                                    </div>
                                </div>
                                
                                <div class="detail-card">
                                    <div class="detail-icon">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div>
                                        <span class="detail-label">Universidad</span>
                                        <span class="detail-value"><?= ucfirst($tutoria['universidad']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($tutoria['horario_disponible']): ?>
                            <div class="tutoria-section">
                                <h2 class="section-title">
                                    <i class="fas fa-clock"></i>
                                    Horario Disponible
                                </h2>
                                <div class="horario-box">
                                    <i class="fas fa-calendar-alt"></i>
                                    <p><?= nl2br(htmlspecialchars($tutoria['horario_disponible'])) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Información del Tutor -->
                        <div class="tutoria-section">
                            <h2 class="section-title">
                                <i class="fas fa-user"></i>
                                Información de Contacto
                            </h2>
                            <div class="contact-card">
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <span class="contact-label">Email</span>
                                        <span class="contact-value"><?= htmlspecialchars($tutoria['tutor_email']) ?></span>
                                    </div>
                                </div>
                                <?php if($tutoria['tutor_telefono']): ?>
                                    <div class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <div>
                                            <span class="contact-label">Teléfono</span>
                                            <span class="contact-value"><?= htmlspecialchars($tutoria['tutor_telefono']) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
                <!-- Sidebar -->
                <div class="tutoria-detalle-sidebar">
                    
                    <!-- Precio y Acciones -->
                    <div class="sidebar-card sidebar-sticky">
                        <div class="precio-destacado">
                            <span class="precio-label">Precio por hora</span>
                            <span class="precio-valor">S/ <?= number_format($tutoria['precio_hora'], 2) ?></span>
                        </div>
                        
                        <div class="sidebar-divider"></div>
                        
                        <?php if ($esPropio): ?>
                            <div class="sidebar-actions">
                                <a href="tutoria-editar.php?id=<?= $tutoria['id'] ?>" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-edit"></i>
                                    Editar Tutoría
                                </a>
                                <a href="../../CONTROLADOR/TutoriaController.php?action=eliminar&id=<?= $tutoria['id'] ?>" 
                                   class="btn btn-outline btn-lg btn-block"
                                   onclick="return confirm('¿Eliminar esta tutoría?')">
                                    <i class="fas fa-trash"></i>
                                    Eliminar
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="sidebar-actions">
                                <a href="mailto:<?= $tutoria['tutor_email'] ?>?subject=Consulta sobre tutoría de <?= urlencode($tutoria['materia']) ?>" 
                                   class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-envelope"></i>
                                    Contactar por Email
                                </a>
                                <?php if($tutoria['tutor_telefono']): ?>
                                    <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $tutoria['tutor_telefono']) ?>?text=Hola, estoy interesado en tu tutoría de <?= urlencode($tutoria['materia']) ?>" 
                                       target="_blank"
                                       class="btn btn-outline btn-lg btn-block">
                                        <i class="fab fa-whatsapp"></i>
                                        WhatsApp
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="sidebar-help">
                            <i class="fas fa-info-circle"></i>
                            <p>Contacta al tutor para coordinar horarios y modalidad de pago</p>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Estadísticas</h3>
                        <div class="stats-list">
                            <div class="stat-item-sidebar">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <span class="stat-label">Desde</span>
                                    <span class="stat-value"><?= date('M Y', strtotime($tutoria['created_at'])) ?></span>
                                </div>
                            </div>
                            <?php if($tutoria['valoracion_promedio'] > 0): ?>
                                <div class="stat-item-sidebar">
                                    <i class="fas fa-star"></i>
                                    <div>
                                        <span class="stat-label">Valoración</span>
                                        <span class="stat-value"><?= number_format($tutoria['valoracion_promedio'], 1) ?>/5</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="stat-item-sidebar">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <span class="stat-label">Estado</span>
                                    <span class="stat-value">Activo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Seguridad -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Consejos de Seguridad</h3>
                        <ul class="tips-list">
                            <li>
                                <i class="fas fa-shield-alt"></i>
                                <span>Verifica la identidad del tutor antes de pagar</span>
                            </li>
                            <li>
                                <i class="fas fa-users"></i>
                                <span>Inicia con sesiones de prueba</span>
                            </li>
                            <li>
                                <i class="fas fa-comments"></i>
                                <span>Mantén la comunicación clara y profesional</span>
                            </li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
    </main>
    
    <?php include '../INCLUDE/footer.php'; ?>
    
    <script src="../JS/main.js"></script>
    
</body>
</html>