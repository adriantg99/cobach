<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Catalogos\PlandeEstudioModel;

class ReglamentoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_reglamento";

    protected $fillable = [
        'nombre'	// WARNING: No hay informacion en la base de datos
    ];

    /* Relations HasMany */

    public function planes(): HasMany {
        return $this->hasMany(PlandeEstudioModel::class, 'id_reglamento', 'id');
    }

}