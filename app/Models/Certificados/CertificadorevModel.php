<?php
// ANA MOLINA 02/05/2024

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadorevModel extends Model
{
    use HasFactory;
    // ANA MOLINA 05/05/2024
    // la bitácora de revisión de certificados
    protected $table = 'esc_certificado_rev';

    protected $fillable = [
         'certificado_id',
         'user_id',
         'ip'

    ];

    public $timestamps = false;

}
