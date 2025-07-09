<?php

namespace App\Models\Cursos;

use App\Models\Catalogos\PlandeEstudioModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\PerfilModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\DocenteModel;
use App\Models\Cursos\CursosOmitidosModel;
use App\Models\Grupos\GruposModel;
use App\Models\Escolares\CalificacionesModel;

class CursosModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "esc_curso";

    protected $fillable = [
        'plan_estudio_id',	// BelongsTo
        'asignatura_id',	// BelongsTo
        'docente_id',		// BelongsTo
        'grupo_id',			// BelongsTo
        'horario_id',
        'horas_semana',
        'curso_tipo',
        'nombre',
    ];

    /* Relations BelongsTo */

    public function grupo(): BelongsTo {
        return $this->belongsTo(GruposModel::class, 'grupo_id', 'id');
    }

    public function asignatura(): BelongsTo {
        return $this->belongsTo(AsignaturaModel::class, 'asignatura_id', 'id');
    }

    public function plan(): BelongsTo {
        return $this->belongsTo(PlandeEstudioModel::class, 'plan_estudio_id', 'id');
    }

    public function docente(): BelongsTo {
        return $this->belongsTo(DocenteModel::class, 'docente_id', 'id');
    }

    public function obtener_docente(): BelongsTo { // WARNING Este modelo es exactamente igual que DocenteModel
        return $this->belongsTo(PerfilModel::class, 'docente_id', 'id');
    }

    /* Relations HasMany */

    public function calificaciones(): HasMany {
        return $this->hasMany(CalificacionesModel::class, 'curso_id', 'id');
    }

    /* Methods */

    public function cantidad_alumnos() {
        $grupo_alumno = GrupoAlumnoModel::where('grupo_id', $this->grupo_id)->get();
        $cursos_omitiros = CursosOmitidosModel::where('curso_id', $this->id)->get();
        return count($grupo_alumno) - count($cursos_omitiros);
    }

     public function tiene_calificaciones()
    {
        $calif = DB::table('esc_calificacion')->select('id')->where('curso_id', $this->id)->get();
        if(count($calif)>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
