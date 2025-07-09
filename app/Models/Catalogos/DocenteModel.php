<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Cursos\CursosModel;
use App\Models\User;

class DocenteModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = 'emp_perfil';

    protected $fillable = [
        'user_id',              // BelongsTo
        'nombre',
        'apellido1',
        'apellido2',
        'fecha_nac',
        'correo_personal',
        'telefono',
        'num_planteles',
        'perfil_finalizado',    // 0=No, 1=Si
        'rfc',
        'curp'
    ];

    /* Relations BelongsTo */

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /* Relations HasMany */

    public function cursos(): HasMany {
        return $this->hasMany(CursosModel::class, 'docente_id', 'id');
    }

}