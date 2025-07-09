<?php
// ANA MOLINA 10/07/2024
namespace App\Http\Livewire\Adminalumnos\Equivalencia;
use App\Models\Adminalumnos\EquivalenciaModel;
use App\Models\Adminalumnos\RevalidacionModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class TableComponent extends Component
{
    use WithPagination;

    public $alumno;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $plantelesIds = obtenerPlanteles()->pluck('id')->toArray();
        
        if ($this->alumno == null) {
            // $rev = RevalidacionModel::select("id,'R' as tipoer,alumno,institucion,folio,fecha")->get();

            // $eq=EquivalenciaModel::select("id,'E' as tipoer,alumno,institucion,folio,fecha")->get();

            // $equivalencia=$eq->merge($rev);
            // //->paginate(10);
            // dd($equivalencia);
            // $count_equivalencia=$equivalencia->count();

            // Realizar la consulta utilizando Query Builder de Eloquent
            $revalidaciones = \DB::table('esc_revalidacion')
                ->select('esc_revalidacion.id', \DB::raw("'R' as tipoer"), 'alumno', 'institucion', 'folio', 'fecha', 'fecha_aut', 'cat_plantel.nombre')
                ->join('cat_plantel', 'cat_plantel.id', '=', 'esc_revalidacion.plantel_id');

            if (array_intersect(Auth::user()->getRoleNames()->toArray(), ['control_escolar', 'super_admin'])) {
                $equivalencias = \DB::table('esc_equivalencia')
                    ->select('esc_equivalencia.id', \DB::raw("'E' as tipoer"), 'alumno', 'institucion', 'folio', 'fecha', 'fecha_aut', 'cat_plantel.nombre')
                    ->join('cat_plantel', 'cat_plantel.id', '=', 'esc_equivalencia.plantel_id');
            } else {
                $equivalencias = \DB::table('esc_equivalencia')
                    ->select('esc_equivalencia.id', \DB::raw("'E' as tipoer"), 'alumno', 'institucion', 'folio', 'fecha', 'fecha_aut', 'cat_plantel.nombre')
                    ->join('cat_plantel', 'cat_plantel.id', '=', 'esc_equivalencia.plantel_id')
                    ->where('esc_equivalencia.expediente', '!=', 'NULL');
            }


            // Aplicamos el filtro para los planteles y combinamos los resultados
            $equivalencia = $revalidaciones
                ->whereIn('cat_plantel.id', $plantelesIds)
                ->union($equivalencias->whereIn('cat_plantel.id', $plantelesIds))
                ->orderByDesc('fecha')
                ->orderByDesc('id')
                ->get();
            //$equivalencia=DB::select($sql);
            $count_equivalencia = count($equivalencia);
        } else {
            $alumnoBusqueda = '%' . $this->alumno . '%';

            // Construimos la primera consulta para la tabla `esc_revalidacion`
            $revalidaciones = \DB::table('esc_revalidacion')
                ->select('esc_revalidacion.id', \DB::raw("'R' as tipoer"), 'alumno', 'institucion', 'folio', 'fecha', 'fecha_aut', 'cat_plantel.nombre')
                ->join('cat_plantel', 'cat_plantel.id', '=', 'esc_revalidacion.plantel_id')
                ->where('alumno', 'like', $alumnoBusqueda);

            // Construimos la segunda consulta para la tabla `esc_equivalencia`
            $equivalencias = \DB::table('esc_equivalencia')
                ->select('esc_equivalencia.id', \DB::raw("'E' as tipoer"), 'alumno', 'institucion', 'folio', 'fecha', 'fecha_aut', 'cat_plantel.nombre')
                ->join('cat_plantel', 'cat_plantel.id', '=', 'esc_equivalencia.plantel_id')
                ->where('alumno', 'like', $alumnoBusqueda);

            // Combinamos ambas consultas con `union` y ordenamos por `fecha` en orden descendente
            $equivalencia = $revalidaciones
                ->union($equivalencias)
                ->orderByDesc('fecha')
                ->get();

            // Contamos los resultados obtenidos
            $count_equivalencia = $equivalencia->count();

        }

        return view('livewire.adminalumnos.equivalencia.table-component', compact('equivalencia', 'count_equivalencia'));
    }
}
