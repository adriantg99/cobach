<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Se agregar alumnos que recursaran solo algunas asignaturas del grupo (LS20231008))
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esc_cursos_rec_presencial', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('alumno_id')->references('id')->on('alu_alumno');
            $table->foreignId('alumno_id');
            //$table->foreignId('grupo_id')->references('id')->on('esc_grupo');
            $table->foreignId('grupo_id');
            //$table->foreignId('curso_id')->references('id')->on('esc_curso');
            $table->foreignId('curso_id');
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
        Schema::dropIfExists('esc_cursos_rec_presencial');
    }
};
