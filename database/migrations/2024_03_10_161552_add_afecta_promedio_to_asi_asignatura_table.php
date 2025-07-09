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
        Schema::table('asi_asignatura', function (Blueprint $table) {
            $table->boolean('afecta_promedio')->nullable()->after('activa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asi_asignatura', function (Blueprint $table) {
            $table->dropColumn('afecta_promedio');
        });
    }
};
