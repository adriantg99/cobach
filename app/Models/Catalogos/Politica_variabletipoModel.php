<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Catalogos\PoliticaModel;

class Politica_variabletipoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_politica_variabletipo";

    protected $fillable = [
        'nombre',	//decimales, letras
        'esletra'	// 0=No, 1=Si
    ];

    /* Relations HasMany */

    public function politicas(): HasMany {
        return $this->hasMany(PoliticaModel::class, 'id_variableperiodo', 'id');
    }

}