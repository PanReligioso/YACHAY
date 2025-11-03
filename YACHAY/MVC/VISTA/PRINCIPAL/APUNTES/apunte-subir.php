<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión para subir apuntes');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

$pageTitle = 'Subir Apunte - YACHAY';
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
                <a href="apuntes.php">Apuntes</a>
                <i class="fas fa-chevron-right"></i>
                <span>Subir Apunte</span>
            </nav>

            <div class="form-container">

                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Subir Apunte
                    </h1>
                    <p class="form-subtitle">
                        Comparte tus apuntes y ayuda a otros estudiantes
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

                <form action="../../CONTROLADOR/ApunteController.php?action=subir" method="POST" enctype="multipart/form-data" class="form">

                    <div class="form-section">
                        <h3 class="form-section-title">Información del Apunte</h3>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-heading"></i>
                                Título del Apunte *
                            </label>
                            <input
                                type="text"
                                name="titulo"
                                class="form-input"
                                placeholder="Ej: Resumen de Cálculo Integral - Capítulo 5"
                                required>
                            <small class="form-help">Sé específico para que sea fácil de encontrar</small>
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
                                placeholder="Describe el contenido de tu apunte: temas tratados, fecha de clase, etc."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-file-pdf"></i>
                                Archivo PDF *
                            </label>
                            <div class="file-upload file-upload-pdf">
                                <input
                                    type="file"
                                    name="archivo_pdf"
                                    id="archivo_pdf"
                                    accept=".pdf,application/pdf"
                                    required
                                    onchange="showFileName(this)">
                                <label for="archivo_pdf" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Haz clic para seleccionar el PDF</span>
                                    <span class="file-upload-help">Solo archivos PDF (máx 10MB)</span>
                                </label>
                                <div id="file-name" class="file-name" style="display: none;">
                                    <i class="fas fa-file-pdf"></i>
                                    <span></span>
                                    <button type="button" onclick="clearFile()" class="file-clear">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
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
                                    <option value="todas">Aplica para todas</option>
                                    <option value="otra">Otra</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-graduation-cap"></i>
                                    Carrera *
                                </label>
                                <input
                                    type="text"
                                    name="carrera"
                                    class="form-input"
                                    placeholder="Ej: Ingeniería de Sistemas"
                                    required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-book-open"></i>
                                    Curso *
                                </label>
                                <input
                                    type="text"
                                    name="curso"
                                    class="form-input"
                                    placeholder="Ej: Cálculo Integral"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-bookmark"></i>
                                    Tema Específico
                                </label>
                                <input
                                    type="text"
                                    name="tema"
                                    class="form-input"
                                    placeholder="Ej: Integrales Definidas">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-layer-group"></i>
                                Ciclo *
                            </label>
                            <select name="ciclo" class="form-select" required>
                                <option value="">Selecciona el ciclo</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?>° Ciclo</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Importante:</strong>
                            <p>Al subir tus apuntes, aceptas compartirlos de forma gratuita con la comunidad estudiantil. Asegúrate de que el contenido sea tuyo o tengas permiso para compartirlo.</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Subir Apunte
                        </button>
                        <a href="apuntes.php" class="btn btn-outline btn-lg">
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
    <script>
        // Mostrar nombre del archivo seleccionado
        function showFileName(input) {
            const fileName = document.getElementById('file-name');
            const fileNameSpan = fileName.querySelector('span');

            if (input.files && input.files[0]) {
                fileNameSpan.textContent = input.files[0].name;
                fileName.style.display = 'flex';
                input.parentElement.querySelector('.file-upload-label').style.display = 'none';
            }
        }

        // Limpiar archivo
        function clearFile() {
            const input = document.getElementById('archivo_pdf');
            const fileName = document.getElementById('file-name');
            const label = input.parentElement.querySelector('.file-upload-label');

            input.value = '';
            fileName.style.display = 'none';
            label.style.display = 'flex';
        }
    </script>

</body>

</html>