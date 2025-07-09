<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * llenar la tabla
     * ordinaria
     * pasantia
     * actua escpecial
     * regularizacion
     * 
     * 
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('esc_calificacion_tipo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',200);
            $table->integer('prioridad');
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
        Schema::dropIfExists('esc_calificacion_tipo');
    }
};
