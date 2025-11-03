@php
    // Cargar datos
    $librosFile = storage_path('app/libros.json');
    $categoriasFile = storage_path('app/categorias.json');

    // Inicializar archivos si no existen
    if (!file_exists($librosFile)) {
        $librosDemo = [
            [
                'id' => 1,
                'titulo' => 'Fundamentos de Programación con Python',
                'autor' => 'John Zelle',
                'editorial' => 'Franklin, Beedle & Associates',
                'anio' => 2016,
                'categoria_id' => 1,
                'descripcion' => 'Libro completo sobre programación con Python, ideal para principiantes',
                'url_drive' => 'https://drive.google.com/ejemplo1',
                'portada' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?w=400',
                'vistas' => 234,
                'descargas' => 156,
                'estado' => 'aprobado',
                'fecha_subida' => '2025-10-15'
            ],
            [
                'id' => 2,
                'titulo' => 'Diseño de Bases de Datos',
                'autor' => 'Carlos Coronel',
                'editorial' => 'Cengage Learning',
                'anio' => 2018,
                'categoria_id' => 2,
                'descripcion' => 'Guía completa sobre modelado y diseño de bases de datos relacionales',
                'url_drive' => 'https://drive.google.com/ejemplo2',
                'portada' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=400',
                'vistas' => 189,
                'descargas' => 98,
                'estado' => 'aprobado',
                'fecha_subida' => '2025-10-20'
            ],
            [
                'id' => 3,
                'titulo' => 'Redes de Computadoras',
                'autor' => 'Andrew Tanenbaum',
                'editorial' => 'Pearson',
                'anio' => 2021,
                'categoria_id' => 3,
                'descripcion' => 'El clásico sobre redes, protocolos TCP/IP y arquitectura de redes',
                'url_drive' => 'https://drive.google.com/ejemplo3',
                'portada' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=400',
                'vistas' => 312,
                'descargas' => 201,
                'estado' => 'aprobado',
                'fecha_subida' => '2025-10-18'
            ],
            [
                'id' => 4,
                'titulo' => 'Cálculo de Una Variable',
                'autor' => 'James Stewart',
                'editorial' => 'Cengage',
                'anio' => 2020,
                'categoria_id' => 4,
                'descripcion' => 'Libro de cálculo diferencial e integral con ejercicios resueltos',
                'url_drive' => 'https://drive.google.com/ejemplo4',
                'portada' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=400',
                'vistas' => 445,
                'descargas' => 287,
                'estado' => 'aprobado',
                'fecha_subida' => '2025-10-10'
            ],
            [
                'id' => 5,
                'titulo' => 'Ingeniería de Software',
                'autor' => 'Ian Sommerville',
                'editorial' => 'Pearson',
                'anio' => 2019,
                'categoria_id' => 5,
                'descripcion' => 'Metodologías ágiles, pruebas de software y arquitectura',
                'url_drive' => 'https://drive.google.com/ejemplo5',
                'portada' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400',
                'vistas' => 278,
                'descargas' => 165,
                'estado' => 'aprobado',
                'fecha_subida' => '2025-10-22'
            ],
            [
                'id' => 6,
                'titulo' => 'Inteligencia Artificial Moderna',
                'autor' => 'Stuart Russell',
                'editorial' => 'Pearson',
                'anio' => 2020,
                'categoria_id' => 6,
                'descripcion' => 'Fundamentos de IA, machine learning y redes neuronales',
                'url_drive' => 'https://drive.google.com/ejemplo6',
                'portada' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400',
                'vistas' => 523,
                'descargas' => 342,
                'estado' => 'aprobado',
                'fecha_subida' => '2025-10-25'
            ]
        ];
        file_put_contents($librosFile, json_encode($librosDemo, JSON_PRETTY_PRINT));
    }

    if (!file_exists($categoriasFile)) {
        $categoriasDemo = [
            ['id' => 1, 'nombre' => 'Programación', 'icono' => 'fa-code'],
            ['id' => 2, 'nombre' => 'Base de Datos', 'icono' => 'fa-database'],
            ['id' => 3, 'nombre' => 'Redes', 'icono' => 'fa-network-wired'],
            ['id' => 4, 'nombre' => 'Matemáticas', 'icono' => 'fa-calculator'],
            ['id' => 5, 'nombre' => 'Ingeniería de Software', 'icono' => 'fa-project-diagram'],
            ['id' => 6, 'nombre' => 'Inteligencia Artificial', 'icono' => 'fa-brain'],
            ['id' => 7, 'nombre' => 'Sistemas Operativos', 'icono' => 'fa-server'],
            ['id' => 8, 'nombre' => 'Seguridad Informática', 'icono' => 'fa-shield-alt'],
            ['id' => 9, 'nombre' => 'General', 'icono' => 'fa-book']
        ];
        file_put_contents($categoriasFile, json_encode($categoriasDemo, JSON_PRETTY_PRINT));
    }

    $libros = json_decode(file_get_contents($librosFile), true);
    $categorias = json_decode(file_get_contents($categoriasFile), true);

    // Filtros
    $busqueda = $_GET['buscar'] ?? '';
    $categoriaFiltro = $_GET['categoria'] ?? '';

    // Aplicar filtros
    $librosFiltrados = array_filter($libros, function($libro) use ($busqueda, $categoriaFiltro) {
        $matchBusqueda = empty($busqueda) ||
                        stripos($libro['titulo'], $busqueda) !== false ||
                        stripos($libro['autor'], $busqueda) !== false;
        $matchCategoria = empty($categoriaFiltro) || $libro['categoria_id'] == $categoriaFiltro;
        return $matchBusqueda && $matchCategoria && $libro['estado'] === 'aprobado';
    });
