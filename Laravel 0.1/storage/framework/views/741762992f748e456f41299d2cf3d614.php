<?php
$id = $id ?? 1;
$apuntesData = json_decode(file_get_contents(storage_path('app/apuntes.json')), true) ?? [];
$cursosData = json_decode(file_get_contents(storage_path('app/cursos.json')), true) ?? [];

$apunte = array_values(array_filter($apuntesData, fn($a) => $a['id_apunte'] == $id))[0] ?? null;

if(!$apunte) {
    header('Location: /apuntes');
    exit;
}

// Incrementar vistas
$apunte['vistas']++;
foreach($apuntesData as $key => $a) {
    if($a['id_apunte'] == $id) {
        $apuntesData[$key]['vistas'] = $apunte['vistas'];
        break;
    }
}
file_put_contents(storage_path('app/apuntes.json'), json_encode($apuntesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

$curso = array_values(array_filter($cursosData, fn($c) => $c['id_curso'] == $apunte['id_curso']))[0] ?? null;

$tiposIconos = [
    'apuntes' => ['icon' => 'fa-file-alt', 'color' => 'var(--primary-600)', 'bg' => 'var(--primary-100)'],
    'guia' => ['icon' => 'fa-book-open', 'color' => 'var(--secondary-600)', 'bg' => 'var(--secondary-100)'],
    'ejercicios' => ['icon' => 'fa-pen', 'color' => 'var(--accent-orange)', 'bg' => '#ff6b3520'],
    'examenes' => ['icon' => 'fa-file-signature', 'color' => 'var(--accent-red)', 'bg' => '#ef444420'],
    'proyecto' => ['icon' => 'fa-project-diagram', 'color' => 'var(--accent-green)', 'bg' => '#10b98120'],
    'otro' => ['icon' => 'fa-file', 'color' => 'var(--text-tertiary)', 'bg' => 'var(--bg-secondary)']
];

$tipoConfig = $tiposIconos[$apunte['tipo_material']] ?? $tiposIconos['otro'];

$relacionados = array_filter($apuntesData, function($a) use ($apunte, $curso) {
    return $a['id_apunte'] != $apunte['id_apunte'] &&
           $a['estado_validacion'] == 'aprobado' &&
           ($a['id_curso'] == $apunte['id_curso']);
});
$relacionados = array_slice($relacionados, 0, 3);
?>

<?php $__env->startSection('title', htmlspecialchars($apunte['titulo']) . ' - YACHAY'); ?>

<?php $__env->startSection('content'); ?>

<!-- Breadcrumb Hero -->
<section style="background: var(--bg-secondary); padding: var(--spacing-2xl) 0; margin-top: 80px;">
    <div class="container">
        <div style="color: var(--text-secondary); margin-bottom: var(--spacing-sm);">
            <a href="/" style="color: var(--text-secondary);">Inicio</a>
            <i class="fas fa-chevron-right" style="margin: 0 var(--spacing-sm); font-size: var(--text-xs);"></i>
            <a href="/apuntes" style="color: var(--text-secondary);">Apuntes</a>
            <i class="fas fa-chevron-right" style="margin: 0 var(--spacing-sm); font-size: var(--text-xs);"></i>
            <span style="color: var(--primary-600);"><?= htmlspecialchars($apunte['titulo']) ?></span>
        </div>
    </div>
</section>

<!-- Contenido Principal -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 350px 1fr; gap: var(--spacing-2xl); align-items: start;">

            <!-- Sidebar Izquierda (Sticky) -->
            <div style="position: sticky; top: 100px;">
                <div class="card" style="padding: var(--spacing-xl);">
                    <!-- Badge Tipo -->
                    <div style="width: 80px; height: 80px; margin: 0 auto var(--spacing-lg);
                                background: <?= $tipoConfig['bg'] ?>; border-radius: var(--radius-lg);
                                display: flex; align-items: center; justify-content: center;">
                        <i class="fas <?= $tipoConfig['icon'] ?>"
                           style="font-size: var(--text-4xl); color: <?= $tipoConfig['color'] ?>;"></i>
                    </div>

                    <div style="text-align: center; margin-bottom: var(--spacing-xl);">
                        <span style="display: inline-block; padding: var(--spacing-xs) var(--spacing-lg);
                                     background: <?= $tipoConfig['bg'] ?>; border-radius: var(--radius-full);
                                     color: <?= $tipoConfig['color'] ?>; font-weight: 600; font-size: var(--text-sm);">
                            <?= ucfirst($apunte['tipo_material']) ?>
                        </span>
                    </div>

                    <!-- Info del Curso -->
                    <?php if($curso): ?>
                        <div style="margin-bottom: var(--spacing-xl); padding-bottom: var(--spacing-xl);
                                    border-bottom: 2px solid var(--primary-100);">
                            <h4 style="font-size: var(--text-sm); color: var(--text-tertiary);
                                       margin-bottom: var(--spacing-md); text-transform: uppercase;
                                       letter-spacing: 1px;">Curso</h4>
                            <p style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);
                                      margin-bottom: var(--spacing-sm);">
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </p>
                            <p style="color: var(--text-secondary); font-size: var(--text-sm);">
                                <i class="fas fa-code"></i> <?= $curso['codigo_curso'] ?>
                            </p>
                            <p style="color: var(--text-secondary); font-size: var(--text-sm);">
                                <i class="fas fa-layer-group"></i> Ciclo <?= $curso['ciclo'] ?>
                            </p>
                            <p style="color: var(--text-secondary); font-size: var(--text-sm);">
                                <i class="fas fa-star"></i> <?= $curso['creditos'] ?> créditos
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Estadísticas -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <div style="display: flex; justify-content: space-around; padding: var(--spacing-md);
                                    background: var(--bg-secondary); border-radius: var(--radius-md);">
                            <div style="text-align: center;">
                                <div style="font-size: var(--text-2xl); font-weight: 800;
                                            color: var(--primary-600);"><?= $apunte['vistas'] ?></div>
                                <div style="font-size: var(--text-xs); color: var(--text-tertiary);">Vistas</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: var(--text-2xl); font-weight: 800;
                                            color: var(--secondary-600);"><?= $apunte['descargas'] ?></div>
                                <div style="font-size: var(--text-xs); color: var(--text-tertiary);">Descargas</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Descargar -->
                    <a href="<?= htmlspecialchars($apunte['url_drive']) ?>" target="_blank"
                       class="btn btn-primary" style="width: 100%; margin-bottom: var(--spacing-md); justify-content: center;">
                        <i class="fas fa-download"></i>
                        Descargar Apunte
                    </a>

                    <!-- Botón Compartir -->
                    <button onclick="copiarEnlace()" class="btn btn-secondary"
                            style="width: 100%; justify-content: center;">
                        <i class="fas fa-share-alt"></i>
                        Compartir
                    </button>
                </div>
            </div>

            <!-- Contenido Derecha -->
            <div>
                <!-- Título y Descripción -->
                <h1 style="font-size: var(--text-4xl); margin-bottom: var(--spacing-lg); color: var(--text-primary);">
                    <?= htmlspecialchars($apunte['titulo']) ?>
                </h1>

                <div style="display: flex; gap: var(--spacing-lg); margin-bottom: var(--spacing-2xl);
                            color: var(--text-secondary);">
                    <span><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($apunte['fecha_subida'])) ?></span>
                </div>

                <!-- Descripción Completa -->
                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-2xl);">
                    <h3 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-lg);">
                        <i class="fas fa-info-circle"></i> Descripción
                    </h3>
                    <p style="color: var(--text-secondary); line-height: 1.8; font-size: var(--text-lg);">
                        <?= nl2br(htmlspecialchars($apunte['descripcion'])) ?>
                    </p>
                </div>

                <!-- Ventajas -->
                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-2xl);">
                    <h3 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-lg);">
                        <i class="fas fa-check-circle"></i> ¿Por qué usar este material?
                    </h3>
                    <div style="display: grid; gap: var(--spacing-md);">
                        <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                            <div style="width: 40px; height: 40px; background: var(--primary-100);
                                        border-radius: var(--radius-md); display: flex; align-items: center;
                                        justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-star" style="color: var(--primary-600);"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">Material verificado</strong>
                                <p style="color: var(--text-secondary); margin: 0; font-size: var(--text-sm);">
                                    Contenido revisado y aprobado por la comunidad
                                </p>
                            </div>
                        </div>
                        <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                            <div style="width: 40px; height: 40px; background: var(--secondary-100);
                                        border-radius: var(--radius-md); display: flex; align-items: center;
                                        justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-users" style="color: var(--secondary-600);"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">Compartido por estudiantes</strong>
                                <p style="color: var(--text-secondary); margin: 0; font-size: var(--text-sm);">
                                    Elaborado por estudiantes que cursaron la materia
                                </p>
                            </div>
                        </div>
                        <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                            <div style="width: 40px; height: 40px; background: var(--accent-green)20;
                                        border-radius: var(--radius-md); display: flex; align-items: center;
                                        justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-download" style="color: var(--accent-green);"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">Acceso inmediato</strong>
                                <p style="color: var(--text-secondary); margin: 0; font-size: var(--text-sm);">
                                    Descarga directa desde Google Drive
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compartir en Redes -->
                <div class="card" style="padding: var(--spacing-xl);">
                    <h4 style="font-size: var(--text-lg); margin-bottom: var(--spacing-md);">Compartir</h4>
                    <div style="display: flex; gap: var(--spacing-md);">
                        <a href="https://wa.me/?text=<?= urlencode($apunte['titulo'] . ' - ' . url()->current()) ?>"
                           target="_blank" class="btn btn-secondary" style="flex: 1; justify-content: center;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(url()->current()) ?>"
                           target="_blank" class="btn btn-secondary" style="flex: 1; justify-content: center;">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <button onclick="copiarEnlace()" class="btn btn-secondary" style="flex: 1; justify-content: center;">
                            <i class="fas fa-link"></i> Copiar
                        </button>
                    </div>
                </div>

                <!-- Apuntes Relacionados -->
                <?php if(count($relacionados) > 0): ?>
                <div style="margin-top: var(--spacing-3xl);">
                    <h3 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-xl);">
                        Material Relacionado
                    </h3>
                    <div class="grid grid-3">
                        <?php foreach($relacionados as $rel): ?>
                            <?php
                            $cursoRel = array_values(array_filter($cursosData, fn($c) => $c['id_curso'] == $rel['id_curso']))[0] ?? null;
                            $tipoConfigRel = $tiposIconos[$rel['tipo_material']] ?? $tiposIconos['otro'];
                            ?>
                            <div class="card" style="padding: var(--spacing-lg);">
                                <div style="display: inline-flex; align-items: center; gap: var(--spacing-xs);
                                            padding: var(--spacing-xs) var(--spacing-sm);
                                            background: <?= $tipoConfigRel['color'] ?>20;
                                            border-radius: var(--radius-full); margin-bottom: var(--spacing-sm);">
                                    <i class="fas <?= $tipoConfigRel['icon'] ?>"
                                       style="color: <?= $tipoConfigRel['color'] ?>; font-size: var(--text-xs);"></i>
                                    <span style="color: <?= $tipoConfigRel['color'] ?>; font-size: var(--text-xs); font-weight: 600;">
                                        <?= ucfirst($rel['tipo_material']) ?>
                                    </span>
                                </div>
                                <h4 style="font-size: var(--text-base); margin-bottom: var(--spacing-sm);">
                                    <?= htmlspecialchars($rel['titulo']) ?>
                                </h4>
                                <a href="/apuntes/<?= $rel['id_apunte'] ?>" class="btn btn-primary"
                                   style="width: 100%; font-size: var(--text-sm); padding: var(--spacing-sm); justify-content: center;">
                                    Ver Apunte
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function copiarEnlace() {
    navigator.clipboard.writeText(window.location.href);
    alert('¡Enlace copiado al portapapeles!');
}
</script>

<style>
@media (max-width: 1061px) {
    section.section > div > div {
        grid-template-columns: 1fr !important;
    }
    section.section > div > div > div:first-child {
        position: static !important;
    }
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\resources\views/includes/apuntes/show.blade.php ENDPATH**/ ?>