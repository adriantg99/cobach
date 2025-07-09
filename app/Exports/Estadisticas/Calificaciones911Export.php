<?php

// ANA MOLINA 18/02/2024
namespace App\Exports\Estadisticas;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Calificaciones911Export implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $ciclo_id;

    public function __construct($ciclo_id)
    {
        $this->ciclo_id = $ciclo_id;

    }


    public function view(): View
    {

        if(!empty($this->ciclo_id))
        {
            ini_set('max_execution_time', 600); // 5 minutes
            $calificaciones = DB::select('call pa_calificaciones(?)',array( $this->ciclo_id));


        }
        else
        {
            $calificaciones =array();

        }


        return view('exports.estadisticas.calificaciones_excel', compact('calificaciones'));
    }
}
