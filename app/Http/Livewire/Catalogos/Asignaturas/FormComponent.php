<?php
// ANA MOLINA 28/06/2023
namespace App\Http\Livewire\Catalogos\Asignaturas;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AreaFormacionModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\NucleoModel;
use App\Models\Cursos\CursosModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormComponent extends Component
{
    public $asignatura_id;
    public $nombre;
    public $id_areaformacion;
    public $id_nucleo;
    public $periodo; //Se refiere a los semestres/cuatrimestres
    public $consecutivo;
    public $clave; //Se forma de los siguientes datos: PERIODO, AREA_FORMACION, NUCLEO, CONSECUTIVO
    public $boleta;
    public $kardex;
    public $expediente;
    public $certificado;
    public $optativa;
    public $activa=1;
    public $creditos;
    public $horas_semana;
    public $nombre_completo;
    public $afecta_promedio;

    //para generar la clave automática: periodo, área formación, núcleo, consecutivo
    public $periodo_change='';
    public $id_areaformacion_change='';
    public $id_nucleo_change='';
    public $consecutivo_change='';
    public $clave_change='';



    public function mount()
    {
        if($this->asignatura_id)
        {
            $asignatura = AsignaturaModel::find($this->asignatura_id);

            $this->nombre = $asignatura->nombre;
            $this->id_areaformacion = $asignatura->id_areaformacion;
            $this->id_nucleo =$asignatura->id_nucleo;
            $this->periodo = $asignatura->periodo;
            $this->consecutivo = $asignatura->consecutivo;
            $this->clave = $asignatura->clave;
            $this->boleta = $asignatura->boleta;
            $this->kardex = $asignatura->kardex;
            $this->expediente = $asignatura->expediente;
            $this->certificado = $asignatura->certificado;
            $this->optativa = $asignatura->optativa;
            $this->activa = $asignatura->activa;
            $this->creditos = $asignatura->creditos;
            $this->horas_semana = $asignatura->horas_semana;
            $this->nombre_completo = $asignatura->nombre_completo;
            $this->afecta_promedio = $asignatura->afecta_promedio;
            //para generar la clave automática
            $this->periodo_change = substr($asignatura->clave,0,1) ;
            $this->id_areaformacion_change =substr($asignatura->clave,1,2) ;
            $this->id_nucleo_change =substr($asignatura->clave,3,2) ;
            $this->consecutivo_change = substr($asignatura->clave,5,2) ;
            $this->clave_change=$asignatura->clave;

        }
    }

    public function guardar()
    {
        $rules=[
            //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
            'nombre'                    =>  'required|max:250',
            'id_areaformacion'                       =>  'required',
            'id_nucleo'                   =>  'required',
            'periodo'                   =>  'required',
            'consecutivo'                 =>  'required|max:2',
            'clave'                      =>  'required|max:8',
            'creditos'               =>  'required',
            'horas_semana'                  =>  'required',
            'nombre_completo'           =>  'required|max:500',

        ];


        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

       /*  $areaformacion = AreaFormacionModel::where('id',$this->id_areaformacion)->first();
        $nucleo = NucleoModel::where('id',$this->id_nucleo)->first(); */

        //arreglo para ingresarlo a la tabla
        if(is_null(  $this->boleta))
            $boleta=false;
        else
            $boleta=$this->boleta;

        if(is_null(  $this->kardex))
            $kardex=false;
        else
            $kardex=$this->kardex;
        if(is_null(  $this->expediente))
            $expediente=false;
        else
            $expediente=$this->expediente;
        if (is_null(  $this->certificado))
            $certificado=false;
        else
            $certificado=$this->certificado;
        if(is_null(  $this->optativa))
            $optativa=false;
        else
            $optativa=$this->optativa;
        if(is_null(  $this->activa))
            $activa=false;
        else
            $activa=$this->activa;
        if(is_null(  $this->afecta_promedio))
            $afecta_promedio=false;
        else
            $afecta_promedio=$this->afecta_promedio;


        $data = [
            'nombre'                =>  $this->nombre,
            //'id_areaformacion'      =>  $this->id_areaformacion,
            'id_nucleo'             =>  $this->id_nucleo,
            'periodo'               =>  $this->periodo,
             'consecutivo'             =>  $this->consecutivo,
            //'clave'                  =>  $this->clave_change,     <<------ cambioLS
             'clave'                  =>  $this->clave,
            'boleta'           =>  $boleta,
            'kardex'        =>  $kardex,
            'expediente'          =>  $expediente,
            'certificado'         =>  $certificado,
            'optativa'     =>  $optativa,
            'activa'            =>  $activa,
            'afecta_promedio'   =>  $afecta_promedio,
            'creditos'           =>  $this->creditos,
            'horas_semana'              =>  $this->horas_semana,
            'nombre_completo'       =>  $this->nombre_completo,

        ];


        if($this->asignatura_id == null)
        {
            //Crear registro
            if(Auth()->user()->hasPermissionTo('asignatura-crear'))
            {
                $data += ['id_areaformacion'      =>  $this->id_areaformacion];

                AsignaturaModel::create($data);
                $asignatura = AsignaturaModel::latest('id')->first();

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creó asignatura id:'.$asignatura->id,
                ]);


                redirect()->route('catalogos.asignaturas.index')->with('success','Asignatura creado correctamente');
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

                redirect()->route('catalogos.asignaturas.index')->with('danger','No tiene los permisos necesarios');
            }
        }
        else
        {
            //Editar registro
            if(Auth()->user()->hasPermissionTo('asignatura-editar'))
            {
                $asignatura = AsignaturaModel::find($this->asignatura_id);
                //Revisa si hay cursos involucrados
                $cursos = CursosModel::where('asignatura_id',$asignatura->id)->first();
                if($cursos)
                {
                    $asignatura->update($data);
                    $with_success = "Asignatura editado correctamente sin modificar área de formación (para mantener consistencia del historial de calificaciones)";
                }
                else
                {
                    $data += ['id_areaformacion'      =>  $this->id_areaformacion];
                    $asignatura->update($data);
                    $with_success = "Asignatura editado correctamente. id:".$asignatura->id;
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
                    'description'   =>  'Editó asignatura id:'.$asignatura->id,
                ]);

                redirect()->route('catalogos.asignaturas.index')->with('success',$with_success);
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

                redirect()->route('catalogos.asignaturas.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }

    public function render()
    {
        //Para cargar los selects:
        $areasformacion = AreaFormacionModel::select('id','nombre')->orderBy('id')
        ->get();

        $nucleos = NucleoModel::select('id','nombre', 'clave_consecutivo')->where('areaformacion_id', $this->id_areaformacion)->orderBy('id')
        ->get();



        return view('livewire.catalogos.asignaturas.form-component', compact('areasformacion', 'nucleos'));
    }
    public function get_clave_automatica()
    {
        $this->clave_change= $this->periodo_change. substr('0'.$this->id_areaformacion_change,-2). substr('0'.$this->id_nucleo_change,-2). substr('0'.$this->consecutivo_change,-2);
        $this->clave=$this->clave_change;
    }
    public function changeEventperiodo($periodo)
    {
        $this->periodo_change=$periodo;
        $this->get_clave_automatica();
    }
    public function changeEventareaformacion($idareaformacion)
    {
        $this->id_areaformacion_change=$idareaformacion;
         $this->get_clave_automatica();
    }

    public function changeEventnucleo($idnucleo)
    {
        $this->id_nucleo_change=$idnucleo;
        $this->get_clave_automatica();
    }
    public function changeEventconsecutivo($consecutivo)
    {
        $this->consecutivo_change=$consecutivo;
        $this->get_clave_automatica();
    }

}

