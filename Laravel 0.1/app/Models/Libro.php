<?php

namespace App\Models; //declara el espacio de nombres del modelo

use Illuminate\Database\Eloquent\Model; //importa la clase base Model

class Libro extends Model //define el modelo Libro
{
    protected $table = 'libros'; //especifica el nombre de la tabla
    protected $primaryKey = 'id_libro'; //especifica la clave primaria
    public $timestamps = false; //deshabilita las marcas de tiempo

    protected $fillable = [ //campos que pueden ser asignados masivamente
        'titulo', //campo titulo del libro
        'autor_libro', //campo autor
        'editorial', //campo editorial
        'anio_publicacion', //campo año de publicacion
        'isbn', //campo ISBN
        'id_categoria', //campo clave foranea de categoria
        'descripcion', //campo descripcion
        'url_drive', //campo url del archivo
        'id_usuario_subida', //campo clave foranea del usuario que subio
        'estado_validacion', //campo estado de validacion
        'id_validador', //campo clave foranea del usuario validador
        'fecha_validacion', //campo fecha de validacion
        'comentario_validacion', //campo comentario de validacion
        'vistas', //campo contador de vistas
        'descargas' //campo contador de descargas
    ];

    protected $casts = [ //conversion de tipos de atributos a tipos nativos de PHP
        'fecha_subida' => 'datetime', //convierte a tipo datetime
        'fecha_actualizacion' => 'datetime', //convierte a tipo datetime
        'fecha_validacion' => 'datetime', //convierte a tipo datetime
        'vistas' => 'integer', //convierte a tipo integer
        'descargas' => 'integer' //convierte a tipo integer
    ];

    // Relación con categoría
    public function categoria() //define la relacion con CategoriaLibro
    {
        return $this->belongsTo(CategoriaLibro::class, 'id_categoria', 'id_categoria'); //un libro pertenece a una categoria
    }

    // Relación con usuario que subió
    public function usuario() //define la relacion con el usuario que subio
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_subida', 'id_usuario'); //un libro pertenece a un usuario subidor
    }

    // Relación con validador
    public function validador() //define la relacion con el usuario validador
    {
        return $this->belongsTo(Usuario::class, 'id_validador', 'id_usuario'); //un libro pertenece a un usuario validador
    }
}
