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
        Schema::table('esc_grupo', function (Blueprint $table) {
            $table->boolean('gpo_base')->nullable()->after('capacidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esc_grupo', function (Blueprint $table) {
            $table->dropColumn('gpo_base');
        });
    }
};
