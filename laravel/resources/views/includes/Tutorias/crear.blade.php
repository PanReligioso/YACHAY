@extends('layouts.app')

@section('title', 'YACHAY - Crear Grupo de Tutoría')

@section('content')

<?php
// Proteger ruta
if (!session('logged_in')) {
    header('Location: /login');
    exit;
}

// Cargar cursos
$cursos_json = file_get_contents(storage_path('app/cursos.json'));
$cursos = json_decode($cursos_json, true);

// Procesar formulario
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $curso = $_POST['curso'] ?? null;
    $tipo = $_POST['tipo'] ?? 'publico';
    $max = intval($_POST['max'] ?? 50);
    $codigo = $tipo === 'privado' ? trim($_POST['codigo'] ?? '') : null;

    // Validar
    if (empty($nombre) || empty($descripcion)) {
        $error = 'Nombre y descripción son obligatorios';
    } elseif ($tipo === 'privado' && empty($codigo)) {
        $error = 'El código de acceso es obligatorio para grupos privados';
    } else {
        // Cargar grupos
        $grupos_json = file_get_contents(storage_path('app/grupos_tutoria.json'));
        $grupos = json_decode($grupos_json, true);

        // Crear nuevo grupo
        $nuevo_id = max(array_column($grupos, 'id_grupo')) + 1;
        $nuevo_grupo = [
            'id_grupo' => $nuevo_id,
            'nombre_grupo' => $nombre,
            'descripcion' => $descripcion,
            'id_curso' => $curso ? intval($curso) : null,
            'id_creador' => session('user_id'),
            'tipo' => $tipo,
            'max_participantes' => $max,
            'codigo_acceso' => $codigo,
            'esta_activo' => true,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        $grupos[] = $nuevo_grupo;
        file_put_contents(storage_path('app/grupos_tutoria.json'), json_encode($grupos, JSON_PRETTY_PRINT));

        // Agregar creador como admin
        $miembros_json = file_get_contents(storage_path('app/miembros_grupo.json'));
        $miembros = json_decode($miembros_json, true);

        $nuevo_miembro_id = max(array_column($miembros, 'id_miembro')) + 1;
        $miembros[] = [
            'id_miembro' => $nuevo_miembro_id,
            'id_grupo' => $nuevo_id,
            'id_usuario' => session('user_id'),
            'rol_grupo' => 'admin',
            'fecha_union' => date('Y-m-d H:i:s')
        ];

        file_put_contents(storage_path('app/miembros_grupo.json'), json_encode($miembros, JSON_PRETTY_PRINT));

        // Redirigir
        header("Location: /tutorias/$nuevo_id");
        exit;
    }
}
?>

<!-- Hero -->
<section class="hero" style="min-height: 40vh; padding-top: 120px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: var(--spacing-xl);">
            <ol style="display: flex; list-style: none; gap: var(--spacing-sm); color: var(--text-secondary);">
                <li><a href="{{ url('/') }}" style="color: var(--primary-600);">Inicio</a></li>
                <li><i class="fas fa-chevron-right" style="font-size: var(--text-xs);"></i></li>
                <li><a href="{{ url('/tutorias') }}" style="color: var(--primary-600);">Tutorías</a></li>
                <li><i class="fas fa-chevron-right" style="font-size: var(--text-xs);"></i></li>
                <li>Crear Grupo</li>
            </ol>
        </nav>

        <h1 style="font-size: var(--text-4xl); margin-bottom: var(--spacing-md);">
            Crear Grupo de <span class="highlight">Tutoría</span>
        </h1>
        <p style="font-size: var(--text-lg); color: var(--text-secondary);">
            Comparte conocimiento y aprende en comunidad
        </p>
    </div>
</section>

