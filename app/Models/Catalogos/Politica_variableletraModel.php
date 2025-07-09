<?php
// ANA MOLINA 05/07/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Politica_variableletraModel extends Model
{
    use HasFactory;

    protected $table = "asi_variableletra";
    protected $fillable = [
        'descripcion',
        'valor'
    ];

}
