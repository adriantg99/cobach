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
        Schema::table('emp_perfil', function (Blueprint $table) {
            $table->integer('num_planteles')->nullable();  
            $table->boolean('perfil_finalizado')->nullable()->after('num_planteles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_perfil', function (Blueprint $table) {
            $table->dropColumn('num_planteles');
            $table->dropColumn('perfil_finalizado');
        });
    }
};
