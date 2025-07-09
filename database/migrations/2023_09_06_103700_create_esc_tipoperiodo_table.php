<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esc_tipoperiodo', function (Blueprint $table) {
            $table->integer('id')->unique(); //permite guardar el id 0 (sin datos) para que funcionen las referencias cuando se marca el id:0
            $table->string('nombre', 15);

        });

        // Agregar la restricción de clave foránea después de crear la tabla
        /*
        Schema::table('esc_periodo', function (Blueprint $table) {
            $table->foreignId('tipoperiodo_id');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esc_tipoperiodo');
    }
};
