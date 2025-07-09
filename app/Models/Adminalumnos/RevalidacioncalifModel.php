<?php
// ANA MOLINA 10/07/2024
namespace App\Models\Adminalumnos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;


class RevalidacioncalifModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "esc_revalidacioncalif";

    protected $fillable = [
        'id',
        'cicloesc_id',
        'asignatura_id',
        'calificacion',
        'calif'
    ];

    public $timestamps = false;

}
