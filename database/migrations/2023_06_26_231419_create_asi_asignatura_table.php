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
        Schema::create('asi_asignatura', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',250);
            $table->foreignId('id_areaformacion');
            $table->foreignId('id_nucleo');
            $table->foreignId('periodo');
            $table->string('consecutivo',2);
            $table->string('clave',20);
            $table->boolean('boleta');
            $table->boolean('kardex');
            $table->boolean('expediente');
            $table->boolean('certificado');
            $table->boolean('optativa');
            $table->boolean('activa');
            $table->integer('creditos');
            $table->integer('horas_semana');
            $table->string('nombre_completo',500);
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
        Schema::dropIfExists('asi_asignatura');
    }
};
