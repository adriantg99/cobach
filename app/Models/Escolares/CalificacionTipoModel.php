<?php

namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Escolares\CalificacionesModel;

class CalificacionTipoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "esc_calificacion_tipo";

    protected $fillable = [
        'nombre',	// ORDINARIO, REGULARIZACION, PASANTIA, ACTA ESCPECIAL, EQUIVALENCIA, EN LINEA, REVALIDACION, ACTA EXTRAORDINARIA, RECURSAMIENTO
        'prioridad',
    ];

    /* Relations HasMany */

    public function calificaciones(): HasMany {
        return $this->hasMany(CalificacionesModel::class, 'calificacion_tipo_id', 'id');
    }

}