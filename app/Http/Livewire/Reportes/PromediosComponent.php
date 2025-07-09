<?php

namespace App\Http\Livewire\Reportes;

use App\Exports\Reportes\PromediosExport;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Excel;
use Livewire\Component;

class PromediosComponent extends Component
{
    public $plantel, $grupo, $docente, $plantel_seleccionado,
    $parcial_seleccionado, $fecha_cierre, $periodo_seleccionado,
    $grupo_seleccionar, $ciclo_seleccionado, $ciclos, $peridos;
    public function render()
    {
        if($this->ciclo_seleccionado != ""){
            $this->peridos = GruposModel::select('periodo')
                ->where('ciclo_esc_id', $this->ciclo_seleccionado)
                ->whereRaw('periodo REGEXP "^[0-9]+$"')
                ->distinct()
                ->orderBy('periodo', 'asc')
                ->pluck('periodo');
                
        }

        
        return view('livewire.reportes.promedios-component');
    }

    public function mount()
    {
        $roles = Auth()->user()->getRoleNames()->toArray();
        foreach ($roles as $role) {
            if ($role === "control_escolar") {
                $todos_los_valores = 1;
                break;
            } elseif (strpos($role, "control_escolar_") === 0) {
                $validaciones[] = substr($role, 16);
                $todos_los_valores = 2;
                continue;
            } else {
                continue;
            }
        }


        if ($todos_los_valores == 1) {
            $this->plantel = PlantelesModel::get();
        } elseif ($todos_los_valores == 2) {
            $this->plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->get();
        } else {
            $this->plantel = collect();
        }

        // $fechaHoy='2024-05-01';
        //$ciclos = CicloEscModel::where('per_inicio', '<=', $fechaHoy)->where('per_final', '>=', $fechaHoy)->get();
        $activo = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('activo', '1')
            ->first();

        $anterior = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('id', '!=', $activo?->id)
            ->where('per_inicio', '<', $activo?->per_inicio)
            ->where('tipo', '!=', 'V') // Excluir ciclos cuyo tipo sea 'V'
            ->orderBy('per_inicio', 'desc')
            ->first();

        $this->ciclos = collect([$activo, $anterior])->filter();
        $this->peridos = collect();
    }

    public function generar_documento()
    {

        //dd(Auth::user()->roles->pluck('name'));
        $plantel_promedio = PlantelesModel::find($this->plantel_seleccionado);

        return Excel::download(new PromediosExport($this->plantel_seleccionado, $this->periodo_seleccionado, $this->ciclo_seleccionado), $plantel_promedio->nombre . '_promedios.xlsx');


    }
}
