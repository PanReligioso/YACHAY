<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. DESHABILITAR RESTRICCIONES DE CLAVE FORÁNEA
        if (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // 2. LLAMAR A LOS SEEDERS
        $this->call([
            RolesSeeder::class,
            CategoriasLibrosSeeder::class,
            MallasCurricularesSeeder::class,
            CursosSeeder::class,
        ]);

        // 3. RE-HABILITAR RESTRICCIONES DE CLAVE FORÁNEA
        if (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }
}
