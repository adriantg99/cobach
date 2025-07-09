<?php
// ANA MOLINA 29/06/2023
namespace App\Http\Livewire\Catalogos\Politicas;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\PoliticaModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

//Para cargar los selects:
use App\Models\Catalogos\AreaFormacionModel;
use App\Models\Catalogos\Politica_variabletipoModel;
use App\Models\Catalogos\Politica_variableModel;
use App\Models\Catalogos\Politica_variableperiodoModel;
use App\Models\Catalogos\Politica_variableletraModel;

use App\Models\Catalogos\Politica_variableletradetModel;
class FormComponent extends Component
{
    public $politica_id=0;
    public $id_areaformacion;
    public $id_variabletipo;
    public $nombre;
    public $descripcion;
    public $formula;
    public $calificacionminima;
    //listado de variables por política
    public $variablesPolitica=[];
    //habilita objeto select
    public $habvar=[];
    //muestra variable seleccionada
    public $varsel=[];
    //muestra variable seleccionada id
    public $varselid=[];

    public $variable_esletra=false;

    public $variablesletra ;

    public function mount()
    {
        if($this->politica_id)
        {
            $politica = PoliticaModel::find($this->politica_id);

            $this->nombre = $politica->nombre;
            $this->id_areaformacion = $politica->id_areaformacion;
            $this->id_variabletipo =$politica->id_variabletipo;
            $this->descripcion = $politica->descripcion;
            $this->formula = $politica->formula;
            $this->calificacionminima = $politica->calificacionminima;

            $this->variable_esletra=Politica_variabletipoModel::find($this->id_variabletipo)->esletra;

            //muestra variables
            $variables=Politica_variableModel::where('id_politica',$this->politica_id)->get();
             if(isset($variables))
                foreach ($variables as $varper)
                {
                    $this->variablesPolitica[] = ['id_variableperiodo' => $varper->id_variableperiodo, 'esregularizacion' =>$varper->esregularizacion];
                    $this->habvar[]=false;

                    $variable=Politica_variableperiodoModel::find($varper->id_variableperiodo);

                    $this->varsel[]=$variable->nombre.' - '.$variable->descripcion;

                    $this->varselid[]=$variable->id;
                }
         }
    }

