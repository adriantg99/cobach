<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Catalogos\Aula_tipoModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Catalogos\Aula_condicionModel;
use App\Models\Grupos\GruposModel;

class AulaModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table="cat_aula";

    protected $fillable = [
        'plantel_id',           // BelongsTo WARNING: Pertenencia múltiple: GruposModel, PlandeEstudioModel y AulaModel
        'nombre',
        'tipo_aula_id',         // BelongsTo
        'condicion_aula_id',    // BelongsTo
        'aula_activa',
        'descripcion',
    ];

    /* Relations BelongsTo */
    
    public function plantel(): BelongsTo {
        return $this->belongsTo(PlantelesModel::class, 'plantel_id', 'id');
    }

    public function tipo_aula(): BelongsTo {
        return $this->belongsTo(Aula_tipoModel::class, 'tipo_aula_id','id');
    }

    public function condicion_aula(): BelongsTo {
        return $this->belongsTo(Aula_condicionModel::class, 'condicion_aula_id','id');
    }

    /* Relations HasOne */

    public function grupo(): HasOne {
        return $this->hasOne(GruposModel::class, 'aula_id', 'id');
    }

}