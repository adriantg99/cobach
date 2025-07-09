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
        Schema::create('fin_fichas', function (Blueprint $table) {
            $table->id();
            $table->string('matricula',20);
            $table->string('folio',20);
            $table->string('nombre_alumno',300);
            $table->date('fecha_creacion');
            $table->double('total',12,2);
            $table->string('cadena_hsbc_bienestar',500);
            $table->string('cadena_banamex',500);
            $table->string('cadena_bbva',500);
            $table->date('fecha_caducidad');
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
        Schema::dropIfExists('fin_fichas');
    }
};
