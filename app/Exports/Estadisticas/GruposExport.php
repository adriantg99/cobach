<?php

// ANA MOLINA 06/12/2023
namespace App\Exports\Estadisticas;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GruposExport implements FromView
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

            $grupos = DB::select('call pa_grupos(?)',array( $this->ciclo_id));


        }
        else
        {
            $grupos =array();

        }


        return view('exports.estadisticas.grupos_excel', compact('grupos'));
    }
}
