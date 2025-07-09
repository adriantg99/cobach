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
        /* Schema::table('asi_Reglamento_Politica', function (Blueprint $table) {
            //
        }); */
        Schema::create('asi_reglamento_politica', function (Blueprint $table) {
           $table->integer('id_reglamento');
           $table->integer('id_politica');
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
        /* Schema::table('asi_Reglamento_Politica', function (Blueprint $table) {
            //
        }); */
        Schema::dropIfExists('asi_reglamento_politica');
    }
};
