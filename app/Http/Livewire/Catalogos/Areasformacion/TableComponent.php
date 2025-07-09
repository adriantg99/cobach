<?php
// ANA MOLINA 27/06/2023
namespace App\Http\Livewire\Catalogos\Areasformacion;
use App\Models\Catalogos\AreaFormacionModel;
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
           $areasformacion = AreaFormacionModel::paginate(10);
           $count_areasformacion =AreaFormacionModel::count();
        }
        else
        {
            $areasformacion = AreaFormacionModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->paginate(10);
            $count_areasformacion = AreaFormacionModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->count();
        }

        return view('livewire.catalogos.areasformacion.table-component', compact('areasformacion', 'count_areasformacion'));
    }
}
