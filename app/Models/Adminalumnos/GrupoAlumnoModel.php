<?php

namespace App\Models\Adminalumnos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Grupos\GruposModel;

class GrupoAlumnoModel extends Pivot {

    /* Traits */
    
    use HasFactory;

    /* Properties */

    protected $table = "esc_grupo_alumno";
    
    protected $fillable = [
        'id',
        'grupo_id',
        'alumno_id',
    ];

    public $incrementing = true;

    /* Relations BelongsTo */

    public function alumno(): BelongsTo {
        return $this->belongsTo(AlumnoModel::class, 'alumno_id', 'id'); //return $this->belongsTo(User::class, 'foreign_key', 'local_key'); return $this->belongsTo(User::class, 'class_id', 'id');
    }

    public function grupo(): BelongsTo {
        return $this->belongsTo(GruposModel::class, 'grupo_id', 'id' );
    }

}