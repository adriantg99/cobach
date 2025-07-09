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
        Schema::table('esc_calificacion', function (Blueprint $table) {
            $table->integer('faltas')->nullable()->after('curso_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esc_calificacion', function (Blueprint $table) {
            $table->dropColumn('faltas');
        });
    }
};
