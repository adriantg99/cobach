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
        //Borrado de la tabla bitacora antes de la creación de la misma 
        Schema::connection('mysql_bitacora')->dropIfExists('adm_bitacora');
        //Crear la base de datos bitacora en la base de datos de bitacora 23-20-05
        Schema::connection('mysql_bitacora')->create('adm_bitacora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('ip',250)->nullable();
            $table->string('path',250)->nullable();
            $table->string('method',250)->nullable();
            $table->string('controller',250)->nullable();
            $table->string('component',250)->nullable();
            $table->string('function',250)->nullable();
            $table->string('description',1000)->nullable();
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
        Schema::connection('mysql_bitacora')->dropIfExists('adm_bitacora');
    }
};
