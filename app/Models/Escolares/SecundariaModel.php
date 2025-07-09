<?php
// ANA MOLINA 22/08/2023
namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecundariaModel extends Model
{
    use HasFactory;

    protected $table = 'esc_Secundaria';

    protected $fillable = [
        'nombre'

    ];

    public $timestamps = false;
}
