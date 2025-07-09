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
        /* Schema::table('asi_Planestudio_asignatura', function (Blueprint $table) {
            //
        }); */
        Schema::create('asi_planestudio_asignatura', function (Blueprint $table) {
           $table->foreignId('id_planestudio')->nullable();
           $table->foreignId('id_asignatura')->nullable();

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
        /* Schema::table('asi_Planestudio_asignatura', function (Blueprint $table) {
            //
        }); */
        Schema::dropIfExists('asi_Planestudio_asignatura');
    }
};
