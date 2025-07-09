<?php
// ANA MOLINA 06/05/2024
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralModel extends Model
{
    use HasFactory;

    protected $table = 'cat_general';

    protected $fillable = [
        'user_id',
        'nombre',
        'rfc',
        'titulo',
        'ciudad',
        'efirma_nombre',
        'efirma_password',
        'efirma_file_certificate',
        'efirma_file_key',
        'fechainicio',
        'fechafinal',
        'directorgeneral',
        'numcertificado',
        'sellodigital',
        'desde',
        'hasta',
        'user_modif'
    ];
}

