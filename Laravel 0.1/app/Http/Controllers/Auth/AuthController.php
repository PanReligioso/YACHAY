<?php

namespace App\Http\Controllers\Auth; //declara el espacio de nombres del controlador de autenticacion

use App\Http\Controllers\Controller; //importa la clase base Controller
use App\Models\Usuario; //importa el modelo Usuario
use Illuminate\Http\Request; //importa la clase Request para manejar peticiones
use Illuminate\Support\Facades\Auth; //importa la fachada Auth para autenticacion
use Illuminate\Support\Facades\Hash; //importa la fachada Hash para encriptacion
use Laravel\Socialite\Facades\Socialite; //importa la fachada Socialite para login social

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login'); //retorna la vista de login
    }

    public function showRegister()
    {
        return view('auth.register'); //retorna la vista de registro
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([ //valida las credenciales
            'email' => 'required|email', //email es requerido y debe ser formato email
            'password' => 'required', //password es requerido
        ]);

        $usuario = Usuario::where('email', $credentials['email'])->first(); //busca el usuario por email

        if ($usuario && Hash::check($credentials['password'], $usuario->password)) {//verifica si el usuario existe y si la contraseña es correcta
            Auth::login($usuario); //inicia sesion para el usuario
            return redirect()->intended('/'); //redirecciona al destino o a la raiz
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']); //regresa con error si las credenciales son invalidas
    }

    public function register(Request $request) //metodo para manejar el registro de un nuevo usuario
    {
        $validated = $request->validate([ //valida los datos de registro
            'nombre' => 'required|string|max:100', //nombre es requerido
            'apellido' => 'required|string|max:100', //apellido es requerido
            'email' => 'required|email|unique:usuarios,email', //email es requerido y debe ser unico
            'password' => 'required|min:6|confirmed', //password es requerido minimo 6 y confirmado
            'codigo_universitario' => 'nullable|string|max:20|unique:usuarios,codigo_universitario', //codigo es opcional y unico
        ]);

        $usuario = Usuario::create([ //crea un nuevo registro de usuario
            'nombre' => $validated['nombre'], //asigna nombre
            'apellido' => $validated['apellido'], //asigna apellido
            'email' => $validated['email'], //asigna email
            'password' => Hash::make($validated['password']), //encripta y asigna password
            'codigo_universitario' => $validated['codigo_universitario'] ?? null, //asigna codigo o nulo
            'rol_id' => 2, // asigna rol de Estudiante por defecto
        ]);
        Auth::login($usuario); //inicia sesion para el nuevo usuario
        return redirect('/'); //redirecciona a la pagina principal
    }

    public function logout() //metodo para cerrar sesion
    {
        Auth::logout(); //cierra la sesion del usuario
        return redirect('/login'); //redirecciona a la pagina de login
    }

    public function redirectToGoogle() //metodo para redirigir a la autenticacion de Google
    {
        // Construir redirect_uri dinámicamente desde la URL actual de la aplicación
        $redirectUrl = url('/auth/google/callback');

        // Forzar redirect_uri en la petición para evitar mismatches con Google Console
        if (method_exists(Socialite::driver('google'), 'redirectUrl')) {
            return Socialite::driver('google')->redirectUrl($redirectUrl)->redirect();
        }

        // Fallback: actualizar config en tiempo de ejecución y usar la configuración
        config(['services.google.redirect' => $redirectUrl]);
        return Socialite::driver('google')->redirect();
    }
public function handleGoogleCallback() //metodo para manejar la respuesta de Google
{
        try {
            $googleUser = Socialite::driver('google')->user(); // obtiene datos del usuario de Google

            // Usar métodos oficiales de Socialite para evitar accesos directos a propiedades
            $googleEmail = method_exists($googleUser, 'getEmail') ? $googleUser->getEmail() : ($googleUser->email ?? null);
            $googleId = method_exists($googleUser, 'getId') ? $googleUser->getId() : ($googleUser->id ?? null);
            $googleAvatar = method_exists($googleUser, 'getAvatar') ? $googleUser->getAvatar() : ($googleUser->avatar ?? null);
            $googleName = method_exists($googleUser, 'getName') ? $googleUser->getName() : ($googleUser->name ?? null);

            $usuario = Usuario::where('email', $googleEmail)->first(); // busca el usuario en DB por email de Google

            if ($usuario) { // si el usuario existe
                $update = [];
                if ($googleId) {
                    $update['google_id'] = $googleId;
                }
                if ($googleAvatar) {
                    $update['avatar'] = $googleAvatar;
                }
                if (!empty($update)) {
                    $usuario->update($update); // actualiza los datos
                }
            } else { // si el usuario no existe se crea
                // Dividir el nombre completo de Google en partes
                $nameParts = [];
                if (!empty($googleName)) {
                    $nameParts = preg_split('/\s+/', trim($googleName));
                }

                // Obtener rol por nombre si existe, fallback a 2
                $defaultRoleId = 2;
                try {
                    $roleIdFromDb = \Illuminate\Support\Facades\DB::table('roles')->where('nombre_rol', 'estudiante')->value('id_rol');
                    if ($roleIdFromDb) {
                        $defaultRoleId = $roleIdFromDb;
                    }
                } catch (\Exception $e) {
                    // Si falla la consulta, mantenemos el valor por defecto
                }

                $usuario = Usuario::create([ // crea un nuevo usuario con datos de Google
                    'nombre' => $nameParts[0] ?? 'Usuario', // usa el primer nombre o 'Usuario'
                    'apellido' => $nameParts[1] ?? '', // usa el apellido o vacío
                    'email' => $googleEmail,
                    'google_id' => $googleId,
                    'avatar' => $googleAvatar,
                    'password' => Hash::make(uniqid()), // crea una contraseña aleatoria y encriptada
                    'rol_id' => $defaultRoleId, // asigna rol (intentamos 'estudiante')
                ]);
            }

            Auth::login($usuario); // inicia sesión con el usuario encontrado o creado
            return redirect('/'); // redirecciona a la página principal

        } catch (\Exception $e) { // captura cualquier excepción
            \Log::error('Google Auth Error: ' . $e->getMessage()); // registra el error en los logs
            return redirect('/login')->withErrors(['error' => $e->getMessage()]); // redirecciona a login con el mensaje de error
        }
}
}
