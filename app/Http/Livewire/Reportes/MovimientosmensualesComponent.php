<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Exports\Reportes\MovimientosmensualesExport;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
class MovimientosmensualesComponent extends Component
{
    public $ciclo,  $ciclo_seleccionado;
    public $plantel,  $plantel_seleccionado;
    public function render()
    {
        $fechaHoy = Carbon::now();
        // $fechaHoy='2024-05-01';
         //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();
         $ciclos=CicloEscModel::where ('activo','=',1)->first();
         $this->ciclo_seleccionado=$ciclos->id;

        return view('livewire.reportes.movimientosmensuales-component');
    }

    public function mount()
    {
         $this->ciclo = CicloEscModel::orderByDesc("per_inicio")->get();
         $roles = Auth()->user()->getRoleNames()->toArray();
         foreach ($roles as $role) {
             if($role === "control_escolar"){
                 $todos_los_valores = 1;
                 break;
             }
             elseif (strpos($role, "control_escolar_") === 0) {
                 $validaciones[] = substr($role, 16);
                 $todos_los_valores = 2;
                 continue;
             }
             else {
                 continue;
             }
         }


         if($todos_los_valores == 1){
             $this->plantel = PlantelesModel::orderBy("nombre")->get();
         }
         elseif($todos_los_valores==2){
             $this->plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->orderBy("nombre")->get();
         }
         else{
             $this->plantel = collect();
         }


    }

    public function generar_excel(){

        //dd(Auth::user()->roles->pluck('name'));
            $ciclo_seleccionado = CicloEscModel::find($this->ciclo_seleccionado);

             return Excel::download(new MovimientosmensualesExport ($this->ciclo_seleccionado,$this->plantel_seleccionado), $ciclo_seleccionado->nombre.'movmensuales.xlsx');


    }

}
