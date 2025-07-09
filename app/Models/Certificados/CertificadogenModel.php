<?php
// ANA MOLINA 23/05/2024

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadogenModel extends Model
{
    use HasFactory;
    // ANA MOLINA 05/05/2024
    // la bitácora de generación de certificados
    protected $table = 'esc_certificado_gen';

    protected $fillable = [
         'certificado_id',
         'user_id',
         'ip',
         'generado'

    ];

    public $timestamps = false;

}
