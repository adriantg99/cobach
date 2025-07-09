<?php

namespace App\Http\Livewire\Adminalumnos\Alumnos;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Cursos\CursosModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Cambiosplancomponent extends Component
{
    public $alumno_id, $alumno, $buscar_materias, $plan_mas_comun;
    public function render()
    {
        if ($this->alumno_id != null) {
            $this->alumno = AlumnoModel::find($this->alumno_id);
            if ($this->alumno) {
                $this->buscar_materias = DB::select('call pa_kardex(?)', [$this->alumno->id]);

                $planes = [];
                if ($this->buscar_materias) {
                    foreach ($this->buscar_materias as $materias_kardex) {
                        $curso = CursosModel::find($materias_kardex->esc_curso_id);
                        if ($curso && $curso->plan) {
                            $nombre_plan = $curso->plan->nombre;
                            if (!isset($planes[$nombre_plan])) {
                                $planes[$nombre_plan] = 0;
                            }
                            $planes[$nombre_plan]++;
                        }
                    }

                    // Encontrar el plan de estudio más común
                    $this->plan_mas_comun = array_keys($planes, max($planes))[0];
                }


            }
        }
        return view('livewire.adminalumnos.alumnos.cambiosplancomponent');
    }

    public function mount()
    {

        $this->buscar_materias;
    }
}
