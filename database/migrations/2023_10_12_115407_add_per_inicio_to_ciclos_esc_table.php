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

    {Schema::table('esc_curso', function (Blueprint $table) {
        $table->foreignId('horario_id')->nullable();
    });
        /*Schema::table('cat_ciclos_esc', function (Blueprint $table) {
            $table->dateTime('per_inicio')->nullable()->after('nombre');
            $table->dateTime('per_final')->nullable()->after('per_inicio');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('cat_ciclos_esc', function (Blueprint $table) {
            $table->dropColumn('per_inicio');
            $table->dropColumn('per_final');
        });*/
    }
};
