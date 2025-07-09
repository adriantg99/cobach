<?php
// ANA MOLINA 04/09/2023
namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BecaModel extends Model
{
    use HasFactory;

    protected $table = 'esc_beca';

    protected $fillable = [
        'nombre'
    ];

    public $timestamps = false;
}

