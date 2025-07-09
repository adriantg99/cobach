<?php
// ANA MOLINA 22/08/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipioModel extends Model
{
    use HasFactory;

    protected $table = 'lug_municipio';

    protected $fillable = [
        'nombre',
        'id_estado'

    ];

    public $timestamps = false;
}
