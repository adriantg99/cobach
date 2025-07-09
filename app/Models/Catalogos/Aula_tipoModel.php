<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Catalogos\AulaModel;

class Aula_tipoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "cat_aula_tipo";

    protected $fillable = [
        'descripcion',
    ];

    /* Relations HasMany */

    public function aulas(): HasMany {
        return $this->hasMany(AulaModel::class, 'tipo_aula_id', 'id');
    }

}