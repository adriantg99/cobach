<?php
// ANA MOLINA 04/09/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtniaModel extends Model
{
    use HasFactory;

    protected $table = 'cat_etnia';

    protected $fillable = [
        'nombre'
    ];

    public $timestamps = false;
}

