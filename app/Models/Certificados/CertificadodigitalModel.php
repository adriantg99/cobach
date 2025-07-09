<?php
// ANA MOLINA 13/06/2024

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadodigitalModel extends Model
{
    use HasFactory;
    // ANA MOLINA 23/06/2023
    // la bitácora se movió a otra base de datos
    protected $connection="mysql_doctosalumnos";
    // ANA MOLINA 23/06/2023
    //se agregó prefijo a la bitácora
    protected $table = 'certificados';

    protected $fillable = [
        'id',
        'certificado_digital',
        'vigente',
        'certificadoscol',
    ];


    public $timestamps = false;
}
