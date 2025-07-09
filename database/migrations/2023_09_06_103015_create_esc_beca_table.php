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
        Schema::create('esc_beca', function (Blueprint $table) {
           $table->integer('id')->unique(); //permite guardar el id 0 (sin datos) para que funcionen las referencias cuando se marca el id:0
           $table->string('nombre',100);

       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esc_beca');
    }
};
