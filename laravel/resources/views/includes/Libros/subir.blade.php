@php
    // Verificar si está logueado
    $isLoggedIn = session('logged_in', false);
    if (!$isLoggedIn) {
        echo "<script>window.location.href = '" . url('/login') . "';</script>";
        exit;
    }

    // Cargar categorías
    $categoriasFile = storage_path('app/categorias.json');
    $categorias = file_exists($categoriasFile) ? json_decode(file_get_contents($categoriasFile), true) : [];

    // Procesar formulario
    $success = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subir_libro'])) {
        $librosFile = storage_path('app/libros.json');
        $libros = file_exists($librosFile) ? json_decode(file_get_contents($librosFile), true) : [];

        // Validaciones básicas
        if (empty($_POST['titulo']) || empty($_POST['autor']) || empty($_POST['url_drive'])) {
            $error = 'Por favor completa todos los campos obligatorios';
        } else {
            // Generar nuevo ID
            $nuevoId = count($libros) > 0 ? max(array_column($libros, 'id')) + 1 : 1;

            // Crear nuevo libro
            $nuevoLibro = [
                'id' => $nuevoId,
                'titulo' => $_POST['titulo'],
                'autor' => $_POST['autor'],
                'editorial' => $_POST['editorial'] ?? 'Sin editorial',
                'anio' => (int)$_POST['anio'] ?? date('Y'),
                'categoria_id' => (int)$_POST['categoria_id'],
                'descripcion' => $_POST['descripcion'],
                'url_drive' => $_POST['url_drive'],
                'portada' => $_POST['portada'] ?? 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400',
                'vistas' => 0,
                'descargas' => 0,
                'estado' => 'aprobado', // En producción sería 'pendiente'
                'usuario_id' => session('user_id'),
                'fecha_subida' => date('Y-m-d')
            ];

            $libros[] = $nuevoLibro;
            file_put_contents($librosFile, json_encode($libros, JSON_PRETTY_PRINT));

            $success = '¡Libro subido exitosamente! Redirigiendo...';
            echo "<script>setTimeout(() => window.location.href = '" . url('/libros/' . $nuevoId) . "', 2000);</script>";
        }
    }
@endphp

@extends('layouts.app')
@section('title', 'Subir Libro - YACHAY')

@section('content')

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                padding: 120px 0 var(--spacing-2xl); color: white;">
    <div class="container">
        <!-- Breadcrumb -->
        <div style="margin-bottom: var(--spacing-lg); opacity: 0.9;">
            <a href="{{ url('/') }}" style="color: white;">
                <i class="fas fa-home"></i> Inicio
            </a>
            <span style="margin: 0 var(--spacing-sm);">/</span>
            <a href="{{ url('/libros') }}" style="color: white;">
                <i class="fas fa-book"></i> Libros
            </a>
            <span style="margin: 0 var(--spacing-sm);">/</span>
            <span style="font-weight: 600;">Subir Libro</span>
        </div>

        <div style="text-align: center; max-width: 700px; margin: 0 auto;">
            <h1 style="color: white; font-size: var(--text-4xl); margin-bottom: var(--spacing-md);">
                <i class="fas fa-cloud-upload-alt"></i> Subir Libro
            </h1>
            <p style="font-size: var(--text-lg); opacity: 0.9;">
                Comparte material educativo con la comunidad estudiantil
            </p>
        </div>
    </div>
</section>

