<?php
// ANA MOLINA 27/06/2023
namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoModel extends Model
{
    use HasFactory;

    protected $table = "esc_periodo";
    protected $fillable = [
        'nombre',
        'id_tipoperiodo'
    ];

}
