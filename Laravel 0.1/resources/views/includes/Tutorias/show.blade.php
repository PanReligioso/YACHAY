@extends('layouts.app')

@section('title', 'YACHAY - Detalle del Grupo')

@section('content')

<?php
$id = $id ?? 0;

// Cargar datos JSON
$grupos_json = file_get_contents(storage_path('app/grupos_tutoria.json'));
$grupos = json_decode($grupos_json, true);

$miembros_json = file_get_contents(storage_path('app/miembros_grupo.json'));
$miembros = json_decode($miembros_json, true);

$cursos_json = file_get_contents(storage_path('app/cursos.json'));
$cursos = json_decode($cursos_json, true);

$usuarios_json = file_get_contents(storage_path('app/usuarios.json'));
$usuarios = json_decode($usuarios_json, true);

// Buscar grupo
$grupo = array_values(array_filter($grupos, fn($g) => $g['id_grupo'] == $id))[0] ?? null;

if (!$grupo) {
    header('Location: /tutorias');
    exit;
}

// Obtener miembros del grupo
$miembros_grupo = array_filter($miembros, fn($m) => $m['id_grupo'] == $id);
$num_miembros = count($miembros_grupo);
$esta_lleno = $num_miembros >= $grupo['max_participantes'];

// Verificar si usuario actual es miembro
$user_id = session('user_id');
$es_miembro = false;
$rol_usuario = '';
if ($user_id) {
    foreach ($miembros_grupo as $miembro) {
        if ($miembro['id_usuario'] == $user_id) {
            $es_miembro = true;
            $rol_usuario = $miembro['rol_grupo'];
            break;
        }
    }
}

// Obtener curso
$curso = null;
if ($grupo['id_curso']) {
    $curso = array_values(array_filter($cursos, fn($c) => $c['id_curso'] == $grupo['id_curso']))[0] ?? null;
}

// Obtener creador
$creador = array_values(array_filter($usuarios, fn($u) => $u['id_usuario'] == $grupo['id_creador']))[0] ?? null;

// Grupos relacionados (mismo curso)
$grupos_relacionados = [];
if ($grupo['id_curso']) {
    $grupos_relacionados = array_filter($grupos, function($g) use ($grupo, $id) {
        return $g['id_curso'] == $grupo['id_curso'] && $g['id_grupo'] != $id && $g['esta_activo'];
    });
    $grupos_relacionados = array_slice($grupos_relacionados, 0, 3);
}
?>

<!-- Hero con Breadcrumb -->
<section class="hero" style="min-height: 40vh; padding-top: 120px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: var(--spacing-xl);">
            <ol style="display: flex; list-style: none; gap: var(--spacing-sm); color: var(--text-secondary);">
                <li><a href="{{ url('/') }}" style="color: var(--primary-600);">Inicio</a></li>
                <li><i class="fas fa-chevron-right" style="font-size: var(--text-xs);"></i></li>
                <li><a href="{{ url('/tutorias') }}" style="color: var(--primary-600);">Tutorías</a></li>
                <li><i class="fas fa-chevron-right" style="font-size: var(--text-xs);"></i></li>
                <li><?= htmlspecialchars($grupo['nombre_grupo']) ?></li>
            </ol>
        </nav>

        <div style="display: flex; align-items: center; gap: var(--spacing-md); margin-bottom: var(--spacing-lg);">
            <h1 style="font-size: var(--text-4xl); margin: 0;">
                <?= htmlspecialchars($grupo['nombre_grupo']) ?>
            </h1>
            <span style="padding: var(--spacing-sm) var(--spacing-lg); border-radius: var(--radius-full);
                        font-size: var(--text-sm); font-weight: 600;
                        background: <?= $grupo['tipo'] === 'publico' ? 'var(--accent-green)' : 'var(--accent-orange)' ?>;
                        color: var(--text-white);">
                <i class="fas fa-<?= $grupo['tipo'] === 'publico' ? 'globe' : 'lock' ?>"></i>
                <?= ucfirst($grupo['tipo']) ?>
            </span>
        </div>

        <p style="font-size: var(--text-lg); color: var(--text-secondary);">
            <?php if ($curso): ?>
                <i class="fas fa-book"></i> <?= htmlspecialchars($curso['nombre_curso']) ?> •
            <?php endif; ?>
            <i class="fas fa-user"></i> Creado por <?= htmlspecialchars($creador['nombre_completo'] ?? 'Usuario') ?>
        </p>
    </div>
</section>

