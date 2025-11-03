<?php
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../../config/funciones.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión para publicar un libro');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

$pageTitle = 'Publicar Libro - YACHAY';
?>
<!DOCTYPE html>
<html lang="es">
<?php include __DIR__ . '/../../INCLUDE/head.php'; ?>
<body>
    
    <?php include __DIR__ . '/../../INCLUDE/header.php'; ?>
    
    <main class="page-form">
        <div class="container">
            
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="<?= BASE_URL ?>index.php">Inicio</a>
                <i class="fas fa-chevron-right"></i>
                <a href="libros.php">Libros</a>
                <i class="fas fa-chevron-right"></i>
                <span>Publicar Libro</span>
            </nav>
            
            <div class="form-container">
                
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-book"></i>
                        Publicar Libro Usado
                    </h1>
                    <p class="form-subtitle">
                        Vende tus libros universitarios y ayuda a otros estudiantes a ahorrar
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
                
                <form action="../../CONTROLADOR/LibroController.php?action=crear" method="POST" enctype="multipart/form-data" class="form">
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Información del Libro</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-book"></i>
                                    Título del Libro *
                                </label>
                                <input type="text" name="titulo" class="form-input" required 
                                       placeholder="Ej: Cálculo de Una Variable">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-edit"></i>
                                    Autor *
                                </label>
                                <input type="text" name="autor" class="form-input" required 
                                       placeholder="Ej: James Stewart">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-building"></i>
                                    Editorial
                                </label>
                                <input type="text" name="editorial" class="form-input" 
                                       placeholder="Ej: Cengage Learning">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-check-circle"></i>
                                    Estado del Libro *
                                </label>
                                <select name="estado" class="form-select" required>
                                    <option value="">Selecciona el estado</option>
                                    <option value="nuevo">Nuevo</option>
                                    <option value="como_nuevo">Como Nuevo</option>
                                    <option value="bueno">Bueno</option>
                                    <option value="aceptable">Aceptable</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Descripción
                            </label>
                            <textarea name="descripcion" class="form-textarea" rows="4" 
                                      placeholder="Describe el estado del libro, si tiene marcas, subrayados, etc."></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Precio</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tag"></i>
                                    Precio de Venta (S/) *
                                </label>
                                <input type="number" name="precio" class="form-input" step="0.01" min="0" required 
                                       placeholder="Ej: 50.00">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-receipt"></i>
                                    Precio Original (S/)
                                </label>
                                <input type="number" name="precio_original" class="form-input" step="0.01" min="0" 
                                       placeholder="Ej: 150.00">
                                <small class="form-help">Ayuda a mostrar el ahorro</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Información Académica</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-university"></i>
                                    Universidad *
                                </label>
                                <select name="universidad" class="form-select" required>
                                    <option value="">Selecciona tu universidad</option>
                                    <option value="unsaac">UNSAAC</option>
                                    <option value="continental">Universidad Continental</option>
                                    <option value="andina">Universidad Andina</option>
                                    <option value="otra">Otra</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-graduation-cap"></i>
                                    Carrera
                                </label>
                                <input type="text" name="carrera" class="form-input" 
                                       placeholder="Ej: Ingeniería de Sistemas">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-book-open"></i>
                                    Curso
                                </label>
                                <input type="text" name="curso" class="form-input" 
                                       placeholder="Ej: Cálculo I">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-layer-group"></i>
                                    Ciclo
                                </label>
                                <input type="text" name="ciclo" class="form-input" 
                                       placeholder="Ej: 1, 2, 3...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Foto del Libro</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-camera"></i>
                                Foto
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="foto" id="foto" class="file-input" accept="image/*">
                                <label for="foto" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Haz clic para subir una foto</span>
                                </label>
                            </div>
                            <small class="form-help">JPG, JPEG o PNG. Máximo 5MB</small>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check"></i>
                            Publicar Libro
                        </button>
                        <a href="libros.php" class="btn btn-outline btn-lg">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                    
                </form>
                
            </div>
            
        </div>
    </main>
    
    <?php include __DIR__ . '/../../INCLUDE/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>MVC/VISTA/JS/main.js"></script>
    
</body>
</html>