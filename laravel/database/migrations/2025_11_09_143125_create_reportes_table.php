<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id('id_reporte');
            $table->unsignedBigInteger('id_usuario_reporta');
            $table->enum('tipo_contenido', ['libro', 'apunte', 'comentario', 'usuario']);
            $table->unsignedBigInteger('id_contenido');
            $table->enum('motivo', ['contenido_inapropiado', 'spam', 'informacion_incorrecta', 'contenido_duplicado', 'derechos_autor', 'otro']);
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'en_revision', 'resuelto', 'rechazado'])->default('pendiente');
            $table->unsignedBigInteger('id_moderador')->nullable();
            $table->text('accion_tomada')->nullable();
            $table->timestamp('fecha_reporte')->useCurrent();
            $table->timestamp('fecha_resolucion')->nullable();

            $table->foreign('id_usuario_reporta')->references('id_usuario')->on('usuarios')->cascadeOnDelete();
            $table->foreign('id_moderador')->references('id_usuario')->on('usuarios')->nullOnDelete();

            $table->index('id_usuario_reporta', 'fk_reporte_usuario');
            $table->index('id_moderador', 'fk_reporte_moderador');
            $table->index('estado', 'idx_reporte_estado');
            $table->index(['tipo_contenido', 'id_contenido'], 'idx_reporte_tipo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};
