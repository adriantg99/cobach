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
        Schema::create('cat_ciclos_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('ciclo_esc_id');
            $table->timestamp('p1')->nullable();
            $table->timestamp('fin_p1')->nullable();
            $table->timestamp('p2')->nullable();
            $table->timestamp('fin_p2')->nullable();
            $table->timestamp('p3')->nullable();
            $table->timestamp('fin_p3')->nullable();
            $table->timestamp('inicio_inscripcion')->nullable();
            $table->timestamp('fin_inscripcion')->nullable();
            $table->timestamp('inicio_semestre')->nullable();
            $table->timestamp('fin_semestre')->nullable();
            $table->timestamp('inicio_repeticion')->nullable();
            $table->timestamp('fin_repeticion')->nullable();
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
