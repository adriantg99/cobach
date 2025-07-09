<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioModel extends Model
{
    use HasFactory;

    protected $table = "cat_horarios";

    protected $fillable = [
        'id',
        'dia',
        'hora'
    ];
}
