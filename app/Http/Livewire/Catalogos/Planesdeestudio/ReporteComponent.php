<?php
//ANA MOLINA 07/08/2023
namespace App\Http\Livewire\Catalogos\Planesdeestudio;

use App\Models\Catalogos\PlandeEstudioModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReporteComponent extends Component
{

    public function render()
    {

        $planes = DB::table('cat_plantel')
        ->join('asi_planestudio', function($join){
          $join->on('cat_plantel.id','=','asi_planestudio.id_plantel');

        })
        ->select('asi_planestudio.id','asi_planestudio.nombre','cat_plantel.nombre as plantel')->orderby('asi_planestudio.nombre')
        ->get()->toArray();


         return view('livewire.catalogos.planesdeestudio.reporte-component',compact('planes'));
    }
}
