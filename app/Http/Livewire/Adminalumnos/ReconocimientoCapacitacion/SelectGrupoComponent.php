<?php

namespace App\Http\Livewire\Adminalumnos\ReconocimientoCapacitacion;

use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;

class SelectGrupoComponent extends Component
{
    public $ciclo_esc_id;
    public $plantel_id;
    public $gpo_id;
    public $bool_consultar_curso;
    public $bool_consultar_alumno;
    public $grupos;
    public $alumnos = [];
    public $gpo_anterior;


    public function descargar()
    {
        $plantel = PlantelesModel::find($this->plantel_id);
        $grupo = GruposModel::find($this->gpo_id);
        return redirect()->route('adminalumnos.reconocimiento_capacitacion.descarga')
            ->with('alumnos', $this->alumnos)
            ->with('nombre_archivo', $this->ciclo_esc_id.'-'.$plantel->abreviatura.' - '.$grupo->nombre.$grupo->turno->abreviatura);
    }

    public function descargar_sf()
    {
        $plantel = PlantelesModel::find($this->plantel_id);
        $grupo = GruposModel::find($this->gpo_id);
        return redirect()->route('adminalumnos.reconocimiento_capacitacion.descarga_sf')
            ->with('alumnos', $this->alumnos)
            ->with('nombre_archivo', $this->ciclo_esc_id.'-'.$plantel->abreviatura.' - '.$grupo->nombre.$grupo->turno->abreviatura);
    }

    public function limpiabusqueda()
    {
        return redirect()->route('adminalumnos.reconocimiento_capacitacion.index');
    }

    public function render()
    {   
        $planteles = null;
        $ciclos_esc = null;

        $grupos = null;
        $plantel = null;
        $ciclo_esc = null;
        
        $grupo_seleccionado = null;
        $gpo_alumnos = null;

        $planteles = PlantelesModel::get();
        $ciclos_esc = CicloEscModel::select('cat_ciclos_esc.id', 'cat_ciclos_esc.nombre', 'activo')
                ->distinct()
                ->join('esc_grupo', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
                ->orderBy('cat_ciclos_esc.id', 'desc')
                ->get();

        if (($this->ciclo_esc_id != null) and ($this->plantel_id != null) and ($this->grupos == null)) {
            $this->grupos = GruposModel::where('plantel_id', $this->plantel_id)
                ->where('ciclo_esc_id', $this->ciclo_esc_id)->withCount('cursos') //->withCount('alumnos')
                ->where('periodo', 6)
                ->orderBy('nombre')
                ->get();
            //dd($grupos);

            $this->dispatchBrowserEvent('cargar_select2_grupo');
        }


        if ($this->plantel_id != null) {
            $plantel = PlantelesModel::find($this->plantel_id);
        }
        if ($this->ciclo_esc_id != null) {
            $ciclo_esc = CicloEscModel::find($this->ciclo_esc_id);
        }

        if(is_null($this->gpo_id)==false)
        {
            $gpo_alumnos = GrupoAlumnoModel::where('grupo_id',$this->gpo_id)->get();
            //dd($gpo_alumnos);
            
            if($this->gpo_anterior <> $this->gpo_id)
            {
                $this->alumnos = [];
                foreach($gpo_alumnos as $alu)
                {
                    $this->alumnos += [$alu->alumno->id => true];
                }
            }
            
            $this->gpo_anterior = $this->gpo_id;
        }

        return view('livewire.adminalumnos.reconocimiento-capacitacion.select-grupo-component', compact('planteles', 'ciclos_esc', 'grupos', 'plantel', 'ciclo_esc', 'gpo_alumnos'));
    }
}
