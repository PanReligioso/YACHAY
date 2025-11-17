// 2025_11_09_143216_create_tutorias_table.php (CORREGIDO)
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tutorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tutor_id');
            $table->string('materia');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_hora', 10, 2);
            $table->text('horario_disponible')->nullable();
            $table->string('modalidad', 50)->nullable()->comment('Presencial, Virtual, Mixta');
            $table->timestamp('created_at')->useCurrent();

            // CORREGIDO: Apunta a 'id' de la tabla 'usuarios' (NO 'id_usuario')
            $table->foreign('tutor_id')->references('id')->on('usuarios')->cascadeOnDelete();
            $table->index('tutor_id', 'fk_tutoria_tutor');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tutorias');
    }
};
