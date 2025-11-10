<?php

namespace App\Models; //declara el espacio de nombres del modelo

use Illuminate\Foundation\Auth\User as Authenticatable; //importa la clase base para autenticacion
use Illuminate\Notifications\Notifiable; //importa el trait para notificaciones

class Usuario extends Authenticatable //define el modelo Usuario que es autenticable
{
    use Notifiable; //usa el trait de notificacion

    protected $table = 'usuarios'; //especifica el nombre de la tabla en la base de datos

    protected $fillable = [ //campos que pueden ser asignados masivamente
        'nombre', //campo nombre
        'apellido', //campo apellido
        'email', //campo email
        'password', //campo password
        'codigo_universitario', //campo codigo universitario
        'google_id', //campo id de google para login social
        'avatar', //campo para el avatar
        'rol_id', //campo para la clave foranea del rol
    ];

    protected $hidden = [ //campos que se ocultan en la serializacion a array o JSON
        'password', //oculta la contraseña
    ];

    public function rol() //define la relacion con el modelo Rol
    {
        return $this->belongsTo(Rol::class); //un usuario pertenece a un rol
    }
}
