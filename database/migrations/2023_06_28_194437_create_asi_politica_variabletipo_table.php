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
        Schema::create('asi_politica_variabletipo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->boolean('esletra');
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
        Schema::dropIfExists('asi_politica_variabletipo');

    }
};
