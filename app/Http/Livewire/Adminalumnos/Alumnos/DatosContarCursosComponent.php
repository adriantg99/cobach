<?php

namespace App\Http\Livewire\Adminalumnos\Alumnos;

use App\Models\Adminalumnos\AlumnoModel;
use Livewire\Component;

class DatosContarCursosComponent extends Component
{
    public $alumno_id;
    public $grupo_id;

    public function render()
    {
        $alu = AlumnoModel::find($this->alumno_id);
        $gru = $this->grupo_id;
        $csos = $alu->cursos_del_grupo($gru, $alu->id);
        return view('livewire.adminalumnos.alumnos.datos-contar-cursos-component', compact('alu', 'gru', 'csos'));
    }
}
