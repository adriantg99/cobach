<?php

namespace App\Http\Livewire\Adminalumnos\ActasExtemporaneas;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Catalogos\PoliticaModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\ActaExtemporaneaModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AlumnoActaComponent extends Component
{
    public $alumno_id;
    public $asignatura_id;
    public $alumno;
    public $kardex;
    public $tipo_acta;
    public $otro;
    public $datos;
    public $docentes;
    public $email_docente;
    public $calificacion;
    public $calif;
    public $cuenta_reprobados = 0;
    public $semestre = 0;

    protected $listeners = ['crear_acta'];

    public function mount()
    {
        $user = Auth::user();
        $roles = $user->getRoleNames()->toArray();
        if ($this->alumno == null) {
            $this->alumno = AlumnoModel::find($this->alumno_id);
            $this->kardex = DB::select('call pa_kardex(?)', array($this->alumno->id));
            //dd(is_object($this->kardex));
            $prueba = obtenerPlanteles();
            //Datos de grupo del alumno
            $data = DB::select("SELECT esc_grupo.nombre AS grupo, case turno_id when 1 then 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno, 
                    cat_plantel.nombre AS plantel, cat_ciclos_esc.nombre as ciclo, cat_plantel.id as plantel_id
                FROM esc_grupo_alumno
                LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id
                LEFT JOIN cat_ciclos_esc ON cat_ciclos_esc.id=esc_grupo.ciclo_esc_id
                LEFT JOIN cat_plantel ON cat_plantel.id=esc_grupo.plantel_id
                WHERE (alumno_id=" . $this->alumno_id . ") AND (cat_plantel.id <> 34) 
                ORDER BY per_inicio DESC
                LIMIT 1");
                
            if ($data[0]->plantel_id == 31) {
                $valida = 0;
                foreach ($roles as $role) {
                    if ($role === "control_escolar" || $role === "super_admin" || $role === "autorizar_rev" || $role === "carga_planteles_todos") {
                        $valida = 1;
                        break;
                    } elseif ((strpos($role, "control_escolar_") === 0)) {
                        $valida = 2;
                        $validaciones[] = substr($role, 16);
                        break;
                    } else {
                        continue;
                    }
                }

                if ($valida == 2) {
                    $buscar_plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->first();
                    $data[0]->plantel = $buscar_plantel->nombre;
                    $data[0]->plantel_id = $buscar_plantel->id;
                }


            }

            //dd($data[0]);
            $this->datos = (array) $data[0];
            if(permisos() == 1){
                           //Docente:
            $sql = "SELECT cat_docentes_correos.email, emp_perfil.nombre, emp_perfil.apellido1, ";
            $sql = $sql . "emp_perfil.apellido2, emp_perfil_plantele.plantel_id, users.name ";
            $sql = $sql . "FROM cat_docentes_correos INNER JOIN users ON cat_docentes_correos.email = users.email ";
            $sql = $sql . "INNER JOIN emp_perfil ON emp_perfil.user_id = users.id ";
            $sql = $sql . "INNER JOIN emp_perfil_plantele ON emp_perfil_plantele.perfil_id = emp_perfil.id ";
            $sql = $sql . "WHERE emp_perfil_plantele.plantel_id = " . $this->datos['plantel_id'] . " ";
            $sql = $sql . "ORDER BY email";
            $sql = $sql . "";
            }else{
           //Docente:
            $plantelesIds = obtenerPlanteles()->pluck('id')->toArray();
            $plantelesIdsStr = implode(',', $plantelesIds);

            //dd($plantelesIds);

            $sql = "SELECT cat_docentes_correos.email, emp_perfil.nombre, emp_perfil.apellido1, ";
            $sql = $sql . "emp_perfil.apellido2, emp_perfil_plantele.plantel_id, users.name ";
            $sql = $sql . "FROM cat_docentes_correos INNER JOIN users ON cat_docentes_correos.email = users.email ";
            $sql = $sql . "INNER JOIN emp_perfil ON emp_perfil.user_id = users.id ";
            $sql = $sql . "INNER JOIN emp_perfil_plantele ON emp_perfil_plantele.perfil_id = emp_perfil.id ";
            $sql = $sql . "WHERE emp_perfil_plantele.plantel_id = " . $this->datos['plantel_id'] . " OR emp_perfil_plantele.plantel_id in(".$plantelesIdsStr .")";
            $sql = $sql . "ORDER BY email";
            $sql = $sql . "";
            }

 

            $this->docentes = (array) DB::select($sql);

        }

    }

    public function crear_acta()
    {
        $rules = [
            'alumno_id' => 'required',
            'asignatura_id' => 'required',
            'tipo_acta' => 'required',
            'email_docente' => 'required',
            'calificacion' => 'required_without:calif',
            'calif' => 'required_without:calificacion',
            'otro' => new RequiredIf($this->tipo_acta == 'OTRO'),
        ];

        $this->validate($rules);

        switch ($this->tipo_acta) {
            case 'PASANTIA':
                $motivo = 'PASANTIA';
                $tipo_calif = 3;
                break;

            case 'ESTRATEGIA DE RECUPERECIÓN ACADÉMICA':
                $motivo = 'ESTRATEGIA DE RECUPERECIÓN ACADÉMICA';
                $tipo_calif = 10;
                break;

            case 'ACTA ESPECIAL SERVICIO SOCIAL':
                $motivo = 'ACTA ESPECIAL SERVICIO SOCIAL';
                $tipo_calif = 8;
                break;

            case 'ACTA ESPECIAL PRACTICAS PREPROFESIONALES':
                $motivo = 'ACTA ESPECIAL PRACTICAS PREPROFESIONALES';
                $tipo_calif = 8;
                break;

            case 'OTRO':
                $motivo = $this->otro;
                $tipo_calif = 8;
                break;

            default:
                // code...
                break;
        }

        //Ultimo ciclo esc activo
        $ciclo_esc = CicloEscModel::orderby('per_inicio', 'DESC')->where('activo', '1')->where('tipo', '!=', 'V')->first(); //Toma el ultimo ciclo escolar para que aparezca en kardex


        $data = [
            'alumno_id' => $this->alumno_id,
            'ciclo_esc_id' => $ciclo_esc->id,
            'grupo' => $this->datos['grupo'],
            'turno' => $this->datos['turno'],
            'plantel' => $this->datos['plantel'],
            'plantel_id' => $this->datos['plantel_id'],
            'asignatura_id' => $this->asignatura_id,
            'email_docente' => $this->email_docente,
            'calificacion' => $this->calificacion,
            'calif' => $this->calif,
            'tipo_acta' => $this->tipo_acta,
            'motivo' => $motivo,
            'user_id_creacion' => Auth()->user()->id,
            'fecha_creacion' => date("Y-m-d H:i:s"),
            'impresiones' => 0,
            //fecha_impresion => null,
        ];

        //crea registro
        $acta_extemp = ActaExtemporaneaModel::create($data);
        //Bitacora

        //INICIO ASIGNACION DE CALIFCACION---------------------------------------------------



        //Creación de Grupo si no existe
        $grupo = GruposModel::where('plantel_id', $acta_extemp->plantel_id)
            ->where('ciclo_esc_id', $ciclo_esc->id)
            ->where('nombre', 'ActasExtemporaneas')
            ->first();
        if ($grupo == null) {
            $data = [
                'turno_id' => 1,
                'plantel_id' => $acta_extemp->plantel_id,
                'ciclo_esc_id' => $ciclo_esc->id,
                'periodo' => $ciclo_esc->nombre,
                'aula_id' => 0,
                'nombre' => 'ActasExtemporaneas',
                'descripcion' => 'ActasExtemporaneas',
            ];
            $grupo = GruposModel::create($data);
        }

        //Ingreso del alumno al grupo
        $data = [
            'grupo_id' => $grupo->id,
            'alumno_id' => $acta_extemp->alumno_id,
        ];
        $grupo_alumno_ch = GrupoAlumnoModel::where('grupo_id', $grupo->id)->where('alumno_id', $acta_extemp->alumno_id)->first();
        if ($grupo_alumno_ch == null) {
            GrupoAlumnoModel::create($data);
        }

        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller' => 'AlumnoActaComponent',
            //'component'     =>  'FormComponent',
            'function' => 'crear_acta',
            'description' => 'Encontrando o creando grupo: ' . $grupo->id . ': ' . $grupo->nombre . ' para el alumno id:' . $acta_extemp->alumno_id,
        ]);

