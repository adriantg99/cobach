<?php
// ANA MOLINA 23/06/2023

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoModel extends Model
{
    use HasFactory;
    // ANA MOLINA 11/03/2024
    // la bitácora de emisión de ertificados
    protected $table = 'esc_certificado';

    protected $fillable = [
        'folio',
        'alumno_id',
        'curp',
        'user_id',
        'fecha_certificado',
        'original',
        'estatus',
        'ip',
        'nombrealumno',
        'fotocertificado',
        'vigente',
        'general_id',
        'autoridadeducativa',
        'numcertificadoautoridad',
        'sellodigitalautoridad',
        'plantel_id',
        'digital',
        'asignaturas',
        'promedio',
        'email',
        'emailtime'
    ];

    

}
