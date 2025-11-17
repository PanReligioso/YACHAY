<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sugerencias_docentes', function (Blueprint $table) {
            $table->id('id_sugerencia');
            $table->unsignedBigInteger('id_docente');
            $table->enum('tipo_contenido', ['libro', 'apunte']);
            $table->unsignedBigInteger('id_contenido')->comment('ID del libro o apunte');
            $table->text('sugerencia');
            $table->enum('estado', ['pendiente', 'revisada', 'aplicada', 'descartada'])->default('pendiente');
            $table->text('respuesta')->nullable();
            $table->unsignedBigInteger('id_usuario_respuesta')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_respuesta')->nullable();

            // CORREGIDO: Apunta a 'id'
            $table->foreign('id_docente')->references('id')->on('usuarios')->cascadeOnDelete();
            // CORREGIDO: Apunta a 'id'
            $table->foreign('id_usuario_respuesta')->references('id')->on('usuarios')->nullOnDelete();

            $table->index('id_docente', 'fk_sugerencia_docente');
            $table->index('id_usuario_respuesta', 'fk_sugerencia_respuesta');
            $table->index('estado', 'idx_sugerencia_estado');
            $table->index(['tipo_contenido', 'id_contenido'], 'idx_sugerencia_tipo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sugerencias_docentes');
    }
};
