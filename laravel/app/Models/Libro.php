<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
    protected $primaryKey = 'id_libro';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'autor_libro',
        'editorial',
        'anio_publicacion',
        'isbn',
        'id_categoria',
        'descripcion',
        'url_drive',
        'id_usuario_subida',
        'estado_validacion',
        'id_validador',
        'fecha_validacion',
        'comentario_validacion',
        'vistas',
        'descargas'
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'fecha_validacion' => 'datetime',
        'vistas' => 'integer',
        'descargas' => 'integer'
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaLibro::class, 'id_categoria', 'id_categoria');
    }

    // Relación con usuario que subió
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_subida', 'id_usuario');
    }

    // Relación con validador
    public function validador()
    {
        return $this->belongsTo(Usuario::class, 'id_validador', 'id_usuario');
    }
}
