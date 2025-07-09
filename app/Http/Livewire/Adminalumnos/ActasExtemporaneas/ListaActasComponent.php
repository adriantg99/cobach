<?php

namespace App\Http\Livewire\Adminalumnos\ActasExtemporaneas;

use App\Models\Catalogos\PlantelesModel;
use App\Models\Escolares\ActaExtemporaneaModel;
use Livewire\Component;

class ListaActasComponent extends Component
{
    public $plantel_id;

    public function render()
    {
        $plantel = PlantelesModel::find($this->plantel_id);
        $actas_ext = ActaExtemporaneaModel::where('plantel_id', $plantel->id)->orderBy('user_id_creacion','DESC')
            ->get();
        return view('livewire.adminalumnos.actas-extemporaneas.lista-actas-component', compact('plantel','actas_ext'));
    }

}
