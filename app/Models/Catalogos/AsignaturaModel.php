<?php

namespace App\Models\Catalogos;

use App\Models\Catalogos\AreaFormacionModel;
use App\Models\Catalogos\NucleoModel;
use App\Models\Catalogos\PoliticaModel;
use App\Models\Catalogos\Politica_variableModel;
use App\Models\Catalogos\Politica_variableperiodoModel;
use App\Models\Cursos\CursosModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsignaturaModel extends Model {

    /* Traits */

    use HasFactory;

    /* Properties */

    protected $table = "asi_asignatura";

    protected $fillable = [
        'nombre',
        'id_areaformacion',	// BelongsTo WARNING: También aparece en PoliticaModel
        'id_nucleo',		// BelongsTo
        'periodo',
        'consecutivo',
        'clave',
        'boleta',
        'kardex',
        'expediente',
        'certificado',
        'optativa',
        'activa',			// 0=No, 1=Si
        'afecta_promedio',	// 0=No, 1=Si
        'creditos',
        'horas_semana',
        'nombre_completo',
    ];

    /* Relations BelongsTo */

    /*
    public function area_formacion(): BelongsTo {
        return $this->belongsTo(AreaFormacionModel::class, 'id_areaformacion', 'id');
    }
    */
/*
    public function nucleo(): BelongsTo {
        return $this->belongsTo(NucleoModel::class, 'id_nucleo', 'id');
    }

    /* Relations HasMany */

    public function cursos(): HasMany {
        return $this->hasMany(CursosModel::class, 'asignatura_id', 'id');
    }
    
    /* Methods */
    
    public function politica_variable_id($variableperiodo_nombre)     {
        $variableperiodo = Politica_variableperiodoModel::where('nombre',$variableperiodo_nombre)->first();
        $politica =  PoliticaModel::where('id_areaformacion', $this->id_areaformacion)->first();
        $politica_variable = Politica_variableModel::where('id_politica', $politica->id)->where('id_variableperiodo', $variableperiodo->id)->first();
        return $politica_variable->id;
    }
    
    public function politicas_variable()     {
        //$variableperiodo = Politica_variableperiodoModel::where('nombre',$variableperiodo_nombre)->first();
        $politica =  PoliticaModel::where('id_areaformacion', $this->id_areaformacion)->first();
        $politicas_variable = Politica_variableModel::where('id_politica', $politica->id)->get();
        return $politicas_variable;
    }

    public function determina_parcial($parcial_id) {
        $politica_variable = Politica_variableModel::find($parcial_id);
        if ($politica_variable) {
            $variableperiodo = Politica_variableperiodoModel::find($politica_variable->id_variableperiodo);
            return $variableperiodo ? $variableperiodo->nombre : null;
        }
        return null;
    }

}
