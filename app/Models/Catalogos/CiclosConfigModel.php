<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CiclosConfigModel extends Model
{
    use HasFactory;

    protected $table = 'cat_ciclos_configuraciones';

    protected $fillable = [
        'nombre',
        'ciclo_esc_id',
        'p1',
        'fin_p1',
        'p2',
        'fin_p2',
        'p3',
        'fin_p3',
        'inicio_inscripcion',
        'fin_inscripcion',
        'inicio_semestre',
        'fin_semestre',
        'inicio_repeticion',
        'fin_repeticion',
        'inicio_reinscripcion',
        'fin_reinscripcion',
        
    ];
}
