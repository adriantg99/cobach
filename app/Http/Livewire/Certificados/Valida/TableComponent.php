<?php
// ANA MOLINA 17/07/2024
namespace App\Http\Livewire\Certificados\Valida;
use App\Models\Certificados\CertificadovalModel;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $oficio;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        if($this->oficio == null)
        {
           $oficios = CertificadovalModel::orderBy('id', 'desc')->paginate(10);
           $count_oficios =CertificadovalModel::count();
        }
        else
        {
            $oficios = CertificadovalModel::where('oficio', 'LIKE', '%'.$this->oficio.'%')->orderBy('id', 'desc')->paginate(10);
            $count_oficios = CertificadovalModel::where('oficio', 'LIKE', '%'.$this->oficio.'%')->count();
        }

        return view('livewire.certificados.valida.table-component', compact('oficios', 'count_oficios'));
    }
}
