<?php
namespace App\Http\Livewire\Administracion\Bitacoras;
use App\Models\Administracion\BitacoraModel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TableComponent extends Component
{
    use WithPagination;
    public $user_id;
    public $path;
    protected $paginationTheme = 'bootstrap';
    public $users, $buscar_usuario;


    public function mount()
    {
        // Cargar usuarios excluyendo los que cumplan con el patrón definido
        $this->users = cache()->remember('users_list', 60, function () {
            return User::where('email', 'NOT REGEXP', '^[a-zA-Z]{2}\d{8}')
                ->select('id', 'name', 'email')
                ->get();
        });
    }
    public function render()
    {
        // Construir la consulta inicial
        $query = BitacoraModel::query();

        // Filtrar por usuario si se selecciona uno
        if (!empty($this->buscar_usuario)) {
            $query->where('user_id', $this->buscar_usuario);
        }

        // Obtener datos paginados y conteo total
        $bitacoras = $query->select('id', 'user_id', 'ip', 'path', 'method', 'controller', 'component', 'function', 'description', 'created_at')
            ->with('user:id,name') // Carga solo 'id' y 'name' del usuario
            ->orderBy('id', 'DESC')
            ->paginate(30);

        $count_bitacoras = $query->count();

        return view('livewire.administracion.bitacoras.table-component', compact('bitacoras', 'count_bitacoras'));
    }
}