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
        Schema::create('cat_paises_posibles', function (Blueprint $table) {
            $table->id();
            $table->string('pais', 100);
            //$table->foreignId('politica_variable_id')->references('id')->on('asi_Politica_variable');
            //$table->foreignId('alumno_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_paises_posibles');
    }
};
