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
        Schema::create('asi_politica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_areaformacion')->nullable();
            $table->foreignId('id_variabletipo')->nullable();
            $table->string('nombre',100)->nullable();
            $table->string('descripcion',250)->nullable();
            $table->text('formula')->nullable();
            //$table->decimal('calificacionminima');
            $table->decimal('calificacionminima')->nullable();
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
        Schema::dropIfExists('asi_politica');
    }
};