    public function guardar()
    {
        $rules=[
            //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
            'nombre'                    =>  'required|max:100',
            'id_areaformacion'                       =>  'required',
            'id_variabletipo'                   =>  'required',
            'descripcion'                   =>  'required|max:100',


        ];


        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

               //arreglo para ingresarlo a la tabla

        $data = [
            'nombre'                =>  $this->nombre,
            'id_areaformacion'      =>  $this->id_areaformacion,
            'id_variabletipo'             =>  $this->id_variabletipo,
            'descripcion'               =>  $this->descripcion,


        ];

        if($this->politica_id == null)
        {
            //Crear registro
            if(Auth()->user()->hasPermissionTo('politica-crear'))
            {
                PoliticaModel::create($data);
                $politica = PoliticaModel::latest('id')->first();

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creó política id:'.$politica->id,
                ]);


                redirect()->route('catalogos.politicas.index')->with('success','Politica creado correctamente');
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

                redirect()->route('catalogos.politicas.index')->with('danger','No tiene los permisos necesarios');
            }
        }
        else
        {
            //Editar registro
            if(Auth()->user()->hasPermissionTo('politica-editar'))
            {
                $politica = PoliticaModel::find($this->politica_id);
                $politica->update($data);

                foreach ($this->variablesPolitica as $variable) {
                    $variablePol=Politica_variableModel::where('id_politica',$this->politica_id)->where('id_variableperiodo',$variable['id_variableperiodo'])->first();

                     Politica_variableletradetModel::where('id_politicavariable',$variablePol->id)->delete();
                }
                if ($this->variable_esletra)
                {
                    foreach ($this->variablesPolitica as $variable) {
                        $variablePol=Politica_variableModel::where('id_politica',$this->politica_id)->where('id_variableperiodo',$variable['id_variableperiodo'])->first();

                        foreach ($this->variablesletra as $variableletra) {
                            $datavarle = [
                                'id_politicavariable'=>$variablePol->id,
                                'id_variableletra'=>$variableletra['id']

                            ];
                            Politica_variableletradetModel::create($datavarle);
                        }
                    }
                }

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Editó política id:'.$politica->id,
                ]);
                redirect()->route('catalogos.politicas.index')->with('success','Politica editado correctamente');
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
                redirect()->route('catalogos.politicas.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }
    public function render()
    {
        //Para cargar los selects:
        $areasformacion = AreaFormacionModel::select('id','nombre')->orderBy('nombre')
        ->get();
        $variablestipo = Politica_variabletipoModel::select('id','nombre')->orderBy('nombre')
        ->get();
        $variablesperiodo = Politica_variableperiodoModel::select('id','nombre','descripcion','orden')->orderBy('orden')
        ->get();
        $this->variablesletra = Politica_variableletraModel::select('id', 'descripcion','valor')->orderBy('valor', 'desc')
        ->get();
        return view('livewire.catalogos.politicas.form-component', compact('areasformacion', 'variablestipo','variablesperiodo'));
    }
    public function agregarVariable()
    {
        //valida que la variable no se encuentre seleccionada previamente
       if (count($this->variablesPolitica)<Politica_variableperiodoModel::select ('id')->count())
           {
                $this->variablesPolitica[] = ['id_variableperiodo' => 0, 'esregularizacion' => false];
                $this->habvar[]=true;
                $this->varsel[]='';
                $this->varselid[]='';
            }
        }
    public function eliminarVariable($index)
    {

        $variablePolitica=$this->variablesPolitica[$index];
        $variable=Politica_variableModel::where('id_politica',$this->politica_id)->where('id_variableperiodo',$variablePolitica['id_variableperiodo'])->first();

                //borra en base de datos

       if(isset($variable))
       {
         Politica_variableletradetModel::where('id_politicavariable',$variable->id)->delete();

        Politica_variableModel::where('id',$variable->id)->delete();
       }
        //borra en memoria
        unset($this->variablesPolitica[$index]);
        $this->variablesPolitica = array_values($this->variablesPolitica);

        unset($this->habvar[$index]);
        $this->habvar = array_values($this->habvar);

        unset($this->varsel[$index]);
        $this->varsel = array_values($this->varsel);

        unset($this->varselid[$index]);
        $this->varselid = array_values($this->varselid);

    }

    public function changeEvent($idvariable,$idx,$name)
    {
       // dd($idvariable);
        $variable=Politica_variableperiodoModel::find($idvariable);
        //dd($variable);
        $vars = collect($this->varselid);
        //dd($vars);
        if($vars->contains($idvariable))
        {
            $this->variablesPolitica[$idx]='';

            session()->flash('message', 'Variable agregada anteriormente!');
            $this->dispatchBrowserEvent('alert',
            ['type' => 'info',  'message' => 'Variable agregada anteriormente!']);
        }
        else
        {
            $this->habvar[$idx]=false;
            $this->varsel[$idx]=$variable->nombre.' - '.$variable->descripcion;
            $this->varselid[$idx]=$idvariable;

            //inserta en variables
            $datavar = [
                'id_politica'=>$this->politica_id,
                'id_variableperiodo'=>$idvariable,
                'esregularizacion'=>false,
                'eslimite'=>false

            ];
            Politica_variableModel::create($datavar);

            $politicavariable = Politica_variableModel::latest('id')->first();

            //dd($this->id_variabletipo);
            $this->id_variabletipo=PoliticaModel::find($this->politica_id )->id_variabletipo;

            //inserta en variables tipo letra
            $this->variable_esletra=Politica_variabletipoModel::find($this->id_variabletipo )->esletra;

            if ($this->variable_esletra)
            {
                foreach ($this->variablesletra as $variableletra) {
                    $datavarle = [
                        'id_politicavariable'=>$politicavariable->id,
                        'id_variableletra'=>$variableletra['id']

                    ];
                    Politica_variableletradetModel::create($datavarle);
                }


            }
        }

    }
    public function changeEvent_seleccionado($index,$idvariable)
    {
          if ($this->variablesPolitica[$index]['esregularizacion'])
        {
            $contar=0;
            foreach ($this->variablesPolitica as $varpol) {
                if ($contar!=$index)
                    $this->variablesPolitica[$contar]['esregularizacion']=false;
                $contar++;

            }

            $variable=Politica_variableModel::where('id_politica',$this->politica_id)->where('esregularizacion',true);

            $data = [
                'esregularizacion'                =>  false,
            ];

            $variable->update($data);

            $data = [
                'esregularizacion'                =>  true,];

        }
        else
        {

            $data = [
                'esregularizacion'                =>  false,
            ];
        }
        $variable=Politica_variableModel::where('id_politica',$this->politica_id)->where('id_variableperiodo',$idvariable);

        $variable->update($data);

    }
    public function changeEventLetra($idvariableletra)
    {
        $this->variable_esletra=Politica_variabletipoModel::find($idvariableletra)->esletra;

    }
}