<!-- Contenido -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--spacing-2xl);">

            <!-- Formulario -->
            <div>
                <!-- Instrucciones -->
                <div class="card" style="padding: var(--spacing-xl); margin-bottom: var(--spacing-xl);
                                        background: var(--primary-50); border-left: 4px solid var(--primary-600);">
                    <h3 style="display: flex; align-items: center; gap: var(--spacing-sm); margin-bottom: var(--spacing-md);">
                        <i class="fas fa-info-circle" style="color: var(--primary-600);"></i>
                        Consejos para tu grupo
                    </h3>
                    <ul style="color: var(--text-secondary); padding-left: var(--spacing-xl); line-height: 1.8;">
                        <li>Usa un nombre descriptivo y atractivo</li>
                        <li>Explica claramente el propósito del grupo</li>
                        <li>Establece reglas y expectativas desde el inicio</li>
                        <li>Si es privado, comparte el código solo con personas de confianza</li>
                    </ul>
                </div>

                <!-- Mensajes -->
                <?php if ($error): ?>
                <div style="padding: var(--spacing-lg); background: var(--accent-red); color: var(--text-white);
                           border-radius: var(--radius-lg); margin-bottom: var(--spacing-xl);">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <!-- Formulario -->
                <form method="POST" class="card" style="padding: var(--spacing-2xl);">

                    <!-- Nombre -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                            <i class="fas fa-signature"></i> Nombre del Grupo *
                        </label>
                        <input type="text" name="nombre" id="nombre" required maxlength="255"
                               placeholder="Ej: Grupo de Estudio - Cálculo Diferencial"
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base);"
                               oninput="actualizarPreview()">
                    </div>

                    <!-- Descripción -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                            <i class="fas fa-align-left"></i> Descripción *
                        </label>
                        <textarea name="descripcion" id="descripcion" required rows="5"
                                  placeholder="Describe el propósito del grupo, temas a tratar, horarios sugeridos, etc."
                                  style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                         border-radius: var(--radius-md); font-size: var(--text-base); resize: vertical;"
                                  oninput="actualizarPreview()"></textarea>
                    </div>

                    <!-- Curso -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                            <i class="fas fa-book"></i> Curso Asociado (opcional)
                        </label>
                        <select name="curso" id="curso"
                                style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                       border-radius: var(--radius-md); font-size: var(--text-base);"
                                onchange="actualizarPreview()">
                            <option value="">Ninguno (Grupo General)</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso['id_curso'] ?>">
                                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-md); color: var(--text-primary);">
                            <i class="fas fa-shield-alt"></i> Tipo de Grupo *
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-md);">
                            <label style="padding: var(--spacing-lg); border: 2px solid var(--primary-200);
                                          border-radius: var(--radius-lg); cursor: pointer; transition: var(--transition);"
                                   onmouseover="this.style.borderColor='var(--primary-600)'"
                                   onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--primary-200)'"
                                   onclick="actualizarTipo('publico')">
                                <input type="radio" name="tipo" value="publico" checked onchange="actualizarPreview()">
                                <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                    <i class="fas fa-globe" style="font-size: var(--text-3xl); color: var(--accent-green);
                                                                   margin-bottom: var(--spacing-sm);"></i>
                                    <strong>Público</strong>
                                    <small style="color: var(--text-secondary);">Cualquiera puede unirse</small>
                                </div>
                            </label>

                            <label style="padding: var(--spacing-lg); border: 2px solid var(--primary-200);
                                          border-radius: var(--radius-lg); cursor: pointer; transition: var(--transition);"
                                   onmouseover="this.style.borderColor='var(--primary-600)'"
                                   onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--primary-200)'"
                                   onclick="actualizarTipo('privado')">
                                <input type="radio" name="tipo" value="privado" onchange="actualizarPreview()">
                                <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                    <i class="fas fa-lock" style="font-size: var(--text-3xl); color: var(--accent-orange);
                                                                  margin-bottom: var(--spacing-sm);"></i>
                                    <strong>Privado</strong>
                                    <small style="color: var(--text-secondary);">Requiere código</small>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Código (solo privado) -->
                    <div id="codigoDiv" style="display: none; margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                            <i class="fas fa-key"></i> Código de Acceso *
                        </label>
                        <input type="text" name="codigo" id="codigo" maxlength="20"
                               placeholder="Ej: CALCULO2025"
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base);"
                               oninput="actualizarPreview()">
                        <small style="color: var(--text-secondary); display: block; margin-top: var(--spacing-xs);">
                            Comparte este código solo con personas que quieras en el grupo
                        </small>
                    </div>

                    <!-- Máximo Participantes -->
                    <div style="margin-bottom: var(--spacing-2xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--text-primary);">
                            <i class="fas fa-users"></i> Máximo de Participantes
                        </label>
                        <input type="number" name="max" id="max" value="50" min="2" max="200"
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base);"
                               oninput="actualizarPreview()">
                    </div>

                    <!-- Botones -->
                    <div style="display: flex; gap: var(--spacing-md);">
                        <a href="{{ url('/tutorias') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">
                            <i class="fas fa-check"></i>
                            Crear Grupo
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview -->
            <div style="position: sticky; top: 100px;">
                <div class="card" style="padding: var(--spacing-xl);">
                    <h3 style="margin-bottom: var(--spacing-lg); display: flex; align-items: center; gap: var(--spacing-sm);">
                        <i class="fas fa-eye" style="color: var(--primary-600);"></i>
                        Vista Previa
                    </h3>

                    <div id="preview" class="card" style="padding: var(--spacing-xl); position: relative;
                                                          box-shadow: var(--shadow-lg);">
                        <div style="position: absolute; top: var(--spacing-md); right: var(--spacing-md);">
                            <span id="preview-badge" style="padding: var(--spacing-xs) var(--spacing-md);
                                                            border-radius: var(--radius-full); font-size: var(--text-xs);
                                                            font-weight: 600; background: var(--accent-green);
                                                            color: var(--text-white);">
                                <i class="fas fa-globe"></i> Público
                            </span>
                        </div>

                        <h3 id="preview-nombre" style="font-size: var(--text-xl); margin-bottom: var(--spacing-md);
                                                       color: var(--text-tertiary); padding-right: 80px;">
                            Nombre del grupo...
                        </h3>

                        <p id="preview-descripcion" style="color: var(--text-tertiary); margin-bottom: var(--spacing-lg);
                                                           display: -webkit-box; -webkit-line-clamp: 3;
                                                           -webkit-box-orient: vertical; overflow: hidden;">
                            Descripción del grupo...
                        </p>

                        <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);
                                    padding-top: var(--spacing-md); border-top: 1px solid var(--primary-100);">
                            <div id="preview-curso-div" style="display: none; align-items: center; gap: var(--spacing-sm);">
                                <i class="fas fa-book" style="color: var(--primary-600);"></i>
                                <span id="preview-curso" style="font-size: var(--text-sm); color: var(--text-secondary);">-</span>
                            </div>

                            <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                                <i class="fas fa-users" style="color: var(--primary-600);"></i>
                                <span id="preview-max" style="font-size: var(--text-sm); color: var(--text-secondary);">
                                    0 / 50 participantes
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div style="margin-top: var(--spacing-xl); padding: var(--spacing-lg);
                                background: var(--secondary-50); border-radius: var(--radius-lg);">
                        <h4 style="margin-bottom: var(--spacing-md); display: flex; align-items: center; gap: var(--spacing-sm);">
                            <i class="fas fa-lightbulb" style="color: var(--secondary-600);"></i>
                            Tips
                        </h4>
                        <ul style="font-size: var(--text-sm); color: var(--text-secondary);
                                   padding-left: var(--spacing-xl); line-height: 1.8;">
                            <li>Nombres cortos y descriptivos funcionan mejor</li>
                            <li>Menciona el horario de reuniones en la descripción</li>
                            <li>Para grupos de cursos, incluye el ciclo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="/js/tutorias-crear.js"></script>
@endpush
