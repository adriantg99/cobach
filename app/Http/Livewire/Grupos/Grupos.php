<?php
namespace App\Http\Livewire\Grupos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;

class Grupos extends Component
{
    public $Grupos;
    public $Ciclos;
    public $Plantel;
    public $ciclos_escolares;
    public $select_plantel;
    public $select_semestre;
    public $variable_aux;
    public $datos_grupos;
    public $plantel_anterior;
    public $ciclo_escolares_pasado;
    
    protected $listeners = ['confirmar_borrado'];

    public function confirmar_borrado($grupo_id)
    {
        $cursos = null;
        $alumnos = null;
        //dd($grupo_id);
        $grupo = GruposModel::find($grupo_id);
        //
        session(['grupos_ciclos_escolares' => $this->ciclos_escolares]);
        session(['grupos_select_plantel' => $this->select_plantel]);

        $cursos = $grupo->cursos;
        if(count($cursos) > 0)
        {
            //dd($cursos);
            //El grupo tiene cursos asignados
            return redirect()->route('Grupos.crear.index')->with('warning','NO se eliminó correctamente el grupo con el id:'.$grupo_id.' por que tiene cursos asociados');
        }
        else
        {
            $alumnos = $grupo->alumnos;
            if(count($alumnos) > 0)
            {

                //El grupo tiene alumnos asignados
                return redirect()->route('Grupos.crear.index')->with('warning','NO se eliminó correctamente el grupo con el id:'.$grupo_id.' por que tiene alumnos asociados');
            }
            else
            {
                //Se realiza el borrado
                return redirect()->route('grupos.eliminar', $grupo_id);
            }
        }
    }

    public function realizarBusqueda()
    {
        $this->datos_grupos = []; // Limpia los datos anteriores
        // Verifica si los tres select tienen valores seleccionados
        if ($this->ciclos_escolares !== "" && $this->select_plantel !== "") {
            $result = GruposModel::select('esc_grupo.id as id_grupo','plantel_id', 'esc_grupo.ciclo_esc_id', 'esc_grupo.periodo', 'esc_grupo.gpo_base', 'esc_grupo.nombre', 'esc_grupo.descripcion', 'cat_ciclos_esc.nombre as periodo', 'cat_plantel.nombre as nombre_plantel', 'esc_grupo.turno_id')
                ->where('ciclo_esc_id', $this->ciclos_escolares)
                ->where('plantel_id', $this->select_plantel)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('descripcion', '!=', 'turno_especial')
                //Agregar el filtro del plan de estudio 
                //->where('periodo_id', $this->select_semestre)
                ->join('cat_plantel', 'cat_plantel.id', '=', 'esc_grupo.plantel_id') // Ajusta "otro_campo" a tu modelo
                ->join('cat_ciclos_esc', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
                ->orderBy('esc_grupo.periodo', 'asc')
                ->orderBy('esc_grupo.nombre')
                ->get();
            if ($result->count()>0) {
                $this->variable_aux = '1';
                $this->datos_grupos = $result;
                $this->plantel_anterior = $this->select_plantel;
                $this->ciclo_escolares_pasado = $this->ciclos_escolares;
            } else {
                $this->variable_aux = '0';
                $this->capacidad = 0;
                $this->cantidad_grupos_extra = 0;
                $this->cantidad_grupos_matutino = 0;
                $this->cantidad_grupos_vespertino   = 0;
            }
        }
        else{
            
        }
    }

    public function borra_grupo($grupo_id){
        if(Auth()->user()->hasPermissionTo('cursos-borrar'))
        {
            //dd($curso_id);
            $curso = GruposModel::find($grupo_id);
            $curso->delete();

            //Bitacora
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                //'controller'    =>  'UserController',
                'component'     =>  'Grupos_component',
                'function'  =>  'borra_curso',
                'description'   =>  'Se borró el registro del grupo :'.$grupo_id,
            ]);
            $this->dispatchBrowserEvent('carga_sweet_borrar');
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'BorrarCurso',
                //'component'     =>  'FormComponent',
                'function'  =>  'borra_curso',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('index')->with('danger','No tiene los permisos necesarios');
        }
    }


    public function mount()
    {

        //$this->Grupos = GruposModel::all();
        $this->Ciclos = CicloEscModel::select('cat_ciclos_esc.id','cat_ciclos_esc.nombre')
        ->distinct()
        ->orderBy('id', 'desc')
        ->get();
        $this->Plantel = obtenerPlanteles();

        $this->ciclos_escolares = session('grupos_ciclos_escolares');
        $this->select_plantel = session('grupos_select_plantel');
        if(($this->ciclos_escolares!=null) AND ($this->select_plantel!=null))
        {
            $this->realizarBusqueda();
        }

    }

    public function render()
    {
        if($this->ciclos_escolares!=$this->ciclo_escolares_pasado || $this->select_plantel!= $this->plantel_anterior){
            $this->datos_grupos = [];
            session(['grupos_ciclos_escolares' => $this->ciclos_escolares]);
            session(['grupos_select_plantel' => $this->select_plantel]);
        }
        


        return view('livewire.grupos.grupos');
    }
}