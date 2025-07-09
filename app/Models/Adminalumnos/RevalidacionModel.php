<?php
// ANA MOLINA 10/07/2024
namespace App\Models\Adminalumnos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;


class RevalidacionModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "esc_revalidacion";

    protected $fillable = [
        'alumno_id',
        'alumno',
        'folio',
        'emitidopor',
        'fecha',
        'semestres',
        'institucion',
        'grados',
        'periodo_escolar',
        'lugar',
        'expediente',
        'firmadcto',
        'ciclo1',
        'ciclo2',
        'ciclo3',
        'ciclo4',
        'ciclo5',
        'plantel_id',
        'planestudio_id',
        'user_id',
        'user_id_aut',
        'fecha_aut'
    ];


}
