<?php

namespace App\Http\Livewire\Catalogos\Nucleos;

use Illuminate\View\View;
use App\Models\Catalogos\NucleoModel;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component {

    /* Traits */

    use WithPagination;

    /* Properties */

    /**
     * ID del usuario autenticado.
     *
     * @var int
     */
    public int $user_id;

    /**
     * ID del núcleo.
     *
     * @var int|null
     */
    public ?int $nucleo_id = null;

    /**
     * Número total de núcleos.
     *
     * @var int
     */
    public int $nucleos_totales = 0;

    /**
     * Propiedad para la paginación de la tabla.
     *
     * @var string
     */
    protected string $paginationTheme = 'bootstrap';

    /**
     * Número de elementos a mostrar por página.
     *
     * @var int
     */
    public int $paginationItems = 10;
    
    /**
     * Nombre del núcleo a buscar.
     *
     * @var string
     */
    public string $nombre_filtrado = '';

    /* Métodos */

    /**
     * Método para cargar el componente.
     *
     * @return void
     */
    public function mount(): void {
        $this->user_id = auth()->id();
    }

    /**
     * Método para renderizar el componente. Incluye la búsqueda de núcleos.
     *
     * @return \Illuminate\View\View La vista de la tabla de núcleos.
     */
    public function render(): View {
        if( $this->nombre_filtrado == '' ) {
            $nucleos = NucleoModel::with(['areaformacion' => function ($query) {
                $query->select('id', 'nombre');
            }])->paginate( $this->paginationItems );
            $count_nucleos = NucleoModel::count();
            $this->nucleos_totales = $count_nucleos;
        } else {
            $nucleos = NucleoModel::where( 'nombre', 'LIKE', '%' . $this->nombre_filtrado . '%' )
                ->with(['areaformacion' => function ($query) {
                    $query->select('id', 'nombre');
                }])
                ->paginate( $this->paginationItems );
            $count_nucleos = NucleoModel::where( 'nombre', 'LIKE', '%' . $this->nombre_filtrado . '%' )->count();
        }
        return view( 'livewire.catalogos.nucleos.table-component', compact( 'nucleos', 'count_nucleos' ) );
    }
}
