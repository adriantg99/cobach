<?php
// ANA MOLINA 22/08/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoModel extends Model
{
    use HasFactory;

    protected $table = 'lug_estado';

    protected $fillable = [
        'nombre',
        'id_pais'

    ];

    public $timestamps = false;
}