        //Crea Curso 
        $curso = CursosModel::where('asignatura_id', $acta_extemp->asignatura_id)
            ->where('grupo_id', $grupo->id)
            ->first();

        if ($curso == null) {
            $buscar_plan = PlandeEstudioAsignaturaModel::where('id_asignatura', $acta_extemp->asignatura_id)->first();

            $data = [
                'plan_estudio_id' => $buscar_plan->id_planestudio,
                'asignatura_id' => $acta_extemp->asignatura_id,
                'docente_id' => 0,
                'grupo_id' => $grupo->id,
                'curso_tipo' => 0,
                'nombre' => $acta_extemp->asignatura->nombre,
            ];

            $curso = CursosModel::create($data);
        }

        //Crea Calificacion
        $data = [
            'alumno_id' => $acta_extemp->alumno_id,
            'politica_variable_id' => $acta_extemp->asignatura->politica_variable_id('F'),
            'calificacion_tipo_id' => $tipo_calif,
            'curso_id' => $curso->id,
            'calificacion' => $acta_extemp->calificacion,
            'calif' => $acta_extemp->calif,
            'calificacion_tipo' => 'Final',
        ];
        $califica = CalificacionesModel::create($data);

        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller' => 'AlumnoActaComponent',
            //'component'     =>  'FormComponent',
            'function' => 'crear_acta',
            'description' => 'Carga curso_id:' . $curso->id . ' - ' . $curso->nombre . '. Carga la calificacion: ' . $califica->id . ' Final:' . $califica->calificacion . $califica->calif,
        ]);
        //FIN ASIGNACION DE CALIFCACION---------------------------------------------------

        //LLamar confirmación OK
        redirect('adminalumnos.actas_ext/' . $this->datos['plantel_id'] . '/list')->with('success', 'Creación de acta completa');

    }

    public function render()
    {
        $numerica = null;
        if (is_null($this->asignatura_id) == false) {
            $this->dispatchBrowserEvent('select2_docente');

            $asignatura = AsignaturaModel::find($this->asignatura_id);
            $politica = PoliticaModel::where('id_areaformacion', $asignatura->id_areaformacion)->first();
            //dd($politica);
            if ($politica->id_variabletipo == 1) {
                $numerica = true;
            } elseif ($politica->id_variabletipo == 2) {
                $numerica = false;
            } else {
                $numerica = null;
            }
        }

        return view('livewire.adminalumnos.actas-extemporaneas.alumno-acta-component', compact('numerica'));
    }
}
