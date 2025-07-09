<?php

namespace App\Http\Livewire\Testing;

use Livewire\Component;
use App\Traits\BitacoraTrait;

class TestingComponent extends Component {

    use BitacoraTrait;

    public $count = 0;

    public function render() {
        $this->createEntry(__CLASS__, __NAMESPACE__, __METHOD__, "Testing bitacora desde Componente Livewire");
        return view('livewire.testing.testing-component');
    }

    public function increment() {
        $this->count++;
    }

    

}