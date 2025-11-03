<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión para ofrecer tutorías');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

$pageTitle = 'Ofrecer Tutoría - YACHAY';
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
                <span>Ofrecer Tutoría</span>
            </nav>

            <div class="form-container">

                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Ofrecer Tutoría
                    </h1>
                    <p class="form-subtitle">
                        Comparte tu conocimiento y ayuda a otros estudiantes
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

                <form action="../../CONTROLADOR/TutoriaController.php?action=crear" method="POST" class="form">

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
                                placeholder="Ej: Matemática I, Física II, Programación en Python"
                                required>
                            <small class="form-help">Indica la materia específica que enseñas</small>
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
                                placeholder="Describe tu experiencia, metodología de enseñanza, temas que dominas, etc."></textarea>
                            <small class="form-help">Cuéntales a los estudiantes por qué eres un buen tutor</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Precio y Modalidad</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Precio por Hora (S/) *
                                </label>
                                <input
                                    type="number"
                                    name="precio_hora"
                                    class="form-input"
                                    placeholder="20.00"
                                    step="0.01"
                                    min="0"
                                    required>
                                <small class="form-help">Precio sugerido: S/ 10 - S/ 30 por hora</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-laptop"></i>
                                    Modalidad *
                                </label>
                                <select name="modalidad" class="form-select" required>
                                    <option value="">Selecciona la modalidad</option>
                                    <option value="presencial">Presencial</option>
                                    <option value="virtual">Virtual</option>
                                    <option value="ambas">Ambas (Presencial y Virtual)</option>
                                </select>
                            </div>
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
                                placeholder="Ej: Lunes a Viernes de 6pm a 9pm&#10;Sábados de 9am a 2pm"
                                required></textarea>
                            <small class="form-help">Indica tus horarios disponibles para dar tutorías</small>
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
                                    <option value="todas">Ofrezco para todas</option>
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
                    </div>

                    <div class="info-box">
                        <i class="fas fa-lightbulb"></i>
                        <div>
                            <strong>Consejos para ser un buen tutor:</strong>
                            <ul style="margin: var(--spacing-sm) 0 0 var(--spacing-lg); line-height: 1.8;">
                                <li>Sé puntual y respeta los horarios acordados</li>
                                <li>Prepara el material con anticipación</li>
                                <li>Ten paciencia y adapta tu metodología al estudiante</li>
                                <li>Mantén una comunicación clara y profesional</li>
                                <li>Solicita retroalimentación para mejorar</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check-circle"></i>
                            Publicar Tutoría
                        </button>
                        <a href="tutorias.php" class="btn btn-outline btn-lg">
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