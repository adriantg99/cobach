<?php

namespace App\Models\Grupos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\TurnoModel;
use App\Models\Adminalumnos\AlumnoModel;

class GruposModel extends Model {

    /* Traits */
    
    use HasFactory;

    /* Properties */

    protected $table = 'esc_grupo';
    protected $fillable = [
        'turno_id',     // BelongsTo
        'plantel_id',   // BelongsTo WARNING: Pertenencia múltiple: GruposModel, PlandeEstudioModel y AulaModel
        'ciclo_esc_id', // BelongsTo
        'capacidad',
        'periodo',
        'aula_id',      // BelongsTo
        'nombre',
        'descripcion',
        'gpo_base',
    ];

    /* Relations BelongsTo */

    public function ciclo(): BelongsTo {
        return $this->belongsTo(CicloEscModel::class, 'ciclo_esc_id', 'id');
    }

    public function plantel(): BelongsTo {
        return $this->belongsTo(PlantelesModel::class, 'plantel_id', 'id');
    }

    public function turno(): BelongsTo {
        return $this->belongsTo(TurnoModel::class, 'turno_id', 'id');
    }

   /* public function aula(): BelongsTo {
        return $this->belongsTo(AulaModel::class, 'aula_id', 'id');
    }*/

    /* Relations HasMany */

    public function cursos(): HasMany {
        return $this->hasMany(CursosModel::class, 'grupo_id', 'id');
    }

    /* Relations BelongsToMany Pivot */

    public function alumnos(): BelongsToMany {
        return $this->belongsToMany(AlumnoModel::class, 'esc_grupo_alumno', 'grupo_id', 'alumno_id' )
            ->using(GrupoAlumnoModel::class)
            ->as('grupo_alumno')
            ->withTimestamps();
    }

 
    

}