<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DateTime;
class MovmensualesComponent extends Component
{
    public $plantel,  $ciclo_esc;

    public function render()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $plantel_sel = PlantelesModel::find($this->plantel);
        $ciclo=CicloEscModel::where ('id','=',$this->ciclo_esc)->first();

          $dat = DB::select('call pa_mvtosmensuales (?,?)  ',array($this->ciclo_esc,$this->plantel));
          $datos=collect($dat);


        $cicloesc=$ciclo->nombre;
        $plantelnom=$plantel_sel->nombre;
        $fecha=date('d-m-Y');
        return view('livewire.reportes.movmensuales-component',compact('datos' ,'cicloesc', 'plantelnom','fecha'));

    }
}
