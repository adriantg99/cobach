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
        Schema::create('lug_pais', function (Blueprint $table) {
           $table->integer('id')->unique(); //permite guardar el id 0 (sin datos) para que funcionen las referencias cuando se marca el id:0
           $table->string('nombre',50);

       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lug_pais');
    }
};
