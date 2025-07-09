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
        Schema::create('esc_certificado', function (Blueprint $table) {
            $table->id();
            $table->integer('folio')->nullable();
            $table->foreignId('alumno_id')->nullable();
            $table->string('curp',20)->nullable();
            $table->integer('user_id')->nullable();
            $table->date('fecha_certificado')->nullable();
            $table->boolean('original')->nullable();
            $table->string('estatus',1)->nullable();
            $table->string('ip',250)->nullable();
            $table->string('nombrealumno',300)->nullable();
            $table->binary('fotocertificado')->nullable();
            $table->boolean('vigente')->nullable();
            $table->foreignId('general_id')->nullable();
            $table->string('autoridadeducativa',200)->nullable();
            $table->string('numcertificadoautoridad',100)->nullable();
            $table->string('sellodigitalautoridad',500)->nullable();
            $table->foreignId('plantel_id')->nullable();
            $table->date('digital')->nullable();
            $table->integer('asignaturas')->nullable();
            $table->integer('promedio')->nullable();
            $table->string('email',500)->nullable();
            $table->date('emailtime')->nullable();
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
        Schema::dropIfExists('esc_certificado');
    }
};
