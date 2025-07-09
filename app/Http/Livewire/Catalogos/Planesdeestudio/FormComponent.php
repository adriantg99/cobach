<?php
// ANA MOLINA 10/08/2023
namespace App\Http\Livewire\Catalogos\Planesdeestudio;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\PlandeEstudioModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

//Para cargar los selects:
use App\Models\Catalogos\PlantelesModel;
use App\Models\Catalogos\ReglamentoModel;
use App\Models\Catalogos\AreaFormacionModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
class FormComponent extends Component
{
    public $plan_id;
    public $id_plantel;
    public $nombre;
    public $descripcion;
    public $totalperiodos;
    public $totalasignaturas;
    public $id_reglamento;
    public $activo=true;

    public $id_reglamento_change='';

    public $id_areaformacion_change='';

    //asignaturas por área de formación
    public $asignaturas;
    //selección asignaturas por área de formación
    public $seleccionado=[];

    //asignaturas almacenadas
    public $asignaturassel=[];
    public $asignaturasselcomp=[];


    public function mount()
    {

        if($this->plan_id)
        {
            $plan = PlandeEstudioModel::find($this->plan_id);

            $this->nombre = $plan->nombre;
            $this->id_plantel = $plan->id_plantel;
            $this->descripcion = $plan->descripcion;
            $this->totalasignaturas = $plan->totalasignaturas;
            $this->totalperiodos = $plan->totalperiodos;
            $this->activo = $plan->activo;
            $this->id_reglamento= $plan->id_reglamento;

            $this->id_reglamento_change=$plan->id_reglamento;

           $this->cargar_asignaturas();
         }
         else
         {
            $this->id_plantel =(session('id_plantel_change'))
            ? session()->get('id_plantel_change') : ''; /* HACK: */

         }
    }
    public function cargar_asignaturas()
    {
        $asignaturas = DB::table('asi_asignatura')
        ->join('asi_planestudio_asignatura', function($join){
        $join->on('asi_planestudio_asignatura.id_asignatura','=','asi_asignatura.id');

        })
        ->where('id_planestudio', $this->plan_id)->select('asi_asignatura.id','asi_asignatura.nombre','asi_asignatura.clave')->orderby('asi_asignatura.nombre')
        ->get();

        $this->asignaturassel=[];

        $this->asignaturasselcomp=[];


        foreach ($asignaturas as $asignatura)
        {
            $this->asignaturassel[]=$asignatura->id;
            $this->asignaturasselcomp[]=['nombre'=>$asignatura->nombre,'clave'=>$asignatura->clave,'original'=>true,'eliminado'=>false,'agregado'=>true];
        }
    }
    public function guardar()
    {
         $rules=[
            //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
            'id_plantel'                       =>  'required',
            'id_reglamento'                       =>  'required',
            'nombre'                    =>  'required|max:100',
            'descripcion'                   =>  'required|max:250',
            'totalperiodos'                   =>  'required',
            'totalasignaturas'                   =>  'required',
            'activo'                        =>'required',


        ];



        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

               //arreglo para ingresarlo a la tabla

        $data = [
            'nombre'                =>  $this->nombre,
            'id_plantel'      =>  $this->id_plantel,
            'totalperiodos'             =>  $this->totalperiodos,
            'totalasignaturas'             =>  $this->totalasignaturas,
            'descripcion'               =>  $this->descripcion,
            'id_reglamento'=>$this->id_reglamento,
            'activo'=>true,


        ];
        if($this->plan_id == null)
        {
            //Crear registro

            if(Auth()->user()->hasPermissionTo('plandeestudio-crear'))
            {
                PlandeEstudioModel::create($data);
                $plan = PlandeEstudioModel::latest('id')->first();

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creó plan de estudio id:'.$plan->id,
                ]);


                redirect()->route('catalogos.planesdeestudio.index')->with('success','Plan de estudio creado correctamente');
            }
            else
            {
                //No tiene los permisos necesarios

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Usuario sin permisos',
                ]);

                redirect()->route('catalogos.planesdeestudio.index')->with('danger','No tiene los permisos necesarios');
            }
        }
        else
        {
            //Editar registro
            if(Auth()->user()->hasPermissionTo('plandeestudio-editar'))
            {
                $plan = PlandeEstudioModel::find($this->plan_id);
                $plan->update($data);


                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Editó plan de estudio id:'.$plan->id,
                ]);
                redirect()->route('catalogos.planesdeestudio.index')->with('success','Plan de estudio editado correctamente');
            }
            else
            {
                //No tiene los permisos necesarios
                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Usuario sin permisos',
                ]);
                redirect()->route('catalogos.planesdeestudio.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }
    public function render()
    {
        //Para cargar los selects:

        $reglamentos = ReglamentoModel::select('id','nombre')->orderBy('nombre')
        ->get();
        $areasformacion = AreaFormacionModel::select('id','nombre')->orderBy('nombre')
        ->get();


        return view('livewire.catalogos.planesdeestudio.form-component', compact('areasformacion', 'reglamentos'));
    }


    public function changeEventreglamento($idreglamento)
    {
        $this->id_reglamento_change=$idreglamento;

    }
    public function changeEventareaformacion($idareaformacion)
    {
        $this->id_areaformacion_change=$idareaformacion;
        $this->asignaturas = AsignaturaModel::where('id_areaformacion', $this->id_areaformacion_change)->select('id','nombre','periodo','consecutivo','clave')->orderBy('nombre')
        ->get();
        $this->seleccionado=[];


        if(isset($this->asignaturas))
            foreach ($this->asignaturas as $asignatura)
            {
                $asi = collect($this->asignaturassel);

                if($asi->contains($asignatura->id ))

                    $this->seleccionado[]=true;
                else
                    $this->seleccionado[]=false;


            }


    }
    public function changeEvent_seleccionado($index,$asignatura)
    {
        $asi = collect($this->asignaturassel);

        $pos='';

        if($asi->contains($asignatura['id'] ))
            $pos=array_search($asignatura['id'], $this->asignaturassel);


        if ($this->seleccionado[$index])
        {
            if($pos=='')
            {
                 $this->asignaturassel[]=$asignatura['id'];
                $this->asignaturasselcomp[]=['nombre'=>$asignatura['nombre'],'clave'=>$asignatura['clave'],'original'=>false,'eliminado'=>false,'agregado'=>true];
            }
            else
            {
              $this->asignaturasselcomp[$pos]['agregado']=true;
                $this->asignaturasselcomp[$pos]['eliminado']=false;
            }
        }
        else
        {
         if($pos<>'')
                        {
                $this->asignaturasselcomp[$pos]['agregado']=false;
                $this->asignaturasselcomp[$pos]['eliminado']=true;

                if(! $this->asignaturasselcomp[$pos]['original'])
                {
                    unset($this->asignaturassel[$pos]);
                    $this->asignaturassel = array_values($this->asignaturassel);

                    unset($this->asignaturasselcomp[$pos]);
                    $this->asignaturasselcomp = array_values($this->asignaturasselcomp);

                }
            }
        }



    }
    public function guardar_asignaturas()
    {
        $rules=[
            //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
            'id_planestudio'                       =>  'required',
            'id_asignatura'                       =>  'required',

        ];



        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        //$this->validate($rules);

        //borrar y agregar asignaturas
        if(!empty($this->asignaturassel))
            foreach ($this->asignaturassel as $index => $asignatura)
            {

                if($this->asignaturasselcomp[$index]['original'])
                {
                    if ($this->asignaturasselcomp[$index]['eliminado'])
                    {
                        PlandeEstudioAsignaturaModel::where('id_planestudio',$this->plan_id)->where('id_asignatura',$asignatura)->delete();


                        BitacoraModel::create([
                            'user_id'   =>  Auth()->user()->id,
                            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                            'path'      =>  $_SERVER["REQUEST_URI"],
                            'method'    =>  $_SERVER['REQUEST_METHOD'],
                            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                            //'controller'    =>  'UserController',
                            'component'     =>  'FormComponent',
                            'function'  =>  'eliminar',
                            'description'   =>  'Borrar de plan de estudio la asignatura id:'.$asignatura,
                        ]);

                    }}
                else
                {
                    if ($this->asignaturasselcomp[$index]['agregado'])
                    {
                        $data = [
                            'id_planestudio'                =>  $this->plan_id,
                            'id_asignatura'      =>  $asignatura,


                        ];

                        PlandeEstudioAsignaturaModel::create($data);

                        BitacoraModel::create([
                            'user_id'   =>  Auth()->user()->id,
                            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                            'path'      =>  $_SERVER["REQUEST_URI"],
                            'method'    =>  $_SERVER['REQUEST_METHOD'],
                            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                            //'controller'    =>  'UserController',
                            'component'     =>  'FormComponent',
                            'function'  =>  'guardar',
                            'description'   =>  'Creó en plan de estudio la asignatura id:'.$asignatura,
                        ]);
                    }
                }


            }


        $this->cargar_asignaturas();


    }

    public function eliminar($index,$id_asignatura)
    {
        
        if($this->asignaturasselcomp[$index]['original'])
        {
            $this->asignaturasselcomp[$index]['agregado']=false;
            $this->asignaturasselcomp[$index]['eliminado']=true;
        }
        else
        {
            unset($this->asignaturassel[$index]);
            $this->asignaturassel = array_values($this->asignaturassel);

            unset($this->asignaturasselcomp[$index]);
            $this->asignaturasselcomp = array_values($this->asignaturasselcomp);

        }

        $this->seleccionado=[];


        if(isset($this->asignaturas))
            foreach ($this->asignaturas as $asignatura)
            {
                $asi = collect($this->asignaturassel);

                $pos='';
                if($asi->contains($asignatura->id ))
                {
                    $pos=array_search($asignatura['id'], $this->asignaturassel);
                    if (  $this->asignaturasselcomp[$pos]['eliminado'])
                        $this->seleccionado[]=false;
                    else
                        $this->seleccionado[]=true;
                }
                else
                    $this->seleccionado[]=false;


            }
    }
}
