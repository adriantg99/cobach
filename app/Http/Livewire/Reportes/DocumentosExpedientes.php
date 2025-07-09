<?php

namespace App\Http\Livewire\Reportes;

use Livewire\Component;

class DocumentosExpedientes extends Component
{
    public $message;
    public function render()
    {
        return view('livewire.reportes.documentos-expedientes');
    }

    public function mount(){
        $this->message = "Hola mundo";
    }
}
