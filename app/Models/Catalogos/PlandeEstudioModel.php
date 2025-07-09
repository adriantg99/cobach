<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Cursos\CursosModel;
use App\Models\PlantelesModel;
use App\Models\Catalogos\ReglamentoModel;

class PlandeEstudioModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_planestudio";

    protected $fillable = [
        'id_plantel',		// BelongsTo WARNING: Pertenencia múltiple: GruposModel, PlandeEstudioModel y AulaModel
        'id_reglamento',	// BelongsTo
        'nombre',			// 17 diferentes planes de estudio
        'descripcion',
        'totalperiodos',	// 6
        'totalasignaturas',
        'activo',			// 0=No, 1=Si
    ];

    /* Relations BelongsTo */

    public function plantel(): BelongsTo {
        return $this->belongsTo(PlantelesModel::class, 'id_plantel', 'id');
    }

    public function reglamento(): BelongsTo {
        return $this->belongsTo(ReglamentoModel::class, 'id_reglamento', 'id');
    }

    /* Relations HasMany */

    public function cursos(): HasMany {
        return $this->hasMany(CursosModel::class, 'plan_estudio_id', 'id');
    }

}