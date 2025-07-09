<?php
// ANA MOLINA 05/07/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Politica_variableletradetModel extends Model
{
    use HasFactory;

    protected $table = "asi_politica_variableletra";
    protected $fillable = [
        'id_politicavariable',
        'id_variableletra'
    ];

}
