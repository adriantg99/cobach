<?php
// ANA MOLINA 21/08/2023
namespace App\Http\Requests\Adminalumnos;

use Illuminate\Foundation\Http\FormRequest;

class AlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
        'id_plantel' =>  'required',
        'id_cicloesc' =>  'required',
        'id_planestudio' =>  'required',
        'noexpediente' =>  'max:8',
        'nombre' =>  'required|max:100',
        'apellidos' =>  'required|max:100',
        'apellidopaterno' =>  'required|max:100',
        'apellidomaterno' =>  'max:100',
        'domicilio' =>  'required|max:150',
        'domicilio_entrecalle' =>  'max:120',
        'domicilio_nointerior' =>  'max:10',
        'domicilio_noexterior' =>  'max:10',
        'colonia' =>  'required|max:50',
        'codigopostal' =>  'required|max:10',
        'telefono' =>  'max:35',
        'celular' =>  'required|max:35',
        'email' =>  'max:70',
        'fechanacimiento' =>  'required',
        'edad' =>  'required',
        'sexo' =>  'required|max:1',
        'estatura' =>  '',
        'peso' =>  '',
        'curp' =>  'required|max:20',
        'id_periodo' =>  'required',
        'fecharegistro' =>  'required',
        'id_secundaria_procedencia' => 'required',
        'secundaria_nombre' =>  'required|max:100',
        'secundaria_clave' =>  'required|max:30',
        'secundaria_promedio' =>  'required',
        'secundaria_fechaegreso' =>  'required',
        'observaciones' =>  '',
        'alergias' =>  'required',
        'alergias_describe' =>  'max:250',
        'tiposangre' =>  'required|max:5',
        'tutor_nombre' =>  'required|max:100',
        'tutor_domicilio' =>  'required|max:100',
        'tutor_colonia' =>  'max:50',
        'tutor_telefono' =>  'max:35',
        'tutor_ocupacion' =>  'required|max:100',
        'tutor_celular' =>  'required|max:35',
        'madre_nombre'  =>  'max:100',
        'madre_celular'  =>  'max:35',
        'id_nacionalidad' =>  'required',
        'id_lugarnacimiento' =>  'required',
        'id_paisnacimiento' =>  'required',
        'id_localidadnacimiento' =>  'required',
        'id_localidaddomicilio' =>  'required',
        'turno_especial' =>  'required',
        'id_beca' =>  'required',
        'beca_otra' =>  'max:100',
        'id_servicio_medico' =>  'required',
        'servicio_medico_otro' =>  'max:100',
        'servicio_medico_afiliacion' =>  'max:35',
        'empresa_nombre'=>'max:100',
        'empresa_domicilio'=>'max:150',
        'empresa_telefono'=>'max:35',
        'empresa_colonia'=>'max:50',
        'tutor_empresa_nombre'=>'max:100',
        'tutor_empresa_domicilio'=>'max:150',
        'tutor_empresa_telefono'=>'max:35',
        'tutor_empresa_colonia'=>'max:50',
        'id_discapacidad' =>  'required',
        'enfermedad' =>  'max:120',
        'id_etnia' =>  'required',
        'lengua_indigena' =>  'required',
        'lengua_indigena_desc' =>  'max:100',
        'extranjero_padre_mexicano' =>  '',
        'extranjero_grado_ems' =>  '',
        'extranjero_habla_espanol'=>'',
        'extranjero_escribe_espanol'=>'',
        'extranjero_lee_espanol'=>'',
        'id_extranjero_paisnacimiento'=>'',
        'id_extranjero_paisestudio'=>'',
        'id_estatus'=>'required',
        ];
    }
}
