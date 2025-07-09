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
        Schema::table('fin_fichas_detalle', function (Blueprint $table) {
            $table->string('matricula',20)->nullable()->after('folio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fin_fichas_detalle', function (Blueprint $table) {
            $table->dropColumn('matricula');
        });
    }
};
