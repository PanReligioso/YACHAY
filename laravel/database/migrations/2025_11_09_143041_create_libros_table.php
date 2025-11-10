// database/migrations/2025_11_09_143041_create_libros_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            // Asumo que esta tabla tiene su propia clave primaria 'id'
            $table->id();

            $table->string('titulo', 255);
            $table->string('autor', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('archivo_url', 500);
            $table->integer('vistas')->default(0);
            $table->string('estado', 50)->default('pendiente'); // 'aprobado', 'rechazado', 'pendiente'

            // Claves foráneas (debes tener campos definidos para ellas)
            $table->unsignedBigInteger('id_usuario_subida');
            $table->unsignedBigInteger('id_categoria');

            $table->timestamps();

            // LÍNEA CORREGIDA: Referencia al campo 'id' en la tabla 'usuarios'
            $table->foreign('id_usuario_subida')->references('id')->on('usuarios')->onDelete('cascade');

            // Asumo que tienes una clave foránea a la tabla de categorías (puede necesitar corrección similar)
            // $table->foreign('id_categoria')->references('id')->on('categorias_libros')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
