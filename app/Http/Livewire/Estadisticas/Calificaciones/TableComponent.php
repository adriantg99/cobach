<?php
// ANA MOLINA 17/02/2024
namespace App\Http\Livewire\Estadisticas\Calificaciones;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{

    use WithPagination;

    public $ciclo_id=0;


    public $calificaciones;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
    if(!empty($this->ciclo_id))
        {
            ini_set('max_execution_time', 600); // 5 minutes
            $cal = DB::select('call pa_calificaciones(?)',array( $this->ciclo_id ));
            $this->calificaciones=$cal;
            $count_alumnos=count($cal);

        }
        else
        {
            $this->calificaciones =array();
            $count_alumnos=0;
        }


        $calificaciones=$this->calificaciones;

        $ciclo_id=$this->ciclo_id;
         return view('livewire.estadisticas.calificaciones.table-component',compact('calificaciones','count_alumnos','ciclo_id'));
    }

}

