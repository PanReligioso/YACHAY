<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';
require_once __DIR__ . '/../../MODELO/Tutoria.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

// Obtener ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    setFlash('error', 'Tutoría no encontrada');
    redirect('MVC/VISTA/TUTORIAS/tutorias.php');
}

// Obtener tutoría
$tutoriaModel = new Tutoria();
$tutoria = $tutoriaModel->obtenerPorId($id);

if (!$tutoria || $tutoria['tutor_id'] != getUserId()) {
    setFlash('error', 'No tienes permiso para editar esta tutoría');
    redirect('MVC/VISTA/TUTORIAS/tutorias.php');
}

$pageTitle = 'Editar Tutoría - YACHAY';
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
                <a href="tutorias.php">Tutorías</a>
                <i class="fas fa-chevron-right"></i>
                <span>Editar Tutoría</span>
            </nav>
            
            <div class="form-container">
                
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-edit"></i>
                        Editar Tutoría
                    </h1>
                    <p class="form-subtitle">
                        Actualiza la información de tu tutoría
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
                
                <form action="../../CONTROLADOR/TutoriaController.php?action=actualizar" method="POST" class="form">
                    
                    <input type="hidden" name="id" value="<?= $tutoria['id'] ?>">
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Información de la Tutoría</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-book"></i>
                                Materia *
                            </label>
                            <input 
                                type="text" 
                                name="materia" 
                                class="form-input" 
                                value="<?= htmlspecialchars($tutoria['materia']) ?>"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Descripción
                            </label>
                            <textarea 
                                name="descripcion" 
                                class="form-textarea" 
                                rows="6"
                            ><?= htmlspecialchars($tutoria['descripcion']) ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Precio y Horario</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave"></i>
                                Precio por Hora (S/) *
                            </label>
                            <input 
                                type="number" 
                                name="precio_hora" 
                                class="form-input" 
                                value="<?= $tutoria['precio_hora'] ?>"
                                step="0.01"
                                min="0"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i>
                                Horario Disponible *
                            </label>
                            <textarea 
                                name="horario_disponible" 
                                class="form-textarea" 
                                rows="4"
                                required
                            ><?= htmlspecialchars($tutoria['horario_disponible']) ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Estado</h3>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="hidden" name="activo" value="0">
                                <input 
                                    type="checkbox" 
                                    name="activo" 
                                    value="1"
                                    <?= $tutoria['activo'] ? 'checked' : '' ?>
                                >
                                <span>La tutoría está activa y visible</span>
                            </label>
                            <small class="form-help">Desmarca si no estás disponible temporalmente</small>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i>
                            Guardar Cambios
                        </button>
                        <a href="tutoria-detalle.php?id=<?= $tutoria['id'] ?>" class="btn btn-outline btn-lg">
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