<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id('id_curso');
            $table->unsignedBigInteger('id_malla');
            $table->string('codigo_curso', 10)->comment('Ej: C.3, C.4');
            $table->string('nombre_curso');
            $table->tinyInteger('ciclo')->comment('Ciclo del 1 al 10');
            $table->decimal('creditos', 3, 1);
            $table->text('descripcion')->nullable();

            $table->foreign('id_malla')->references('id_malla')->on('mallas_curriculares')->cascadeOnDelete();
            $table->unique(['id_malla', 'nombre_curso'], 'uk_malla_nombre');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cursos');
    }
};
