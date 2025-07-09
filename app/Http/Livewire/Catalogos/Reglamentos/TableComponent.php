<?php
// ANA MOLINA 02/08/2023
namespace App\Http\Livewire\Catalogos\Reglamentos;
use App\Models\Catalogos\ReglamentoModel;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $descripcion;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        if($this->descripcion == null)
        {
           $reglamentos = ReglamentoModel::paginate(10);
           $count_reglamentos =ReglamentoModel::count();
        }
        else
        {
            $reglamentos = ReglamentoModel::where('descripcion', 'LIKE', '%'.$this->descripcion.'%')->paginate(10);
            $count_reglamentos = ReglamentoModel::where('descripcion', 'LIKE', '%'.$this->descripcion.'%')->count();
        }

        return view('livewire.catalogos.reglamentos.table-component', compact('reglamentos', 'count_reglamentos'));
    }
}
