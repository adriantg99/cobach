<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * regular
     * recursamiento virtual
     * verano
     * equivalencia
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esc_curso_tipo', function (Blueprint $table) {
            $table->integer('id')->unique(); //permite guardar el id 0 (sin datos) para que funcionen las referencias cuando se marca el id:0
            $table->string('descripcion',30);
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
        Schema::dropIfExists('esc_curso_tipo');
    }
};
