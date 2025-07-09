<?php

namespace App\Http\Livewire\Adminalumnos\Traslados;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\Formato_importarModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
use App\Models\Catalogos\PlandeEstudioModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TrasladosAlumnosComponent extends Component
{ //INgreso de calificaciones por ciclo Equivalencias o revalidaciones
    public $alumno_id;
    public $alumno_id_old;
    public $alumno;
    public $agregar_ciclo_esc = false;
    public $ciclo_esc;
    public $ciclo_esc_old;
    public $mostrar_selector_de_asignaturas = false;
    public $plan_estudio_id;
    public $asignatura_id;
    public $plan_estudio_id_old;
    public $calificacion;
    public $calif;
    public $plantel_id;

    protected $listeners = ['emitBorraAsi' => 'borrar_asignatura'];

    public function guardar_calificacion()
    {
        $rules = [
            'alumno_id'         =>  'required',
            'plantel_id'        =>  'required',
            'ciclo_esc'      =>  'required',
            'asignatura_id'     =>  'required',
            'calificacion'      =>  'required_without:calif',
            'calif'             =>  'required_without:calificacion',

        ];  
        $this->validate($rules);

        $asignatura = AsignaturaModel::find($this->asignatura_id);
        $ciclo_esc_dato = CicloEscModel::find($this->ciclo_esc);

        $data = [
            'expediente'        =>  $this->alumno->noexpediente,
            'nombre'            =>  $this->alumno->apellidos.' '.$this->alumno->nombre,
            'asignatura'        =>  $asignatura->nombre,
            'clave'             =>  $asignatura->clave,
            'ciclo'             =>  $ciclo_esc_dato->abreviatura,
            'calificacion'      =>  $this->calificacion,
            'calif'             =>  $this->calif,
            'alumno_id'         =>  $this->alumno_id,
            'plantel_id'        =>  $this->plantel_id,
            'ciclo_esc_id'      =>  $this->ciclo_esc,
            'asignatura_id'     =>  $this->asignatura_id,

        ];

        $formato_importar = Formato_importarModel::create($data);
        $this->asignatura_id = null;
        $this->calif = null;
        $this->calificacion = null;
        $this->plan_estudio_id = null;

    }

    public function borrar_asignatura($id_formato)
    {
        $formato = Formato_importarModel::find($id_formato);
        $formato->delete();
    }

    public function ingresar_alumno()
    {
        
        $ciclo_escs = Formato_importarModel::select('ciclo_esc_id')->where('alumno_id',$this->alumno_id)->distinct()->get();
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
            $grupo_alumno_ch = GrupoAlumnoModel::where('grupo_id',$grupo->id)->where('alumno_id',$this->alumno_id)->first();
            if($grupo_alumno_ch==false)
            {
                GrupoAlumnoModel::create($data);
            }

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

            $asigns_import = Formato_importarModel::where('ciclo_esc_id',$ciclo_esc->id)->where('alumno_id',$this->alumno_id)->get();

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
                if($ai->calif == "REV"){
                    $tipo = "7";
                }
                else{
                    $tipo = "5";
                }
                $data = [
                    'alumno_id'     =>  $this->alumno_id,
                    'politica_variable_id'  =>  $asignatura->politica_variable_id('F'),
                    'calificacion_tipo_id'  =>  $tipo,
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

    public function render()
    {
        $plantes_estudio = null;
        $plan_est_asignaturas = null;
        $lista = null;

        $planteles = PlantelesModel::get();

        if($this->alumno_id)
        {
            $lista = Formato_importarModel::where('alumno_id',$this->alumno_id)->get();
            if($this->alumno == null)
            {
                $this->alumno = AlumnoModel::find($this->alumno_id);
                //$this->alumno_id_ant = $this->alumno_id;

                $sql = "SELECT DISTINCT esc_grupo.ciclo_esc_id, cat_ciclos_esc.abreviatura, cat_ciclos_esc.nombre ";
                $sql = $sql."FROM esc_grupo_alumno ";
                $sql = $sql."INNER JOIN esc_grupo ON esc_grupo_alumno.grupo_id = esc_grupo.id ";
                $sql = $sql."INNER JOIN cat_ciclos_esc ON esc_grupo.ciclo_esc_id = cat_ciclos_esc.id ";
                $sql = $sql."INNER JOIN esc_curso ON esc_curso.grupo_id = esc_grupo.id ";
                $sql = $sql."INNER JOIN esc_calificacion ON ((esc_calificacion.curso_id = esc_curso.id) AND (esc_calificacion.alumno_id = ".$this->alumno->id.")) ";
                $sql = $sql."WHERE esc_grupo_alumno.alumno_id = ".$this->alumno->id." ";
                $sql = $sql."ORDER BY esc_grupo.ciclo_esc_id";

                $ciclo_esc_alumn = DB::select($sql);
                //dd($ciclo_esc_alumn);
            } else { $ciclo_esc_alumn = null; }

            
        }
        else
        {
            $ciclo_esc_alumn = null;
        }

        $ciclos_esc = CicloEscModel::get();

        if($this->agregar_ciclo_esc)
        {
            $this->dispatchBrowserEvent('activa_select2_ciclos');
        }
        if(($this->ciclo_esc))
        {
            //Formato_importarModel::where('alumno_id', $this->ciclo_esc)->delete();

            $this->alumno_id_old = $this->alumno_id;

            $this->agregar_ciclo_esc = false;
            $this->mostrar_selector_de_asignaturas = true;

            $plantes_estudio = PlandeEstudioModel::get();
            $this->ciclo_esc_old = $this->ciclo_esc;
            //if(($this->plan_estudio_id) AND ($this->plan_estudio_id <> $this->plan_estudio_id_old))
            if(($this->plan_estudio_id))
            {
                $this->dispatchBrowserEvent('activa_select2_asignaturas');
                $plan_est_asignaturas = PlandeEstudioAsignaturaModel::where('id_planestudio', $this->plan_estudio_id)->get();
                $this->plan_estudio_id_old = $this->plan_estudio_id;
                //dd('ddd');
            }
        }

        if(($this->alumno_id) AND ($this->alumno_id_old <> $this->alumno_id))
        {
            $sql = "DELETE FROM esc_formato_importar WHERE alumno_id = ".$this->alumno_id;
            //dd($sql);
            $d = DB::select($sql);
        }

        return view('livewire.adminalumnos.traslados.traslados-alumnos-component', compact('ciclo_esc_alumn','ciclos_esc', 'plantes_estudio', 'plan_est_asignaturas', 'planteles','lista'));
    }
}
