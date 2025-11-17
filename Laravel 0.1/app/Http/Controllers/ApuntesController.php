<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * ApuntesController - Gestiona operaciones de apuntes (lectura desde JSON temporal)
 * TODO: Migrar a tabla `apuntes` en BD y usar Eloquent modelo
 */
class ApuntesController extends Controller
{
    public function __construct()
    {
        // Proteger las rutas de creación y almacenamiento
        $this->middleware('auth')->only(['create', 'store']);
    }
    /**
     * Lista todos los apuntes aprobados
     */
    public function index(Request $request)
    {
        try {
            // Lectura temporal desde JSON (hasta migración a BD)
            $apuntesFile = storage_path('app/apuntes.json');
            $cursosFile = storage_path('app/cursos.json');

            $apuntesData = file_exists($apuntesFile) ? json_decode(file_get_contents($apuntesFile), true) : [];
            $cursosData = file_exists($cursosFile) ? json_decode(file_get_contents($cursosFile), true) : [];

            $buscar = $request->get('buscar', '');
            $cicloFiltro = $request->get('ciclo', '');
            $tipoFiltro = $request->get('tipo', '');

            // Filtrar apuntes por búsqueda, ciclo y tipo
            $apuntesFiltrados = array_filter($apuntesData, function($apunte) use ($buscar, $cicloFiltro, $tipoFiltro, $cursosData) {
                $curso = null;
                foreach ($cursosData as $c) {
                    if (($c['id_curso'] ?? null) == ($apunte['id_curso'] ?? null)) {
                        $curso = $c;
                        break;
                    }
                }

                $matchBuscar = empty($buscar) ||
                               stripos($apunte['titulo'] ?? '', $buscar) !== false ||
                               stripos($apunte['descripcion'] ?? '', $buscar) !== false;
                $matchCiclo = empty($cicloFiltro) || ($curso && ($curso['ciclo'] ?? '') == $cicloFiltro);
                $matchTipo = empty($tipoFiltro) || ($apunte['tipo_material'] ?? '') == $tipoFiltro;

                return $matchBuscar && $matchCiclo && $matchTipo && ($apunte['estado_validacion'] ?? '') === 'aprobado';
            });

            return view('includes.Apuntes.index', [
                'apuntesData' => $apuntesData,
                'cursosData' => $cursosData,
                'apuntesFiltrados' => $apuntesFiltrados,
                'buscar' => $buscar,
                'cicloFiltro' => $cicloFiltro,
                'tipoFiltro' => $tipoFiltro
            ]);
        } catch (\Exception $e) {
            \Log::error('ApuntesController@index: ' . $e->getMessage());
            abort(500, 'Error al cargar apuntes');
        }
    }

    /**
     * Muestra un apunte específico
     */
    public function show($id)
    {
        try {
            $apuntesFile = storage_path('app/apuntes.json');
            $cursosFile = storage_path('app/cursos.json');

            $apuntesData = file_exists($apuntesFile) ? json_decode(file_get_contents($apuntesFile), true) : [];
            $cursosData = file_exists($cursosFile) ? json_decode(file_get_contents($cursosFile), true) : [];

            $apunte = null;
            foreach ($apuntesData as $a) {
                if (($a['id_apunte'] ?? null) == $id) {
                    $apunte = $a;
                    break;
                }
            }

            if (!$apunte) {
                return redirect('/apuntes');
            }

            // Incrementar vistas
            foreach ($apuntesData as &$a) {
                if (($a['id_apunte'] ?? null) == $id) {
                    $a['vistas'] = (($a['vistas'] ?? 0) + 1);
                    break;
                }
            }
            file_put_contents($apuntesFile, json_encode($apuntesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return view('includes.Apuntes.show', [
                'apunte' => $apunte,
                'apuntes' => $apuntesData,
                'cursos' => $cursosData
            ]);
        } catch (\Exception $e) {
            \Log::error('ApuntesController@show: ' . $e->getMessage());
            abort(500, 'Error al cargar el apunte');
        }
    }

    /**
     * Formulario para subir apunte
     */
    public function create()
    {
        try {
            // El middleware 'auth' ya protege esta ruta
            // Si llegamos aquí, el usuario está autenticado
            $cursosFile = storage_path('app/cursos.json');
            $cursosData = file_exists($cursosFile) ? json_decode(file_get_contents($cursosFile), true) : [];

            return view('includes.Apuntes.subir', [
                'cursos' => $cursosData,
                'usuario' => auth()->user()
            ]);
        } catch (\Exception $e) {
            \Log::error('ApuntesController@create: ' . $e->getMessage());
            abort(500, 'Error al cargar el formulario');
        }
    }

    /**
     * Almacena un nuevo apunte
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'titulo' => 'required|string|min:3|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'url_drive' => 'required|url|max:500',
                'id_curso' => 'required|integer',
                'tipo_material' => 'required|string'
            ]);

            $apuntesFile = storage_path('app/apuntes.json');
            $apuntesData = file_exists($apuntesFile) ? json_decode(file_get_contents($apuntesFile), true) : [];

            $nuevoId = count($apuntesData) > 0 ? (max(array_column($apuntesData, 'id_apunte')) + 1) : 1;

            $nuevoApunte = [
                'id_apunte' => $nuevoId,
                'titulo' => $request->input('titulo'),
                'descripcion' => $request->input('descripcion') ?? '',
                'url_drive' => $request->input('url_drive'),
                'id_curso' => (int)$request->input('id_curso'),
                'tipo_material' => $request->input('tipo_material'),
                'vistas' => 0,
                'descargas' => 0,
                'estado_validacion' => 'pendiente',
                'fecha_subida' => date('Y-m-d'),
                'id_usuario_subida' => auth()->check() ? auth()->user()->id : null
            ];

            $apuntesData[] = $nuevoApunte;
            file_put_contents($apuntesFile, json_encode($apuntesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return redirect('/apuntes/' . $nuevoId)->with('success', '¡Apunte subido exitosamente! Espera validación.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('ApuntesController@store: ' . $e->getMessage());
            return back()->with('error', 'Error al subir el apunte');
        }
    }
}
