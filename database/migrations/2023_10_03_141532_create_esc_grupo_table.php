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
        
        Schema::create('esc_grupo', function (Blueprint $table) {
            $table->id();
            $table->string('turno_id')->nullable();
            ////$table->foreignId('plantel_id')->references('id')->on('cat_plantel');
            $table->foreignId('plantel_id')->nullable(); //LS
            $table->bigInteger('ciclo_esc_id')->unsigned()->nullable();
            //$table->foreign('ciclo_esc_id')->references('id')->on('cat_ciclos_esc');
            $table->string('periodo')->nullable();
            $table->bigInteger('aula_id')->unsigned()->nullable();
            // Referencia la columna 'id' en lugar de la tabla 'cat_aula'
            //$table->foreign('periodo_id')->references('id')->on('esc_periodo');
            $table->string('nombre')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('capacidad')->nullable();
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
        Schema::dropIfExists('esc_grupo');
    }
};
