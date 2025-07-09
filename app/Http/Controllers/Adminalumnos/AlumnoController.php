<?php

// ANA MOLINA 28/08/2023
namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Catalogos\CiclosConfigModel;
use Carbon\Carbon;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Response;
// use Illuminate\Pagination\Paginator;

class AlumnoController extends Controller
{
    /* public $nombre='';
    public $id_plantel_change=2;
    public $noexpediente=''; */
    public function index()
    {
        /* Paginator::useBootstrap();
        $id_plantel=$this->id_plantel_change;
        if(empty($this->id_plantel_change))
        {

            $alumnos = AlumnoModel::paginate(10);
            $count_alumnos = AlumnoModel::count();

        }
        else
        {
            //dd($id_plantel);
            $alumnos = AlumnoModel::where('id_plantel', $this->id_plantel_change)->paginate(10);
            $count_alumnos = AlumnoModel::where('id_plantel', $this->id_plantel_change)->count();

        }
        return view('adminalumnos.alumnos.index', compact('alumnos','count_alumnos','id_plantel')); */
        return view('adminalumnos.alumnos.index');
    }

    public function agregar($id_plantel)
    {
        //dd($id_plantel);
        return view('adminalumnos.alumnos.agregar', compact('id_plantel'));
    }

    public function editar($alumno_id = null)
    {
        if ($alumno_id) {
            // Si hay un $alumno_id, busca el modelo y carga los datos
            $alumno = AlumnoModel::find($alumno_id);

            if (!$alumno) {
                // Manejar el caso en que el alumno no se encuentre
                abort(404); // O alguna otra acción que desees tomar
            }
        } else {
            // Si no hay $alumno_id, crea un nuevo modelo con campos vacíos
            $alumno = new AlumnoModel();
        }

        return view('adminalumnos.alumnos.editar')->with('alumno', $alumno);
    }

