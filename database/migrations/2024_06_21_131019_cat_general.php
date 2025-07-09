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
        Schema::create('cat_general', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('nombre', 100);
            $table->string('rfc', 20);
            $table->string('titulo', 10);
            $table->string('ciudad', 100);
            $table->string('efirma_nombre', 200);
            $table->string('efirma_password', 30);
            $table->string('efirma_file_certificate', 100);
            $table->string('efirma_file_key', 100);
            $table->date('fechainicio');
            $table->date('fechafinal');
            $table->integer('user_modif');
            $table->boolean('directorgeneral');
            $table->string('numcertificado', 100);
            $table->string('sellodigital', 500);
            $table->date('desde');
            $table->date('hasta');

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
        Schema::dropIfExists('cat_general');
    }
};
