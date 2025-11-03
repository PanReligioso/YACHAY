<?php
require_once 'config/database.php';
require_once 'config/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YACHAY - Plataforma Educativa Cusco</title>
    <meta name="description" content="Plataforma de recursos educativos compartidos para estudiantes universitarios de Cusco">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="MVC/VISTA/ESTILOS/style.css">
</head>
<body>
    
    <!-- Header -->
    <?php include 'MVC/VISTA/INCLUDE/header.php'; ?>
    
    <!-- Hero Section -->
    <?php include 'MVC/VISTA/INCLUDE/hero.php'; ?>
    
    <!-- Main Content -->
    <main>
        
        <!-- Sección Libros -->
        <section id="libros" class="section">
            <div class="container">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h2>Marketplace de Libros Universitarios</h2>
                    <p>Compra y vende libros usados. Ahorra hasta 70%</p>
                </div>
                
                <div class="cta-box">
                    <p>Encuentra los libros que necesitas a precios accesibles</p>
                    <a href="MVC/VISTA/PRINCIPAL/LIBROS/libros.php" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Ver Todos los Libros
                    </a>
                </div>
            </div>
        </section>
        
        <!-- Sección Apuntes -->
        <section id="apuntes" class="section section-alt">
            <div class="container">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h2>Repositorio de Apuntes Compartidos</h2>
                    <p>Descarga apuntes de clase gratuitamente</p>
                </div>
                
                <div class="cta-box">
                    <p>Accede a más de 1,000 apuntes de todas las carreras</p>
                    <a href="MVC/VISTA/PRINCIPAL/APUNTES/apuntes.php" class="btn btn-primary">
                        <i class="fas fa-download"></i>
                        Explorar Apuntes
                    </a>
                </div>
            </div>
        </section>
        
        <!-- Sección Tutorías -->
        <section id="tutorias" class="section">
            <div class="container">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h2>Red de Tutorías Entre Estudiantes</h2>
                    <p>Encuentra tutores verificados o comparte tu conocimiento</p>
                </div>
                
                <div class="cta-box">
                    <p>Tutorías desde S/10 por hora con estudiantes destacados</p>
                    <a href="MVC/VISTA/PRINCIPAL/TUTORIAS/tutoria.php" class="btn btn-primary">
                        <i class="fas fa-user-graduate"></i>
                        Buscar Tutores
                    </a>
                </div>
            </div>
        </section>
        
        <!-- Sección Comedores -->
        <section id="comedores" class="section section-alt">
            <div class="container">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h2>Directorio de Comedores Económicos</h2>
                    <p>Encuentra los mejores lugares para comer cerca de tu universidad</p>
                </div>
                
                <div class="cta-box">
                    <p>Menús desde S/7 cerca de UNSAAC, Continental y Andina</p>
                    <a href="MVC/VISTA/PRINCIPAL/COMEDORES/comedores.php" class="btn btn-primary">
                        <i class="fas fa-map-marker-alt"></i>
                        Ver Comedores
                    </a>
                </div>
            </div>
        </section>
        
    </main>
    
    <!-- Footer -->
    <?php include 'MVC/VISTA/INCLUDE/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="MVC/VISTA/JS/main.js"></script>
    
</body>
</html>