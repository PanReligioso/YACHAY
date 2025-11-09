<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'nombre_rol' => 'admin',
                'descripcion' => 'Administrador del sistema con acceso total',
                'fecha_creacion' => now(),
            ],
            [
                'nombre_rol' => 'validador',
                'descripcion' => 'Usuario encargado de validar contenido subido',
                'fecha_creacion' => now(),
            ],
            [
                'nombre_rol' => 'estudiante',
                'descripcion' => 'Estudiante regular de la Universidad Continental',
                'fecha_creacion' => now(),
            ],
            [
                'nombre_rol' => 'docente',
                'descripcion' => 'Docente que puede sugerir mejoras en el contenido',
                'fecha_creacion' => now(),
            ],
        ]);
    }
}
