<?php

namespace App\Models; //declara el espacio de nombres del modelo

use Illuminate\Database\Eloquent\Model; //importa la clase base Model

class CategoriaLibro extends Model //define el modelo CategoriaLibro
{
    protected $table = 'categorias_libros'; //especifica el nombre de la tabla
    protected $primaryKey = 'id_categoria'; //especifica la clave primaria
    public $timestamps = false; //deshabilita las marcas de tiempo

    protected $fillable = [ //campos que pueden ser asignados masivamente
        'nombre_categoria', //campo nombre de la categoria
        'descripcion', //campo descripcion
        'icono' //campo para el icono
    ];

    // Relación con libros
    public function libros() //define la relacion con el modelo Libro
    {
        return $this->hasMany(Libro::class, 'id_categoria', 'id_categoria'); //una categoria tiene muchos libros
    }
}
