<?php
// ANA MOLINA 22/08/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NacionalidadModel extends Model
{
    use HasFactory;

    protected $table = 'lug_nacionalidad';

    protected $fillable = [
        'nombre'

    ];

    public $timestamps = false;
}
