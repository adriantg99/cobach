<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\PlandeEstudioModel;
use App\Models\Catalogos\AulaModel;

class PlantelesModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "cat_plantel";

    protected $fillable = [
        'abreviatura',	// 34 planteles en base de datos
        'nombre',
        'cct',
        'cve_mun',
        'municipio',
        'cve_loc',
        'localidad',
        'domicilio',
        'telefono',
        'zona',
        'coordenadas',
        'director',
        'correo_director',
        'telefono_director',
        'subdirector',
        'correo_subdirector',
        'telefono_subdirector',
        'plan_domicilio',
        'plan_colonia',
        'plan_telefono',
        'plan_codigopostal',
        'plan_email',
        'plan_acceso',
    ];

    /* Relations HasMany WARNING: Pertenencia múltiple: GruposModel, PlandeEstudioModel y AulaModel */

    public function grupos(): HasMany {
        return $this->hasMany(GruposModel::class, 'plantel_id', 'id');
    }

    public function planes(): HasMany {
        return $this->hasMany(PlandeEstudioModel::class, 'id_plantel', 'id');
    }

    public function aulas(): HasMany {
        return $this->hasMany(AulaModel::class, 'plantel_id', 'id');
    }

}