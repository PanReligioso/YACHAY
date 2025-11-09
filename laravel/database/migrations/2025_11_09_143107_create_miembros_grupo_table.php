<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('miembros_grupo', function (Blueprint $table) {
            $table->id('id_miembro');
            $table->unsignedBigInteger('id_grupo');
            $table->unsignedBigInteger('id_usuario');
            $table->enum('rol_grupo', ['admin', 'moderador', 'miembro'])->default('miembro');
            $table->timestamp('fecha_union')->useCurrent();

            $table->foreign('id_grupo')->references('id_grupo')->on('grupos_tutoria')->cascadeOnDelete();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->cascadeOnDelete();

            $table->unique(['id_grupo', 'id_usuario'], 'uk_grupo_usuario');
            $table->index('id_usuario', 'fk_miembro_usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('miembros_grupo');
    }
};
