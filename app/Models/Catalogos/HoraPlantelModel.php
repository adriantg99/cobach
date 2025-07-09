<?php

namespace App\Models\Catalogos;

use App\Models\Catalogos\PlantelesModel;
use App\Models\Escolares\TurnoModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoraPlantelModel extends Model
{
    use HasFactory;

    protected $table = 'cat_hora_plantel';

    protected $fillable = [
        'plantel_id',
        'turno_id',
        'hr_inicio',
        'hr_fin'
    ];

    public function plantel()
    {
        return $this->belongsTo(PlantelesModel::class, 'plantel_id','id');
    }

    public function turno()
    {
        return $this->belongsTo(TurnoModel::class, 'turno_id','id');
    }
    
}
