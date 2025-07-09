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
        Schema::create('asi_politica_variable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_politica')->nullable();
            $table->foreignId('id_variableperiodo')->nullable();
            $table->boolean('esregularizacion')->nullable();
            $table->boolean('eslimite')->nullable();
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
        Schema::dropIfExists('asi_politica_variable');

    }
};
