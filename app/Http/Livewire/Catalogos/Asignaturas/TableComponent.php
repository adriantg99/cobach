<?php
// ANA MOLINA 28/06/2023
namespace App\Http\Livewire\Catalogos\Asignaturas;

use App\Models\Catalogos\AsignaturaModel;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $search; // Campo unificado para búsqueda
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $query = AsignaturaModel::join('asi_areaformacion', 'asi_asignatura.id_areaformacion', '=', 'asi_areaformacion.id')
            ->select('asi_asignatura.*', 'asi_areaformacion.nombre as area_formacion')
            ->orderBy('asi_asignatura.id');

        // Aplicar búsqueda global en las columnas relevantes
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('asi_asignatura.nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('asi_asignatura.clave', 'like', '%' . $this->search . '%')
                    ->orWhere('asi_asignatura.id_nucleo', 'like', '%' . $this->search . '%')
                    ->orWhere('asi_areaformacion.nombre', 'like', '%' . $this->search . '%');
            });
        }

        $asignaturas = $query->paginate(10);

        return view('livewire.catalogos.asignaturas.table-component', [
            'asignaturas' => $asignaturas,
        ]);
    }
}
