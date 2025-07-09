<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocentesCorreosModel extends Model
{
    use HasFactory;

    public $table = "cat_docentes_correos";

    public $fillable = [
        'email',
    ];
}
