<?php
// ANA MOLINA 08/08/2023
namespace App\Http\Livewire\Catalogos\Planesdeestudio;

use App\Models\Catalogos\PlandeEstudioModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $nombre;
    public $id_plantel;

    public $id_plantel_change;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        if($this->nombre == null)
        {
            if(empty($this->id_plantel))
            {
                //$planes = PlandeEstudioModel::paginate(10);
                $planes=DB::table('asi_planestudio')
                ->join('cat_plantel', function($join){
                    $join->on('cat_plantel.id','=','asi_planestudio.id_plantel');

                })
                ->select('asi_planestudio.id','asi_planestudio.nombre','cat_plantel.nombre as plantel','asi_planestudio.activo','asi_planestudio.updated_at')->orderby('asi_planestudio.id')
                ->paginate(10);
                $count_planes = PlandeEstudioModel::count();
            }
            else
            {
                //$planes = PlandeEstudioModel::where('id_plantel', $this->id_plantel)->paginate(10);
                $planes=DB::table('asi_planestudio')
                ->join('cat_plantel', function($join){
                $join->on('cat_plantel.id','=','asi_planestudio.id_plantel');

                })
                ->where('id_plantel', $this->id_plantel)
                ->select('asi_planestudio.id','asi_planestudio.nombre','cat_plantel.nombre as plantel','asi_planestudio.activo','asi_planestudio.updated_at')->orderby('asi_planestudio.nombre')
                ->paginate(10);
                $count_planes = PlandeEstudioModel::where('id_plantel', $this->id_plantel)->count();
            }
        }
        else
        {


            if(empty($this->id_plantel))
            {

            //$planes = PlandeEstudioModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->paginate(10);
            $planes=DB::table('asi_planestudio')
            ->join('cat_plantel', function($join){
            $join->on('cat_plantel.id','=','asi_planestudio.id_plantel');

            })
            ->where('asi_planestudio.nombre', 'LIKE', '%'.$this->nombre.'%')
            ->select('asi_planestudio.id','asi_planestudio.nombre','cat_plantel.nombre as plantel','asi_planestudio.activo','asi_planestudio.updated_at')->orderby('asi_planestudio.nombre')
            ->paginate(10);
            $count_planes = PlandeEstudioModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->count();
            }
            else
            {

            //$planes = PlandeEstudioModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->where('id_plantel', $this->id_plantel)->paginate(10);
            $planes=DB::table('asi_planestudio')
            ->join('cat_plantel', function($join){
            $join->on('cat_plantel.id','=','asi_planestudio.id_plantel');

            })
            ->where('asi_planestudio.nombre', 'LIKE', '%'.$this->nombre.'%')->where('id_plantel', $this->id_plantel)
            ->select('asi_planestudio.id','asi_planestudio.nombre','cat_plantel.nombre as plantel','asi_planestudio.activo','asi_planestudio.updated_at')->orderby('asi_planestudio.nombre')
            ->paginate(10);
            $count_planes = PlandeEstudioModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->where('id_plantel', $this->id_plantel)->count();


            }

            //dd($planes);
        }
        return view('livewire.catalogos.planesdeestudio.table-component', compact('planes', 'count_planes'));
    }

    public function changeEvent($idplantel)
    {
        $this->id_plantel_change=$idplantel;
        //variable plantel seleccionado
        session(['id_plantel_change' => $idplantel]);

    }

}
