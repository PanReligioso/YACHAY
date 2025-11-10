<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resenas_comedores', function (Blueprint $table) {
            $table->id('id_resena');
            $table->unsignedBigInteger('id_comedor');
            $table->unsignedBigInteger('id_usuario');
            $table->tinyInteger('calificacion')->comment('De 1 a 5 estrellas');
            $table->text('comentario')->nullable();
            $table->date('fecha_visita')->nullable();
            $table->timestamp('fecha_resena')->useCurrent();

            $table->foreign('id_comedor')->references('id')->on('comedores')->cascadeOnDelete();
            // CORREGIDO: Apunta a 'id'
            $table->foreign('id_usuario')->references('id')->on('usuarios')->cascadeOnDelete();

            $table->unique(['id_usuario', 'id_comedor'], 'uk_usuario_comedor');
            $table->index('id_comedor', 'fk_resena_comedor');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resenas_comedores');
    }
};
