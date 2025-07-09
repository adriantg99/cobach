<?php

namespace App\Models\Cursos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoHoraModel extends Model
{
    use HasFactory;

    protected $table = "esc_curso_hora";

    protected $fillable = [
        'curso_id',
        'dia_semana',
        'hora_plantel_id',
    ];
}
