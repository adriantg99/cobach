<?php
// ANA MOLINA 04/09/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscapacidadModel extends Model
{
    use HasFactory;

    protected $table = 'cat_discapacidad';

    protected $fillable = [
        'nombre'
    ];

    public $timestamps = false;
}

