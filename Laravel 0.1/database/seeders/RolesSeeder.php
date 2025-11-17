<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id_rol' => 1, 'nombre_rol' => 'admin', 'descripcion' => 'Administrador del sistema'],
            ['id_rol' => 2, 'nombre_rol' => 'estudiante', 'descripcion' => 'Usuario estudiante por defecto'],
            ['id_rol' => 3, 'nombre_rol' => 'docente', 'descripcion' => 'Usuario docente'],
            ['id_rol' => 4, 'nombre_rol' => 'validador', 'descripcion' => 'Usuario validador de contenidos'],
        ];

        foreach ($roles as $r) {
            $exists = DB::table('roles')->where('id_rol', $r['id_rol'])->exists();
            if (! $exists) {
                DB::table('roles')->insert(array_merge($r, ['fecha_creacion' => now()]));
            }
        }
    }
}
