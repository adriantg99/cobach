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
        Schema::create('cat_hora_plantel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantel_id');
            $table->foreignId('turno_id');
            $table->time('hr_inicio');
            $table->time('hr_fin');
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
        Schema::dropIfExists('cat_hora_plantel');
    }
};
