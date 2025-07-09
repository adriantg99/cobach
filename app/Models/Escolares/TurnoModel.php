<?php

namespace App\Models\Escolares;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Grupos\GruposModel;

class TurnoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = 'cat_turno';

    protected $fillable = [
        'nombre',		// Matutino, Vespertino, Nocturno
        'abreviatura',
    ];

    /* Relations HasMany */

    public function grupos(): HasMany {
        return $this->hasMany(GruposModel::class, 'turno_id','id');
    }
}