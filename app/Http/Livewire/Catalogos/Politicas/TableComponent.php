<?php
// ANA MOLINA 29/06/2023
namespace App\Http\Livewire\Catalogos\Politicas;

use App\Models\Catalogos\PoliticaModel;
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

            $politicas = PoliticaModel::paginate(10);
            $count_politicas = PoliticaModel::count();

        }
        else
        {
            $politicas = PoliticaModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->paginate(10);
            $count_politicas= PoliticaModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->count();


        }




        return view('livewire.catalogos.politicas.table-component', compact('politicas', 'count_politicas'));
    }
}
