<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;

class ListaAsistenciaComponent extends Component
{

    public $plantel_seleccionado, $ciclo_activo,
    $periodo_seleccionado, $plantel, $periodos,
    $grupo_seleccionar, $buscar_cursos, $curso_origen, $curso_destino, $parcial;

    public function render()
    {
        if ($this->plantel_seleccionado != "") {
            $this->periodos = GruposModel::select('periodo')
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->distinct('periodo')
                ->orderBy('periodo')
                ->get();
        }

        if ($this->periodo_seleccionado != "") {
            $this->grupos = GruposModel::where('periodo', $this->periodo_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('plantel_id', $this->plantel_seleccionado)
                ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
            WHEN 1 THEN ' M'
            WHEN 2 THEN ' V'
            END) AS nombre")
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->orderBy("nombre")
                ->get();
        }

        if ($this->grupo_seleccionar != "") {
            $this->buscar_cursos = CursosModel::where('grupo_id', $this->grupo_seleccionar)->get();
        }


        return view('livewire.reportes.lista-asistencia-component');
    }

    public function mount()
    {
        $this->periodos = collect();
        $this->grupos = collect();
        $this->ciclo_activo = CicloEscModel::where('activo', '1')->orderBy('per_inicio', 'desc')->first();

        $roles = Auth()->user()->getRoleNames()->toArray();

        $this->plantel = obtenerPlanteles();
       




    }
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['plantel_seleccionado', 'periodo_seleccionado', 'grupo_seleccionar'])) {
            $this->curso_origen = ''; // Vaciar curso_origen al cambiar alguna opción
        }
    }
    

}
