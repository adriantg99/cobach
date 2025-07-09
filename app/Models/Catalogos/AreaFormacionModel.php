<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Catalogos\PoliticaModel;
use App\Models\Catalogos\AsignaturaModel;

class AreaFormacionModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_areaformacion";

    protected $fillable = [
        'nombre' // Formación Común, Formación Específica, Especialidad, Especialidad EMSAD, Extracurricular, Formación Integral, Formación Integral Complementaria
    ];

    /* Relations HasMany WARNING: Pertenencia múltiple con PoliticaModel y AsignaturaModel */

    public function politicas(): HasMany {
        return $this->hasMany(PoliticaModel::class, 'id_areaformacion', 'id');
    }

    public function asignaturas(): HasMany {
        return $this->hasMany(AsignaturaModel::class, 'id_areaformacion', 'id');
    }

    public function nucleos(): HasMany {
        return $this->hasMany(NucleoModel::class, 'areaformacion_id', 'id');
    }

}