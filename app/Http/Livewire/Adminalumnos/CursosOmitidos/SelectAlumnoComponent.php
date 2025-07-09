<?php

namespace App\Http\Livewire\Adminalumnos\CursosOmitidos;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Cursos\CursosModel;
use App\Models\Cursos\CursosOmitidosModel;
use App\Models\Catalogos\CicloEscModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SelectAlumnoComponent extends Component
{
    public $plantel_id;
    public $ciclo_esc_id;
    public $alumnos_vista;
    public $alumno_id;
    public $alumno;
    public $kardex;
    public $asignatura_clave;
    public $curso_plantel_id;


    public function mount()
    {
        $sql = "SELECT tmp_alumnos_".Auth()->user()->id.".*, alu_alumno.noexpediente, alu_alumno.nombre, alu_alumno.apellidos ";
        $sql = $sql."FROM tmp_alumnos_".Auth()->user()->id." ";
        $sql = $sql."INNER JOIN alu_alumno ON tmp_alumnos_".Auth()->user()->id.".alumno_id = alu_alumno.id ORDER BY alu_alumno.apellidos";
        $alum_vista = DB::select($sql);

        $this->alumnos_vista = $alum_vista;
    }

    public function ingresa_alumno()
    {
        BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'SelectAlumnoComponent',
            //'component'     =>  'FormComponent',
            'function'  =>  'ingresa_alumno',
            'description'   =>  'Seleccionó recursar la curso_plantel_id: '.$this->curso_plantel_id.' ',
        ]);
        $curso_plantel = CursosModel::find($this->curso_plantel_id);
        $grupo = $curso_plantel->grupo;

        $gpo_alum = GrupoAlumnoModel::where('grupo_id',$grupo->id)
            ->where('alumno_id',$this->alumno_id)->first();
        if(is_null($gpo_alum))
        {
            $data = [
                'grupo_id'  =>  $grupo->id,
                'alumno_id' =>  $this->alumno_id,
            ];
            $gpo_alum = GrupoAlumnoModel::create($data);
        }
        BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'SelectAlumnoComponent',
            //'component'     =>  'FormComponent',
            'function'  =>  'ingresa_alumno',
            'description'   =>  'Se crea o se selecciona el grupo al que pertenece el curso gpo_id: '.$gpo_alum->id.' ',
        ]);
        
        $sql = "SELECT esc_cursos_omitidos.*, esc_curso.grupo_id ";
        $sql = $sql."FROM esc_cursos_omitidos ";
        $sql = $sql."INNER JOIN esc_curso ON esc_cursos_omitidos.curso_id = esc_curso.id ";
        $sql = $sql."WHERE ((esc_cursos_omitidos.alumno_id = ".$this->alumno_id.") AND ";
        $sql = $sql."(esc_curso.grupo_id = ".$grupo->id."))";
        //dd($sql);
        $cursos_alumnos = DB::select($sql);
        if(count($cursos_alumnos)==0)
        {
            $sql = "SELECT esc_curso.id AS curso_id ";
            $sql = $sql."FROM esc_curso WHERE esc_curso.grupo_id = ".$grupo->id;
            //dd($sql);
            $cursos_alumnos = DB::select($sql);
        }
        else
        {
            //hay cursos pertenecientes al grupo que se seleccoono.
            foreach ($cursos_alumnos as $ca) 
            {
                $cu_omit = CursosOmitidosModel::find($ca->id);
                $cu_omit->delete();
            }
        }
        //dd($cursos_alumnos);
        //$sql = "DELETE FROM esc_cursos_omitidos WHERE alumno_id = ".$this->alumno_id;
        //$x = DB::select($sql);

        foreach($cursos_alumnos as $cur)
        {
            $data = [
                'curso_id'      =>  $cur->curso_id,
                'alumno_id'     =>  $this->alumno_id,
                'motivo'        =>  'RECURSAMIENTO',
            ];

            if($cur->curso_id <> $curso_plantel->id)
            {
                $curso_omit = CursosOmitidosModel::create($data);
                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller'    =>  'SelectAlumnoComponent',
                    //'component'     =>  'FormComponent',
                    'function'  =>  'ingresa_alumno',
                    'description'   =>  'Se crea los registros de cursos omit id '.$curso_omit->id.' curso_id:'.$cur->curso_id.' alumno_id:'.$this->alumno_id,
                ]);
            }

        }


        $this->dispatchBrowserEvent('name-updated');
        $this->dispatchBrowserEvent('confirma_ingreso');
        $this->alumno_id = null;
        $this->asignatura_clave = null;
        $this->curso_plantel_id = null;
        //dd('dddd');
    }

    public function render()
    {
        //$alumno = null;
        if(is_null($this->alumno_id) == false)
        {
            if(is_null($this->alumno)==false)
            {
                if($this->alumno_id <> $this->alumno['id'])
                {
                    $this->alumno = AlumnoModel::find($this->alumno_id)->toArray();
                    $this->dispatchBrowserEvent('carga_sel2');
                    $this->asignatura_clave = null;
                    $this->curso_plantel_id = null;
                }
            }
            else
            {
                $this->alumno = AlumnoModel::find($this->alumno_id)->toArray();
                $this->dispatchBrowserEvent('carga_sel2');
                $this->asignatura_clave = null;
                $this->curso_plantel_id = null;
            }
            $this->kardex = DB::select('call pa_kardex(?)',array( $this->alumno_id));

            BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'SelectAlumnoComponent',
            //'component'     =>  'FormComponent',
            'function'  =>  'render',
            'description'   =>  'Consulta al alumno: '.$this->alumno_id,
            ]);
        }
        
        if($this->asignatura_clave)
        {
            $ciclo_esc_activo = CicloEscModel::where('activo',1)->first();
            $sql = "SELECT esc_grupo_alumno.*, esc_grupo.ciclo_esc_id, asi_asignatura.clave, esc_grupo.plantel_id, esc_grupo.nombre ";
            $sql = $sql."FROM esc_grupo_alumno ";
            $sql = $sql."INNER JOIN esc_grupo ON esc_grupo_alumno.grupo_id = esc_grupo.id ";
            $sql = $sql."INNER JOIN esc_curso ON esc_curso.grupo_id = esc_grupo.id ";
            $sql = $sql."INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id ";
            $sql = $sql."WHERE ((esc_grupo_alumno.alumno_id = ".$this->alumno_id.") AND ";
            $sql = $sql."(esc_grupo.ciclo_esc_id = ".$ciclo_esc_activo->id.") AND (asi_asignatura.clave = '".$this->asignatura_clave."') AND ";
                $sql = $sql."(esc_grupo.nombre <> 'ActasExtemporaneas') AND ";
            $sql = $sql."(esc_curso.id NOT IN (SELECT curso_id FROM esc_cursos_omitidos WHERE alumno_id = ".$this->alumno_id.")))";
            
            $estatus_asignatura = DB::select($sql);

            if($estatus_asignatura)
            {
                //dd('La asignatura ya la está cursando');
                $cursos_del_plantel = null;
                //dd($estatus_asignatura);
            }
            else
            {
                

                //dd('buscar cursos en el plantel');
                $sql = "SELECT esc_curso.grupo_id, esc_grupo.plantel_id, esc_grupo.ciclo_esc_id, esc_curso.id AS curso_id, ";
                $sql = $sql."asi_asignatura.clave, cat_turno.nombre AS turno, asi_asignatura.nombre AS asignatura, esc_grupo.nombre, esc_grupo.descripcion ";
                $sql = $sql."FROM esc_curso ";
                $sql = $sql."INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id ";
                $sql = $sql."INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id ";
                $sql = $sql."INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id ";
                $sql = $sql."WHERE ((esc_grupo.plantel_id = ".$this->plantel_id.") AND ";
                $sql = $sql."(esc_grupo.ciclo_esc_id = ".$ciclo_esc_activo->id.") AND ";
                $sql = $sql."(asi_asignatura.clave = '".$this->asignatura_clave."'))";
                //dd($sql);
                $cursos_del_plantel = DB::select($sql);
                //dd($cursos_del_plantel);

                BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'SelectAlumnoComponent',
            //'component'     =>  'FormComponent',
            'function'  =>  'render',
            'description'   =>  'Seleccionó recursar la asignatura: '.$this->asignatura_clave.' ',
                ]);
            }

            //dd($this->alumno_id);
        }
        else
        {
            $estatus_asignatura = null;
            $cursos_del_plantel = null;
        }


        return view('livewire.adminalumnos.cursos-omitidos.select-alumno-component', compact('estatus_asignatura', 'cursos_del_plantel'));
    }
}
