<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class MejorespromediosalumnoComponent extends Component
{
    public $plantel,  $periodo;

    public function render()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $plantel_sel = PlantelesModel::find($this->plantel);
        $cicloesc=CicloEscModel::where ('activo','=',1)->first();
        if ($this->periodo=='P1')
        {
        $periodo=1;
        $titulo='PARCIAL 1';
        }
    else if ($this->periodo=='P2')
    {
        $periodo=2;
        $titulo='PARCIAL 2';
        }
    if ($this->periodo=='P3')
    {
        $periodo=3;
        $titulo='PARCIAL 3';
        }
          $dat = DB::select('call pa_mejorespromedios (?,?,?)  ',array($cicloesc->id,$plantel_sel->id,$periodo));
            $datos=collect($dat)->sortBy([['cursadas','desc'],['promedio','desc'],['aprobadas','desc'],['alumno','asc']]);


      /*dd($cicloesc->id.'-'.$plantel_sel->id.'-'."'".$this->grupos."'".'-'."'".$curso."'".'-'.$docente);*/


        $cicloesc=$cicloesc->nombre;
        $plantelnom=$plantel_sel->nombre;

        return view('livewire.reportes.mejorespromediosalumno-component',compact('datos' ,'cicloesc', 'plantelnom', 'titulo'));

    }
}
