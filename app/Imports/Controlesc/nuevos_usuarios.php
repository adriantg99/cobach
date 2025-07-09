<?php

namespace App\Imports\Controlesc;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;

class nuevos_usuarios implements ToModel, WithHeadingRow, WithValidation
{
    use SkipsErrors;

    private $plantelId;
    private $existingNoExpedientes = [];

    public function __construct($plantelId)
    {
        $this->plantelId = $plantelId;
        // Cargar todos los noexpedientes existentes al inicio
        $this->existingNoExpedientes = AlumnoModel::pluck('noexpediente')->toArray();
    }

    public function model(array $row)
    {
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '900'); // 2 minutos
        $buscar_alumno = AlumnoModel::where('curp', $row['curp'])->first();

        if ($buscar_alumno) {
            return null;
        }
        $yearDigits = date('y'); // "24" para 2024
        $plantelId = PlantelesModel::where('cct', $row["clave_ct"])->first();
        //$plantelId = str_pad($row['clave_ct'], 2, '0', STR_PAD_LEFT); // Asegúrate que el plantel_id tenga dos dígitos
        if($plantelId->id < 10){
            $plantel_id = "0".$plantelId->id;
        }
        else{
            $plantel_id = $plantelId->id;
        }
        // Construir el patrón de búsqueda
        $pattern = $yearDigits . $plantel_id . '%';
        // Buscar en la base de datos solo una vez
        $lastRecord = AlumnoModel::where('noexpediente', 'like', $pattern)
            ->orderBy('noexpediente', 'desc')
            ->first();

        if ($lastRecord) {
            // Obtener los cuatro dígitos siguientes y añadir 1
            $lastNumber = intval(substr($lastRecord->noexpediente, -4)) + 1;
        } else {
            // Empezar en "0001" si no hay registros
            $lastNumber = 1;
        }

        // Formatear el nuevo número a cuatro dígitos
        $newNumber = str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

     
        
        // Construir el nuevo valor de noexpediente
        $noExpediente = $yearDigits . $plantel_id . $newNumber;
        $apellidos = $row['apellidopaterno']. " ". $row['apellidomaterno'];
        // Verificar si el noexpediente ya existe
        if (in_array($noExpediente, $this->existingNoExpedientes)) {
            Log::warning('No expediente repetido encontrado en la fila', [
                'row' => $row,
                'noexpediente' => $noExpediente
            ]);
            return null; // No procesar este registro
        }
        $fecha = Carbon::now();

        $letra_nombre = substr($row['nombre'], 0, 1);
        $letra_ap = substr($row['apellidopaterno'], 0, 1);
        $correo_institucional = $letra_nombre . $letra_ap . $noExpediente . "@bachilleresdesonora.edu.mx";

        // Agregar el noexpediente a la lista de existentes
        $this->existingNoExpedientes[] = $noExpediente;

        return new AlumnoModel([
            'folio_prepason' => $row['folio_prepason'],
            'planestudio_id' => $row['planestudio_id'],
            'cicloesc_id' => $row['cicloesc_id'],
            'plantel_id' => $plantelId->id,
            'nombre' => $row['nombre'],
            'curp' => $row['curp'],
            'domicilio' => $row['domicilio'],
            'apellidos' => $apellidos,
            'apellidopaterno' => $row['apellidopaterno'],
            'apellidomaterno' => $row['apellidomaterno'],
            'correo_institucional' => $correo_institucional,
            'tutor_nombre' => $row['tutor_nombre'],
            'tutor_apellido' => $row['tutor_apellido'],
            'tutor_telefono' => $row['tutor_telefono'],
            'noexpediente' => $noExpediente, // Guardar el nuevo valor de noexpediente
            'domicilio_entrecalle' => $row['domicilio_entrecalle'],
            'domicilio_nointerior' => $row['domicilio_nointerior'],
            'domicilio_noexterior' => $row['domicilio_noexterior'],
            'colonia' => $row['colonia'],
            'codigopostal' => $row['codigopostal'],
            'telefono' => $row['telefono'],
            'celular' => $row['celular'],
            'email' => $row['correo_personal'],
            'fechanacimiento' => $row['fechanacimiento'],
            'edad' => $row['edad'],
            'sexo' => $row['sexo'],
            'estatura' => $row['estatura'],
            'peso' => $row['peso'],
            'fecharegistro' => $fecha,
            'fechabaja' => $row['fechabaja'],
            'secundaria_nombre' => $row['secundaria_nombre'],
            'secundaria_clave' => $row['secundaria_clave'],
            'secundaria_promedio' => $row['secundaria_promedio'],
            'observaciones' => $row['observaciones'],
            'alergias_describe' => $row['alergias_describe'],
            'meds_permit' => $row['meds_permit'],
            'discapacidad_describe' => $row['discapacidad_describe'],
            'tiposangre' => $row['tiposangre'],
            'tutor_apellido1' => $row['tutor_apellido1'],
            'tutor_apellido2' => $row['tutor_apellido2'],
            'tutor_email' => $row['tutor_email'],
            'tutor_domicilio' => $row['tutor_domicilio'],
            'tutor_colonia' => $row['tutor_colonia'],
            'tutor_ocupacion' => $row['tutor_ocupacion'],
            'tutor_celular' => $row['tutor_celular'],
            'familiar_nombre' => $row['familiar_nombre'],
            'familiar_apellido1' => $row['familiar_apellido1'],
            'familiar_apellido2' => $row['familiar_apellido2'],
            'familiar_celular' => $row['familiar_celular'],
            'familiar_email' => $row['familiar_email'],                        
            'servicio_medico_afiliacion' => $row['servicio_medico_afiliacion'],
            'deudas_finanzas_desc' => $row['deudas_finanzas_desc'],
            'deudas_biblioteca_desc' => $row['deudas_biblioteca_desc'],
            'tutor_empresa_domicilio' => $row['tutor_empresa_domicilio'],
            'tutor_empresa_telefono' => $row['tutor_empresa_telefono'],
            'tutor_empresa_colonia' => $row['tutor_empresa_colonia'],
            'enfermedad' => $row['enfermedad'],            
        ]);
    }

    public function rules(): array
    {
        return [
            //'*.plantel_id' => ['required', 'integer'],
            '*.curp' => ['required'],
            '*.nombre' => ['required', 'string'],
            '*.apellidopaterno' => ['required', 'string'],
            //'*.apellidomaterno' => ['required', 'string'],
            '*.correo_personal' => ['nullable', 'string'],
           // '*.domicilio' => ['nullable', 'string'],
            //'*.tutor_nombre' => ['nullable', 'string'],
            //'*.tutor_apellido' => ['nullable', 'string'],
            //'*.tutor_telefono' => ['nullable', 'string'],
            //'*.tiposangre' => ['nullable', 'string'],
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error('Validation failure', [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ]);
        }
    }
}
