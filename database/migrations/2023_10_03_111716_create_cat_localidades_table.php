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
        Schema::create('cat_localidades', function (Blueprint $table) {
            $table->id();
            $table->string('mapa', 10)->nullable(false)->unique()   ;
            $table->string('estatus', 10)->default(null);
            $table->string('cve_ent', 10)->nullable(false);
            $table->string('nom_ent', 20)->nullable(false);
            $table->string('nom_abr', 10)->nullable(false);
            $table->string('cve_mun', 10)->nullable(false);
            $table->string('nom_mun', 50)->nullable(false);
            $table->string('cve_loc', 10)->nullable(false);
            $table->string('nom_loc', 90)->nullable(false);
            $table->string('ambito', 10)->nullable(false);
            $table->string('latitud', 20)->nullable(false);
            $table->string('longitud', 20)->nullable(false);
            $table->decimal('lat_decimal', 20,6)->nullable(false);
            $table->string('lon_decimal', 20)->nullable(false);
            $table->smallInteger('altitud')->notNull();
            $table->string('cve_carta', 10)->nullable(false);
            $table->string('pob_total', 10)->default(NULL);
            $table->string('pob_masculina', 10)->default(NULL);
            $table->string('pob_femenina', 10)->default(NULL);
            $table->string('total_de_viviendas_habitadas', 10)->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_localidades');
    }
};
