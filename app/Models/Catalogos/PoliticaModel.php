<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Catalogos\AreaFormacionModel;
use App\Models\Catalogos\Politica_variabletipoModel;
use App\Models\Catalogos\Politica_variableModel;

class PoliticaModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_politica";

    protected $fillable = [
        'id_areaformacion',		// BelongsTo WARNING: También aparece en AsignaturaModel
        'id_variabletipo',		// BelongsTo: decimales, letras
        'nombre',
        'descripcion',
        'formula',				// formula para calcular la calificacion
        'calificacionminima'	// calificacion minima para aprobar
    ];

    /* Relations BelongsTo */

    public function area_formacion(): BelongsTo {
        return $this->belongsTo(AreaFormacionModel::class, 'id_areaformacion', 'id');
    }

    public function variable_tipo(): BelongsTo {
        return $this->belongsTo(Politica_variabletipoModel::class, 'id_variableperiodo', 'id');
    }

    /* Relations HasMany */

    public function politica_variables(): HasMany {
        return $this->hasMany(Politica_variableModel::class, 'id_politica', 'id');
    }

}