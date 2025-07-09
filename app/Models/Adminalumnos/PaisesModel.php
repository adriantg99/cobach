<?php

namespace App\Models\Adminalumnos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaisesModel extends Model
{
    use HasFactory;

    protected $table = 'cat_paises_posibles';
    protected $fillable = [
        'id',
        'pais',
    ];

}
