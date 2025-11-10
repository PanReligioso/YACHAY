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
        return Socialite::driver('google')->redirect(); //usa Socialite para redirigir a Google
    }
public function handleGoogleCallback() //metodo para manejar la respuesta de Google
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user(); //obtiene datos del usuario de Google

        $usuario = Usuario::where('email', $googleUser->email)->first(); //busca el usuario en DB por email de Google

        if ($usuario) { //si el usuario existe
            $usuario->update([ //actualiza los datos de google
                'google_id' => $googleUser->id, //actualiza el id de google
                'avatar' => $googleUser->avatar, //actualiza el avatar
            ]);
        } else { //si el usuario no existe se crea
            $nameParts = explode(' ', $googleUser->name); //divide el nombre completo de Google
            $usuario = Usuario::create([ //crea un nuevo usuario con datos de Google
                'nombre' => $nameParts[0] ?? 'Usuario', //usa el primer nombre o 'Usuario
                'apellido' => $nameParts[1] ?? '', //usa el apellido o vacio
                'email' => $googleUser->email, //asigna email de Google
                'google_id' => $googleUser->id, //asigna id de Google
                'avatar' => $googleUser->avatar, //asigna avatar de Google
                'password' => Hash::make(uniqid()), //crea una contraseña aleatoria y encriptada
                'rol_id' => 2, // asigna rol de Estudiante
            ]);
        }

        Auth::login($usuario); //inicia sesion con el usuario encontrado o creado
        return redirect('/'); //redirecciona a la pagina principal

    } catch (\Exception $e) { //captura cualquier excepcion
        \Log::error('Google Auth Error: ' . $e->getMessage()); //registra el error en los logs
        return redirect('/login')->withErrors(['error' => $e->getMessage()]); //redirecciona a login con el mensaje de error
    }
}
}
