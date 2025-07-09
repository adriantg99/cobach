<?php

namespace App\Models\Docentes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AperturaModel extends Model
{
    use HasFactory;
    protected $table = "cat_docentes_apertura";

    protected $fillable = [
        'id',
        'emp_perfil_id',
        'grupo_id',
        'parcial',
        'activado',
        'ciclos_esc_id',
        'nuevo_cierre',
        'created_at',
        'updated_at',
    ];
}
