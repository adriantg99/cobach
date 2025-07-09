<?php
//ANA MOLINA 15/03/2024
namespace App\Http\Livewire\Certificados\Certificado;

use App\Models\Adminalumnos\AlumnoModel;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportegrupoComponent extends Component
{
    public $alumnos_sel;
    public $grupo_id;
      public function render()
    {

        $alumnos_sel=$this->alumnos_sel;

        $grupo_id=$this->grupo_id;
        
        return view('livewire.certificados.certificado.reportegrupo-component',compact('alumnos_sel' ,'grupo_id'  ));

    }
}
