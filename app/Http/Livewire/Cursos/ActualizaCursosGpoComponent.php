<?php

namespace App\Http\Livewire\Cursos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Administracion\PerfilModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\DocenteModel;
use App\Models\Catalogos\HorarioModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
use App\Models\Catalogos\PlandeEstudioModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ActualizaCursosGpoComponent extends Component
{
    public $ciclo_esc_id;
    public $plantel_id;
    public $gpo_id;
    //public $bool_consultar_curso; 
    //public $bool_consultar_alumno;
    public $grupos;
    public $bool_mostrar_coment;
    public $plan_est_id;
    public $asignatura_id;
    public $asignatura_id_cache;
    public $nombre;
    public $horarios;
    public $docente_seleccionado;
    public $docentes_porPlantel;
    public $activa_docente;
    public $curso_editar;
    public $docente_id;

    protected $listeners = ['borra_curso', 'actualiza_listado'];

    public function mount()
    {
        $this->bool_mostrar_coment = false;
        $this->activa_docente = false;
        $this->curso_editar = 0;
    }

    public function limpiabusqueda()
    {
        return redirect()->route('cursos.actualizar');
    }

    public function editar_curso($id_curso)
    {
        $curso = CursosModel::find($id_curso);
        //if ($curso->tiene_calificaciones()) {
        if(1==2){
            //Bitacora
            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                //'controller'    =>  'UserController',
                'component' => 'ActualizaCursosComponent',
                'function' => 'borra_curso',
                'description' => 'Se trató de modifical el registro del curso :' . $id_curso,
            ]);

            $this->dispatchBrowserEvent('carga_sweet_no_editado');
        }
        else{
            $this->bool_mostrar_coment = true;
        
            $this->activa_docente = true;
    
            $datos_curso = CursosModel::find($id_curso);
            //dd($datos_curso);
    
            $this->plan_est_id = $datos_curso->plan_estudio_id;
            //dd($this->plan_est_id);
            $this->asignatura_id = $datos_curso->asignatura_id;
    
            $this->docentes_porPlantel = PerfilModel::select('emp_perfil.id', 'emp_perfil.apellido1', 'emp_perfil.apellido2', 'emp_perfil.nombre')
            ->join('emp_perfil_plantele', 'emp_perfil_plantele.perfil_id', '=', 'emp_perfil.id')
            ->where('plantel_id', $this->plantel_id)->orderBy('apellido1', 'asc')->get();
    
            //$this->docente_id = CursosModel::;
    
            $this->curso_editar = $id_curso;
        }
          
        $this->dispatchBrowserEvent('focusField', ['fieldId' => 'nombreCampo']);

    }

    public function guardarcurso()
    {
        
        if ($this->curso_editar == 0) {
            $rules = [
                //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
                'plan_est_id' => 'required',
                'asignatura_id' => 'required',
                'gpo_id' => 'required',
                'nombre' => 'required|max:255',
            ];



            $this->validate($rules);

            $data = [
                'plan_estudio_id' => $this->plan_est_id,
                'asignatura_id' => $this->asignatura_id,
                'grupo_id' => $this->gpo_id,
                'nombre' => $this->nombre,
                'docente_id' => $this->docente_id,
            ];

            if (Auth()->user()->hasPermissionTo('cursos-crear')) {
                //Buscar cursos de la misma asignatura en el mismo grupo
                //$grupo_seleccionado = GruposModel::find($this->gpo_id);
                $existe = CursosModel::where('grupo_id', $this->gpo_id)
                    ->where('asignatura_id', $this->asignatura_id)
                    ->first();
                if ($existe) {
                    //sweeet alert
                    BitacoraModel::create([
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                        'path' => $_SERVER["REQUEST_URI"],
                        'method' => $_SERVER['REQUEST_METHOD'],
                        //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                        //'controller'    =>  'UserController',
                        'component' => 'ActualizaCursosComponent',
                        'function' => 'guardarcurso',
                        'description' => 'Se trato de crear el registro del curso  pero la asignatura estaba repetida',
                    ]);
                    $this->dispatchBrowserEvent('carga_sweet_alerta_asign_repetida');
                } else {
                    $curso = CursosModel::create($data);
                    //Bitacora
                    BitacoraModel::create([
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                        'path' => $_SERVER["REQUEST_URI"],
                        'method' => $_SERVER['REQUEST_METHOD'],
                        //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                        //'controller'    =>  'UserController',
                        'component' => 'ActualizaCursosComponent',
                        'function' => 'guardarcurso',
                        'description' => 'Se creo el registro del curso :' . $curso->id,
                    ]);
                    $this->bool_mostrar_coment = false;

                    //sweetalert
                    $this->dispatchBrowserEvent('carga_sweet_guardar');
                }


            } else {
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller' => 'ActualizaCursosComponent',
                    //'component'     =>  'FormComponent',
                    'function' => 'guardarcurso',
                    'description' => 'Usuario sin permisos',
                ]);

                return redirect()->route('dashboard')->with('danger', 'No tiene los permisos necesarios');
            }
        } else {
            if (Auth()->user()->hasPermissionTo('cursos-editar')) {
                $rules = [
                    //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
                    'plan_est_id' => 'required',
                    'asignatura_id' => 'required',
                    'gpo_id' => 'required',
                    'nombre' => 'required|max:255',
                    'docente_id' => 'required', // Agrega cualquier regla de validación necesaria aquí
                ]; 
                $this->validate($rules);

                $data = [
                    'plan_estudio_id' => $this->plan_est_id,
                    'asignatura_id' => $this->asignatura_id,
                    'grupo_id' => $this->gpo_id,
                    'nombre' => $this->nombre,
                    'docente_id' => $this->docente_id,
                ];

                $actualizar_registro = CursosModel::find($this->curso_editar);
                $actualizar_registro->update($data);

                $this->docente_id = 0;

                $this->bool_mostrar_coment = false;

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'ActualizaCursosComponent',
                    'function' => 'guardarcurso',
                    'description' => 'Se modifico el curso :' . $actualizar_registro->id,
                ]);
                $this->dispatchBrowserEvent('carga_sweet_guardar');
                //Borrar el horario del curso.
                DB::select('DELETE FROM esc_curso_hora WHERE esc_curso_hora.curso_id = '.$actualizar_registro->id);

            }
        }
    }

    public function borra_curso($curso_id)
    {
        if (Auth()->user()->hasPermissionTo('cursos-borrar')) {
            //dd($curso_id);
            $curso = CursosModel::find($curso_id);
            if ($curso->tiene_calificaciones()) {
                //Bitacora
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'ActualizaCursosComponent',
                    'function' => 'borra_curso',
                    'description' => 'Se trató de borrar el registro del curso :' . $curso_id,
                ]);

                $this->dispatchBrowserEvent('carga_sweet_no_borrado');
            } else {
                $curso->delete();

                //Bitacora
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'ActualizaCursosComponent',
                    'function' => 'borra_curso',
                    'description' => 'Se borró el registro del curso :' . $curso_id,
                ]);

                $this->dispatchBrowserEvent('carga_sweet_borrar');
            }

        } else {
            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'ActualizaCursosComponent',
                //'component'     =>  'FormComponent',
                'function' => 'borra_curso',
                'description' => 'Usuario sin permisos',
            ]);

            return redirect()->route('dashboard')->with('danger', 'No tiene los permisos necesarios');
        }
    }

    public function agregar_horas($id_horas, $id_curso)
    {

    }

    public function actualiza_listado()
    {
        //Para ser llamado por un emit y actualizar boton de horarios
        $this->render();
    }

    public function render()
    {
        $this->activa_docente = false;
        $grupos = null;
        $plantel = null;
        $ciclo_esc = null;
        $planteles = null;
        $ciclos_esc = null;
        $grupo_seleccionado = null;
        $alumnos = null;
        $planes_estudio = null;
        $plan_est_asignaturas = null;
        $datos_curso = null;

        $this->horarios = HorarioModel::all();

        //dd($this->horarios);
        $planteles = obtenerPlanteles();
        $ciclos_esc = CicloEscModel::select('cat_ciclos_esc.id', 'cat_ciclos_esc.nombre')->distinct()->join('esc_grupo', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')->orderBy('cat_ciclos_esc.id', 'desc')->get();

        if (($this->ciclo_esc_id != null) and ($this->plantel_id != null) and ($this->grupos == null)) {
            $this->grupos = GruposModel::where('plantel_id', $this->plantel_id)
                ->where('ciclo_esc_id', $this->ciclo_esc_id)->withCount('cursos') //->withCount('alumnos')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->orderBy('nombre')
                ->get();
            //dd($grupos);

            $this->dispatchBrowserEvent('cargar_select2_grupo');

            $this->docentes_porPlantel = PerfilModel::
            select('emp_perfil.id', 'emp_perfil.apellido1', 'emp_perfil.apellido2', 'emp_perfil.nombre')
            ->join('emp_perfil_plantele', 'emp_perfil_plantele.perfil_id', '=', 'emp_perfil.id')
            ->where('plantel_id', $this->plantel_id)->orderBy('apellido1', 'asc')->get();

           
        }
        $grupos = $this->grupos;

        if ($this->plantel_id != null) {
            $plantel = PlantelesModel::find($this->plantel_id);
        }
        if ($this->ciclo_esc_id != null) {
            $ciclo_esc = CicloEscModel::find($this->ciclo_esc_id);
        }

        if ($this->gpo_id != null) {
            $grupo_seleccionado = GruposModel::find($this->gpo_id);
        }

        if ($this->bool_mostrar_coment) {
            $planes_estudio = PlandeEstudioModel::where('activo', '=', '1')->get();
            //dd($planes_estudio);

            $datos_curso = CursosModel::find($this->curso_editar);
            //dd($datos_curso);
            if($datos_curso)
            {
                $this->plan_est_id = $datos_curso->plan_estudio_id;
            }
            


            if ($this->plan_est_id) {
                //Solo mostrar asignaturas que correspondan al semestre del grupo
                $plan_est_asignaturas = PlandeEstudioAsignaturaModel::where('id_planestudio', $this->plan_est_id)->get();

                //dd($plan_est_asignaturas);
            }
            if (($this->asignatura_id) and ($this->asignatura_id <> $this->asignatura_id_cache)) {
                $this->asignatura_id_cache = $this->asignatura_id;
                $asign_nom = AsignaturaModel::find($this->asignatura_id_cache);
                $this->nombre = $asign_nom->nombre;
            }

        }


        return view('livewire.cursos.actualiza-cursos-gpo-component', compact('planteles', 'ciclos_esc', 'grupos', 'plantel', 'ciclo_esc', 'grupo_seleccionado', 'alumnos', 'planes_estudio', 'plan_est_asignaturas','datos_curso'));
    }


}
