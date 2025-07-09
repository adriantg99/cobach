<?php

namespace App\Exports\Catalogos;

use App\Models\Catalogos\AsignaturaModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AsignaturaExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $asignatura_id;

    /*
    public function __construct($asignatura_id)
    {
        $this->asignatura_id = $asignatura_id;
    }*/

    public function view(): View
    {
        $asignaturas = AsignaturaModel::
            select('asi_asignatura.id', 'asi_asignatura.nombre', 'clave', 'activa', 'asi_asignatura.created_at as fecha','periodo', 'activa', 'asi_areaformacion.nombre as area_formacion', 'asi_nucleo.nombre as nucleo')->
            join('asi_areaformacion', 'asi_asignatura.id_areaformacion', '=', 'asi_areaformacion.id')
            ->join('asi_nucleo', 'asi_asignatura.id_nucleo', '=', 'asi_nucleo.id')
            ->get();
        //dd($aulas);
        return view('exports.catalogos.asignaturas_excel', compact('asignaturas'));
    }
}
