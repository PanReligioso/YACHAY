<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sumas', function (Blueprint $table) {
            $table->id();
            $table->integer('num1');
            $table->integer('num2');
            $table->integer('resultado');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sumas');
    }
};