@endphp

@extends('layouts.app')
@section('title', 'Biblioteca Digital - YACHAY')

@section('content')

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
                padding: 100px 0 var(--spacing-3xl); color: white;">
    <div class="container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <h1 style="color: white; font-size: var(--text-5xl); margin-bottom: var(--spacing-lg);">
                <i class="fas fa-book-open"></i> Biblioteca Digital
            </h1>
            <p style="font-size: var(--text-xl); opacity: 0.9; margin-bottom: var(--spacing-2xl);">
                Explora nuestra colección de más de 500 libros digitales para tu carrera
            </p>

            <!-- Buscador -->
            <form method="GET" action="{{ url('/libros') }}"
                  style="display: flex; gap: var(--spacing-md); max-width: 600px; margin: 0 auto;">
                <input type="text" name="buscar" placeholder="Buscar por título o autor..."
                       value="{{ $busqueda }}"
                       style="flex: 1; padding: var(--spacing-lg); border: none;
                              border-radius: var(--radius-lg); font-size: var(--text-base);">
                <button type="submit" class="btn"
                        style="background: white; color: var(--primary-600); padding: var(--spacing-lg) var(--spacing-2xl);">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Filtros por Categoría -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <h3 style="text-align: center; margin-bottom: var(--spacing-xl);">
            <i class="fas fa-filter"></i> Filtrar por Categoría
        </h3>

        <div style="display: flex; gap: var(--spacing-md); flex-wrap: wrap; justify-content: center;">
            <a href="{{ url('/libros') }}"
               class="btn {{ empty($categoriaFiltro) ? 'btn-primary' : 'btn-secondary' }}"
               style="padding: var(--spacing-sm) var(--spacing-lg);">
                <i class="fas fa-th"></i> Todas
            </a>
            @foreach($categorias as $cat)
                <a href="{{ url('/libros?categoria=' . $cat['id']) }}"
                   class="btn {{ $categoriaFiltro == $cat['id'] ? 'btn-primary' : 'btn-secondary' }}"
                   style="padding: var(--spacing-sm) var(--spacing-lg);">
                    <i class="fas {{ $cat['icono'] }}"></i> {{ $cat['nombre'] }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Grid de Libros -->
<section class="section">
    <div class="container">

        <!-- Contador -->
        <div style="margin-bottom: var(--spacing-2xl); text-align: center;">
            <h2 style="color: var(--primary-600);">
                {{ count($librosFiltrados) }} libro(s) encontrado(s)
            </h2>
        </div>

        @if(count($librosFiltrados) > 0)
            <div class="grid grid-3" style="gap: var(--spacing-xl);">
                @foreach($librosFiltrados as $libro)
                    <div class="card" style="overflow: hidden; transition: var(--transition);">
                        <!-- Portada -->
                        <div style="height: 300px; overflow: hidden; background: var(--primary-100);">
                            <img src="{{ $libro['portada'] }}" alt="{{ $libro['titulo'] }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <!-- Contenido -->
                        <div style="padding: var(--spacing-xl);">
                            <!-- Categoría Badge -->
                            @php
                                $catNombre = '';
                                foreach($categorias as $cat) {
                                    if($cat['id'] == $libro['categoria_id']) {
                                        $catNombre = $cat['nombre'];
                                        break;
                                    }
                                }
                            @endphp
                            <div style="display: inline-block; padding: var(--spacing-xs) var(--spacing-md);
                                        background: var(--primary-100); color: var(--primary-600);
                                        border-radius: var(--radius-full); font-size: var(--text-sm);
                                        font-weight: 600; margin-bottom: var(--spacing-md);">
                                {{ $catNombre }}
                            </div>

                            <h3 style="font-size: var(--text-xl); margin-bottom: var(--spacing-sm);
                                       display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
                                       overflow: hidden;">
                                {{ $libro['titulo'] }}
                            </h3>

                            <p style="color: var(--text-secondary); font-size: var(--text-sm); margin-bottom: var(--spacing-xs);">
                                <i class="fas fa-user"></i> {{ $libro['autor'] }}
                            </p>

                            <p style="color: var(--text-tertiary); font-size: var(--text-sm); margin-bottom: var(--spacing-md);">
                                <i class="fas fa-building"></i> {{ $libro['editorial'] }} ({{ $libro['anio'] }})
                            </p>

                            <p style="color: var(--text-secondary); font-size: var(--text-sm);
                                      display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
                                      overflow: hidden; margin-bottom: var(--spacing-lg);">
                                {{ $libro['descripcion'] }}
                            </p>

                            <!-- Estadísticas -->
                            <div style="display: flex; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);
                                        padding-top: var(--spacing-md); border-top: 1px solid var(--primary-200);">
                                <span style="color: var(--text-tertiary); font-size: var(--text-sm);">
                                    <i class="fas fa-eye"></i> {{ $libro['vistas'] }}
                                </span>
                                <span style="color: var(--text-tertiary); font-size: var(--text-sm);">
                                    <i class="fas fa-download"></i> {{ $libro['descargas'] }}
                                </span>
                            </div>

                            <!-- Botones -->
                            <div style="display: flex; gap: var(--spacing-sm);">
                                <a href="{{ url('/libros/' . $libro['id']) }}" class="btn btn-primary"
                                   style="flex: 1; justify-content: center; font-size: var(--text-sm);">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ $libro['url_drive'] }}" target="_blank" class="btn btn-secondary"
                                   style="flex: 1; justify-content: center; font-size: var(--text-sm);">
                                    <i class="fas fa-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Sin Resultados -->
            <div style="text-align: center; padding: var(--spacing-3xl);">
                <i class="fas fa-search" style="font-size: 5rem; color: var(--text-tertiary); margin-bottom: var(--spacing-lg);"></i>
                <h3 style="color: var(--text-secondary); margin-bottom: var(--spacing-md);">
                    No se encontraron libros
                </h3>
                <p style="color: var(--text-tertiary);">
                    Intenta con otros términos de búsqueda o categoría
                </p>
                <a href="{{ url('/libros') }}" class="btn btn-primary" style="margin-top: var(--spacing-lg);">
                    <i class="fas fa-refresh"></i> Ver todos los libros
                </a>
            </div>
        @endif

    </div>
</section>

<!-- CTA Subir Libro -->
@php $isLoggedIn = session('logged_in', false); @endphp
<section class="section" style="background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));">
    <div class="container" style="text-align: center;">
        <h2 style="margin-bottom: var(--spacing-md);">¿Tienes un libro que compartir?</h2>
        <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl); font-size: var(--text-lg);">
            Ayuda a la comunidad subiendo material educativo de calidad
        </p>
        @if($isLoggedIn)
            <a href="{{ url('/libros/subir') }}" class="btn btn-primary" style="padding: var(--spacing-lg) var(--spacing-2xl);">
                <i class="fas fa-cloud-upload-alt"></i> Subir Libro
            </a>
        @else
            <a href="{{ url('/registro') }}" class="btn btn-primary" style="padding: var(--spacing-lg) var(--spacing-2xl);">
                <i class="fas fa-user-plus"></i> Registrarse para Subir
            </a>
        @endif
    </div>
</section>

@endsection
