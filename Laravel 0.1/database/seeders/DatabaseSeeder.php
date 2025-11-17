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

        // 2. LLAMAR A LOS SEEDERS (orden importante: roles -> categorias/cursos -> usuarios -> contenido)
        $this->call([
            RolesSeeder::class,
            CategoriasLibrosSeeder::class,
            MallasCurricularesSeeder::class,
            CursosSeeder::class,
            UsuariosSeeder::class,
            LibrosSeeder::class,
            ApuntesSeeder::class,
            TutoriasSeeder::class,
        ]);

        // 3. RE-HABILITAR RESTRICCIONES DE CLAVE FORÁNEA
        if (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }
}
