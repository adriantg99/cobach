<?php
// ANA MOLINA 22/08/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaisModel extends Model
{
    use HasFactory;

    protected $table = 'lug_pais';

    protected $fillable = [
        'nombre'

    ];

    public $timestamps = false;
}
