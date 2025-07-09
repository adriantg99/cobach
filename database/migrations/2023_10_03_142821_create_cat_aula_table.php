<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * LS 2023-10-05
     * @return void
     */
    public function up()
    {
        Schema::create('cat_aula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantel_id')->references('id')->on('cat_plantel');
            $table->string('nombre',250);
            $table->foreignId('tipo_aula_id')->references('id')->on('cat_aula_tipo');
            $table->foreignId('condicion_aula_id')->references('id')->on('cat_aula_condicion');
            $table->boolean('aula_activa');
            $table->string('descripcion',2000)->nullable();
            $table->timestamps();
        });

        
        // Agregar la restricción de clave foránea después de crear la tabla
        Schema::table('esc_grupo', function (Blueprint $table) {
            //$table->foreign('aula_id')->references('id')->on('cat_aula');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_aula');
    }
};
