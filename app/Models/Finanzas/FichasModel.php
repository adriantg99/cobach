<?php

namespace App\Models\Finanzas;

use App\Models\Finanzas\FichasDetallesModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichasModel extends Model
{
    use HasFactory;

    protected $table="fin_fichas";

    protected $fillable = [
        'matricula',
        'folio',
        'nombre_alumno',
        'semestre',
        'generada',
        'fecha_creacion',
        'total',
        'cadena_hsbc_bienestar',
        'cadena_bamamex',
        'cadena_bbva',
        'cadena_banco_azteca',
        'fecha_caducidad',
    ];

    public function fichas_detalles()
    {
        //return $this->hasMany(FichasDetallesModel::class, 'folio','folio');
        $fichas_detalles = FichasDetallesModel::where('folio', $this->folio)
            ->where('matricula',$this->matricula)->get();
        return $fichas_detalles;
    }
}
