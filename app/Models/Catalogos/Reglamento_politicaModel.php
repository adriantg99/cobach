<?php
// ANA MOLINA 03/08/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reglamento_politicaModel extends Model
{
    use HasFactory;

    protected $table = "asi_reglamento_politica";
    protected $fillable = [
        'id_reglamento',
        'id_politica'
    ];

}
