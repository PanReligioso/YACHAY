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
            $table->id();

            $table->string('titulo', 255);
            $table->string('autor', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('archivo_url', 500);
            $table->string('editorial', 255)->nullable();
            $table->year('anio_publicacion')->nullable();
            $table->integer('vistas')->default(0);
            $table->integer('descargas')->default(0);
            $table->string('estado', 50)->default('pendiente'); // 'aprobado', 'rechazado', 'pendiente'
            $table->text('comentario_validacion')->nullable();
            $table->datetime('fecha_validacion')->nullable();

            // Claves foráneas (debes tener campos definidos para ellas)
            $table->unsignedBigInteger('id_usuario_subida');
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_validador')->nullable();

            $table->timestamps();

            $table->foreign('id_usuario_subida')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias_libros')->onDelete('cascade');
            $table->foreign('id_validador')->references('id')->on('usuarios')->onDelete('set null');

            // Índices para optimizar búsquedas
            $table->index('titulo');
            $table->index('autor');
            $table->index('id_categoria');
            $table->index('id_usuario_subida');
            $table->index('estado');
            $table->index('created_at');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
