<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('direccion', 500);
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->string('universidad_cercana')->nullable();
            $table->decimal('precio_menu_min', 10, 2);
            $table->decimal('precio_menu_max', 10, 2)->nullable();
            $table->time('horario_apertura')->nullable();
            $table->time('horario_cierre')->nullable();
            $table->string('dias_atencion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('tipo_comida', 100)->nullable();
            $table->text('menu_dia')->nullable();
            $table->string('foto')->default('default-comedor.jpg');
            $table->boolean('activo')->default(true);
            $table->decimal('valoracion_promedio', 2, 1)->default(0.0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comedores');
    }
};
