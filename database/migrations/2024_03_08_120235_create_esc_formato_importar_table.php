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
        Schema::create('esc_formato_importar', function (Blueprint $table) {
            $table->id();
            $table->integer('expediente');
            $table->string('nombre',500)->nullable();
            $table->string('asignatura',500);
            $table->string('clave',30);
            $table->string('ciclo',20);
            $table->double('calificacion',5,2)->nullable();
            $table->string('calif', 50)->nullable();
            $table->foreignId('alumno_id')->nullable();
            $table->foreignId('plantel_id')->nullable();
            $table->foreignId('ciclo_esc_id')->nullable();
            $table->foreignId('asignatura_id')->nullable();
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
        Schema::dropIfExists('esc_formato_importar');
    }
};
