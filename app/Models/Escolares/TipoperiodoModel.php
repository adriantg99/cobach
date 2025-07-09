<?php
// ANA MOLINA 27/06/2023
namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoperiodoModel extends Model
{
    use HasFactory;

    protected $table = "esc_tipoperiodo";
    protected $fillable = [
        'nombre'
    ];

}
