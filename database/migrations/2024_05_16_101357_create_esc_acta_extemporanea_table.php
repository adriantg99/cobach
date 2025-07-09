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
    {/*
        Schema::create('esc_acta_extemporanea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id');
            $table->foreignId('ciclo_esc_id');
            $table->string('grupo',100)->nullable();
            $table->string('turno',100)->nullable();
            $table->string('plantel',200);
            $table->foreignId('plantel_id');
            $table->foreignId('asignatura_id');
            $table->string('email_docente',255);
            $table->double('calificacion',5,2)->nullable();//3 digitos 2 decimales
            $table->string('calif', 50)->nullable();
            $table->string('tipo_acta',100)->nullable();
            $table->string('motivo',255)->nullable();
            $table->foreignId('user_id_creacion');
            $table->dateTime('fecha_creacion');
            $table->unsignedInteger('impresiones');
            $table->dateTime('fecha_impresion')->nullable();
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esc_acta_extemporanea');
    }
};
