<?php
// ANA MOLINA 05/09/2023
namespace App\Http\Livewire\Adminalumnos\Alumnos;

use App\Models\Adminalumnos\AlumnoModel;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $nombre;
    public $expediente;
    public $planteles;

    public $id_plantel_change;
    public $id_plantel;
    public $search = '';

    public $arreglo_planteles = array();
    protected $paginationTheme = 'bootstrap';

    public function render()
    {

        if (empty($this->id_plantel)) {

            if (permisos() == 1) {
                $alumnos = AlumnoModel::
                    select('alu_alumno.*')
                    //->whereIn('esc_grupo.plantel_id', $validaciones)
                    ->when($this->search && strlen($this->search) >= 5, function ($query) {
                        $query->where('noexpediente', 'like', '%' . $this->search . '%')
                            ->orWhere('alu_alumno.nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('alu_alumno.apellidos', 'like', '%' . $this->search . '%')
                            ->orWhere('alu_alumno.email', 'like', '%' . $this->search . '%')
                            ->orWhere('alu_alumno.correo_institucional', 'like', '%' . $this->search . '%');
                    })
                    ->distinct('alu_alumno.id')
                    ->orderby('id_estatus', 'asc')
                    ->paginate(10);
            } else {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                    ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->select(
                        'alu_alumno.id',
                        'alu_alumno.noexpediente',
                        'alu_alumno.nombre',
                        'alu_alumno.apellidos',
                        'alu_alumno.email',
                        'alu_alumno.correo_institucional',
                        'alu_alumno.id_estatus'
                    )
                    ->whereIn('esc_grupo.plantel_id', $this->arreglo_planteles) // Filtro de planteles
                    ->when($this->search && strlen($this->search) >= 5, function ($query) {
                        $term = '%' . $this->search . '%'; // Preparar término de búsqueda
                        $query->where(function ($q) use ($term) {
                            $q->where('alu_alumno.noexpediente', 'like', $term)
                                ->orWhere('alu_alumno.nombre', 'like', $term)
                                ->orWhere('alu_alumno.apellidos', 'like', $term)
                                ->orWhere('alu_alumno.email', 'like', $term)
                                ->orWhere('alu_alumno.correo_institucional', 'like', $term);
                        });
                    })
                    ->groupBy('alu_alumno.id') // Evitar duplicados
                    ->orderBy('id_estatus', 'desc')
                    ->paginate(10);

            }

            $count_alumnos = $alumnos->count();
        } else {

        }

        return view('livewire.adminalumnos.alumnos.table-component', compact('alumnos', 'count_alumnos'));
    }

    public function mount()
    {
        $planteles = obtenerPlanteles();

        foreach ($planteles as $auxiliar) {
            array_push($this->arreglo_planteles, $auxiliar->id);
        }

        //dd($this->arreglo_planteles);
        //dd($this->planteles);
    }


    public function changeEvent($idplantel)
    {
        /*
        $this->id_plantel_change = $idplantel;
        //variable plantel seleccionado
        session(['id_plantel_change' => $idplantel]);
*/
    }
}
