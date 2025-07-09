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
        Schema::table('cat_ciclos_esc', function (Blueprint $table) {
            $table->boolean('activo')->nullable()->after('per_final');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cat_ciclos_esc', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};
