<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('apuntes', function (Blueprint $table) {
            $table->id('id_apunte');
            $table->string('titulo', 500);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_curso');
            $table->enum('tipo_material', ['apuntes', 'guia', 'ejercicios', 'examenes', 'proyecto', 'otro']);
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

            $table->foreign('id_usuario_subida')->references('id_usuario')->on('usuarios')->cascadeOnDelete();
            $table->foreign('id_validador')->references('id_usuario')->on('usuarios')->nullOnDelete();
            $table->foreign('id_curso')->references('id_curso')->on('cursos')->cascadeOnDelete();

            $table->index('id_usuario_subida', 'fk_apunte_usuario');
            $table->index('id_validador', 'fk_apunte_validador');
            $table->index('id_curso', 'idx_apunte_curso');
            $table->index('estado_validacion', 'idx_apunte_estado');
            $table->index('tipo_material', 'idx_apunte_tipo');
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE apuntes ADD FULLTEXT idx_apunte_busqueda (titulo, descripcion)');
        }
    }

    public function down()
    {
        Schema::dropIfExists('apuntes');
    }
};
