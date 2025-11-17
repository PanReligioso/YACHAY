<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApuntesSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener un usuario existente
        $userId = DB::table('usuarios')->value('id');
        $cursoId = DB::table('cursos')->value('id_curso') ?? 1;

        $apuntes = [
            [
                'titulo' => 'Apuntes Estructura de Datos - Resumen',
                'descripcion' => 'Resumen con ejercicios y soluciones',
                'id_curso' => $cursoId,
                'tipo_material' => 'apuntes',
                'url_drive' => 'https://drive.google.com/file/d/test-apunte-1',
                'id_usuario_subida' => $userId ?? 1,
                'estado_validacion' => 'aprobado',
                'vistas' => 45,
                'descargas' => 10,
                'fecha_subida' => now(),
                'fecha_validacion' => now(),
            ],
            [
                'titulo' => 'Guía Laboratorio Programación Web',
                'descripcion' => 'Instrucciones paso a paso para el laboratorio',
                'id_curso' => $cursoId,
                'tipo_material' => 'guia',
                'url_drive' => 'https://drive.google.com/file/d/test-apunte-2',
                'id_usuario_subida' => $userId ?? 1,
                'estado_validacion' => 'pendiente',
                'vistas' => 20,
                'descargas' => 5,
                'fecha_subida' => now(),
            ],
        ];

        foreach ($apuntes as $a) {
            try {
                DB::table('apuntes')->insert($a);
            } catch (\Exception $e) {
                \Log::error('ApuntesSeeder error: ' . $e->getMessage());
            }
        }

        \Log::info('ApuntesSeeder completado');
    }
}
