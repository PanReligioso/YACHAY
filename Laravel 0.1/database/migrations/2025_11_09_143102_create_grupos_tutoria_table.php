// 2025_11_09_143102_create_grupos_tutoria_table.php (Código para la tabla GRUPOS_TUTORIA)
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grupos_tutoria', function (Blueprint $table) {
            $table->id('id_grupo');
            $table->string('nombre_grupo');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_curso')->nullable()->comment('Curso relacionado (opcional)');
            $table->unsignedBigInteger('id_creador');
            $table->enum('tipo', ['publico', 'privado'])->default('publico');
            $table->integer('max_participantes')->default(50);
            $table->string('codigo_acceso', 20)->unique()->nullable()->comment('Para grupos privados');
            $table->boolean('esta_activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();

            $table->foreign('id_curso')->references('id_curso')->on('cursos')->nullOnDelete();

            // CORREGIDO: Apunta a 'id' en la tabla usuarios
            $table->foreign('id_creador')->references('id')->on('usuarios')->cascadeOnDelete();

            $table->index('id_curso', 'fk_grupo_curso');
            $table->index('id_creador', 'fk_grupo_creador');
            $table->index('esta_activo', 'idx_grupo_activo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupos_tutoria');
    }
};
