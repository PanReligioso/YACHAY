<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * TutoriasController - Gestiona operaciones de tutorías (lectura desde JSON temporal)
 * TODO: Migrar a tabla `grupos_tutoria` y `miembros_grupo` en BD
 */
class TutoriasController extends Controller
{
    public function __construct()
    {
        // Proteger las rutas de creación y almacenamiento
        $this->middleware('auth')->only(['create', 'store']);
    }
    /**
     * Lista todos los grupos de tutoría
     */
    public function index(Request $request)
    {
        try {
            $gruposFile = storage_path('app/grupos_tutoria.json');
            $miembrosFile = storage_path('app/miembros_grupo.json');
            $cursosFile = storage_path('app/cursos.json');

            $gruposData = file_exists($gruposFile) ? json_decode(file_get_contents($gruposFile), true) : [];
            $miembrosData = file_exists($miembrosFile) ? json_decode(file_get_contents($miembrosFile), true) : [];
            $cursosData = file_exists($cursosFile) ? json_decode(file_get_contents($cursosFile), true) : [];

            return view('includes.Tutorias.index', [
                'grupos' => $gruposData,
                'miembros' => $miembrosData,
                'cursos' => $cursosData
            ]);
        } catch (\Exception $e) {
            \Log::error('TutoriasController@index: ' . $e->getMessage());
            abort(500, 'Error al cargar tutorías');
        }
    }

    /**
     * Muestra un grupo de tutoría específico
     */
    public function show($id)
    {
        try {
            $gruposFile = storage_path('app/grupos_tutoria.json');
            $miembrosFile = storage_path('app/miembros_grupo.json');
            $cursosFile = storage_path('app/cursos.json');
            $usuariosFile = storage_path('app/usuarios.json');

            $gruposData = file_exists($gruposFile) ? json_decode(file_get_contents($gruposFile), true) : [];
            $miembrosData = file_exists($miembrosFile) ? json_decode(file_get_contents($miembrosFile), true) : [];
            $cursosData = file_exists($cursosFile) ? json_decode(file_get_contents($cursosFile), true) : [];
            $usuariosData = file_exists($usuariosFile) ? json_decode(file_get_contents($usuariosFile), true) : [];

            $grupo = null;
            foreach ($gruposData as $g) {
                if (($g['id_grupo'] ?? null) == $id) {
                    $grupo = $g;
                    break;
                }
            }

            if (!$grupo) {
                return redirect('/tutorias');
            }

            return view('includes.Tutorias.show', [
                'grupo' => $grupo,
                'miembros' => $miembrosData,
                'cursos' => $cursosData,
                'usuarios' => $usuariosData
            ]);
        } catch (\Exception $e) {
            \Log::error('TutoriasController@show: ' . $e->getMessage());
            abort(500, 'Error al cargar la tutoría');
        }
    }

    /**
     * Formulario para crear grupo
     */
    public function create()
    {
        try {
            // El middleware 'auth' ya protege esta ruta
            // Si llegamos aquí, el usuario está autenticado
            $cursosFile = storage_path('app/cursos.json');
            $cursosData = file_exists($cursosFile) ? json_decode(file_get_contents($cursosFile), true) : [];

            return view('includes.Tutorias.crear', [
                'cursos' => $cursosData,
                'usuario' => auth()->user()
            ]);
        } catch (\Exception $e) {
            \Log::error('TutoriasController@create: ' . $e->getMessage());
            abort(500, 'Error al cargar el formulario');
        }
    }

    /**
     * Almacena un nuevo grupo de tutoría
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_grupo' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'id_curso' => 'required|integer',
                'horario' => 'nullable|string|max:255'
            ]);

            $gruposFile = storage_path('app/grupos_tutoria.json');
            $gruposData = file_exists($gruposFile) ? json_decode(file_get_contents($gruposFile), true) : [];

            $nuevoId = count($gruposData) > 0 ? (max(array_column($gruposData, 'id_grupo')) + 1) : 1;

            $nuevoGrupo = [
                'id_grupo' => $nuevoId,
                'nombre_grupo' => $request->input('nombre_grupo'),
                'descripcion' => $request->input('descripcion') ?? '',
                'id_curso' => (int)$request->input('id_curso'),
                'horario' => $request->input('horario') ?? '',
                'fecha_creacion' => date('Y-m-d'),
                'id_tutor' => auth()->check() ? auth()->user()->id : null
            ];

            $gruposData[] = $nuevoGrupo;
            file_put_contents($gruposFile, json_encode($gruposData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return redirect('/tutorias/' . $nuevoId)->with('success', '¡Grupo de tutoría creado exitosamente!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('TutoriasController@store: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el grupo');
        }
    }
}
