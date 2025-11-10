<?php

namespace App\Models; //declara el espacio de nombres del modelo

use Illuminate\Database\Eloquent\Factories\HasFactory; //importa el trait para factorias
use Illuminate\Database\Eloquent\Model; //importa la clase base Model

class Rol extends Model //define el modelo Rol
{
    use HasFactory; //usa el trait de factorias

    protected $table = 'roles'; //especifica el nombre de la tabla

    // 🔥 ESTA LÍNEA FALTABA Y SOLUCIONA EL ERROR:
    // Le dice a Laravel que el ID de esta tabla es 'rol_id', no 'id'.
    protected $primaryKey = 'rol_id'; //especifica la clave primaria no estandar

    protected $fillable = [ //campos que pueden ser asignados masivamente
        'nombre', //campo nombre del rol
    ];

    public $timestamps = true; //indica que la tabla usa marcas de tiempo created_at y updated_at

    public function usuarios() //define la relacion con el modelo Usuario
    {
        return $this->hasMany(Usuario::class, 'rol_id'); //un rol tiene muchos usuarios usando rol_id como clave foranea
    }
}
