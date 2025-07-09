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
        Schema::create('cat_ciclos_esc', function (Blueprint $table) {
            $table->id();
            $table->string('abreviatura',20);
            $table->string('nombre',150);
            $table->date('per_inicio');
            $table->date('per_final');
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
        Schema::dropIfExists('cat_ciclos_esc');
    }
};
