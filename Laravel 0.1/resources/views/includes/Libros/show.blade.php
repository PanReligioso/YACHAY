{{-- El controlador provee: $libro, $categoria, $librosRelacionados --}}

@extends('layouts.app')
@section('title', $libro['titulo'] . ' - YACHAY')

@section('content')

<!-- Hero con Breadcrumb -->
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
            <span style="font-weight: 600;">{{ $libro['titulo'] }}</span>
        </div>
    </div>
</section>

<!-- Contenido Principal -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: var(--spacing-3xl); align-items: start;">

            <!-- Columna Izquierda: Portada -->
            <div style="position: sticky; top: 100px;">
                <div class="card" style="padding: var(--spacing-xl); text-align: center;">
                    <img src="{{ $libro['portada'] }}" alt="{{ $libro['titulo'] }}"
                         style="width: 100%; border-radius: var(--radius-lg); margin-bottom: var(--spacing-xl);
                                box-shadow: var(--shadow-xl);">

                    <!-- Categoría Badge -->
                    @if($categoria)
                        <div style="display: inline-block; padding: var(--spacing-sm) var(--spacing-lg);
                                    background: var(--primary-100); color: var(--primary-600);
                                    border-radius: var(--radius-full); font-weight: 600; margin-bottom: var(--spacing-lg);">
                            <i class="fas {{ $categoria['icono'] }}"></i> {{ $categoria['nombre'] }}
                        </div>
                    @endif

                    <!-- Estadísticas -->
                    <div style="display: flex; justify-content: space-around; padding: var(--spacing-lg) 0;
                                border-top: 2px solid var(--primary-100); border-bottom: 2px solid var(--primary-100);
                                margin-bottom: var(--spacing-xl);">
                        <div>
                            <div style="font-size: var(--text-2xl); font-weight: 800; color: var(--primary-600);">
                                {{ $libro['vistas'] }}
                            </div>
                            <div style="font-size: var(--text-sm); color: var(--text-tertiary);">Vistas</div>
                        </div>
                        <div>
                            <div style="font-size: var(--text-2xl); font-weight: 800; color: var(--secondary-600);">
                                {{ $libro['descargas'] }}
                            </div>
                            <div style="font-size: var(--text-sm); color: var(--text-tertiary);">Descargas</div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <a href="{{ $libro['url_drive'] }}" target="_blank" class="btn btn-primary"
                       style="width: 100%; margin-bottom: var(--spacing-md); justify-content: center;">
                        <i class="fas fa-download"></i> Descargar Libro
                    </a>

                    <button onclick="window.open('{{ $libro['url_drive'] }}', '_blank')"
                            class="btn btn-secondary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-eye"></i> Ver en Drive
                    </button>
                </div>
            </div>

            <!-- Columna Derecha: Información -->
            <div>
                <!-- Título y Autor -->
                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-xl);">
                    <h1 style="font-size: var(--text-4xl); margin-bottom: var(--spacing-lg); color: var(--text-primary);">
                        {{ $libro['titulo'] }}
                    </h1>

                    <div style="display: flex; gap: var(--spacing-2xl); flex-wrap: wrap; margin-bottom: var(--spacing-xl);">
                        <div>
                            <div style="color: var(--text-tertiary); font-size: var(--text-sm); margin-bottom: var(--spacing-xs);">
                                <i class="fas fa-user"></i> Autor
                            </div>
                            <div style="font-weight: 600; color: var(--text-primary);">
                                {{ $libro['autor'] }}
                            </div>
                        </div>

                        <div>
                            <div style="color: var(--text-tertiary); font-size: var(--text-sm); margin-bottom: var(--spacing-xs);">
                                <i class="fas fa-building"></i> Editorial
                            </div>
                            <div style="font-weight: 600; color: var(--text-primary);">
                                {{ $libro['editorial'] }}
                            </div>
                        </div>

                        <div>
                            <div style="color: var(--text-tertiary); font-size: var(--text-sm); margin-bottom: var(--spacing-xs);">
                                <i class="fas fa-calendar"></i> Año
                            </div>
                            <div style="font-weight: 600; color: var(--text-primary);">
                                {{ $libro['anio'] }}
                            </div>
                        </div>

                        <div>
                            <div style="color: var(--text-tertiary); font-size: var(--text-sm); margin-bottom: var(--spacing-xs);">
                                <i class="fas fa-clock"></i> Subido
                            </div>
                            <div style="font-weight: 600; color: var(--text-primary);">
                                {{ date('d/m/Y', strtotime($libro['fecha_subida'])) }}
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div style="border-top: 2px solid var(--primary-100); padding-top: var(--spacing-xl);">
                        <h3 style="color: var(--primary-600); margin-bottom: var(--spacing-md);">
                            <i class="fas fa-info-circle"></i> Descripción
                        </h3>
                        <p style="color: var(--text-secondary); line-height: 1.8; font-size: var(--text-lg);">
                            {{ $libro['descripcion'] }}
                        </p>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card" style="padding: var(--spacing-2xl); margin-bottom: var(--spacing-xl);
                                         background: var(--primary-50);">
                    <h3 style="color: var(--primary-600); margin-bottom: var(--spacing-lg);">
                        <i class="fas fa-lightbulb"></i> ¿Por qué leer este libro?
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: var(--spacing-md); background: white; border-radius: var(--radius-md);
                                   margin-bottom: var(--spacing-sm); display: flex; align-items: center; gap: var(--spacing-md);">
                            <i class="fas fa-check-circle" style="color: var(--accent-green); font-size: var(--text-xl);"></i>
                            <span>Material aprobado por la comunidad estudiantil</span>
                        </li>
                        <li style="padding: var(--spacing-md); background: white; border-radius: var(--radius-md);
                                   margin-bottom: var(--spacing-sm); display: flex; align-items: center; gap: var(--spacing-md);">
                            <i class="fas fa-check-circle" style="color: var(--accent-green); font-size: var(--text-xl);"></i>
                            <span>Contenido actualizado y relevante para tu carrera</span>
                        </li>
                        <li style="padding: var(--spacing-md); background: white; border-radius: var(--radius-md);
                                   display: flex; align-items: center; gap: var(--spacing-md);">
                            <i class="fas fa-check-circle" style="color: var(--accent-green); font-size: var(--text-xl);"></i>
                            <span>Descarga gratuita disponible 24/7</span>
                        </li>
                    </ul>
                </div>

                <!-- Botón Compartir -->
                <div class="card" style="padding: var(--spacing-xl); text-align: center;">
                    <h4 style="margin-bottom: var(--spacing-md);">
                        <i class="fas fa-share-alt"></i> Compartir este libro
                    </h4>
                    <div style="display: flex; gap: var(--spacing-md); justify-content: center;">
                        <button onclick="compartirWhatsApp()" class="btn"
                                style="background: #25D366; color: white;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                        <button onclick="compartirFacebook()" class="btn"
                                style="background: #1877F2; color: white;">
                            <i class="fab fa-facebook"></i> Facebook
                        </button>
                        <button onclick="copiarEnlace()" class="btn btn-secondary">
                            <i class="fas fa-link"></i> Copiar enlace
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Libros Relacionados -->
@if(count($librosRelacionados) > 0)
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: var(--spacing-2xl);">
            <i class="fas fa-book-reader"></i> Libros Relacionados
        </h2>

        <div class="grid grid-3" style="gap: var(--spacing-xl);">
            @foreach($librosRelacionados as $relacionado)
                <div class="card" style="overflow: hidden;">
                    <div style="height: 250px; overflow: hidden; background: var(--primary-100);">
                        <img src="{{ $relacionado['portada'] }}" alt="{{ $relacionado['titulo'] }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <div style="padding: var(--spacing-lg);">
                        <h3 style="font-size: var(--text-lg); margin-bottom: var(--spacing-sm);
                                   display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
                                   overflow: hidden;">
                            {{ $relacionado['titulo'] }}
                        </h3>

                        <p style="color: var(--text-secondary); font-size: var(--text-sm); margin-bottom: var(--spacing-md);">
                            <i class="fas fa-user"></i> {{ $relacionado['autor'] }}
                        </p>

                        <a href="{{ url('/libros/' . $relacionado['id']) }}" class="btn btn-primary"
                           style="width: 100%; justify-content: center; font-size: var(--text-sm);">
                            <i class="fas fa-eye"></i> Ver Detalle
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Scripts para compartir -->
<script>
function compartirWhatsApp() {
    const texto = encodeURIComponent('📚 Mira este libro: {{ $libro["titulo"] }} - {{ url("/libros/" . $libro["id"]) }}');
    window.open(`https://wa.me/?text=${texto}`, '_blank');
}

function compartirFacebook() {
    const url = encodeURIComponent('{{ url("/libros/" . $libro["id"]) }}');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function copiarEnlace() {
    const url = '{{ url("/libros/" . $libro["id"]) }}';
    navigator.clipboard.writeText(url).then(() => {
        alert('✅ Enlace copiado al portapapeles');
    });
}
</script>

<style>
@media (max-width: 1061px) {
    section > div > div[style*="grid-template-columns: 1fr 2fr"] {
        grid-template-columns: 1fr !important;
    }

    div[style*="position: sticky"] {
        position: static !important;
    }
}
</style>

@endsection
