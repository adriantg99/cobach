<?php
// ANA MOLINA 01/07/2024
namespace App\Http\Livewire\Certificados\Valida;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OficioautComponent extends Component
{
    public $datos;
    public $detalle;

    public function render()
    {
         return view('livewire.certificados.valida.oficioaut-component');

    }


}
