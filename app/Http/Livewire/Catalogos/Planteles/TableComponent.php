<?php

namespace App\Http\Livewire\Catalogos\Planteles;

use App\Models\Catalogos\PlantelesModel;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $nombre;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        if($this->nombre == null)
        {
            $planteles = PlantelesModel::all();
            $count_planteles = PlantelesModel::count();
        }
        else
        {
            $planteles = PlantelesModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->get();
            $count_planteles = PlantelesModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->count();
        }

        return view('livewire.catalogos.planteles.table-component', compact('planteles', 'count_planteles'));
    }
}
