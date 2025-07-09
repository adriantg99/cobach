<?php

// ANA MOLINA 30/11/2023
namespace App\Exports\Estadisticas;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MatriculaExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $ciclo_id;
    public $fecha;

    public function __construct($ciclo_id,$fecha)
    {
        $this->ciclo_id = $ciclo_id;
        $this->fecha=$fecha;
    }


    public function view(): View
    {

        if(!empty($this->ciclo_id))
        {

            $matricula = DB::select('call pa_matricula(?,?)',array( $this->ciclo_id,$this->fecha));


        }
        else
        {
            $matricula =array();

        }

    $fecha=$this->fecha;

        return view('exports.estadisticas.matricula_excel', compact('matricula','fecha'));
    }
}
