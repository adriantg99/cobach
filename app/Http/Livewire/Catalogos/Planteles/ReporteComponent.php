<?php
//ANA MOLINA 07/08/2023
namespace App\Http\Livewire\Catalogos\Planteles;

use App\Models\Catalogos\PlantelesModel;
use Livewire\Component;

class ReporteComponent extends Component
{

    public function render()
    {
        $planteles = PlantelesModel::orderBy('nombre')->get();

         return view('livewire.catalogos.planteles.reporte-component',compact('planteles'));
    }
}
