<?php
// RUTA CORREGIDA: Asumiendo que esta es la ruta de 3 niveles que funciona para todas las configuraciones
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';

// CORRECCIÓN: La ruta a los Modelos es un nivel menos que a config.
require_once __DIR__ . '/../../MODELO/Usuario.php';
require_once __DIR__ . '/../../MODELO/Libro.php';
require_once __DIR__ . '/../../MODELO/Apunte.php';
require_once __DIR__ . '/../../MODELO/Tutoria.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión');
    // Usamos redirect() que ya utiliza BASE_URL
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

$pageTitle = 'Mi Perfil - YACHAY';

// Obtener datos del usuario
$usuarioModel = new Usuario();
$usuario = $usuarioModel->obtenerPorId(getUserId()); 

// Validación: Si el usuario no existe (aunque esté logueado), debe salir.
if (!$usuario) {
    session_destroy();
    setFlash('error', 'Error al cargar perfil.');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

// Obtener publicaciones del usuario
$libroModel = new Libro();
$apunteModel = new Apunte();
$tutoriaModel = new Tutoria();

// CORRECCIÓN: Los modelos ya fueron corregidos para usar getUserId() como id_usuario
$misLibros = $libroModel->obtenerPorUsuario(getUserId());
$misApuntes = $apunteModel->obtenerPorUsuario(getUserId());
$misTutorias = $tutoriaModel->obtenerPorTutor(getUserId());
?>
<!DOCTYPE html>
<html lang="es">
<?php include '../INCLUDE/head.php'; ?>
<body>
    
    <?php include '../INCLUDE/header.php'; ?>
    
    <main class="perfil-page">
        <div class="container">
            
            <?php 
            $flash = getFlash();
            if ($flash): 
            ?>
                <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
                    <i class="fas fa-info-circle"></i>
                    <?= htmlspecialchars($flash['mensaje']) ?>
                </div>
            <?php endif; ?>
            
            <div class="perfil-header">
                <div class="perfil-avatar">
                    <img src="<?= BASE_URL ?>MVC/VISTA/IMG/uploads/perfil/<?= $usuario['foto_perfil'] ?? 'default-avatar.png' ?>" 
                         alt="<?= htmlspecialchars($usuario['nombre']) ?>"
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($usuario['nombre_completo'] ?? $usuario['email']) ?>&size=150&background=4f46e5&color=fff'">
                </div>
                <div class="perfil-info">
                    <h1><?= htmlspecialchars($usuario['nombre']) ?> <?= htmlspecialchars($usuario['apellidos']) ?></h1>
                    <p class="perfil-meta">
                        <i class="fas fa-university"></i>
                        <span><?= htmlspecialchars(ucfirst($usuario['universidad'] ?? 'Sin datos')) ?></span>
                        <span class="divider">•</span>
                        <i class="fas fa-graduation-cap"></i>
                        <span><?= htmlspecialchars($usuario['carrera'] ?? 'Sin datos') ?></span>
                        <span class="divider">•</span>
                        <i class="fas fa-layer-group"></i>
                        <span><?= htmlspecialchars($usuario['ciclo'] ?? '0') ?>° Ciclo</span>
                    </p>
                    <p class="perfil-contact">
                        <i class="fas fa-envelope"></i>
                        <?= htmlspecialchars($usuario['email']) ?>
                        <?php if($usuario['telefono']): ?>
                            <span class="divider">•</span>
                            <i class="fas fa-phone"></i>
                            <?= htmlspecialchars($usuario['telefono']) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="perfil-actions">
                    <a href="../PRINCIPAL/perfil-editar.php" class="btn btn-primary" id="btnEditarPerfil">
                        <i class="fas fa-edit"></i>
                        Editar Perfil
                    </a>
                </div>
            </div>
            
            <div class="perfil-stats">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-book"></i></div>
                    <div class="stat-content">
                        <h3><?= count($misLibros) ?></h3>
                        <p>Libros Publicados</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-file-pdf"></i></div>
                    <div class="stat-content">
                        <h3><?= count($misApuntes) ?></h3>
                        <p>Apuntes Compartidos</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="stat-content">
                        <h3><?= count($misTutorias) ?></h3>
                        <p>Tutorías Ofrecidas</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-heart"></i></div>
                    <div class="stat-content">
                        <h3>0</h3>
                        <p>Favoritos</p>
                    </div>
                </div>
            </div>
            
            <div class="perfil-tabs">
                <div class="tabs-header">
                    <button class="tab-btn active" data-tab="libros"><i class="fas fa-book"></i> Mis Libros (<?= count($misLibros) ?>)</button>
                    <button class="tab-btn" data-tab="apuntes"><i class="fas fa-file-pdf"></i> Mis Apuntes (<?= count($misApuntes) ?>)</button>
                    <button class="tab-btn" data-tab="tutorias"><i class="fas fa-chalkboard-teacher"></i> Mis Tutorías (<?= count($misTutorias) ?>)</button>
                </div>
                
                <div class="tabs-content">
                    
                    <div class="tab-panel active" id="tab-libros">
                        <?php if (empty($misLibros)): /* ... empty state ... */ ?>
                            <div class="empty-state"><i class="fas fa-book"></i><h3>No has publicado libros aún</h3><p>Comparte los libros que ya no usas y ayuda a otros estudiantes</p><a href="../LIBROS/libro-crear.php" class="btn btn-primary"><i class="fas fa-plus"></i> Publicar Libro</a></div>
                        <?php else: ?>
                            <div class="perfil-grid">
                                <?php foreach($misLibros as $libro): ?>
                                    <div class="perfil-item">
                                        <div class="item-image">
                                            <img src="<?= BASE_URL ?>MVC/VISTA/IMG/uploads/libros/<?= $libro['url_drive'] ?? 'default-book.jpg' ?>" alt="<?= htmlspecialchars($libro['titulo']) ?>">
                                            
                                            <?php if($libro['estado_validacion'] == 'rechazado'): ?>
                                                <span class="item-badge badge-vendido">Vendido/No disponible</span>
                                            <?php elseif ($libro['estado_validacion'] == 'aprobado'): ?>
                                                <span class="item-badge badge-disponible">Disponible</span>
                                            <?php else: ?>
                                                <span class="item-badge badge-pendiente">Pendiente</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="item-content">
                                            <h4><?= htmlspecialchars($libro['titulo']) ?></h4>
                                            <p class="item-meta">
                                                <?= htmlspecialchars($libro['autor_libro']) ?>
                                                </p>
                                            <p class="item-stats"><i class="fas fa-eye"></i> <?= $libro['vistas'] ?> vistas</p>
                                            <div class="item-actions">
                                                <a href="../LIBROS/libros-detalles.php?id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Ver</a>
                                                <a href="../LIBROS/libro-editar.php?id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Editar</a>
                                                
                                                <?php if($libro['estado_validacion'] == 'aprobado'): ?>
                                                    <a href="../../CONTROLADOR/LibroController.php?action=marcarVendido&id=<?= $libro['id_libro'] ?>" 
                                                         class="btn btn-sm btn-outline"
                                                         onclick="return confirm('¿Marcar como vendido?')">
                                                         <i class="fas fa-check"></i> Vendido
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <a href="../../CONTROLADOR/LibroController.php?action=eliminar&id=<?= $libro['id_libro'] ?>" 
                                                     class="btn btn-sm btn-outline"
                                                     onclick="return confirm('¿Eliminar este libro?')">
                                                     <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-panel" id="tab-apuntes">
                        <?php if (empty($misApuntes)): /* ... empty state ... */ ?>
                            <div class="empty-state"><i class="fas fa-file-pdf"></i><h3>No has compartido apuntes aún</h3><p>Sube tus apuntes y ayuda a otros estudiantes que faltaron a clase</p><a href="../APUNTES/apunte-subir.php" class="btn btn-primary"><i class="fas fa-upload"></i> Subir Apunte</a></div>
                        <?php else: ?>
                            <div class="perfil-list">
                                <?php foreach($misApuntes as $apunte): ?>
                                    <div class="list-item">
                                        <div class="list-icon"><i class="fas fa-file-pdf"></i></div>
                                        <div class="list-content">
                                            <h4><?= htmlspecialchars($apunte['titulo']) ?></h4>
                                            <p class="list-meta">
                                                Curso ID: <?= htmlspecialchars($apunte['id_curso'] ?? 'N/A') ?> 
                                            </p>
                                            <p class="list-stats">
                                                <i class="fas fa-download"></i> <?= $apunte['descargas'] ?> descargas
                                                <span class="divider">•</span>
                                                <i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($apunte['fecha_subida'])) ?>
                                            </p>
                                        </div>
                                        <div class="list-actions">
                                            <a href="../APUNTES/apunte-detalle.php?id=<?= $apunte['id_apunte'] ?>" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Ver</a>
                                            <a href="../../CONTROLADOR/ApunteController.php?action=eliminar&id=<?= $apunte['id_apunte'] ?>" 
                                               class="btn btn-sm btn-outline"
                                               onclick="return confirm('¿Eliminar este apunte?')">
                                               <i class="fas fa-trash"></i> Eliminar
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-panel" id="tab-tutorias">
                        <?php if (empty($misTutorias)): /* ... empty state ... */ ?>
                            <div class="empty-state"><i class="fas fa-chalkboard-teacher"></i><h3>No ofreces tutorías aún</h3><p>Comparte tu conocimiento y ayuda a otros estudiantes</p><a href="../TUTORIAS/tutoria-crear.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ofrecer Tutoría</a></div>
                        <?php else: ?>
                            <div class="perfil-list">
                                <?php foreach($misTutorias as $tutoria): ?>
                                    <div class="list-item">
                                        <div class="list-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                        <div class="list-content">
                                            <h4><?= htmlspecialchars($tutoria['materia']) ?></h4>
                                            <p class="list-meta">
                                                S/ <?= number_format($tutoria['precio_hora'], 2) ?>/hora • <?= htmlspecialchars(ucfirst($tutoria['modalidad'])) ?>
                                            </p>
                                            <p class="list-stats">
                                                <i class="fas fa-star"></i> 0.0 (0 opiniones)
                                            </p>
                                        </div>
                                        <div class="list-actions">
                                            <a href="../TUTORIAS/tutoria-detalle.php?id=<?= $tutoria['id'] ?>" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Ver</a>
                                            <a href="../../CONTROLADOR/TutoriaController.php?action=eliminar&id=<?= $tutoria['id'] ?>" 
                                               class="btn btn-sm btn-outline"
                                               onclick="return confirm('¿Eliminar esta tutoría?')">
                                               <i class="fas fa-trash"></i> Eliminar
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </main>
    
    <?php include '../INCLUDE/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>MVC/VISTA/JS/main.js"></script>  
    <script>
        // Tabs functionality
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabName = this.dataset.tab;
                
                // Remove active class
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                
                // Add active class
                this.classList.add('active');
                document.getElementById('tab-' + tabName).classList.add('active');
            });
        });
    </script>
    
</body>
</html>