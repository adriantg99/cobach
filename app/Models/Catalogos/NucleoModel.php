<?php
// ANA MOLINA 26/06/2023
namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Catalogos\AsignaturaModel;

class NucleoModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_nucleo";

    protected $fillable = [
        'clave_consecutivo',
        'areaformacion_id',
        'nombre', // WARNING: Noy hay infomacion en la tabla
        'id'
    ];

    /* Relations HasMany */

    public function asignaturas(): HasMany {
        return $this->hasMany(AsignaturaModel::class, 'id_nucleo', 'id');
    }

    /* Relations BelongsTo */

    public function areaFormacion(): BelongsTo {
        return $this->belongsTo(AreaFormacionModel::class, 'areaformacion_id', 'id');
    }

}