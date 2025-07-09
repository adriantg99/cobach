<?php
// ANA MOLINA 23/05/2024

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadovalaModel extends Model
{
    use HasFactory;
    // ANA MOLINA 05/05/2024
    // la bitácora de generación de certificados
    protected $table = 'esc_certificado_val_al';

    protected $fillable = [
        'id',
        'curp',
        'folio',
        'certificado_id',
        'alumno_id',
        'nombrealumno',
        'el_la',
        'user_id'
    ];

    public $timestamps = false;

}
