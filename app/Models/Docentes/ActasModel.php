<?php

namespace App\Models\Docentes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActasModel extends Model
{
    use HasFactory;

    protected $table = "esc_actas";

    protected $fillable = [
        'alumno_id',
        'calificacion_id',
        'docente_id',
        'grupo_id',
        'curso_id',
        'user_aut_id',
        'calificacion_anterior',
        'nueva_calif',
        'nueva_falta',
        'motivo',
        'estado',
        'tipo_acta',
        'parcial',
    ];
}
