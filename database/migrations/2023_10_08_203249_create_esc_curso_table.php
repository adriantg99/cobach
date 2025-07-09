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
        Schema::create('esc_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_estudio_id');
            $table->foreignId('asignatura_id');
            $table->foreignId('docente_id')->nullable();
            $table->foreignId('grupo_id');
            $table->foreignId('horario_id')->nullable();
            $table->foreignId('curso_tipo')->nullable();
            $table->string('nombre',255);
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
        Schema::dropIfExists('esc_curso');
    }
};
