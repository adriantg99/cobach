<?php

namespace App\Http\Livewire\Adminalumnos\Traslados;

use App\Imports\Controlesc\TrasladosImport;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\Formato_importarModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class TrasladosComponent extends Component
{
    use WithFileUploads;

    public $file;
    public $plantel_id;
    public $semestre;
    public $ciclo = [];
    public $editando_sem;
    public $tabla_cargada;
    public $asignatura = [];
    public $alumno_id;
    public $registros;

    public function mount()
    {
        $this->editando_sem = false;
        $this->tabla_cargada = false;
        BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'TrasladosComponent',
            //'component'     =>  'FormComponent',
            'function'  =>  'mount',
            'description'   =>  'Ingresó a modulo de traslados',
        ]);
    }

    public function importar()
    {
        $ciclo_escs = Formato_importarModel::select('ciclo_esc_id')->distinct()->get();
        foreach($ciclo_escs as $ciclo_e)
        {
            $ciclo_esc = CicloEscModel::find($ciclo_e->ciclo_esc_id);

            $grupo = GruposModel::where('plantel_id',$this->plantel_id)
                ->where('ciclo_esc_id',$ciclo_esc->id)
                ->where('nombre','Migracion')
                ->first();
            if($grupo == null)
            {
                $data = [
                    'turno_id'          =>  1,
                    'plantel_id'        =>  $this->plantel_id,
                    'ciclo_esc_id'      =>  $ciclo_e->ciclo_esc_id,
                    'periodo'           =>  $ciclo_esc->nombre,
                    'aula_id'           =>  0,
                    'nombre'            =>  'Migracion',
                    'descripcion'       =>  'Migracion '.date('Y-m-d'),
                ];
                $grupo = GruposModel::create($data);
            }
            
            $data = [
                'grupo_id'      =>  $grupo->id,
                'alumno_id'     =>  $this->alumno_id,
            ];
            GrupoAlumnoModel::create($data);

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'TrasladosComponent',
                //'component'     =>  'FormComponent',
                'function'  =>  'importar',
                'description'   =>  'Encontrando o creando grupo: '.$grupo->id.': '.$grupo->nombre.' para el alumno id:'.$this->alumno_id,
            ]);

            $asigns_import = Formato_importarModel::where('ciclo_esc_id',$ciclo_esc->id)->get();

            foreach($asigns_import as $ai)
            {
                $asignatura = AsignaturaModel::find($ai->asignatura_id);

                $curso = CursosModel::where('asignatura_id',$ai->asignatura_id)
                    ->where('grupo_id',$grupo->id)
                    ->first();
                
                if($curso == null)
                {
                    $data = [
                        'plan_estudio_id'           =>  0,
                        'asignatura_id'             =>  $ai->asignatura_id,
                        'docente_id'                =>  0,
                        'grupo_id'                  =>  $grupo->id,
                        'curso_tipo'                =>  0,
                        'nombre'                    =>  $asignatura->nombre,
                    ];

                    $curso = CursosModel::create($data);
                }
                //dd($curso);
                $data = [
                    'alumno_id'     =>  $this->alumno_id,
                    'politica_variable_id'  =>  $asignatura->politica_variable_id('F'),
                    'calificacion_tipo_id'  =>  1,
                    'curso_id'              =>  $curso->id,
                    'calificacion'          =>  $ai->calificacion,
                    'calif'                 =>  $ai->calif,
                    'calificacion_tipo'     =>  'Final',
                ];
                
                $califica =  CalificacionesModel::create($data);

                BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'TrasladosComponent',
                //'component'     =>  'FormComponent',
                'function'  =>  'importar',
                'description'   =>  'Carga curso_id:'.$curso->id.' - '.$curso->nombre.'. Carga la calificacion: '.$califica->id.' Final:'.$califica->calificacion.$califica->calif,
            ]);
            }

        }
        //dd('importacion finalizada');
        BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'TrasladosComponent',
            //'component'     =>  'FormComponent',
            'function'  =>  'importar',
            'description'   =>  'Importación terminada',
        ]);

        redirect('/adminalumnos/ingresos')->with('success','Importación completa'); 
    }


    public function guardar_seleccion()
    {

        $sql="SELECT id, asignatura, clave, ciclo, calificacion, calif, ciclo_esc_id, asignatura_id ";
            $sql=$sql."FROM esc_formato_importar ";
            $sql=$sql."WHERE (SUBSTRING(clave, 1, 1) = ".$this->semestre.") " ;
            $sql=$sql."ORDER BY ciclo, asignatura";
        $lista = DB::select($sql);

        foreach($lista as $ls)
        {
            $data = [
                'alumno_id'     => $this->alumno_id,
                'plantel_id'    => $this->plantel_id,
                'ciclo_esc_id'  => $this->ciclo[$ls->id],
                'asignatura_id' => $this->asignatura[$ls->id],
            ];
            $formato = Formato_importarModel::find($ls->id);
            $formato->update($data);
        }

        $this->editando_sem = false;
        $this->semestre = null;
    }

    public function cargar_otro()
    {
        return redirect()->route('adminalumnos.ingresos.index');
    }

    public function render()
    {
        $alumno=null;
        $semestres=null;
        $planteles=null;
        $lista=null;
        $ciclos=null;

        if(($this->file) AND ($this->tabla_cargada == false))
        {
            //Checar formato
            if(substr($this->file->getRealPath(),-4)<>"xlsx")
            { 
                redirect('/adminalumnos/ingresos')->with('warning','La extención del archivo debe ser xlsx'); 
            }

            $array = Excel::toArray(new TrasladosImport, $this->file->getRealPath());

            if(($array[0][0][0]=='expediente') 
            AND ($array[0][0][1]=='nombre') 
            AND ($array[0][0][2]=='asignatura') 
            AND ($array[0][0][3]=='clave') 
            AND ($array[0][0][4]=='ciclo') 
            AND ($array[0][0][5]=='calificacion') 
            AND ($array[0][0][6]=='tipo') 
            )
            {
                //formato correcto
                //vaciar tabla primero
                $sql = "DELETE FROM esc_formato_importar";
                $vacia = DB::select($sql);

                $cont = 0;
                foreach($array[0] as $excel)
                {
                    if($cont <> 0)
                    {
                        $calificacion = $calif = null;
                        if(is_numeric($excel[5]))
                        { $calificacion = $excel[5]; }
                        else
                        { $calif = $excel[5]; }
                        $data = [
                            'expediente'    =>  $excel[0],
                            'nombre'        =>  $excel[1],
                            'asignatura'    =>  $excel[2],
                            'clave'         =>  $excel[3],
                            'ciclo'         =>  $excel[4],
                            'calificacion'  =>  $calificacion,
                            'calif'         =>  $calif,
                        ];
                        $formato = Formato_importarModel::create($data);
                        //$this->ciclo[$formato->id]=$formato->ciclo_esc_id;
                    }
                    $cont++;
                }
                //---------------------------------------------
                $this->registros = $cont - 1;
                $this->tabla_cargada = true;
                
            }
            else
            {
            //formato incorrecto
                redirect('/adminalumnos/ingresos')->with('warning','El formato del archivo es incorrecto. Verifique que los campos sean: expediente, nombre, asignatura, clave, ciclo, calificacion, tipo');
            }
            
        }
        if($this->tabla_cargada == true)
        {
            $expediente = Formato_importarModel::select('expediente')->distinct()->get();

            if(count($expediente)<>1)
            {
                redirect('/adminalumnos/ingresos')->with('warning','Se encontró mas de un expediente de alumno en el archivo'); 
            }

            $alumno = AlumnoModel::where('noexpediente',$expediente[0]->expediente)->first();
            if($alumno==null)
            { 
                redirect('/adminalumnos/ingresos')->with('warning','No se encontró al alumno: '.$expediente[0]->expedient); 
            }
            else
            {
                $this->alumno_id = $alumno->id;
            }
        }
        //dd($alumno);
        $sql_semestre = "SELECT DISTINCT SUBSTRING(clave, 1, 1) AS semestre FROM esc_formato_importar";
        $semestres = DB::select($sql_semestre);
        //dd($semestres);
        $planteles = PlantelesModel::get();
        $ciclos = CicloEscModel::get();


        if(($this->semestre))
        {
            $sql="SELECT id, asignatura, clave, ciclo, calificacion, calif, ciclo_esc_id, asignatura_id ";
            $sql=$sql."FROM esc_formato_importar ";
            $sql=$sql."WHERE (SUBSTRING(clave, 1, 1) = ".$this->semestre.") " ;
            $sql=$sql."ORDER BY ciclo, asignatura";
            $lista = DB::select($sql);
            if($this->editando_sem == false)
            {
                foreach($lista as $ls)
                {
                    $this->ciclo[$ls->id]=$ls->ciclo_esc_id;
                    $this->asignatura[$ls->id]=$ls->asignatura_id;
                }
            }

            $this->editando_sem=true;
        }

        $asignaturas = AsignaturaModel::orderBy('clave')->get();


        return view('livewire.adminalumnos.traslados.traslados-component', compact('alumno','semestres','planteles','lista','ciclos','asignaturas'));
    }
}
