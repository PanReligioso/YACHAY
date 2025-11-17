<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LibroServicio;

/**
 * LibroController - Gestiona operaciones de libros
 * La lógica está en LibroServicio, aquí solo orquestación
 */
class LibroController extends Controller
{
    private LibroServicio $libroServicio;

    public function __construct()
    {
        $this->libroServicio = new LibroServicio();
    }

    /**
     * Lista todos los libros aprobados
     */
    public function index(Request $request)
    {
        try {
            // Usar LibroServicio para obtener libros desde la base de datos
            $busqueda = $request->get('buscar', '');
            $categoriaFiltro = $request->get('categoria', '');
            $pagina = max(1, (int)$request->get('pagina', 1));

            // Cargar categorias desde JSON temporalmente (hasta migración de categorias)
            $categoriasFile = storage_path('app/categorias.json');
            $categorias = file_exists($categoriasFile) ? json_decode(file_get_contents($categoriasFile), true) : [];

            if (!empty($busqueda) && strlen($busqueda) >= 2) {
                $librosFiltrados = $this->libroServicio->buscarLibros($busqueda, $pagina, 50);
                $libros = $librosFiltrados;
            } elseif (!empty($categoriaFiltro)) {
                $librosFiltrados = $this->libroServicio->obtenerLibrosPorCategoria((int)$categoriaFiltro, $pagina, 50);
                $libros = $this->libroServicio->obtenerLibrosAprobados(1, 100);
            } else {
                $libros = $this->libroServicio->obtenerLibrosAprobados($pagina, 50);
                $librosFiltrados = $libros;
            }

            // Normalizar campos para compatibilidad con las vistas que aún esperan claves antiguas
            $normalizar = function(array $l) {
                return [
                    'id' => $l['id'] ?? $l['ID'] ?? null,
                    'titulo' => $l['titulo'] ?? '',
                    'autor' => $l['autor'] ?? '',
                    'descripcion' => $l['descripcion'] ?? '',
                    'editorial' => $l['editorial'] ?? '',
                    'anio' => $l['anio_publicacion'] ?? $l['anio'] ?? null,
                    'portada' => $l['portada'] ?? ($l['thumbnail'] ?? null),
                    'url_drive' => $l['archivo_url'] ?? $l['url_drive'] ?? null,
                    'vistas' => $l['vistas'] ?? 0,
                    'descargas' => $l['descargas'] ?? 0,
                    'categoria_id' => $l['id_categoria'] ?? $l['categoria_id'] ?? null,
                    'estado' => $l['estado'] ?? 'pendiente'
                ];
            };

            $libros = array_map($normalizar, $libros);
            $librosFiltrados = array_map($normalizar, $librosFiltrados);

            return view('includes.Libros.index', [
                'libros' => $libros,
                'categorias' => $categorias,
                'busqueda' => $busqueda,
                'categoriaFiltro' => $categoriaFiltro,
                'librosFiltrados' => $librosFiltrados
            ]);
        } catch (\Exception $e) {
            \Log::error('LibroController@index: ' . $e->getMessage());
            return view('error.500');
        }
    }

    /**
     * Muestra un libro específico
     */
    public function show(int $id)
    {
        try {
            // Obtener libro desde la BD mediante el servicio
            $libro = $this->libroServicio->obtenerPorId($id);
            if (!$libro) {
                return redirect('/libros');
            }

            // Incrementar vistas en BD
            $this->libroServicio->incrementarVistas($id);

            // Cargar categorias desde JSON temporalmente
            $categoriasFile = storage_path('app/categorias.json');
            $categorias = file_exists($categoriasFile) ? json_decode(file_get_contents($categoriasFile), true) : [];

            $categoria = null;
            foreach ($categorias as $cat) {
                if (($cat['id'] ?? null) == ($libro['id_categoria'] ?? $libro['categoria_id'] ?? null)) {
                    $categoria = $cat;
                    break;
                }
            }

            // Libros relacionados desde BD
            $rel = $this->libroServicio->obtenerLibrosPorCategoria((int)($libro['id_categoria'] ?? $libro['categoria_id'] ?? 0), 1, 10);
            $librosRelacionados = array_filter($rel, function($l) use ($libro) {
                return ($l['id'] ?? null) != ($libro['id'] ?? null) && ($l['estado'] ?? '') === 'aprobado';
            });
            $librosRelacionados = array_slice($librosRelacionados, 0, 3);

            // Normalizar libro y relacionados para las vistas
            $normalizar = function(array $l) {
                return [
                    'id' => $l['id'] ?? null,
                    'titulo' => $l['titulo'] ?? '',
                    'autor' => $l['autor'] ?? '',
                    'descripcion' => $l['descripcion'] ?? '',
                    'editorial' => $l['editorial'] ?? '',
                    'anio' => $l['anio_publicacion'] ?? $l['anio'] ?? null,
                    'portada' => $l['portada'] ?? null,
                    'url_drive' => $l['archivo_url'] ?? $l['url_drive'] ?? null,
                    'vistas' => $l['vistas'] ?? 0,
                    'descargas' => $l['descargas'] ?? 0,
                    'categoria_id' => $l['id_categoria'] ?? $l['categoria_id'] ?? null,
                    'estado' => $l['estado'] ?? 'pendiente'
                ];
            };

            $libro = $normalizar($libro);
            $librosRelacionados = array_map($normalizar, $librosRelacionados);

            return view('includes.Libros.show', [
                'libro' => $libro,
                'categoria' => $categoria,
                'librosRelacionados' => $librosRelacionados
            ]);
        } catch (\Exception $e) {
            \Log::error('LibroController@show: ' . $e->getMessage());
            return view('error.500');
        }
    }

