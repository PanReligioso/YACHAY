<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historial_actividad', function (Blueprint $table) {
            $table->id('id_actividad');
            $table->unsignedBigInteger('id_usuario');
            $table->enum('tipo_accion', ['login', 'subir_libro', 'subir_apunte', 'descargar', 'validar', 'reportar', 'unirse_grupo', 'crear_grupo', 'otro']);
            $table->string('descripcion', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('fecha_accion')->useCurrent();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->cascadeOnDelete();

            $table->index(['id_usuario', 'fecha_accion'], 'idx_usuario_fecha');
            $table->index('tipo_accion', 'idx_historial_tipo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historial_actividad');
    }
};
