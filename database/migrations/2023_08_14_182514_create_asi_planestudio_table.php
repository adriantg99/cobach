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
        /* Schema::table('asi_Planestudio', function (Blueprint $table) {
            //
        }); */
        Schema::create('asi_planestudio', function (Blueprint $table) {
           $table->id();
           $table->foreignId('id_plantel')->nullable();
           $table->foreignId('id_reglamento')->nullable();
           $table->string('nombre',250)->nullable();

           //$table->string('descripcion',250);
           //$table->integer('totalperiodos');
           $table->string('descripcion',250)->nullable();
           $table->integer('totalperiodos')->nullable();

           $table->integer('totalasignaturas')->nullable();
           $table->boolean('activo')->nullable();

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
        /* Schema::table('asi_Planestudio', function (Blueprint $table) {
            //
        }); */
        Schema::dropIfExists('asi_planestudio');
    }
};
