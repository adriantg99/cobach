<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla PIVOTE que relaciona alumnos con grupos (LS231008)
     * 
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esc_grupo_alumno', function (Blueprint $table) {
      
            //$table->foreignId('grupo_id')->references('id')->on('esc_grupo');
            $table->foreignId('grupo_id');
            //$table->foreignId('alumno_id')->references('id')->on('alu_alumno');
            $table->foreignId('alumno_id');
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
        Schema::dropIfExists('esc_grupo_alumno');
    }
};
