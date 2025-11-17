<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * LibrosSeeder - Siembra datos de libros en la BD
 * 
 * Valida datos antes de insertar.
 * Proporciona logging detallado.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0.0
 */
class LibrosSeeder extends Seeder
{
    /**
     * Siembra la tabla de libros
     */
    public function run(): void
    {
        // Datos de ejemplo (en producción vendrían de JSON migrado)
        $libros = [
            [
                'titulo' => 'Introducción a PHP',
                'autor' => 'Juan Pérez',
                'descripcion' => 'Guía completa para aprender PHP desde cero',
                'editorial' => 'Tech Press',
                'anio_publicacion' => 2023,
                'isbn' => '978-3-16-148410-0',
                'archivo_url' => '/archivos/php-intro.pdf',
                'id_categoria' => 1,
                'id_usuario_subida' => 1,
                'estado' => 'aprobado',
                'vistas' => 150,
                'descargas' => 45
            ],
            [
                'titulo' => 'Laravel Moderno',
                'autor' => 'María García',
                'descripcion' => 'Aprende Laravel con buenas prácticas',
                'editorial' => 'Dev Books',
                'anio_publicacion' => 2023,
                'isbn' => '978-3-16-148410-1',
                'archivo_url' => '/archivos/laravel-moderno.pdf',
                'id_categoria' => 1,
                'id_usuario_subida' => 1,
                'estado' => 'aprobado',
                'vistas' => 200,
                'descargas' => 60
            ],
            [
                'titulo' => 'SQL Avanzado',
                'autor' => 'Carlos López',
                'descripcion' => 'Optimización y queries complejas',
                'editorial' => 'Database Press',
                'anio_publicacion' => 2022,
                'isbn' => '978-3-16-148410-2',
                'archivo_url' => '/archivos/sql-avanzado.pdf',
                'id_categoria' => 2,
                'id_usuario_subida' => 1,
                'estado' => 'aprobado',
                'vistas' => 180,
                'descargas' => 50
            ]
        ];

        foreach ($libros as $libro) {
            try {
                // Validar campos requeridos
                if (empty($libro['titulo']) || empty($libro['archivo_url'])) {
                    \Log::warning('Libro inválido saltado en seeder: ' . json_encode($libro));
                    continue;
                }

                // Agregar timestamps
                $libro['created_at'] = now();
                $libro['updated_at'] = now();

                DB::table('libros')->insert($libro);
            } catch (\Exception $e) {
                \Log::error('Error al insertar libro en seeder: ' . $e->getMessage());
            }
        }

        \Log::info('LibrosSeeder completado exitosamente');
    }
}
