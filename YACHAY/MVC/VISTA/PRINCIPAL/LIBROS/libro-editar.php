<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';
require_once __DIR__ . '/../../MODELO/Libro.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

// Obtener ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    setFlash('error', 'Libro no encontrado');
    redirect('MVC/VISTA/LIBROS/libros.php');
}

// Obtener libro
$libroModel = new Libro();
$libro = $libroModel->obtenerPorId($id);

if (!$libro || $libro['user_id'] != getUserId()) {
    setFlash('error', 'No tienes permiso para editar este libro');
    redirect('MVC/VISTA/LIBROS/libros.php');
}

$pageTitle = 'Editar Libro - YACHAY';
?>
<!DOCTYPE html>
<html lang="es">
<?php include '../INCLUDE/head.php'; ?>
<body>
    
    <?php include '../INCLUDE/header.php'; ?>
    
    <main class="page-form">
        <div class="container">
            
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="<?= BASE_URL ?>index.php">Inicio</a>
                <i class="fas fa-chevron-right"></i>
                <a href="libros.php">Libros</a>
                <i class="fas fa-chevron-right"></i>
                <span>Editar Libro</span>
            </nav>
            
            <div class="form-container">
                
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-edit"></i>
                        Editar Libro
                    </h1>
                    <p class="form-subtitle">
                        Actualiza la información de tu libro
                    </p>
                </div>
                
                <!-- Mensaje flash -->
                <?php 
                $flash = getFlash();
                if ($flash): 
                ?>
                    <div class="alert alert-<?= $flash['tipo'] ?>">
                        <i class="fas fa-info-circle"></i>
                        <?= $flash['mensaje'] ?>
                    </div>
                <?php endif; ?>
                
                <form action="../../CONTROLADOR/LibroController.php?action=editar" method="POST" class="form">
                    
                    <input type="hidden" name="id" value="<?= $libro['id'] ?>">
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Información Básica</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-book"></i>
                                    Título del Libro *
                                </label>
                                <input 
                                    type="text" 
                                    name="titulo" 
                                    class="form-input" 
                                    value="<?= htmlspecialchars($libro['titulo']) ?>"
                                    required
                                >
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-edit"></i>
                                    Autor *
                                </label>
                                <input 
                                    type="text" 
                                    name="autor" 
                                    class="form-input" 
                                    value="<?= htmlspecialchars($libro['autor']) ?>"
                                    required
                                >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                Foto Actual
                            </label>
                            <div class="current-image">
                                <img src="<?= BASE_URL ?>MVC/VISTA/IMG/uploads/libros/<?= $libro['foto'] ?>" 
                                     alt="<?= htmlspecialchars($libro['titulo']) ?>"
                                     style="max-width: 200px; border-radius: 8px;">
                            </div>
                            <small class="form-help">La foto no se puede cambiar al editar</small>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Precio y Estado</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Precio de Venta (S/) *
                                </label>
                                <input 
                                    type="number" 
                                    name="precio" 
                                    class="form-input" 
                                    value="<?= $libro['precio'] ?>"
                                    step="0.01"
                                    min="0"
                                    required
                                >
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-check-circle"></i>
                                    Estado del Libro *
                                </label>
                                <select name="estado" class="form-select" required>
                                    <option value="nuevo" <?= $libro['estado'] == 'nuevo' ? 'selected' : '' ?>>Nuevo</option>
                                    <option value="como_nuevo" <?= $libro['estado'] == 'como_nuevo' ? 'selected' : '' ?>>Como Nuevo</option>
                                    <option value="muy_bueno" <?= $libro['estado'] == 'muy_bueno' ? 'selected' : '' ?>>Muy Bueno</option>
                                    <option value="bueno" <?= $libro['estado'] == 'bueno' ? 'selected' : '' ?>>Bueno</option>
                                    <option value="aceptable" <?= $libro['estado'] == 'aceptable' ? 'selected' : '' ?>>Aceptable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Descripción</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Descripción
                            </label>
                            <textarea 
                                name="descripcion" 
                                class="form-textarea" 
                                rows="6"
                            ><?= htmlspecialchars($libro['descripcion']) ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Disponibilidad</h3>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="hidden" name="disponible" value="0">
                                <input 
                                    type="checkbox" 
                                    name="disponible" 
                                    value="1"
                                    <?= $libro['disponible'] ? 'checked' : '' ?>
                                >
                                <span>El libro está disponible para venta</span>
                            </label>
                            <small class="form-help">Desmarca si ya vendiste el libro</small>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i>
                            Guardar Cambios
                        </button>
                        <a href="libros-detalles.php?id=<?= $libro['id'] ?>" class="btn btn-outline btn-lg">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                    
                </form>
                
            </div>
            
        </div>
    </main>
    
    <?php include '../INCLUDE/footer.php'; ?>
    
    <script src="../JS/main.js"></script>
    
</body>
</html>