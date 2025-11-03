<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión para agregar comedores');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

$pageTitle = 'Agregar Comedor - YACHAY';
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
                <a href="comedores.php">Comedores</a>
                <i class="fas fa-chevron-right"></i>
                <span>Agregar Comedor</span>
            </nav>
            
            <div class="form-container">
                
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-utensils"></i>
                        Agregar Comedor
                    </h1>
                    <p class="form-subtitle">
                        Comparte información sobre comedores económicos cerca de universidades
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
                
                <form action="../../CONTROLADOR/ComedorController.php?action=crear" method="POST" enctype="multipart/form-data" class="form">
                    
                    <div class="form-section">
                        <h3 class="form-section-title">Información Básica</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-store"></i>
                                Nombre del Comedor *
                            </label>
                            <input 
                                type="text" 
                                name="nombre" 
                                class="form-input" 
                                placeholder="Ej: Comedor Universitario El Buen Sabor"
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
                                rows="4"
                                placeholder="Describe el comedor: ambiente, especialidades, etc."
                            ></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                Foto del Comedor
                            </label>
                            <div class="file-upload">
                                <input 
                                    type="file" 
                                    name="foto" 
                                    id="foto" 
                                    accept="image/*"
                                    onchange="previewImage(this)"
                                >
                                <label for="foto" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Haz clic para subir una foto</span>
                                    <span class="file-upload-help">JPG, PNG o GIF (máx 5MB)</span>
                                </label>
                                <div id="image-preview" class="image-preview" style="display: none;">
                                    <img src="" alt="Preview">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section