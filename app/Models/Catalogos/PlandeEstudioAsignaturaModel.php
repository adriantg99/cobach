<?php
// ANA MOLINA 11/08/2023
namespace App\Models\Catalogos;

use App\Models\Catalogos\AsignaturaModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlandeEstudioAsignaturaModel extends Model
{
    use HasFactory;

    protected $table = "asi_planestudio_asignatura";

    protected $fillable = [
        'id_planestudio',
        'id_asignatura',
    ];

    public function asignatura()
    {
        return $this->belongsTo(AsignaturaModel::class, 'id_asignatura', 'id');
    }
}
