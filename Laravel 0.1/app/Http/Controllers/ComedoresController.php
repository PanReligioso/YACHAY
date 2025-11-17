<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * ComedoresController - Gestiona operaciones de comedores (lectura desde JSON temporal)
 * TODO: Migrar a tabla `comedores` y `resenas_comedores` en BD
 */
class ComedoresController extends Controller
{
    public function __construct()
    {
        // Proteger las rutas de creación y almacenamiento
        $this->middleware('auth')->only(['create', 'store']);
    }
    /**
     * Lista todos los comedores
     */
    public function index(Request $request)
    {
        try {
            $comedoresFile = storage_path('app/comedores.json');
            $reseniasFile = storage_path('app/resenas_comedores.json');

            $comedoresData = file_exists($comedoresFile) ? json_decode(file_get_contents($comedoresFile), true) : [];
            $reseniasData = file_exists($reseniasFile) ? json_decode(file_get_contents($reseniasFile), true) : [];

            return view('includes.Comedores.index', [
                'comedores' => $comedoresData,
                'resenas' => $reseniasData
            ]);
        } catch (\Exception $e) {
            \Log::error('ComedoresController@index: ' . $e->getMessage());
            abort(500, 'Error al cargar comedores');
        }
    }

    /**
     * Muestra un comedor específico
     */
    public function show($id)
    {
        try {
            $comedoresFile = storage_path('app/comedores.json');
            $reseniasFile = storage_path('app/resenas_comedores.json');
            $usuariosFile = storage_path('app/usuarios.json');

            $comedoresData = file_exists($comedoresFile) ? json_decode(file_get_contents($comedoresFile), true) : [];
            $reseniasData = file_exists($reseniasFile) ? json_decode(file_get_contents($reseniasFile), true) : [];
            $usuariosData = file_exists($usuariosFile) ? json_decode(file_get_contents($usuariosFile), true) : [];

            $comedor = null;
            foreach ($comedoresData as $c) {
                if (($c['id_comedor'] ?? null) == $id) {
                    $comedor = $c;
                    break;
                }
            }

            if (!$comedor) {
                return redirect('/comedores');
            }

            // Filtrar reseñas por comedor
            $reseniasComedor = array_filter($reseniasData, function ($r) use ($id) {
                return ($r['id_comedor'] ?? null) == $id;
            });

            return view('includes.Comedores.show', [
                'comedor' => $comedor,
                'resenas' => $reseniasComedor,
                'usuarios' => $usuariosData
            ]);
        } catch (\Exception $e) {
            \Log::error('ComedoresController@show: ' . $e->getMessage());
            abort(500, 'Error al cargar el comedor');
        }
    }

    /**
     * Formulario para crear comedor
     */
    public function create()
    {
        try {
            // El middleware 'auth' ya protege esta ruta
            // Si llegamos aquí, el usuario está autenticado
            return view('includes.Comedores.crear', [
                'usuario' => auth()->user()
            ]);
        } catch (\Exception $e) {
            \Log::error('ComedoresController@create: ' . $e->getMessage());
            abort(500, 'Error al cargar el formulario');
        }
    }

    /**
     * Almacena un nuevo comedor
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_comedor' => 'required|string|max:255',
                'ubicacion' => 'required|string|max:500',
                'horario_atencion' => 'nullable|string|max:255',
                'descripcion' => 'nullable|string|max:1000'
            ]);

            $comedoresFile = storage_path('app/comedores.json');
            $comedoresData = file_exists($comedoresFile) ? json_decode(file_get_contents($comedoresFile), true) : [];

            $nuevoId = count($comedoresData) > 0 ? (max(array_column($comedoresData, 'id_comedor')) + 1) : 1;

            $nuevoComedor = [
                'id_comedor' => $nuevoId,
                'nombre_comedor' => $request->input('nombre_comedor'),
                'ubicacion' => $request->input('ubicacion'),
                'horario_atencion' => $request->input('horario_atencion') ?? '',
                'descripcion' => $request->input('descripcion') ?? '',
                'fecha_creacion' => date('Y-m-d'),
                'id_usuario_creador' => auth()->check() ? auth()->user()->id : null,
                'rating_promedio' => 0
            ];

            $comedoresData[] = $nuevoComedor;
            file_put_contents($comedoresFile, json_encode($comedoresData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return redirect('/comedores/' . $nuevoId)->with('success', '¡Comedor creado exitosamente!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('ComedoresController@store: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el comedor');
        }
    }
}
