<?php
// ANA MOLINA 22/08/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalidadModel extends Model
{
    use HasFactory;

    protected $table = 'lug_localidad';

    protected $fillable = [
        'nombre',
        'id_municipio'

    ];

    public $timestamps = false;
}
