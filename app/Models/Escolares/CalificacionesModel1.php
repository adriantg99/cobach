<?php

namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionesNoEncontrados extends Model
{
    use HasFactory;

    protected $table = "esc_calificacion";

    protected $fillable = [
        'alumno_id',
        'alumno_id',
        'politica_variable_id',
        'calificacion_tipo_id',
        'curso_id',
        'calificacion',
        'calif ',
        'created_at',
        'updated_at',
        'calificacion_tipo',

    ];

}
