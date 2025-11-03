<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';
require_once __DIR__ . '/../../MODELO/Libro.php';

// Obtener ID del libro
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    setFlash('error', 'Libro no encontrado');
    redirect('MVC/VISTA/LIBROS/libros.php');
}

// Obtener datos del libro
$libroModel = new Libro();
$libro = $libroModel->obtenerPorId($id);

if (!$libro) {
    setFlash('error', 'Libro no encontrado');
    redirect('MVC/VISTA/LIBROS/libros.php');
}

$pageTitle = $libro['titulo'] . ' - YACHAY';
$esPropio = isLoggedIn() && getUserId() == $libro['user_id'];
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
                <a href="libros.php">Libros</a>
                <i class="fas fa-chevron-right"></i>
                <span><?= htmlspecialchars($libro['titulo']) ?></span>
            </nav>
            
            <div class="detalle-container">
                
                <!-- Columna Izquierda: Imagen -->
                <div class="detalle-gallery">
                    <div class="main-image">
                        <img src="<?= BASE_URL ?>MVC/VISTA/IMG/uploads/libros/<?= $libro['foto'] ?>" 
                             alt="<?= htmlspecialchars($libro['titulo']) ?>"
                             onerror="this.src='https://via.placeholder.com/600x800/4f46e5/ffffff?text=Sin+Imagen'">
                    </div>
                    
                    <div class="image-badges">
                        <span class="badge-lg badge-estado-<?= $libro['estado'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $libro['estado'])) ?>
                        </span>
                        <?php if(!$libro['disponible']): ?>
                            <span class="badge-lg badge-vendido">
                                <i class="fas fa-check-circle"></i>
                                Vendido
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Columna Derecha: Información -->
                <div class="detalle-info">
                    
                    <div class="detalle-header">
                        <h1 class="detalle-title"><?= htmlspecialchars($libro['titulo']) ?></h1>
                        <button class="btn-icon btn-favorite-lg">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    
                    <p class="detalle-author">
                        <i class="fas fa-user-edit"></i>
                        Por <strong><?= htmlspecialchars($libro['autor']) ?></strong>
                        <?php if($libro['editorial']): ?>
                            • <?= htmlspecialchars($libro['editorial']) ?>
                        <?php endif; ?>
                    </p>
                    
                    <div class="detalle-precio">
                        <div class="precio-actual">
                            <span class="precio-label">Precio:</span>
                            <span class="precio-valor">S/ <?= number_format($libro['precio'], 2) ?></span>
                        </div>
                        <?php if($libro['precio_original']): ?>
                            <div class="precio-original">
                                <span class="precio-strikethrough">S/ <?= number_format($libro['precio_original'], 2) ?></span>
                                <span class="precio-ahorro">
                                    Ahorras S/ <?= number_format($libro['precio_original'] - $libro['precio'], 2) ?>
                                    (<?= round((($libro['precio_original'] - $libro['precio']) / $libro['precio_original']) * 100) ?>%)
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="detalle-specs">
                        <h3>Detalles del Libro</h3>
                        <div class="specs-grid">
                            <div class="spec-item">
                                <i class="fas fa-university"></i>
                                <div>
                                    <span class="spec-label">Universidad</span>
                                    <span class="spec-value"><?= ucfirst($libro['universidad']) ?></span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <i class="fas fa-graduation-cap"></i>
                                <div>
                                    <span class="spec-label">Carrera</span>
                                    <span class="spec-value"><?= htmlspecialchars($libro['carrera']) ?></span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <i class="fas fa-book-open"></i>
                                <div>
                                    <span class="spec-label">Curso</span>
                                    <span class="spec-value"><?= htmlspecialchars($libro['curso']) ?></span>
                                </div>
                            </div>
                            
                            <?php if($libro['ciclo']): ?>
                                <div class="spec-item">
                                    <i class="fas fa-layer-group"></i>
                                    <div>
                                        <span class="spec-label">Ciclo</span>
                                        <span class="spec-value"><?= $libro['ciclo'] ?>° Ciclo</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($libro['edicion']): ?>
                                <div class="spec-item">
                                    <i class="fas fa-bookmark"></i>
                                    <div>
                                        <span class="spec-label">Edición</span>
                                        <span class="spec-value"><?= htmlspecialchars($libro['edicion']) ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="spec-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <span class="spec-label">Publicado</span>
                                    <span class="spec-value"><?= date('d/m/Y', strtotime($libro['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($libro['descripcion']): ?>
                        <div class="detalle-description">
                            <h3>Descripción</h3>
                            <p><?= nl2br(htmlspecialchars($libro['descripcion'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Vendedor -->
                    <div class="detalle-vendedor">
                        <h3>Información del Vendedor</h3>
                        <div class="vendedor-card">
                            <div class="vendedor-avatar">
                                <?= strtoupper(substr($libro['vendedor_nombre'], 0, 1)) ?>
                            </div>
                            <div class="vendedor-info">
                                <h4><?= htmlspecialchars($libro['vendedor_nombre']) ?> <?= htmlspecialchars($libro['vendedor_apellidos']) ?></h4>
                                <p>
                                    <i class="fas fa-envelope"></i>
                                    <?= htmlspecialchars($libro['vendedor_email']) ?>
                                </p>
                                <?php if($libro['vendedor_telefono']): ?>
                                    <p>
                                        <i class="fas fa-phone"></i>
                                        <?= htmlspecialchars($libro['vendedor_telefono']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="detalle-actions">
                        <?php if ($esPropio): ?>
                            <a href="libro-editar.php?id=<?= $libro['id'] ?>" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-edit"></i>
                                Editar Publicación
                            </a>
                            <?php if($libro['disponible']): ?>
                                <a href="../../CONTROLADOR/LibroController.php?action=marcarVendido&id=<?= $libro['id'] ?>" 
                                   class="btn btn-outline btn-lg btn-block"
                                   onclick="return confirm('¿Marcar este libro como vendido?')">
                                    <i class="fas fa-check-circle"></i>
                                    Marcar como Vendido
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if($libro['disponible']): ?>
                                <a href="mailto:<?= $libro['vendedor_email'] ?>?subject=Interesado en: <?= urlencode($libro['titulo']) ?>" 
                                   class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-envelope"></i>
                                    Contactar por Email
                                </a>
                                <?php if($libro['vendedor_telefono']): ?>
                                    <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $libro['vendedor_telefono']) ?>?text=Hola, estoy interesado en el libro: <?= urlencode($libro['titulo']) ?>" 
                                       target="_blank"
                                       class="btn btn-outline btn-lg btn-block">
                                        <i class="fab fa-whatsapp"></i>
                                        Contactar por WhatsApp
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Este libro ya fue vendido
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="detalle-stats">
                        <div class="stat-item-small">
                            <i class="fas fa-eye"></i>
                            <span><?= $libro['vistas'] ?> vistas</span>
                        </div>
                        <div class="stat-item-small">
                            <i class="fas fa-heart"></i>
                            <span>0 favoritos</span>
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