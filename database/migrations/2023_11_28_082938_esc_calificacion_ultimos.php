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
        Schema::create('esc_calificacion_pivote', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('alumno_id')->references('id')->on('alu_alumno');
            $table->foreignId('alumno_id');
            //$table->foreignId('politica_variable_id')->references('id')->on('asi_Politica_variable');
            $table->foreignId('politica_variable_id');
            //$table->foreignId('calificacion_tipo_id')->references('id')->on('esc_calificacion_tipo');
            $table->foreignId('calificacion_tipo_id')->nullable();
            //$table->foreignId('curso_id')->references('id')->on('esc_curso');
            $table->foreignId('curso_id');
            $table->double('calificacion',5,2);//3 digitos 2 decimales
            $table->string('calif', 50)->nullable();
            $table->timestamps();
            $table->string('calificacion_tipo',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
