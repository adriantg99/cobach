<?php

namespace App\Http\Livewire\Reportes;

use App\Exports\Reportes\EgresoAlumnosExport;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Excel;
use Livewire\Component;

class EgresoAlumnosComponent extends Component
{
    public $plantel, $plantel_seleccionado,
    $periodo_seleccionado, $periodos,
    $grupos, $grupo_seleccionar, $ciclo_activo,
    $ciclo_buscado;
    public function render()
    {
        return view('livewire.reportes.egreso-alumnos-component');
    }

    public function mount()
    {
        $this->periodos = collect();
        $this->grupos = collect();

        $this->ciclo_activo = CicloEscModel::where('activo', '1')
            ->where('tipo', '!=', 'V')
            ->orderBy('per_inicio', 'desc')
            ->first();

        // Si se encuentra un ciclo activo
        if ($this->ciclo_activo) {
            // Obtener el ID del ciclo actual
            $id_ciclo_actual = $this->ciclo_activo->id;

            // Obtener el ciclo anterior al actual
            $this->ciclo_buscado = CicloEscModel::where('id', '<', $id_ciclo_actual)
                ->where('tipo', '!=', 'V')
                ->orderBy('id', 'desc')
                ->first();

            // Puedes ahora utilizar $ciclo_anterior->id
        }

        
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
        } elseif ($todos_los_valores = 2) {
            $this->plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->get();
        } else {
            $this->plantel = collect();
        }
    }

    public function generar_documento()
    {
        $plantel = PlantelesModel::find($this->plantel_seleccionado);
        
        return Excel::download(new EgresoAlumnosExport($this->ciclo_buscado, $this->plantel_seleccionado), 'Alumnos_egreso_'.$plantel->nombre.'_'.$this->ciclo_buscado->nombre.'.xlsx');
        /*
        $buscar_datos = GruposModel::join('esc_grupo_alumno', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
        ->where('esc_grupo.ciclo_esc_id', $this->ciclo_buscado->id)
        ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
        ->select('noexpediente', 'alu_alumno.nombre', 'alu_alumno.apellidopaterno', 'alu_alumno.apellidomaterno')
        ->groupBy('noexpediente', 'alu_alumno.nombre', 'alu_alumno.apellidopaterno', 'alu_alumno.apellidomaterno')
        ->get();
        */
        //dd($buscar_datos->count());
    }
}
