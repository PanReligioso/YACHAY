<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('google_id')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable()->comment('Hash de la contraseña para login local');
            $table->string('nombre_completo');
            $table->string('apellidos')->nullable();
            $table->string('foto_perfil', 500)->nullable();
            $table->unsignedBigInteger('id_rol')->default(3);
            $table->enum('estado', ['activo', 'suspendido', 'inactivo'])->default('activo');
            $table->boolean('puede_descargar')->default(false)->comment('TRUE si ha subido al menos un libro');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('ultimo_acceso')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('universidad')->nullable();
            $table->string('carrera')->nullable();
            $table->string('ciclo', 50)->nullable();

            $table->foreign('id_rol')->references('id_rol')->on('roles')->onUpdate('cascade');
            $table->index('id_rol', 'fk_usuario_rol');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
