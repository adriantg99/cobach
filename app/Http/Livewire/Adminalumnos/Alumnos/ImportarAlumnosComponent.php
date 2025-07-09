<?php

namespace App\Http\Livewire\Adminalumnos\Alumnos;

use App\Imports\Controlesc\nuevos_usuarios;
use Excel;
use Illuminate\Support\MessageBag;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;

use App\Imports\UsersImport;
use Illuminate\Support\Facades\Log;


class ImportarAlumnosComponent extends Component
{
    use WithFileUploads;

    public $file;
    public $errors = [];
    public $plantelId; // Variable para guardar plantel_id

    protected $errorsBag;


    public function render()
    {
        return view('livewire.adminalumnos.alumnos.importar-alumnos-component',[
        'errorsBag' => $this->errorsBag,]);
    }

    public function mount()
    {
        $this->errorsBag = new MessageBag();
    }

    public function getErrorsBagProperty()
    {
        return $this->errorsBag->toArray();
    }
    public function import()
    {
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '900'); // 2 minutos
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {

            Excel::import(new nuevos_usuarios($this->plantelId), $this->file);
            $this->file = null; // Desasocia el archivo
            Session::flash('message', 'Importación exitosa.');

        } catch (\Exception $e) {
            $this->file = null; // Desasocia el archivo
            Session::flash('error', 'Error en la importación: ' . $e->getMessage());
        }
    }

}
