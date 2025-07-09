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
        Schema::create('cat_plantel', function (Blueprint $table) {
            $table->id();
            $table->string('abreviatura', 10)->unique();
            $table->string('nombre', 100);
            $table->string('cct', 10);
            $table->string('cve_mun', 10);
            $table->string('municipio', 50);
            $table->string('cve_loc', 10);
            $table->string('localidad', 50);
            $table->string('domicilio', 110)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('zona', 10);
            $table->string('coordenadas', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('director', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('correo_director', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('telefono_director', 20);
            $table->string('subdirector', 100);
            $table->string('correo_subdirector', 100);
            $table->string('telefono_subdirector', 20);
            $table->string('plan_domicilio', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('plan_colonia', 20);
            $table->string('plan_telefono', 20)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('plan_codigopostal', 20)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('plan_email', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('plan_acceso', 2000)->nullable();
            $table->dateTime('created_at')->nullable(false);
            $table->dateTime('updated_at')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_plantel');
    }
};
