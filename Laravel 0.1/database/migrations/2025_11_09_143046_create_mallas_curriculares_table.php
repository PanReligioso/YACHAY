<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mallas_curriculares', function (Blueprint $table) {
            $table->id('id_malla');
            $table->integer('periodo')->unique()->comment('Año de la malla: 2018, 2025');
            $table->string('nombre_malla');
            $table->enum('formato_material', ['foto', 'pdf', 'mixto']);
            $table->boolean('esta_activa')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mallas_curriculares');
    }
};
