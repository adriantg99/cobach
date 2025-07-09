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
        Schema::create('emp_perfil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->string('nombre',300);
            $table->string('apellido1',300);
            $table->string('apellido2',300)->nullable();
            $table->date('fecha_nac');
            $table->string('expediente',4);
            $table->string('correo_personal',300);
            $table->string('telefono',15);
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
        Schema::dropIfExists('emp_perfil');
    }
};
