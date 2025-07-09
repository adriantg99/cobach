<?php

namespace App\Models\Cursos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursosOmitidosModel extends Model
{
    use HasFactory;

    protected $table = "esc_cursos_omitidos";

    protected $fillable = [
        'curso_id',
        'alumno_id',
        'motivo',
    ];

    public $timestamps = false;
}
