<?php

namespace App\Models\Adminalumnos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formato_importarModel extends Model
{
    use HasFactory;

    protected $table = 'esc_formato_importar';

    protected $fillable = [
        'expediente',
        'nombre',
        'asignatura',
        'clave',
        'ciclo',
        'calificacion',
        'calif',
        'alumno_id',
        'plantel_id',
        'ciclo_esc_id',
        'asignatura_id',
        
    ];
}
