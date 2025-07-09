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
        
        Schema::create('esc_actas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id');
            $table->foreignId('calificacion_id');
            $table->foreignId('docente_id');
            $table->foreignId('grupo_id');
            $table->foreignId('curso_id');
            $table->foreignId('user_aut_id')->nullable();
            $table->double('nueva_calif');
            $table->integer('nueva_falta');
            $table->string('motivo', 500);
            $table->tinyInteger('estado')->default(1);
            $table->string('tipo_acta', 1);
            $table->string('observaciones', 500);
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
        //
    }
};
