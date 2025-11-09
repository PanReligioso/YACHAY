<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mensajes_chat', function (Blueprint $table) {
            $table->id('id_mensaje');
            $table->unsignedBigInteger('id_grupo');
            $table->unsignedBigInteger('id_usuario');
            $table->text('mensaje');
            $table->enum('tipo_mensaje', ['texto', 'archivo', 'imagen', 'link'])->default('texto');
            $table->string('url_adjunto', 1000)->nullable();
            $table->boolean('editado')->default(false);
            $table->timestamp('fecha_envio')->useCurrent();
            $table->timestamp('fecha_edicion')->nullable();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->cascadeOnDelete();
            $table->foreign('id_grupo')->references('id_grupo')->on('grupos_tutoria')->cascadeOnDelete();

            $table->index('id_usuario', 'fk_mensaje_usuario');
            $table->index(['id_grupo', 'fecha_envio'], 'idx_grupo_fecha');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensajes_chat');
    }
};
