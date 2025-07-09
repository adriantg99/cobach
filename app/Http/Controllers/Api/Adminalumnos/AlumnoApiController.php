<?php
// ANA MOLINA 21/08/2023
namespace App\Http\Controllers\Api\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Adminalumnos\AlumnoRequest;
use App\Http\Resources\Adminalumnos\AlumnoResource;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CtModel;
use App\Models\Catalogos\LocalidadesModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Helpers\helpers;


use Spatie\Permission\Models\Role;



class AlumnoApiController extends Controller
{
    //
    public function storeAlumno(AlumnoRequest $request)
    {
        $alumno = AlumnoModel::create($request->validated());
        return new AlumnoResource($alumno);
    }

    public function getAlumno($id)
    {

        $alumno = DB::table('alu_alumno')
            ->join('lug_localidad', function ($join) {
                $join->on('alu_alumno.id_localidadnacimiento', '=', 'lug_localidad.id');
            })
            ->join('lug_municipio', function ($join) {
                $join->on('lug_localidad.id_municipio', '=', 'lug_municipio.id');
            })
            ->join('lug_localidad as local', function ($join) {
                $join->on('alu_alumno.id_localidaddomicilio', '=', 'local.id');
            })
            ->join('lug_municipio as muni', function ($join) {
                $join->on('local.id_municipio', '=', 'muni.id');
            })
            ->join('esc_periodo', function ($join) {
                $join->on('alu_alumno.id_periodo', '=', 'esc_periodo.id');
            })

            ->where('alu_alumno.id', $id)
            ->select(
                'alu_alumno.*'
                ,
                'lug_localidad.id_municipio as id_municipionacimiento',
                'lug_municipio.id_estado as id_estadonacimiento'
                ,
                'local.id_municipio as id_municipiodomicilio',
                'muni.id_estado as id_estadodomicilio',
                'esc_periodo.tipoperiodo_id'
            )
            ->get();



        return new AlumnoResource($alumno);


    }

    public function editarAlumno(AlumnoRequest $request, $id)
    {
        $alumno = AlumnoModel::find($id);
        $alumno->update($request->validated());
        return new AlumnoResource($alumno);
    }
    public function getMaxExpediente($id_plantel, $id_ciclo)
    {
        $expediente = AlumnoModel::where('id_plantel', $id_plantel)->where('id_cicloesc', $id_ciclo)->max('noexpediente');
        $max = '';
        if (empty($expediente))
            $max = substr('0' . $id_ciclo, -2) . substr('0' . $id_plantel, -2) . '0001';
        else
            $max = substr('0' . $expediente + 1, -8);

        return $max;

    }