<!-- Formulario -->
<section class="section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">

            <!-- Instrucciones -->
            <div class="card" style="padding: var(--spacing-xl); margin-bottom: var(--spacing-2xl);
                                     background: var(--primary-50); border-left: 4px solid var(--primary-600);">
                <h3 style="color: var(--primary-600); margin-bottom: var(--spacing-md);">
                    <i class="fas fa-info-circle"></i> Instrucciones
                </h3>
                <ul style="color: var(--text-secondary); line-height: 1.8; margin: 0; padding-left: var(--spacing-xl);">
                    <li>Sube el libro a tu Google Drive y obtén el enlace de descarga</li>
                    <li>Completa todos los campos del formulario con información precisa</li>
                    <li>Asegúrate de que el contenido no viole derechos de autor</li>
                    <li>El libro será revisado antes de ser publicado (automático en demo)</li>
                </ul>
            </div>

            <!-- Mensajes -->
            @if($success)
                <div style="padding: var(--spacing-lg); background: #10b981; color: white;
                            border-radius: var(--radius-md); margin-bottom: var(--spacing-xl); text-align: center;">
                    <i class="fas fa-check-circle"></i> {{ $success }}
                </div>
            @endif

            @if($error)
                <div style="padding: var(--spacing-lg); background: #ef4444; color: white;
                            border-radius: var(--radius-md); margin-bottom: var(--spacing-xl); text-align: center;">
                    <i class="fas fa-exclamation-circle"></i> {{ $error }}
                </div>
            @endif

            <!-- Formulario Principal -->
            <div class="card" style="padding: var(--spacing-2xl);">
                <form method="POST" action="{{ url('/libros/subir') }}">
                    @csrf

                    <!-- Título -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                      color: var(--text-primary);">
                            <i class="fas fa-book"></i> Título del Libro *
                        </label>
                        <input type="text" name="titulo" required
                               placeholder="Ej: Fundamentos de Programación con Python"
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base); transition: var(--transition);"
                               onfocus="this.style.borderColor='var(--primary-600)'"
                               onblur="this.style.borderColor='var(--primary-200)'">
                    </div>

                    <!-- Autor y Año -->
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                          color: var(--text-primary);">
                                <i class="fas fa-user"></i> Autor *
                            </label>
                            <input type="text" name="autor" required
                                   placeholder="Ej: John Zelle"
                                   style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                          border-radius: var(--radius-md); font-size: var(--text-base); transition: var(--transition);"
                                   onfocus="this.style.borderColor='var(--primary-600)'"
                                   onblur="this.style.borderColor='var(--primary-200)'">
                        </div>

                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                          color: var(--text-primary);">
                                <i class="fas fa-calendar"></i> Año
                            </label>
                            <input type="number" name="anio" min="1900" max="2025" value="2024"
                                   style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                          border-radius: var(--radius-md); font-size: var(--text-base); transition: var(--transition);"
                                   onfocus="this.style.borderColor='var(--primary-600)'"
                                   onblur="this.style.borderColor='var(--primary-200)'">
                        </div>
                    </div>

                    <!-- Editorial y Categoría -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                          color: var(--text-primary);">
                                <i class="fas fa-building"></i> Editorial
                            </label>
                            <input type="text" name="editorial"
                                   placeholder="Ej: Pearson Education"
                                   style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                          border-radius: var(--radius-md); font-size: var(--text-base); transition: var(--transition);"
                                   onfocus="this.style.borderColor='var(--primary-600)'"
                                   onblur="this.style.borderColor='var(--primary-200)'">
                        </div>

                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                          color: var(--text-primary);">
                                <i class="fas fa-tag"></i> Categoría *
                            </label>
                            <select name="categoria_id" required
                                    style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                           border-radius: var(--radius-md); font-size: var(--text-base); transition: var(--transition);"
                                    onfocus="this.style.borderColor='var(--primary-600)'"
                                    onblur="this.style.borderColor='var(--primary-200)'">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat['id'] }}">{{ $cat['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                      color: var(--text-primary);">
                            <i class="fas fa-align-left"></i> Descripción *
                        </label>
                        <textarea name="descripcion" required rows="5"
                                  placeholder="Describe brevemente el contenido del libro, temas que aborda, nivel (básico/intermedio/avanzado), etc."
                                  style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                         border-radius: var(--radius-md); font-size: var(--text-base); resize: vertical;
                                         transition: var(--transition); font-family: var(--font-primary);"
                                  onfocus="this.style.borderColor='var(--primary-600)'"
                                  onblur="this.style.borderColor='var(--primary-200)'"></textarea>
                    </div>

                    <!-- URL Drive -->
                    <div style="margin-bottom: var(--spacing-xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                      color: var(--text-primary);">
                            <i class="fab fa-google-drive"></i> URL de Google Drive *
                        </label>
                        <input type="url" name="url_drive" required
                               placeholder="https://drive.google.com/file/d/..."
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base); transition: var(--transition);"
                               onfocus="this.style.borderColor='var(--primary-600)'"
                               onblur="this.style.borderColor='var(--primary-200)'">
                        <small style="color: var(--text-tertiary); display: block; margin-top: var(--spacing-xs);">
                            <i class="fas fa-info-circle"></i> Asegúrate de que el enlace sea público o accesible para todos
                        </small>
                    </div>

                    <!-- URL Portada (Opcional) -->
                    <div style="margin-bottom: var(--spacing-2xl);">
                        <label style="display: block; font-weight: 600; margin-bottom: var(--spacing-sm);
                                      color: var(--text-primary);">
                            <i class="fas fa-image"></i> URL de Portada (Opcional)
                        </label>
                        <input type="url" name="portada"
                               placeholder="https://ejemplo.com/portada.jpg"
                               style="width: 100%; padding: var(--spacing-md); border: 2px solid var(--primary-200);
                                      border-radius: var(--radius-md); font-size: var(--text-base); transition: var(--transition);"
                               onfocus="this.style.borderColor='var(--primary-600)'"
                               onblur="this.style.borderColor='var(--primary-200)'">
                        <small style="color: var(--text-tertiary); display: block; margin-top: var(--spacing-xs);">
                            <i class="fas fa-info-circle"></i> Si no proporcionas una, se usará una imagen por defecto
                        </small>
                    </div>

                    <!-- Términos y condiciones -->
                    <div style="padding: var(--spacing-lg); background: var(--bg-secondary);
                                border-radius: var(--radius-md); margin-bottom: var(--spacing-xl);">
                        <label style="display: flex; align-items: start; gap: var(--spacing-md); cursor: pointer;">
                            <input type="checkbox" required
                                   style="width: 20px; height: 20px; margin-top: 2px; cursor: pointer;">
                            <span style="color: var(--text-secondary); font-size: var(--text-sm); line-height: 1.6;">
                                Acepto que el material que estoy subiendo no viola derechos de autor y que tengo
                                permiso para compartirlo. Entiendo que el contenido será revisado antes de ser publicado.
                            </span>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                        <a href="{{ url('/libros') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" name="subir_libro" class="btn btn-primary">
                            <i class="fas fa-cloud-upload-alt"></i> Subir Libro
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- Guía Rápida -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <h2 style="text-align: center; margin-bottom: var(--spacing-2xl); color: var(--primary-600);">
                <i class="fas fa-question-circle"></i> ¿Cómo subir un libro a Google Drive?
            </h2>

            <div class="grid grid-3" style="gap: var(--spacing-xl);">
                <div class="card" style="padding: var(--spacing-xl); text-align: center;">
                    <div style="width: 70px; height: 70px; margin: 0 auto var(--spacing-md);
                                background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                border-radius: var(--radius-full); display: flex; align-items: center;
                                justify-content: center; color: white; font-size: var(--text-3xl); font-weight: 800;">
                        1
                    </div>
                    <h4 style="margin-bottom: var(--spacing-sm);">Sube el archivo</h4>
                    <p style="color: var(--text-secondary); font-size: var(--text-sm);">
                        Ve a drive.google.com y sube tu archivo PDF
                    </p>
                </div>

                <div class="card" style="padding: var(--spacing-xl); text-align: center;">
                    <div style="width: 70px; height: 70px; margin: 0 auto var(--spacing-md);
                                background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                border-radius: var(--radius-full); display: flex; align-items: center;
                                justify-content: center; color: white; font-size: var(--text-3xl); font-weight: 800;">
                        2
                    </div>
                    <h4 style="margin-bottom: var(--spacing-sm);">Comparte el archivo</h4>
                    <p style="color: var(--text-secondary); font-size: var(--text-sm);">
                        Click derecho > Compartir > "Cualquier persona con el enlace"
                    </p>
                </div>

                <div class="card" style="padding: var(--spacing-xl); text-align: center;">
                    <div style="width: 70px; height: 70px; margin: 0 auto var(--spacing-md);
                                background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                                border-radius: var(--radius-full); display: flex; align-items: center;
                                justify-content: center; color: white; font-size: var(--text-3xl); font-weight: 800;">
                        3
                    </div>
                    <h4 style="margin-bottom: var(--spacing-sm);">Copia el enlace</h4>
                    <p style="color: var(--text-secondary); font-size: var(--text-sm);">
                        Copia el enlace y pégalo en el formulario
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 2fr 1fr"],
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

@endsection
