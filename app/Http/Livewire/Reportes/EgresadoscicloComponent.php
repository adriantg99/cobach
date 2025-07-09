<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Catalogos\CicloEscModel;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DateTime;
class EgresadoscicloComponent extends Component
{
    public   $ciclo_esc;
    public function render()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $ciclo=CicloEscModel::where ('id','=',$this->ciclo_esc)->first();

          $dat = DB::select('call pa_egresos (?)  ',array($this->ciclo_esc));
          $datos=collect($dat);


        $cicloesc=$ciclo->nombre;
        $fecha=date('d-m-Y');
        return view('livewire.reportes.egresadosciclo-component',compact('datos' ,'cicloesc','fecha'));

    }
}
