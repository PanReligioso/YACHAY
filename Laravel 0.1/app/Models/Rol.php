<?php

namespace App\Models; //declara el espacio de nombres del modelo

use Illuminate\Database\Eloquent\Factories\HasFactory; //importa el trait para factorias
use Illuminate\Database\Eloquent\Model; //importa la clase base Model

class Rol extends Model //define el modelo Rol
{
    use HasFactory; //usa el trait de factorias

    protected $table = 'roles'; //especifica el nombre de la tabla
    // La tabla usa 'id_rol' como clave primaria y 'nombre_rol' como nombre del rol
    protected $primaryKey = 'id_rol';

    // No usar timestamps estándar (la migración usa 'fecha_creacion')
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol',
        'descripcion',
    ];

    public function usuarios()
    {
        // Relación: localKey = id_rol, foreignKey en usuarios = rol_id
        return $this->hasMany(Usuario::class, 'rol_id', 'id_rol');
    }
}