<!-- Contenido Principal -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: var(--spacing-2xl);">

            <!-- Columna Izquierda: Descripción y Miembros -->
            <div>
                <!-- Descripción -->
                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-xl);">
                    <h2 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-lg);
                               display: flex; align-items: center; gap: var(--spacing-sm);">
                        <i class="fas fa-info-circle" style="color: var(--primary-600);"></i>
                        Descripción
                    </h2>
                    <p style="color: var(--text-secondary); line-height: 1.8; font-size: var(--text-base);">
                        <?= nl2br(htmlspecialchars($grupo['descripcion'])) ?>
                    </p>
                </div>

                <!-- Miembros -->
                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-xl);">
                    <h2 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-lg);
                               display: flex; align-items: center; gap: var(--spacing-sm);">
                        <i class="fas fa-users" style="color: var(--primary-600);"></i>
                        Miembros (<?= $num_miembros ?>)
                    </h2>

                    <div style="display: grid; gap: var(--spacing-md);">
                        <?php foreach ($miembros_grupo as $miembro): ?>
                            <?php
                            $usuario = array_values(array_filter($usuarios, fn($u) => $u['id_usuario'] == $miembro['id_usuario']))[0] ?? null;
                            if (!$usuario) continue;

                            $rol_color = match($miembro['rol_grupo']) {
                                'admin' => 'var(--accent-red)',
                                'moderador' => 'var(--accent-orange)',
                                default => 'var(--primary-600)'
                            };
                            ?>
                            <div style="display: flex; align-items: center; gap: var(--spacing-md);
                                        padding: var(--spacing-md); background: var(--bg-secondary);
                                        border-radius: var(--radius-lg);">
                                <div style="width: 50px; height: 50px; border-radius: var(--radius-full);
                                           background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                           display: flex; align-items: center; justify-content: center;
                                           color: var(--text-white); font-weight: 700; font-size: var(--text-lg);">
                                    <?= strtoupper(substr($usuario['nombre_completo'], 0, 1)) ?>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: var(--text-primary);">
                                        <?= htmlspecialchars($usuario['nombre_completo']) ?>
                                    </div>
                                    <div style="font-size: var(--text-sm); color: var(--text-secondary);">
                                        <?= htmlspecialchars($usuario['email']) ?>
                                    </div>
                                </div>
                                <span style="padding: var(--spacing-xs) var(--spacing-md); border-radius: var(--radius-full);
                                            background: <?= $rol_color ?>; color: var(--text-white);
                                            font-size: var(--text-xs); font-weight: 600;">
                                    <?= ucfirst($miembro['rol_grupo']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Reglas del Grupo -->
                <div class="card" style="padding: var(--spacing-2xl);">
                    <h2 style="font-size: var(--text-2xl); margin-bottom: var(--spacing-lg);
                               display: flex; align-items: center; gap: var(--spacing-sm);">
                        <i class="fas fa-clipboard-list" style="color: var(--primary-600);"></i>
                        Reglas del Grupo
                    </h2>
                    <ul style="color: var(--text-secondary); line-height: 2; padding-left: var(--spacing-xl);">
                        <li>Respeto entre todos los miembros</li>
                        <li>Participación activa en las sesiones</li>
                        <li>Compartir material de estudio útil</li>
                        <li>Evitar spam o contenido fuera de tema</li>
                        <li>Asistir puntualmente a las reuniones acordadas</li>
                    </ul>
                </div>
            </div>

            <!-- Columna Derecha: Información y Acciones (Sticky) -->
            <div style="position: sticky; top: 100px; height: fit-content;">

                <!-- Card de Información -->
                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-lg);">

                    <!-- Estado -->
                    <div style="text-align: center; margin-bottom: var(--spacing-xl);">
                        <?php if ($esta_lleno): ?>
                            <span style="padding: var(--spacing-md) var(--spacing-xl); border-radius: var(--radius-full);
                                        background: var(--accent-red); color: var(--text-white);
                                        font-weight: 700; font-size: var(--text-base);">
                                <i class="fas fa-ban"></i> Grupo Lleno
                            </span>
                        <?php else: ?>
                            <span style="padding: var(--spacing-md) var(--spacing-xl); border-radius: var(--radius-full);
                                        background: var(--accent-green); color: var(--text-white);
                                        font-weight: 700; font-size: var(--text-base);">
                                <i class="fas fa-check-circle"></i> Plazas Disponibles
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Información -->
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-lg);
                                margin-bottom: var(--spacing-xl); padding-bottom: var(--spacing-xl);
                                border-bottom: 1px solid var(--primary-100);">

                        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                            <i class="fas fa-users" style="color: var(--primary-600); font-size: var(--text-xl);"></i>
                            <div>
                                <div style="font-size: var(--text-sm); color: var(--text-tertiary);">Participantes</div>
                                <div style="font-weight: 700; color: var(--text-primary); font-size: var(--text-lg);">
                                    <?= $num_miembros ?> / <?= $grupo['max_participantes'] ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($curso): ?>
                        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                            <i class="fas fa-book" style="color: var(--primary-600); font-size: var(--text-xl);"></i>
                            <div>
                                <div style="font-size: var(--text-sm); color: var(--text-tertiary);">Curso</div>
                                <div style="font-weight: 600; color: var(--text-primary);">
                                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                            <i class="fas fa-calendar" style="color: var(--primary-600); font-size: var(--text-xl);"></i>
                            <div>
                                <div style="font-size: var(--text-sm); color: var(--text-tertiary);">Creado</div>
                                <div style="font-weight: 600; color: var(--text-primary);">
                                    <?= date('d/m/Y', strtotime($grupo['fecha_creacion'])) ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($grupo['tipo'] === 'privado' && $es_miembro): ?>
                        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                            <i class="fas fa-key" style="color: var(--primary-600); font-size: var(--text-xl);"></i>
                            <div>
                                <div style="font-size: var(--text-sm); color: var(--text-tertiary);">Código</div>
                                <div style="font-weight: 700; color: var(--primary-600); font-size: var(--text-lg);">
                                    <?= htmlspecialchars($grupo['codigo_acceso']) ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Botón de Acción -->
                    <?php if (session('logged_in')): ?>
                        <?php if ($es_miembro): ?>
                            <button onclick="salirGrupo(<?= $id ?>)" class="btn btn-outline"
                                    style="width: 100%; justify-content: center; border-color: var(--accent-red);
                                           color: var(--accent-red);">
                                <i class="fas fa-sign-out-alt"></i>
                                Salir del Grupo
                            </button>
                        <?php elseif ($esta_lleno): ?>
                            <button disabled class="btn"
                                    style="width: 100%; justify-content: center; background: var(--text-tertiary);
                                           cursor: not-allowed;">
                                <i class="fas fa-ban"></i>
                                Grupo Lleno
                            </button>
                        <?php else: ?>
                            <button onclick="<?= $grupo['tipo'] === 'privado' ? 'mostrarModalCodigo()' : 'unirseGrupo(' . $id . ')' ?>"
                                    class="btn btn-primary" style="width: 100%; justify-content: center;">
                                <i class="fas fa-user-plus"></i>
                                Unirse al Grupo
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="{{ url('/login') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-sign-in-alt"></i>
                            Inicia Sesión para Unirte
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Grupos Relacionados -->
                <?php if (count($grupos_relacionados) > 0): ?>
                <div class="card" style="padding: var(--spacing-xl);">
                    <h3 style="font-size: var(--text-lg); margin-bottom: var(--spacing-lg);
                               display: flex; align-items: center; gap: var(--spacing-sm);">
                        <i class="fas fa-link" style="color: var(--primary-600);"></i>
                        Grupos Similares
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
                        <?php foreach ($grupos_relacionados as $g_rel): ?>
                            <a href="{{ url('/tutorias') }}/<?= $g_rel['id_grupo'] ?>"
                               style="padding: var(--spacing-md); background: var(--bg-secondary);
                                      border-radius: var(--radius-lg); transition: var(--transition);"
                               onmouseover="this.style.background='var(--primary-50)'"
                               onmouseout="this.style.background='var(--bg-secondary)'">
                                <div style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--spacing-xs);">
                                    <?= htmlspecialchars($g_rel['nombre_grupo']) ?>
                                </div>
                                <div style="font-size: var(--text-sm); color: var(--text-secondary);">
                                    <i class="fas fa-users"></i>
                                    <?php
                                    $rel_miembros = count(array_filter($miembros, fn($m) => $m['id_grupo'] == $g_rel['id_grupo']));
                                    echo $rel_miembros . ' / ' . $g_rel['max_participantes'];
                                    ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Modal Código (solo si es privado) -->
<?php if ($grupo['tipo'] === 'privado' && !$es_miembro): ?>
<div id="modalCodigo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                               background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="card" style="padding: var(--spacing-2xl); max-width: 400px; margin: var(--spacing-xl);">
        <h3 style="margin-bottom: var(--spacing-lg);">
            <i class="fas fa-lock"></i> Grupo Privado
        </h3>
        <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
            Este grupo requiere un código de acceso. Pídelo al creador del grupo.
        </p>
        <input type="text" id="codigoInput" placeholder="Ingresa el código..."
               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                      border-radius: var(--radius-md); margin-bottom: var(--spacing-lg);">
        <div style="display: flex; gap: var(--spacing-md);">
            <button onclick="cerrarModal()" class="btn btn-outline" style="flex: 1;">Cancelar</button>
            <button onclick="verificarCodigo(<?= $id ?>, '<?= $grupo['codigo_acceso'] ?>')"
                    class="btn btn-primary" style="flex: 1;">Verificar</button>
        </div>
    </div>
</div>
<?php endif; ?>

@endsection

@push('scripts')
<script src="/js/tutorias-show.js"></script>
@endpush
