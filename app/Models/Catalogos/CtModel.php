<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtModel extends Model
{
    use HasFactory;

       /* Properties */
       protected $connection = "mysql";

       protected $table = "cat_bachilleratos_siged";


       protected $fillable = [
            'id',
            'ct',
            'nombre_ct',
            'entidad',
            'municipio',
            'localidad',
            'domicilio',
       ];
}
