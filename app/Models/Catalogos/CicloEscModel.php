<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Grupos\GruposModel;

class CicloEscModel extends Model {
    
    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "cat_ciclos_esc";

    protected $fillable = [
        'nombre',
        'abreviatura',
        'per_inicio',
        'per_final',
        'activo',
    ];

    /* Relations HasMany */

    public function grupos(): HasMany {
        return $this->hasMany(GruposModel::class, 'ciclo_esc_id', 'id');
    }
}