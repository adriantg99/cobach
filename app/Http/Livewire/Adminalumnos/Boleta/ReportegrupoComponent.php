<?php
//ANA MOLINA 17/11/2023
namespace App\Http\Livewire\Adminalumnos\Boleta;

use App\Models\Adminalumnos\AlumnoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportegrupoComponent extends Component
{
    public $grupos_sel;
      public function render()
    {

        $grupos_sel=$this->grupos_sel;

        return view('livewire.adminalumnos.boleta.reportegrupo-component',compact('grupos_sel'   ));
    }
}
