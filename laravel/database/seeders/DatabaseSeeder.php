<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            CategoriasLibrosSeeder::class,
            MallasCurricularesSeeder::class,
            CursosSeeder::class,
        ]);
    }
}
