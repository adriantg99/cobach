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
        Schema::create('esc_certificado_can', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificado_id');
            $table->string('motivo', 200);
            $table->integer('user_id');
            $table->string('ip', 250);
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
        Schema::dropIfExists('esc_certificado_can');
    }
};

