<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esc_curso_hora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id');
            $table->string('dia_semana',15);
            $table->foreignId('hora_plantel_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esc_curso_hora');
    }
};
