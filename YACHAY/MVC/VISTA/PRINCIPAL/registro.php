<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/funciones.php';
require_once __DIR__ . '/../../MODELO/Usuario.php';
require_once __DIR__ . '/../../MODELO/Libro.php';
require_once __DIR__ . '/../../MODELO/Apunte.php';
require_once __DIR__ . '/../../MODELO/Tutoria.php';

// Verificar login
if (!isLoggedIn()) {
    setFlash('error', 'Debes iniciar sesión');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

$pageTitle = 'Mi Perfil - YACHAY';

$usuarioModel = new Usuario();
$usuario = $usuarioModel->obtenerPorId(getUserId()); 

if (!$usuario) {
    session_destroy();
    setFlash('error', 'Error al cargar perfil.');
    redirect('MVC/VISTA/PRINCIPAL/login.php');
}

$libroModel = new Libro();
$apunteModel = new Apunte();
$tutoriaModel = new Tutoria();

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
                <div class="stat-card">...</div>
                <div class="stat-card">...</div>
                <div class="stat-card">...</div>
                <div class="stat-card">...</div>
            </div>
            
            <div class="perfil-tabs">
                <div class="tabs-header">
                    </div>
                
                <div class="tabs-content">
                    
                    <div class="tab-panel active" id="tab-libros">
                        <?php if (empty($misLibros)): /* ... empty state ... */ ?>
                            <div class="empty-state">...</div>
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
                                            <p class="item-meta"><?= htmlspecialchars($libro['autor_libro']) ?></p>
                                            <p class="item-stats"><i class="fas fa-eye"></i> <?= $libro['vistas'] ?> vistas</p>
                                            <div class="item-actions">
                                                </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-panel" id="tab-apuntes">
                        <?php if (empty($misApuntes)): /* ... empty state ... */ ?>
                            <div class="empty-state">...</div>
                        <?php else: ?>
                            <div class="perfil-list"> 
                                <?php foreach($misApuntes as $apunte): ?>
                                    <div class="list-item">
                                        </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-panel" id="tab-tutorias">
                        <?php if (empty($misTutorias)): /* ... empty state ... */ ?>
                            <div class="empty-state">...</div>
                        <?php else: ?>
                            <div class="perfil-list"> 
                                <?php foreach($misTutorias as $tutoria): ?>
                                    <div class="list-item">
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
                
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById('tab-' + tabName).classList.add('active');
            });
        });
    </script>
    
</body>
</html>