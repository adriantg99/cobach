<?php
// ANA MOLINA 23/05/2024

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadovalModel extends Model
{
    use HasFactory;
    // ANA MOLINA 05/05/2024
    // la bitácora de generación de certificados
    protected $table = 'esc_certificado_val';

    protected $fillable = [
        'fecha_solicitud',
        'oficio',
        'entidad',
        'solicitante',
        'puesto',
        'email',
        'numoficio',
        'user_id',
        'enviado'
    ];

    public $timestamps = false;

}
