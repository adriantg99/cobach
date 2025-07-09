<?php

namespace App\Models\escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcepcionCertificadosModel extends Model
{
    use HasFactory;

    protected $table = "esc_excepciones_certificados";

    protected $fillable = [
        'id',
        'alumno_id',
        'periodo',
        'ciclo_esc_id',
        'created_at',
        'updated_at',
    ];
    
}
