<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id('id_libro');
            $table->string('titulo', 500);
            $table->string('autor_libro')->nullable();
            $table->string('editorial')->nullable();
            $table->year('anio_publicacion')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->unsignedBigInteger('id_categoria')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('url_drive', 1000);
            $table->unsignedBigInteger('id_usuario_subida');
            $table->enum('estado_validacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->unsignedBigInteger('id_validador')->nullable();
            $table->timestamp('fecha_validacion')->nullable();
            $table->text('comentario_validacion')->nullable();
            $table->integer('vistas')->default(0);
            $table->integer('descargas')->default(0);
            $table->timestamp('fecha_subida')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_categoria')->references('id_categoria')->on('categorias_libros')->nullOnDelete();
            $table->foreign('id_usuario_subida')->references('id_usuario')->on('usuarios')->cascadeOnDelete();
            $table->foreign('id_validador')->references('id_usuario')->on('usuarios')->nullOnDelete();

            $table->index('id_categoria', 'fk_libro_categoria');
            $table->index('id_usuario_subida', 'fk_libro_usuario');
            $table->index('id_validador', 'fk_libro_validador');
            $table->index('estado_validacion', 'idx_libro_estado');
            $table->index(['titulo'], 'idx_libro_titulo');
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE libros ADD FULLTEXT idx_libro_busqueda (titulo, autor_libro, descripcion)');
        }
    }

    public function down()
    {
        Schema::dropIfExists('libros');
    }
};
