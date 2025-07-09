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
        Schema::create('esc_cursos_omitidos', function (Blueprint $table) {
            $table->foreignId('curso_id');
            //$table->foreignId('politica_variable_id')->references('id')->on('asi_Politica_variable');
            $table->foreignId('alumno_id');
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
