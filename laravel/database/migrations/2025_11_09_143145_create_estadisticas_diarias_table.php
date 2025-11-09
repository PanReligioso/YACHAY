<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estadisticas_diarias', function (Blueprint $table) {
            $table->id('id_estadistica');
            $table->date('fecha')->unique();
            $table->integer('usuarios_activos')->default(0);
            $table->integer('libros_subidos')->default(0);
            $table->integer('apuntes_subidos')->default(0);
            $table->integer('descargas_totales')->default(0);
            $table->integer('grupos_creados')->default(0);
            $table->integer('mensajes_enviados')->default(0);
            $table->integer('reportes_generados')->default(0);
            $table->timestamp('fecha_calculo')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estadisticas_diarias');
    }
};
