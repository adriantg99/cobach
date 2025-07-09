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
        Schema::table('fin_fichas', function (Blueprint $table) {
            $table->string('semestre',2)->nullable()->after('nombre_alumno');
            $table->dateTime('generada')->nullable()->after('semestre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fin_fichas', function (Blueprint $table) {
            $table->dropColumn('semestre');
            $table->dropColumn('generada');
        });
    }
};
