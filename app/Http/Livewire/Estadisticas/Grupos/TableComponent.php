<?php
// ANA MOLINA 06/12/2023
namespace App\Http\Livewire\Estadisticas\Grupos;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\Factory;
use Illuminate\Pagination\Paginator;
class TableComponent extends Component
{

    use WithPagination;

    public $ciclo_id=0;
    public $grupos;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {


        if(!empty($this->ciclo_id))
        {

            $mat = DB::select('call pa_grupos(?)',array( $this->ciclo_id)) ;
            //$this->grupos = Paginator::make($mat, count($mat), 10);

            $this->grupos=$mat;
            $count_grupos=count($mat);

        }
        else
        {
            $this->grupos =array();
            $count_grupos=0;
        }


        $grupos=$this->grupos;

        $ciclo_id=$this->ciclo_id;
        return view('livewire.estadisticas.grupos.table-component',compact('grupos','count_grupos','ciclo_id'));
    }

}
