<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\CalificacionesModel;
use App\Models\Catalogos\PoliticaModel;
use App\Models\Catalogos\Politica_variableperiodoModel;

class Politica_variableModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_politica_variable";

    protected $fillable = [
        'id_politica', 			// BelongsTo
        'id_variableperiodo',	// BelongsTo
        'esregularizacion',		// 0=No, 1=Si
        'eslimite' 				//ultimo parcial a evaluar
    ];

    /* Relations BelongsTo */

    public function politica(): BelongsTo {
        return $this->belongsTo(PoliticaModel::class, 'id_politica', 'id');
    }

    public function variable_periodo(): BelongsTo {
        return $this->belongsTo(Politica_variableperiodoModel::class, 'id_variableperiodo', 'id');
    }

    /* Relations HasMany */

    public function calificaciones(): HasMany {
        return $this->hasMany(CalificacionesModel::class, 'politica_variable_id', 'id');
    }

}