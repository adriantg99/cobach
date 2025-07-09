<?php
// ANA MOLINA 26/11/2023
namespace App\Http\Livewire\Estadisticas\Matricula;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{

    use WithPagination;

    public $ciclo_id=0;
    public $fecha=null;


    public $matricula;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        if ($this->fecha==null)
            $this->fecha=date("Y-m-d");

    if(!empty($this->ciclo_id))
        {
            $mat = DB::select('call pa_matricula(?,?)',array( $this->ciclo_id,$this->fecha));
            $this->matricula=$mat;
            $count_alumnos=count($mat);

        }
        else
        {
            $this->matricula =array();
            $count_alumnos=0;
        }


        $matricula=$this->matricula;

        $ciclo_id=$this->ciclo_id;
        $fecha=$this->fecha;
        return view('livewire.estadisticas.matricula.table-component',compact('matricula','count_alumnos','ciclo_id','fecha'));
    }

}

