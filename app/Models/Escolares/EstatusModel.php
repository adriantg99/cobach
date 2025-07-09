<?php
// ANA MOLINA 04/09/2023
namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusModel extends Model
{
    use HasFactory;

    protected $table = 'cat_estatus';

    protected $fillable = [
        'nombre'
    ];

    public $timestamps = false;
}

