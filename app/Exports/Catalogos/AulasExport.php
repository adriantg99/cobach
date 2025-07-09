<?php

namespace App\Exports\Catalogos;


use App\Models\Catalogos\AulaModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AulasExport implements FromView
{   
    /**
    * @return \Illuminate\Support\Collection
    */

    public $plantel_id;
    
    public function __construct($plantel_id)
    {
        $this->plantel_id = $plantel_id;        
    }
    
    public function view(): View
    {
        $aulas = AulaModel::where('plantel_id',$this->plantel_id)->get();
        //dd($aulas);
        return view('exports.catalogos.aulas_excel', compact('aulas'));
    }
    
}
