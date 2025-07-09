<?php

namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Catalogos\Politica_variableModel;
use App\Models\Cursos\CursoModel;
use App\Models\Escolares\CalificacionTipoModel;


class CalificacionesModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "esc_calificacion";

    protected $fillable = [
        'alumno_id',			// BelongsTo
        'politica_variable_id',	// BelongsTo
        'calificacion_tipo_id',	// BelongsTo
        'curso_id',				// BelongsTo
        'faltas',
        'calificacion',
        'calif',
        'created_at',
        'updated_at',
        'calificacion_tipo',	// 1=Parcial, 2=Final
    ];

    /* Relations BelongsTo */

    public function alumno(): BelongsTo {
        return $this->belongsTo(AlumnoModel::class, 'alumno_id', 'id');
    }

    public function politica_variable(): BelongsTo {
        return $this->belongsTo(Politica_variableModel::class, 'politica_variable_id', 'id');
    }

    public function calificacion_tipo(): BelongsTo {
        return $this->belongsTo(CalificacionTipoModel::class, 'calificacion_tipo_id', 'id');
    }

    public function curso(): BelongsTo {
        return $this->belongsTo(CursoModel::class, 'curso_id', 'id');
    }

}