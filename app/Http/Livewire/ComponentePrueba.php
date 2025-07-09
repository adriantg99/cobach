<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ComponentePrueba extends Component
{
    public $title;
    public $body;

    public $message;
    
    public function mount(){
        $this->title = "gello worl";
        $this->body = "Ya por favor";

    }
    public function render()
    {
        return view('livewire.componente-prueba');
    }
}
