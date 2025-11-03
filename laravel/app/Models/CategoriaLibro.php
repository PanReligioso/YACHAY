<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaLibro extends Model
{
    protected $table = 'categorias_libros';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria',
        'descripcion',
        'icono'
    ];

    // Relación con libros
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_categoria', 'id_categoria');
    }
}
