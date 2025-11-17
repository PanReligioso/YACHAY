<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutoriasSeeder extends Seeder
{
    public function run(): void
    {
        $tutorId = DB::table('usuarios')->where('rol_id', 3)->value('id') ?? DB::table('usuarios')->value('id');

        $tutorias = [
            [
                'tutor_id' => $tutorId ?? 1,
                'materia' => 'Programación Orientada a Objetos',
                'descripcion' => 'Clases particulares para reforzar conceptos de POO en PHP y Java',
                'precio_hora' => 30.00,
                'horario_disponible' => json_encode(['Lunes 18:00-20:00', 'Miércoles 16:00-18:00']),
                'modalidad' => 'Virtual',
                'created_at' => now(),
            ],
            [
                'tutor_id' => $tutorId ?? 1,
                'materia' => 'Base de Datos 1',
                'descripcion' => 'Resolución de ejercicios y diseño de consultas avanzadas',
                'precio_hora' => 25.00,
                'horario_disponible' => json_encode(['Martes 17:00-19:00', 'Jueves 17:00-19:00']),
                'modalidad' => 'Presencial',
                'created_at' => now(),
            ],
        ];

        foreach ($tutorias as $t) {
            try {
                DB::table('tutorias')->insert($t);
            } catch (\Exception $e) {
                \Log::error('TutoriasSeeder error: ' . $e->getMessage());
            }
        }

        \Log::info('TutoriasSeeder completado');
    }
}
