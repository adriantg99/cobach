<?php
// ANA MOLINA 16/10/2023
namespace App\Http\Livewire\Adminalumnos\Kardex;


use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableComponent extends Component
{
    public $id_ciclo;
    public $id_plantel;
    public $apellidos;
    public $noexpediente;
    public $asignaturas_encontradas;


    public $id_alumno_change=0;
    public $calificaciones;
    public $asignaturas_totales;
    public $clave_asignatura;

    protected $listeners = ['guardar_nuevoCurso', 'cambiar_calificacion', 'eliminar_calificacion'];

public function mount(){
    $this->asignaturas_encontradas = AsignaturaModel::where('nombre_completo', 'not like', 'VER%')->get();
}
    public function render()
    {

        if($this->noexpediente == null)
        {

            if (!empty ($this->apellidos))
            {
                $alu = AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%');
            }
            else{
                $alumnos=array();
                $count_alumnos=0;
            }


            if(empty($this->id_plantel))
            {

                if(empty($this->id_ciclo))
                {
                    if (!empty ($this->apellidos))
                    {
                        $alumnos=$alu->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->count();
                    }

                }
                else
                {
                    if (!empty ($this->apellidos))
                    {
                    $alumnos =$alu->where('cicloesc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                    $count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->where('cicloesc_id', $this->id_ciclo)->count();
                    }
                    else
                    {
                        $alumnos = AlumnoModel::where('cicloesc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('cicloesc_id', $this->id_ciclo)->count();
                        }
                }
            }
            else
            {
                if(empty($this->id_ciclo))
                {
                    if (!empty ($this->apellidos))
                    {
                        $alumnos =$alu->where('plantel_id', $this->id_plantel)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->where('plantel_id', $this->id_plantel)->count();

                    }
                          else
                    {
                        $alumnos =AlumnoModel::where('plantel_id', $this->id_plantel)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('plantel_id', $this->id_plantel)->count();
                    }
                }
                else
                {
                    if (!empty ($this->apellidos))
                    {
                        $alumnos = $alu->where('plantel_id', $this->id_plantel)->where('cicloesc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->where('plantel_id', $this->id_plantel)->where('cicloesc_id', $this->id_ciclo)->count();

                    }
                    else{
                        $alumnos = AlumnoModel::where('plantel_id', $this->id_plantel)->where('cicloesc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('plantel_id', $this->id_plantel)->where('cicloesc_id', $this->id_ciclo)->count();
                    }
                }
            }
        }
        else
        {
            $alumnos = AlumnoModel::where('noexpediente', $this->noexpediente)->get();
            $count_alumnos= AlumnoModel::where('noexpediente', $this->noexpediente)->count();
            if ($count_alumnos==1)
            {
                $this->id_alumno_change=$alumnos[0]->id;
               /*  echo '<script type="text/javascript">
                cargando('.$id_alumno.');
                    </script>'; */
                $this->calificaciones = DB::select('call pa_kardex(?)',array( $this->id_alumno_change));

            }
        }
        $calificaciones=$this->calificaciones;

        //-----------------------------------------------------------------------------------------------PROMEDIOS APROB Y REPROB
        $cantidad = 0;
        $cal_acumulada = 0;
        $reprobados = 0;
        $aprobados = 0;
        $promedio = 0;
        //dd($this->calificacioneska);\
        if($calificaciones)
        {
        foreach($calificaciones as $cal)
        {
        if(is_object($cal)){
            $asignatura = AsignaturaModel::find($cal->asignatura_id);
            
           if($asignatura->kardex)
           {
            if(( is_null($cal->calificacion) == false) OR ( is_null($cal->calif)== false))
            {
                if($asignatura->afecta_promedio)
                {
                    if($cal->calif=="REV")
                    {
                         //$cal_acumulada = $cal_acumulada + 100;
                        $cantidad--;
                    }
                    else
                    {
                        $cal_acumulada = $cal_acumulada + (int)$cal->calificacion;
                    }
                    $cantidad++;
                }
                if(($cal->calificacion>=60) OR ($cal->calif=="AC") OR ($cal->calif=="REV"))
                {
                    $aprobados++; 
                } else { $reprobados++; }
            }
            elseif(( is_null($cal->calificacion3) == false) OR ( is_null($cal->calif3)== false))
            {
                if($asignatura->afecta_promedio)
                {
                    if($cal->calif3=="REV")
                    {
                         //$cal_acumulada = $cal_acumulada + 100;
                        $cantidad--;
                    }
                    else
                    {
                        $cal_acumulada = $cal_acumulada + (int)$cal->calificacion3;
                    }
                    $cantidad++;
                }
                if(($cal->calificacion3>=60) OR ($cal->calif3=="AC") OR ($cal->calif3=="REV"))
                {
                    $aprobados++; 
                } else { $reprobados++; }
            }
            elseif(( is_null($cal->calificacion2) == false) OR ( is_null($cal->calif2)== false))
            {
                if($asignatura->afecta_promedio)
                {
                    if($cal->calif2=="REV")
                    {
                         //$cal_acumulada = $cal_acumulada + 100;
                        $cantidad--;
                    }
                    else
                    {
                        $cal_acumulada = $cal_acumulada + (int)$cal->calificacion2;
                    }
                    $cantidad++;
                }
                if(($cal->calificacion2>=60) OR ($cal->calif2=="AC") OR ($cal->calif2=="REV"))
                {
                    $aprobados++; 
                } else { $reprobados++; }
            }
            elseif(( is_null($cal->calificacion1) == false) OR ( is_null($cal->calif1)== false))
            {
                if($asignatura->afecta_promedio)
                {
                    if($cal->calif1=="REV")
                    {
                        // $cal_acumulada = $cal_acumulada + 100;
                        $cantidad--;
                    }
                    else
                    {
                        $cal_acumulada = $cal_acumulada + (int)$cal->calificacion1;
                    }
                    $cantidad++;
                }
                if(($cal->calificacion1>=60) OR ($cal->calif1=="AC") OR ($cal->calif1=="REV"))
                {
                    $aprobados++; 
                } else { $reprobados++; }
            }
            
           }
           
        }
        

        }
        //dd($cal_acumulada." - ".$cantidad);
            if($cantidad >0)
            {
                $promedio = $cal_acumulada/$cantidad;
            }
        

        }
        //-----------------------------------------------------------------------------------------------PROMEDIOS APROB Y REPROB

        return view('livewire.adminalumnos.kardex.table-component',compact('alumnos', 'count_alumnos','calificaciones','promedio','aprobados','reprobados'));
    }

    public function buscar_asignaturas($clave){
        if(strlen($clave) >= 4) {
            $this->asignaturas_encontradas = AsignaturaModel::where('clave', 'like', '%' . $clave . '%')->get();
        }
    }

    public function cambiar_calificacion($calificacion_id, $calificacion_texto, $calificacion_numerica, $calificacion_tipo_id)
    {
        $nueva_calif = CalificacionesModel::find($calificacion_id);
        
        // Verificar si se encontró el registro
        if ($nueva_calif) {
            // Actualizar el campo adecuado
            $tipo_actual = $nueva_calif->calificacion_tipo_id;
            if ($calificacion_texto == 0) {
                $calificacion_anterior = $nueva_calif->calificacion;
                $nueva_calif->calificacion = $calificacion_numerica;
            } else {
                $calificacion_anterior = $nueva_calif->calif;
                $nueva_calif->calif = $calificacion_texto;
            }
            $nueva_calif->calificacion_tipo_id = $calificacion_tipo_id;
            // Guardar los cambios
            $nueva_calif->save();

            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                //'controller'    =>  'UserController',
                'component' => 'Actualización calificación Kardex',
                'function' => 'Cambio la calificación manualmente del alumno: '. $nueva_calif->alumno_id,
                'description' => 'Se modifico la calificacion con el ID: ' . $nueva_calif->id. ' del valor anterior: '. $calificacion_anterior.' Tipo anterior: '. $tipo_actual,
            ]);
            return 1;
        } else {
            // Manejar el caso en que no se encuentra el registro
            return 0; // O lanzar una excepción, o manejarlo de otra forma
        }
    }

    public function guardar_nuevoCurso($id_curso_anterior, $nueva_clave, $tipo_calif){
        //dd($nueva_clave);
        if(isset($id_curso_anterior))
        {
            $curso_ant = CursosModel::find($id_curso_anterior);
            $ciclo_calif = GruposModel::find($curso_ant->grupo_id);
            
            $ciclo_activo = CicloEscModel::where('activo', 1)->first();
            //if($ciclo_activo){
                /*
                if($ciclo_calif->ciclo_esc_id == $ciclo_activo->id){
                    return 0;
                }
            }
            else{*/
                $sql = "SELECT esc_curso.asignatura_id, esc_grupo_alumno.grupo_id, esc_grupo_alumno.alumno_id, esc_curso.id AS curso_id ";
                $sql = $sql."FROM esc_grupo_alumno ";
                $sql = $sql."INNER JOIN esc_curso ON esc_curso.grupo_id = esc_grupo_alumno.grupo_id ";
                $sql = $sql."WHERE esc_grupo_alumno.alumno_id = ".$this->id_alumno_change." AND esc_curso.asignatura_id = ".$curso_ant->asignatura_id;
    
                $datos_cambio = DB::select($sql);
                //dd($datos_cambio);
                foreach($datos_cambio as $dc)
                {
                    $curso_viejo = CursosModel::find($dc->curso_id);
                    //Crea nuevo curso se asigna al mismo grupo solo se cambia la asignatura_id
                    $asignatura = AsignaturaModel::find($nueva_clave);

                    $buscar_plan_estudio = PlandeEstudioAsignaturaModel::where('id_asignatura', $asignatura->id)->first();


        
                    $buscar_plan_estudio = PlandeEstudioAsignaturaModel::where('id_asignatura', $asignatura->id)->first();


                    $data_p_curso_nuevo = [
                        'plan_estudio_id'   =>  $buscar_plan_estudio->id_planestudio,
                        'asignatura_id'     =>  $nueva_clave,
                        'docente_id'        =>  $curso_viejo->docente_id,
                        'grupo_id'          =>  $curso_viejo->grupo_id,
                        'horario_id'        =>  $curso_viejo->horario_id,
                        'curso_tipo'        =>  $curso_viejo->curso_tipo,
                        'nombre'            =>  $asignatura->nombre,
                    ];
                    $curso_nuevo = CursosModel::create($data_p_curso_nuevo);
    
                    $calificaciones_del_curso_viejo = CalificacionesModel::where('alumno_id',$this->id_alumno_change)
                        ->where('curso_id',$curso_viejo->id)->get();
                    foreach($calificaciones_del_curso_viejo as $cdcv)
                    {
                        //Se recorre las calificaciones reasignando el curso 
                        $cdcv->curso_id = $curso_nuevo->id;
                        $cdcv->update();
                    }
                }
              
                if (!empty($curso_nuevo)) {
                    BitacoraModel::create([
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                        'path' => $_SERVER["REQUEST_URI"],
                        'method' => $_SERVER['REQUEST_METHOD'],
                        //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                        //'controller'    =>  'UserController',
                        'component' => 'Actualización clave Kardex',
                        'function' => 'Cambio clave de asignatura',
                        'description' => 'Se modifico la clave de asignatura para corrección de kardex, la clave antigua: ' . $id_curso_anterior. ' por la nueva clave: '. $nueva_clave,
                    ]);
                    return 1;
                } else {
                    BitacoraModel::create([
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                        'path' => $_SERVER["REQUEST_URI"],
                        'method' => $_SERVER['REQUEST_METHOD'],
                        //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                        //'controller'    =>  'UserController',
                        'component' => 'Actualización clave Kardex',
                        'function' => 'Error en cambio clave',
                        'description' => 'No existe alguna de las variables, checar la consulta con los datos del alumno: '. $this->id_alumno_change. ' Del curso: '. $curso_ant,
                    ]);
                    return 0;
                }
            
            //}
        
        }
        /*
        if(isset($id_curso_anterior)){
            $curso_actual = CursosModel::find($id_curso_anterior);
            $nuevo_curso = AsignaturaModel::where('id',$nueva_clave)->first();
            $curso_nuevo = CursosModel::create([
                'plan_estudio_id' => $curso_actual->plan_estudio_id,
                'asignatura_id' => $nuevo_curso->id,
                'docente_id' => $curso_actual->docente_id,
                'grupo_id' => $curso_actual->grupo_id,
                'horario_id' => $curso_actual->horario_id,
                'curso_tipo' => $curso_actual->curso_tipo,
                'nombre' => $nuevo_curso->nombre
            ]);

            $cambio_cal = CalificacionesModel::
            where('alumno_id', $this->id_alumno_change)
            ->where('curso_id', $curso_actual->id)
            ->where('calificacion_tipo_id', $tipo_calif)
            //dd($query->toSql(), $query->getBindings());
            ->first();



            if($cambio_cal){
                $cambio_cal->curso_id = $curso_nuevo->id;
                $cambio_cal->save();

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'Actualización clave Kardex',
                    'function' => 'Cambio clave de asignatura',
                    'description' => 'Se modifico la clave de asignatura para corrección de kardex, la clave antigua: ' . $curso_actual->id. ' por la nueva clave: '. $curso_nuevo->id,
                ]);
                return 1;


            }
            else{

            }
        }
        */
    }
        public function eliminar_calificacion($calificacion_id, $motivo){
        $buscar_calif = CalificacionesModel::find($calificacion_id);
        $ciclo = CursosModel::join('esc_grupo','esc_grupo.id', '=', 'esc_curso.grupo_id')
        ->where('esc_curso.id', $buscar_calif->curso_id)
        ->select('esc_grupo.ciclo_esc_id as ciclo')
        ->first();

        $motivo .= ": Del curso ID: ". $buscar_calif->curso_id. " Con la calificación: ". 
        $buscar_calif->calificacion. ".O si es alfabetica: ". $buscar_calif->calif. 
        " Con calificación tipo: " .$buscar_calif->calificacion_tipo_id;

        $registroId = DB::table('esc_bajas_alumnos')->insertGetId([
            'alumno_id' => $buscar_calif->alumno_id,
            'ciclo_esc_id' => $ciclo->ciclo,
            'motivo' => $motivo,
            'user_id' => Auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            //'controller'    =>  'UserController',
            'component' => 'Eliminación de calificación',
            'function' => 'Se ha eliminado una calificación',
            'description' => 'Se eliminó una calificación con ID: ' . $registroId,
        ]);
        $borrar_calificacion = $buscar_calif->delete();
        if ($borrar_calificacion) {
            return 1;
        }   

    }

    public function buscar_asignatura(){
        $this->asignaturas_totales = AsignaturaModel::where('clave', $this->clave_asignatura)->first();

    }


    public function changeEvent($id_alumno)
    {
        //echo "<script>cargando(1);</script>";

        $this->id_alumno_change=$id_alumno;
         //variable alumno seleccionado

        if (empty($id_alumno))
        {
            $this->calificaciones = DB::select('call pa_kardex(?)',array(0)) ;
        }
        else
        {
            $this->calificaciones = DB::select('call pa_kardex(?)',array($id_alumno));
        }
        session()->put('calificaciones', $this->calificaciones);
        session(['calificaciones' =>  $this->calificaciones ]);

        //echo "<script>Swal.close()</script>";
   }

}

