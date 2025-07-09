<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPlanteleModel extends Model
{
    use HasFactory;

    protected $table = "emp_perfil_plantele";

    protected $fillable = [
        'perfil_id',
        'plantel_id',
    ];
}
