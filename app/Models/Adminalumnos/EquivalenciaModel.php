<?php
// ANA MOLINA 10/07/2024
namespace App\Models\Adminalumnos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;


class EquivalenciaModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "esc_equivalencia";

    protected $fillable = [
        'alumno_id',
        'alumno',
        'folio',
        'emitidopor',
        'fecha',
        'institucion',
        'expediente',
        'firmadcto',
        'cct',
        'tipopa',
        'ciclo1',
        'ciclo2',
        'ciclo3',
        'ciclo4',
        'ciclo5',
        'prom1',
        'prom2',
        'prom3',
        'prom4',
        'prom5',
        'calif1',
        'calif2',
        'calif3',
        'calif4',
        'calif5',
        'plantel_id',
        'planestudio_id',
        'user_id',
        'user_id_aut',
        'fecha_aut'
    ];


}
