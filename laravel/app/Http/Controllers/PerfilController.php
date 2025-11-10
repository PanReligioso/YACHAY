<?php

namespace App\Http\Controllers; //declara el espacio de nombres del controlador

use Illuminate\Http\Request;//importa la clase Request para manejar peticiones
use Illuminate\Support\Facades\Auth; //importa la fachada Auth para autenticacion
use Illuminate\Support\Facades\Hash; //importa la fachada Hash para encriptacion de contraseñas

class PerfilController extends Controller
{
    public function show()
    {
        return view('perfil.show', [ //retorna la vista de perfil.show
            'usuario' => Auth::user() //pasa los datos del usuario autenticado a la vista
        ]);
    }

    public function update(Request $request)
    {
        $usuario = Auth::user(); //obtiene el usuario autenticado

        $validated = $request->validate([ //valida los datos de la peticion
            'nombre' => 'required|string|max:100', //regla de validacion para nombre
            'apellido' => 'required|string|max:100', //regla de validacion para apellido
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id, //regla de validacion para email debe ser unico ignorando el id actual
            'codigo_universitario' => 'nullable|string|max:20|unique:usuarios,codigo_universitario,' . $usuario->id, //regla de validacion para codigo universitario debe ser unico
            'password' => 'nullable|min:6|confirmed', //regla de validacion para password opcional minimo 6 caracteres y confirmado
        ]);

        $usuario->nombre = $validated['nombre']; //asigna el nombre validado al usuario
        $usuario->apellido = $validated['apellido']; //asigna el apellido validado al usuario
        $usuario->email = $validated['email']; //asigna el email validado al usuario
        $usuario->codigo_universitario = $validated['codigo_universitario']; //asigna el codigo validado al usuario

        if ($request->filled('password')) { //verifica si el campo password no esta vacio
            $usuario->password = Hash::make($validated['password']); //encripta y asigna la nueva contraseña
        }

        $usuario->save(); //guarda los cambios del usuario en la base de datos

        return back()->with('success', 'Perfil actualizado correctamente'); //redirecciona a la pagina anterior con un mensaje de exito
    }
}
