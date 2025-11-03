@extends('layouts.app')

@section('title', 'Subir Apuntes - YACHAY')

@section('content')

@php
// Verificar si está logueado
if(!session('logged_in', false)) {
    header('Location: /login');
    exit;
}

$cursosData = json_decode(file_get_contents(storage_path('app/cursos.json')), true) ?? [];
$cursosPorCiclo = [];
foreach($cursosData as $curso) {
    $cursosPorCiclo[$curso['ciclo']][] = $curso;
}
ksort($cursosPorCiclo);

$mensaje = '';
$tipo = '';

// Procesar formulario
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $idCurso = $_POST['id_curso'] ?? '';
    $tipoMaterial = $_POST['tipo_material'] ?? '';
    $urlDrive = $_POST['url_drive'] ?? '';
    $terminos = isset($_POST['terminos']);

    if($titulo && $descripcion && $idCurso && $tipoMaterial && $urlDrive && $terminos) {
        $apuntesData = json_decode(file_get_contents(storage_path('app/apuntes.json')), true) ?? [];

        $nuevoId = 1;
        if(count($apuntesData) > 0) {
            $nuevoId = max(array_column($apuntesData, 'id_apunte')) + 1;
        }

        $nuevoApunte = [
            'id_apunte' => $nuevoId,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'id_curso' => (int)$idCurso,
            'tipo_material' => $tipoMaterial,
            'url_drive' => $urlDrive,
            'id_usuario_subida' => session('user_id', 1),
            'estado_validacion' => 'aprobado',
            'vistas' => 0,
            'descargas' => 0,
            'fecha_subida' => date('Y-m-d H:i:s')
        ];

        $apuntesData[] = $nuevoApunte;
        file_put_contents(storage_path('app/apuntes.json'), json_encode($apuntesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header('Location: /apuntes/' . $nuevoId);
        exit;
    } else {
        $mensaje = 'Por favor, completa todos los campos requeridos';
        $tipo = 'error';
    }
}
@endphp

<!-- Breadcrumb Hero -->
<section style="background: var(--bg-secondary); padding: var(--spacing-2xl) 0; margin-top: 80px;">
    <div class="container">
        <div style="color: var(--text-secondary); margin-bottom: var(--spacing-sm);">
            <a href="/" style="color: var(--text-secondary);">Inicio</a>
            <i class="fas fa-chevron-right" style="margin: 0 var(--spacing-sm); font-size: var(--text-xs);"></i>
            <a href="/apuntes" style="color: var(--text-secondary);">Apuntes</a>
            <i class="fas fa-chevron-right" style="margin: 0 var(--spacing-sm); font-size: var(--text-xs);"></i>
            <span style="color: var(--primary-600);">Subir Apuntes</span>
        </div>
        <h1 style="font-size: var(--text-4xl); margin-top: var(--spacing-md);">Compartir Material de Estudio</h1>
    </div>
</section>

<!-- Contenido -->
<section class="section">
    <div class="container" style="max-width: 900px;">

        <?php if($mensaje): ?>
        <div style="padding: var(--spacing-lg); margin-bottom: var(--spacing-xl);
                    background: <?= $tipo === 'error' ? '#ef444420' : '#10b98120' ?>;
                    border-left: 4px solid <?= $tipo === 'error' ? '#ef4444' : '#10b981' ?>;
                    border-radius: var(--radius-md);">
            <p style="color: <?= $tipo === 'error' ? '#ef4444' : '#10b981' ?>; margin: 0; font-weight: 600;">
                <?= htmlspecialchars($mensaje) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Instrucciones -->
        <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-2xl);">
            <h2 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-lg);">
                <i class="fas fa-info-circle"></i> Antes de subir tu material
            </h2>
            <div style="display: grid; gap: var(--spacing-md);">
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div style="width: 30px; height: 30px; background: var(--primary-600);
                                border-radius: 50%; color: white; display: flex;
                                align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">1</div>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Asegúrate de que el material sea de tu autoría o tengas permiso para compartirlo
                    </p>
                </div>
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div style="width: 30px; height: 30px; background: var(--primary-600);
                                border-radius: 50%; color: white; display: flex;
                                align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">2</div>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Verifica que el contenido sea claro, organizado y útil para otros estudiantes
                    </p>
                </div>
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div style="width: 30px; height: 30px; background: var(--primary-600);
                                border-radius: 50%; color: white; display: flex;
                                align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">3</div>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Configura el enlace de Google Drive con permisos de visualización para cualquiera con el enlace
                    </p>
                </div>
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div style="width: 30px; height: 30px; background: var(--primary-600);
                                border-radius: 50%; color: white; display: flex;
                                align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">4</div>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Usa títulos descriptivos y proporciona una descripción completa del contenido
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="card" style="padding: var(--spacing-2xl);">
            <h2 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-2xl);">
                Formulario de Subida
            </h2>

            <form method="POST" id="formSubir">
                <!-- Título -->
                <div style="margin-bottom: var(--spacing-xl);">
                    <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                        Título del Material <span style="color: var(--accent-red);">*</span>
                    </label>
                    <input type="text" name="titulo" required
                           placeholder="Ej: Apuntes Completos de Cálculo Diferencial"
                           style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                  border-radius: var(--radius-md); font-size: var(--text-base);">
                </div>

                <!-- Descripción -->
                <div style="margin-bottom: var(--spacing-xl);">
                    <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                        Descripción <span style="color: var(--accent-red);">*</span>
                    </label>
                    <textarea name="descripcion" required rows="5"
                              placeholder="Describe el contenido del material, temas que cubre, formato, etc."
                              style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                     border-radius: var(--radius-md); font-size: var(--text-base); resize: vertical;"></textarea>
                </div>

                <!-- Curso -->
                <div style="margin-bottom: var(--spacing-xl);">
                    <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                        Curso <span style="color: var(--accent-red);">*</span>
                    </label>
                    <select name="id_curso" required
                            style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                   border-radius: var(--radius-md); font-size: var(--text-base);">
                        <option value="">Selecciona un curso</option>
                        <?php foreach($cursosPorCiclo as $ciclo => $cursos): ?>
                            <optgroup label="CICLO <?= $ciclo ?>">
                                <?php foreach($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>">
                                        <?= htmlspecialchars($curso['nombre_curso']) ?> (<?= $curso['codigo_curso'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tipo de Material -->
                <div style="margin-bottom: var(--spacing-xl);">
                    <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                        Tipo de Material <span style="color: var(--accent-red);">*</span>
                    </label>
                    <select name="tipo_material" required
                            style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                   border-radius: var(--radius-md); font-size: var(--text-base);">
                        <option value="">Selecciona el tipo</option>
                        <option value="apuntes">📝 Apuntes</option>
                        <option value="guia">📖 Guía</option>
                        <option value="ejercicios">✏️ Ejercicios</option>
                        <option value="examenes">📋 Exámenes</option>
                        <option value="proyecto">🎯 Proyecto</option>
                        <option value="otro">📄 Otro</option>
                    </select>
                </div>

                <!-- URL Google Drive -->
                <div style="margin-bottom: var(--spacing-xl);">
                    <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                        Enlace de Google Drive <span style="color: var(--accent-red);">*</span>
                    </label>
                    <input type="url" name="url_drive" required
                           placeholder="https://drive.google.com/file/d/..."
                           style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                  border-radius: var(--radius-md); font-size: var(--text-base);">
                    <p style="color: var(--text-tertiary); font-size: var(--text-sm); margin-top: var(--spacing-sm);">
                        <i class="fas fa-info-circle"></i> Asegúrate de que el enlace tenga permisos de visualización
                    </p>
                </div>

                <!-- Términos -->
                <div style="margin-bottom: var(--spacing-2xl);">
                    <label style="display: flex; align-items: start; gap: var(--spacing-md); cursor: pointer;">
                        <input type="checkbox" name="terminos" required
                               style="margin-top: 4px; width: 20px; height: 20px; cursor: pointer;">
                        <span style="color: var(--text-secondary);">
                            Acepto los términos y condiciones. Confirmo que tengo derecho a compartir este material
                            y que no infringe derechos de autor. <span style="color: var(--accent-red);">*</span>
                        </span>
                    </label>
                </div>

                <!-- Botones -->
                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                    <a href="/apuntes" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i>
                        Subir Apuntes
                    </button>
                </div>
            </form>
        </div>

        <!-- Tutorial Google Drive -->
        <div class="card" style="padding: var(--spacing-2xl); margin-top: var(--spacing-2xl);">
            <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-lg);">
                <i class="fab fa-google-drive"></i> ¿Cómo subir archivos a Google Drive?
            </h3>
            <div style="display: grid; gap: var(--spacing-lg);">
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                border-radius: 50%; color: white; display: flex;
                                align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">1</div>
                    <div>
                        <strong style="color: var(--text-primary); display: block; margin-bottom: var(--spacing-xs);">
                            Sube tu archivo
                        </strong>
                        <p style="color: var(--text-secondary); margin: 0;">
                            Ve a drive.google.com, haz clic en "Nuevo" y selecciona "Subir archivo"
                        </p>
                    </div>
                </div>
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                border-radius: 50%; color: white; display: flex;
                                align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">2</div>
                    <div>
                        <strong style="color: var(--text-primary); display: block; margin-bottom: var(--spacing-xs);">
                            Configura permisos
                        </strong>
                        <p style="color: var(--text-secondary); margin: 0;">
                            Clic derecho en el archivo → "Compartir" → "Cualquier persona con el enlace puede ver"
                        </p>
                    </div>
                </div>
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                border-radius: 50%; color: white; display: flex;
                                align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">3</div>
                    <div>
                        <strong style="color: var(--text-primary); display: block; margin-bottom: var(--spacing-xs);">
                            Copia el enlace
                        </strong>
                        <p style="color: var(--text-secondary); margin: 0;">
                            Haz clic en "Copiar enlace" y pégalo en el formulario de arriba
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
document.getElementById('formSubir').addEventListener('submit', function(e) {
    const terminos = document.querySelector('input[name="terminos"]');
    if(!terminos.checked) {
        e.preventDefault();
        alert('Debes aceptar los términos y condiciones');
        return false;
    }
});
</script>

@endsection
