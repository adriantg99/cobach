<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstanciasModel extends Model
{
    use HasFactory;

    protected $table = 'esc_constancias';


    protected $fillable = [
        'alumno_id',
        'curp',
        'noexpediente',
        'ciclo_esc_id',
        'plantel_id_emite',
        'created_at',
        'updated_at',
    ];
}
