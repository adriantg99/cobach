<?php

namespace App\Models\escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BajasAlumnos extends Model
{
    use HasFactory;

    protected $table = "esc_bajas_alumnos";

    protected $fillable = [
        'id',
        'alumno_id',
        'ciclo_esc_id',
        'motivo',
        'user_id',
    ];
}
