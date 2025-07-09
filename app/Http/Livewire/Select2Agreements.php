<?php

namespace App\Http\Livewire;

use App\Models\Adminalumnos\AlumnoModel;
use Livewire\Component;

class Select2Agreements extends Component
{

    public $agreements;
    public $resultados;

    public function mount()
    {
        // Cargar las opciones del select2, por ejemplo, desde tu modelo de Eloquent
   


    }

    public function render()
    {
        return view('livewire.select2-agreements');
    }

}
