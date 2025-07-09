<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Catalogos\Politica_variableModel;

class Politica_variableperiodoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_variableperiodo";
    
    protected $fillable = [
        'nombre',	// Parcial 1, Parcial 2, Parcial 3, Regularizacion
        'descripcion',
        'orden'
    ];

    /* Relations HasMany */

    public function politica_variables(): HasMany {
        return $this->hasMany(Politica_variableModel::class, 'id_variableperiodo', 'id');
    }

}