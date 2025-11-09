<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasLibrosSeeder extends Seeder
{
    public function run()
    {
        DB::table('categorias_libros')->insert([
            [
                'nombre_categoria' => 'Programación',
                'descripcion' => 'Libros sobre lenguajes de programación y desarrollo',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'Base de Datos',
                'descripcion' => 'Libros sobre gestión y diseño de bases de datos',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'Redes',
                'descripcion' => 'Libros sobre redes de computadoras y telecomunicaciones',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'Matemáticas',
                'descripcion' => 'Libros de cálculo, álgebra y matemática aplicada',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'Ingeniería de Software',
                'descripcion' => 'Libros sobre metodologías y procesos de desarrollo',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'Inteligencia Artificial',
                'descripcion' => 'Libros sobre IA, Machine Learning y Data Science',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'Sistemas Operativos',
                'descripcion' => 'Libros sobre arquitectura y administración de SO',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'Seguridad Informática',
                'descripcion' => 'Libros sobre ciberseguridad y protección de datos',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
            [
                'nombre_categoria' => 'General',
                'descripcion' => 'Libros de cultura general y desarrollo personal',
                'icono' => null,
                'fecha_creacion' => now(),
            ],
        ]);
    }
}