    public function agregarsuccess($alumno_id)
    {
        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller' => 'AlumnoController',
            //'component'     =>  'FormComponent',
            'function' => 'agregarsuccess',
            'description' => 'se guardó alumno_id:' . $alumno_id,
        ]);
        return redirect()->route('adminalumnos.alumnos.index')->with('success', 'Alumno ID: ' . $alumno_id . ' guardado correctamente');
    }
    public function nuevos_alumnos()
    {
        $hoy = Carbon::now();
        $fecha_inscripcion = CiclosConfigModel::join('cat_ciclos_esc', 'cat_ciclos_esc.id', '=', 'cat_ciclos_configuraciones.ciclo_esc_id')
            ->where('cat_ciclos_esc.activo', '1')
            ->first();

        return view('adminalumnos.alumnos.nuevos_alumnos');
        /* Para cuando ya este bien
        if($hoy >= $fecha_inscripcion->inicio_inscripcion && $hoy <= $fecha_inscripcion->fin_inscripcion){
            return view('adminalumnos.alumnos.nuevos_alumnos');
        }
        else {
            return redirect()->route('dashboard')->with('error', 'El periodo para inscripciones de nuevos alumnos se encuentra cerrado');
        }
            */


    }

    public function guardar_datos_nuevo_ingreso()
    {

    }

    public function eliminar($alumno_id)
    {
        if (Auth()->user()->hasPermissionTo('alumno-borrar')) {
            $alumnos = AlumnoModel::find($alumno_id);
            $alumnos->delete();

            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'AlumnoController',
                //'component'     =>  'FormComponent',
                'function' => 'eliminar',
                'description' => 'Eliminó alumno_id:' . $alumno_id,
            ]);

            return redirect()->route('adminalumnos.alumnos.index')->with('warning', 'Se eliminó correctamente el alumno id:' . $alumno_id);
        } else {
            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'AlumnoController',
                //'component'     =>  'FormComponent',
                'function' => 'eliminar',
                'description' => 'Usurio sin permisos',
            ]);

            return redirect()->route('adminalumnos.alumnos.index')->with('danger', 'No tiene los permisos necesarios');
        }
    }

    public function datos($alumno_id)
    {
        $saltar = 0;
        $roles = Auth()->user()->getRoleNames()->toArray();

        foreach ($roles as $role) {
            if ($role === "alumno") {
                $saltar = 1;
            }

            if (
                $role === "control_escolar" || $role === "credenciales" || $role === "orientacion_educativa" || $role === "asistencia_educativa"
                || $role === "inclusion_educativa" || $role === "tutoria_grupal"
            ) {
                $todos_los_valores = 1;
                break;
            } elseif (strpos($role, "control_escolar_") === 0) {
                $validaciones[] = substr($role, 16);
                $todos_los_valores = 2;
                continue;
            } else {
                continue;
            }
        }

        //dd($validaciones);
        if($todos_los_valores == 2){
            array_push($validaciones, 'HE7_');
        }
        
        if ($saltar == 1) {
            return redirect('/logout')->with('error');
        } else {
            $alumno = AlumnoModel::find($alumno_id);

            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'Adminalumnos-AlumnoController',
                //'component'     =>  'FormComponent',
                'function' => 'datos',
                'description' => 'Busqueda de alumno Exp:' . $alumno->noexpediente,
            ]);
            $sql = "SELECT esc_grupo.nombre AS grupo, esc_grupo.id, ";
            $sql = $sql . "CASE turno_id WHEN 1 THEN 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno, ";
            $sql = $sql . "cat_ciclos_esc.activo AS ciclo_activo, cat_plantel.nombre AS plantel, 
                           cat_plantel.id AS plantel_id, cat_ciclos_esc.nombre, cat_ciclos_esc.id AS id_ciclo ";
            $sql = $sql . "FROM esc_grupo_alumno ";
            $sql = $sql . "LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id ";
            $sql = $sql . "LEFT JOIN cat_ciclos_esc ON cat_ciclos_esc.id=esc_grupo.ciclo_esc_id ";
            $sql = $sql . "LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id ";
            $sql = $sql . "WHERE ((esc_grupo_alumno.alumno_id=" . $alumno->id . ") ";
            $sql = $sql . "AND (plantel_id != 34) AND (esc_grupo.nombre <> 'ActasExtemporaneas') ";
            $sql = $sql . "AND esc_grupo.descripcion <> 'turno_especial'";
            //dd($validaciones);
            if ($todos_los_valores != 1) {
                $contador = 0;
                $sql = $sql . "AND(";
                foreach ($validaciones as $array_validacion) {
                    if ($contador >= 1) {
                        $sql = $sql . "OR cat_plantel.abreviatura = '" . $array_validacion . "' ";
                    } else {
                        $sql = $sql . "cat_plantel.abreviatura = '" . $array_validacion . "' ";
                    }

                    $contador += 1;
                }
                $sql = $sql . ")";

            }

            $sql = $sql . ") ORDER BY cat_ciclos_esc.per_inicio DESC ";
            //dd($sql);
            //$sql = $sql."LIMIT 1";
            //dd($sql);
            $data = DB::select($sql);
            //dd($data);
            if (count($data) > 0) {
                $datos = $data[0];
            } else {
                $datos = null;
                //$datos = $data[0];
            }
            //dd($datos);

            return view('adminalumnos.alumnos.datos_alumno', compact('alumno', 'datos', 'data'));
        }
    }

    public function cargar_nuevos_alumnos()
    {
        return view('adminalumnos.nuevos_alumnos.nuevos_alumnos');

        /*
        if(Auth()->user()->hasPermissionTo('alumno-nuevos')){

        }
        else{
            return redirect()->route('adminalumnos.alumnos.index')->with('danger','No tiene los permisos necesarios');
        }*/

    }

    public function descargar($alumno_id)
    {
        // Encuentra el archivo por su ID
        $archivo = ImagenesalumnoModel::where('alumno_id', $alumno_id)->where('tipo', '3')->first();

        // Extrae el contenido del archivo
        $contenido = $archivo->imagen;

        // Crea una respuesta de descarga
        return Response::make($contenido, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $archivo->filename . '"',
        ]);
    }

    public function cambio_plan()
    {
        return view('adminalumnos.plan_estudio.cambio_plan');


    }
}