    /**
     * Formulario para crear libro
     */
    public function create()
    {
        // Mostrar formulario de subida (categorías todavía desde JSON hasta migrar)
        $categoriasFile = storage_path('app/categorias.json');
        $categorias = file_exists($categoriasFile) ? json_decode(file_get_contents($categoriasFile), true) : [];

        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect('/login');
        }

        return view('includes.Libros.subir', [
            'categorias' => $categorias,
            'usuario' => auth()->user(),
            'success' => '',
            'error' => ''
        ]);
    }

    /**
     * Almacena un nuevo libro
     */
    public function store(Request $request)
    {
        try {
            // Validaciones básicas
            $request->validate([
                'titulo' => 'required|string|min:3|max:255',
                'autor' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'url_drive' => 'required|url|max:500',
                'categoria_id' => 'required|integer'
            ]);
            // Preparar datos para LibroServicio
            $datos = [
                'titulo' => $request->input('titulo'),
                'autor' => $request->input('autor'),
                'editorial' => $request->input('editorial') ?? 'Sin editorial',
                'anio_publicacion' => (int)($request->input('anio') ?? date('Y')),
                'id_categoria' => (int)$request->input('categoria_id'),
                'descripcion' => $request->input('descripcion') ?? '',
                'archivo_url' => $request->input('url_drive'),
                'vistas' => 0,
                'descargas' => 0,
                'estado' => 'pendiente',
                'id_usuario_subida' => auth()->check() ? auth()->user()->id : (session('user_id') ?? 1),
            ];

            $resultado = $this->libroServicio->crearLibro($datos);
            if ($resultado === false) {
                return back()->with('error', 'No se pudo crear el libro')->withInput();
            }

            $nuevoId = $resultado['id'] ?? null;
            return redirect('/libros/' . $nuevoId)->with('success', '¡Libro subido exitosamente!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('LibroController@store: ' . $e->getMessage());
            return back()->with('error', 'Error al subir el libro');
        }
    }

    /**
     * Formulario para editar
     */
    public function edit(int $id)
    {
        try {
            $libro = $this->libroServicio->obtenerPorId($id);

            if (!$libro || $libro['id_usuario_subida'] != auth()->user()->id) {
                return view('error.403');
            }

            return view('libros.edit', compact('libro'));
        } catch (\Exception $e) {
            \Log::error('LibroController@edit: ' . $e->getMessage());
            return view('error.500');
        }
    }

    /**
     * Actualiza un libro
     */
    public function update(Request $request, int $id)
    {
        try {
            $libro = $this->libroServicio->obtenerPorId($id);

            if (!$libro || $libro['id_usuario_subida'] != auth()->user()->id) {
                return view('error.403');
            }

            $validated = $request->validate([
                'titulo' => 'required|string|min:3|max:255',
                'autor' => 'nullable|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'archivo_url' => 'required|string|max:500',
                'id_categoria' => 'required|integer'
            ]);

            $actualizado = $this->libroServicio->actualizarLibro($id, $validated);

            if (!$actualizado) {
                return back()->with('error', 'No se pudo actualizar');
            }

            return redirect()->route('libros.show', $id)
                ->with('success', 'Libro actualizado correctamente');
        } catch (\Exception $e) {
            \Log::error('LibroController@update: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar');
        }
    }

    /**
     * Elimina un libro
     */
    public function destroy(int $id)
    {
        try {
            $libro = $this->libroServicio->obtenerPorId($id);

            if (!$libro || $libro['id_usuario_subida'] != auth()->user()->id) {
                return view('error.403');
            }

            $eliminado = $this->libroServicio->eliminarLibro($id);

            if (!$eliminado) {
                return back()->with('error', 'No se pudo eliminar');
            }

            return redirect()->route('libros.index')
                ->with('success', 'Libro eliminado correctamente');
        } catch (\Exception $e) {
            \Log::error('LibroController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar');
        }
    }

    /**
     * Busca libros
     */
    public function search(Request $request)
    {
        try {
            $termino = $request->get('q', '');
            $pagina = max(1, (int)$request->get('pagina', 1));

            if (strlen($termino) < 2) {
                return view('libros.search', ['libros' => [], 'termino' => $termino]);
            }

            $libros = $this->libroServicio->buscarLibros($termino, $pagina, 15);

            return view('libros.search', compact('libros', 'termino'));
        } catch (\Exception $e) {
            \Log::error('LibroController@search: ' . $e->getMessage());
            return view('error.500');
        }
    }

    /**
     * Obtiene libros por categoría
     */
    public function porCategoria(int $idCategoria, Request $request)
    {
        try {
            $pagina = max(1, (int)$request->get('pagina', 1));
            $libros = $this->libroServicio->obtenerLibrosPorCategoria($idCategoria, $pagina, 15);

            return view('libros.categoria', compact('libros', 'idCategoria'));
        } catch (\Exception $e) {
            \Log::error('LibroController@porCategoria: ' . $e->getMessage());
            return view('error.500');
        }
    }
}
