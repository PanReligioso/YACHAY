<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MallasCurricularesSeeder extends Seeder
{
    public function run()
    {
        DB::table('mallas_curriculares')->insert([
            [
                'periodo' => 2018,
                'nombre_malla' => 'Ingeniería de Sistemas e Informática 2018',
                'formato_material' => 'foto',
                'esta_activa' => true,
                'fecha_creacion' => now(),
            ],
            [
                'periodo' => 2025,
                'nombre_malla' => 'Ingeniería de Sistemas e Informática 2025',
                'formato_material' => 'pdf',
                'esta_activa' => true,
                'fecha_creacion' => now(),
            ],
        ]);
    }
}
