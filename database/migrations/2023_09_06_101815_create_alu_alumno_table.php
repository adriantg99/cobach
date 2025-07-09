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
        Schema::create('alu_alumno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_plantel');
            $table->foreignId('id_cicloesc');
            $table->foreignId('id_planestudio')->nullable();
            $table->string('noexpediente',20);
            $table->string('nombre',100);
            $table->string('apellidos',100);
            $table->string('apellidopaterno',100);
            $table->string('apellidomaterno',100)->nullable();           // $table->string('nombre',100);
            $table->string('domicilio',250);
            $table->string('domicilio_entrecalle',120)->nullable();
            $table->string('domicilio_nointerior',20)->nullable();
            $table->string('domicilio_noexterior',20)->nullable();
            $table->string('colonia',100);
            $table->string('codigopostal',10);
            $table->string('telefono',50)->nullable();
            $table->string('celular',50);
            $table->string('email',300)->nullable();
            $table->date('fechanacimiento');
            $table->integer('edad');
            $table->string('sexo',1);
            $table->decimal('estatura',4,1);
            $table->decimal('peso',6,3);
            $table->string('curp',30);
            $table->integer('id_periodo');
            $table->date('fecharegistro');
            $table->date('fechabaja')->nullable();
            $table->integer('id_secundaria_procedencia');
            $table->string('secundaria_nombre',250);
            $table->string('secundaria_clave',30);
            $table->decimal('secundaria_promedio',5,2);
            $table->date('secundaria_fechaegreso');
            $table->text('observaciones')->nullable();
            $table->boolean('alergias');
            $table->string('alergias_describe',500)->nullable();
            $table->string('meds_permit',500)->nullable();
            $table->string('discapacidad_describe',500)->nullable();

            $table->string('tiposangre',15);
            $table->string('tutor_nombre',300);
            $table->string('tutor_apellido1',150)->nullable();
            $table->string('tutor_apellido2',150)->nullable();
            $table->string('tutor_email',100);
            $table->string('tutor_domicilio',100);
            $table->string('tutor_colonia',50);
            $table->string('tutor_telefono',35)->nullable();
            $table->string('tutor_ocupacion',100);
            $table->string('tutor_celular',35);
            $table->string('familiar_nombre',150)->nullable();
            $table->string('familiar_apellido1',150)->nullable();
            $table->string('familiar_apellido2',150)->nullable();
            $table->string('familiar_celular',35)->nullable();
            $table->string('familiar_email',100)->nullable();
            $table->integer('id_nacionalidad');
            $table->integer('id_paisnacimiento');
            $table->integer('id_localidadnacimiento');
            $table->integer('id_localidaddomicilio');
            $table->integer('id_lugarnacimiento');
            $table->boolean('turno_especial');
            $table->integer('id_beca');
            $table->string('beca_otra',100)->nullable();
            $table->integer('id_servicio_medico');
            $table->string('servicio_medico_otro',105)->nullable();
            $table->string('servicio_medico_afiliacion',100)->nullable();
            $table->boolean('deudas_finanzas');
            $table->text('deudas_finanzas_desc')->nullable();
            $table->boolean('deudas_biblioteca');
            $table->text('deudas_biblioteca_desc')->nullable();
            $table->string('empresa_nombre',150)->nullable();
            $table->string('empresa_domicilio',150)->nullable();
            $table->string('empresa_telefono',35)->nullable();
            $table->string('empresa_colonia',50)->nullable();
            $table->string('tutor_empresa_nombre',100)->nullable();
            $table->string('tutor_empresa_domicilio',150)->nullable();
            $table->string('tutor_empresa_telefono',35)->nullable();
            $table->string('tutor_empresa_colonia',50)->nullable();
            $table->integer('id_discapacidad');
            $table->string('enfermedad',500)->nullable();
            $table->integer('id_etnia');
            $table->boolean('lengua_indigena');
            $table->string('lengua_indigena_desc',100)->nullable();
            $table->boolean('extranjero_padre_mexicano');
            $table->boolean('extranjero_grado_ems');
            $table->boolean('extranjero_habla_espanol');
            $table->boolean('extranjero_escribe_espanol');
            $table->boolean('extranjero_lee_espanol');
            $table->integer('id_extranjero_paisnacimiento');
            $table->integer('id_extranjero_paisestudio');

            $table->string('correo_institucional',300)->nullable();

            $table->string('contrasena',50)->nullable();
            $table->integer('id_estatus');

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
        Schema::dropIfExists('alu_alumno');
    }
};
