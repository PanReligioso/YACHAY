<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un usuario de prueba (estudiante)
        $exists = DB::table('usuarios')->where('email', 'test@local')->exists();
        if (! $exists) {
            DB::table('usuarios')->insert([
                'nombre' => 'Test',
                'apellido' => 'Usuario',
                'email' => 'test@local',
                'password' => Hash::make('password'),
                'codigo_universitario' => 'TEST001',
                'rol_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Crear un usuario validador
        $exists2 = DB::table('usuarios')->where('email', 'validador@local')->exists();
        if (! $exists2) {
            DB::table('usuarios')->insert([
                'nombre' => 'Validador',
                'apellido' => 'Contenido',
                'email' => 'validador@local',
                'password' => Hash::make('password'),
                'codigo_universitario' => null,
                'rol_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        \Log::info('UsuariosSeeder completado');
    }
}
