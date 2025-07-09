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
        /*
        Schema::table('esc_cursos_omitidos', function (Blueprint $table) {
            $table->id()->after('curso_id');
            $table->string('motivo',200)->nullable()->after('alumno_id');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esc_cursos_omitidos', function (Blueprint $table) {
            $table->dropColumn('motivo');
        });
    }
};
