<?php

namespace App\Models\Documentos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotosModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql_doctosalumnos';

    protected $table = 'documentos_alumnos';

}
