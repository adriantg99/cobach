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
            
        Schema::create('cat_docentes_apertura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_perfil_id');
            $table->foreignId('grupo_id');
            $table->double('parcial');
            $table->integer('activado');
            $table->timestamp('nuevo_cierre')->nullable();
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
        //
    }
};
