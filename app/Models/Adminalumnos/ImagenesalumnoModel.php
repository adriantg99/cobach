<?php

namespace App\Models\Adminalumnos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Adminalumnos\AlumnoModel;

class ImagenesalumnoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    // ANA MOLINA 23/06/2023
    // la bitácora se movió a otra base de datos

    protected $connection="mysql_doctosalumnos";
    // ANA MOLINA 23/06/2023
    //se agregó prefijo a la bitácora

    protected $table = 'imagenes';

    protected $fillable = [
        'id',
        'imagen',
        'no_expediente',
        'alumno_id',
        'filename',
        'filesize',
        'tipo',
    ];

    /* Relations BelongsTo */

    public function alumno(): BelongsTo {
        return $this->belongsTo(AlumnoModel::class, 'alumno_id', 'id');
    }

}