    public function buscar()
    {
        $rol = array();
        //API para llenar select2 búsqueda de correos instucionales
        $term = base64_decode($_GET['term']);
        array_push($rol, 'HE7_');
        $type = base64_decode($_GET['type']);
        if (isset($_GET['rol'])) {

            $validaciones = [];

            $todos_los_valores = 0;

            $planteles_array = array(
                'VSE',
                'NAV',
                'EFK',
                'AFU',
                'CAB',
                'PPE',
                'REF',
                'ELR',
                'SLR',
                'OB2',
                'ALA',
                'EMP',
                'ETC',
                'SON',
                'AOS',
                'SIR',
                'PYA',
                'NHE',
                'NOG',
                'QUE',
                'FFS',
                'PEC',
                'JMM',
                'HE5',
                'OB3',
                'NAC',
                'BDK',
                'CAL',
                'HE7',
                'NO2'
            );

            foreach ($_GET['rol'] as $auxiliar_roles) {
                if ($auxiliar_roles === "control_escolar" || $auxiliar_roles === "super_admin" || $auxiliar_roles === "credenciales" || $auxiliar_roles === "carga_planteles_todos") {
                    $todos_los_valores = 1;
                    break;
                } elseif (strpos($auxiliar_roles, "control_escolar_") === 0) {
                    array_push($rol, substr($auxiliar_roles, 16));
                    $todos_los_valores = 2;
                } elseif (in_array($auxiliar_roles, $planteles_array)) {
                    $todos_los_valores == 2;
                    array_push($rol, $auxiliar_roles);
                }

            }

            if ($todos_los_valores == 1) {
                $correos = AlumnoModel::select('alu_alumno.id', 'alu_alumno.apellidos', 'alu_alumno.nombre', 'alu_alumno.noexpediente')
                    ->where(function ($query) use ($term) {
                        $query->where('alu_alumno.noexpediente', 'LIKE', '%' . $term . '%')
                            ->orWhere('alu_alumno.apellidos', 'LIKE', '%' . $term . '%')
                            ->orWhere('alu_alumno.nombre', 'LIKE', '%' . $term . '%')
                            ->orWhere(DB::raw("CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%')
                            ->orWhere(DB::raw("CONCAT(apellidopaterno, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%')
                            ->orWhere(DB::raw("CONCAT(apellidomaterno, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%');
                    })
                    ->get();
            } else {
                $correos = AlumnoModel::join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                    ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                    ->select('alu_alumno.id', 'alu_alumno.apellidos', 'alu_alumno.nombre', 'alu_alumno.noexpediente')
                    ->whereIn('cat_plantel.abreviatura', $rol)
                    ->where(function ($query) use ($term) {
                        $query->where('alu_alumno.noexpediente', 'LIKE', '%' . $term . '%')
                            ->orWhere('alu_alumno.apellidos', 'LIKE', '%' . $term . '%')
                            ->orWhere('alu_alumno.nombre', 'LIKE', '%' . $term . '%')
                            ->orWhere(DB::raw("CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%');
                    })
                    ->groupBy('alu_alumno.id')
                    ->get();
                    
                if ($correos->isEmpty()) {
                    
                    $correos = AlumnoModel::join('cat_plantel', 'alu_alumno.plantel_id', '=', 'cat_plantel.id')
                    ->select('alu_alumno.id', 'alu_alumno.apellidos', 'alu_alumno.nombre', 'alu_alumno.noexpediente')
                        ->whereIn('cat_plantel.abreviatura', $rol)
                        
                        ->where(function ($query) use ($term) {
                            $query->where('alu_alumno.noexpediente', 'LIKE', '%' . $term . '%')
                                ->orWhere('alu_alumno.apellidos', 'LIKE', '%' . $term . '%')
                                ->orWhere('alu_alumno.nombre', 'LIKE', '%' . $term . '%')
                                ->orWhere(DB::raw("CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%');
                        })
                        ->groupBy('alu_alumno.id')
                        ->get();
                }

            }

        } else {
            $correos = AlumnoModel::select('alu_alumno.id', 'alu_alumno.apellidos', 'alu_alumno.nombre', 'alu_alumno.noexpediente')
                ->where(function ($query) use ($term) {
                    $query->where('alu_alumno.noexpediente', 'LIKE', '%' . $term . '%')
                        ->orWhere('alu_alumno.apellidos', 'LIKE', '%' . $term . '%')
                        ->orWhere('alu_alumno.nombre', 'LIKE', '%' . $term . '%')
                        ->orWhere(DB::raw("CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%')
                        ->orWhere(DB::raw("CONCAT(apellidopaterno, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%')
                        ->orWhere(DB::raw("CONCAT(apellidomaterno, ' ', alu_alumno.nombre)"), 'LIKE', '%' . $term . '%');
                })
                ->get();

            //dd($query->toSql(), $query->getBindings());

        }

        return $correos;




    }

    public function buscar_alumno(Request $request)
    {
        //$sql = "SELECT * FROM alu_alummno WHERE correo_institucional = '".$request->correo."'";
        //$alumno = DB::connection('mysql')->select($sql);
        /*
        $alumno = AlumnoModel::select('id','apellidos', 'nombre', 'noexpediente')
            ->where('correo_institucional', $request->correo)
            ->get();
          */
        //dd($alumno);
        return '$alumno->noexpediente';
    }

    public function buscar_ct(Request $request)
    {
        // Obtén el parámetro term desde el request
        $ct = $request->get('term');

        // Decodifica el término en Base64
        $ct = base64_decode($ct);

        if ($ct) {
            $buscar_ct = CtModel::where('ct', 'like', '%' . $ct . '%')
                ->orWhere('nombre_ct', 'like', '%' . $ct . '%')
                ->get();
        } else {
            $buscar_ct = collect();
        }
        // Realiza la búsqueda en el modelo


        // Retorna los resultados en formato JSON
        return response()->json($buscar_ct);
    }

     public function municipios($cve_ent)
    {
        return DB::table('cat_localidades')
            ->select('cve_mun', 'nom_mun')
            ->where('cve_ent', $cve_ent)
            ->groupBy('cve_mun', 'nom_mun')
            ->orderBy('nom_mun')
            ->get();

            
    }

    public function localidades($cve_ent, $cve_mun)
    {
        return DB::table('cat_localidades')
            ->select('cve_loc', 'nom_loc')
            ->where('cve_ent', $cve_ent)
            ->where('cve_mun', str_pad($cve_mun, 3, '0', STR_PAD_LEFT))
            ->groupBy('cve_loc', 'nom_loc')
            ->orderBy('nom_loc')
            ->get();
    }

}
