<?php
//ANA MOLINA 31/05/2024
namespace App\Http\Livewire\Certificados\Revisa;


use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RevisalistadoComponent extends Component
{
    public $grupo_id;
    public $listado;
    public $plantel;
    public $grupo;


      public function render()
    {
        $grupo_id=$this->grupo_id;

        $grupo_datos = GruposModel::find($grupo_id);
        //dd($grupo_id);
        $alumnos_estatus = DB::select('call pa_alumnos_certgenera(?)', [$grupo_id]);

        return view('livewire.certificados.revisa.revisalistado-component',compact('grupo_id', 'alumnos_estatus', 'grupo_datos'));

    }
}
