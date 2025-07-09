<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\CicloEscModel;
use App\Exports\Reportes\MejorespromediosExport;
use App\Models\Catalogos\PlantelesModel;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
class MejorespromediosComponent extends Component
{
    public $plantel,  $plantel_seleccionado, $periodo_seleccionado='P1';

      public $reporte_seleccionado='A';
    public function render()
    {
        $fechaHoy = Carbon::now();
        // $fechaHoy='2024-05-01';
         //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();
         $ciclos=CicloEscModel::where ('activo','=',1)->first();


        return view('livewire.reportes.mejorespromedios-component');
    }

    public function mount()
    {
        $this->plantel = obtenerPlanteles();


    }

    public function generar_excel(){

        //dd(Auth::user()->roles->pluck('name'));
            $plantel_sel = PlantelesModel::find($this->plantel_seleccionado);

             return Excel::download(new MejorespromediosExport($this->plantel_seleccionado, $this->periodo_seleccionado), $plantel_sel->nombre.'mejorespromedios.xlsx');


    }

}
