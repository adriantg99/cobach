<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\AulaModel;

class Aula_condicionModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table="cat_aula_condicion";

    protected $fillable = [
        'descripcion',
    ];

    /* Relations HasMany */

    public function aulas() {
        return $this->hasMany(AulaModel::class, 'condicion_aula_id', 'id');
    }

}