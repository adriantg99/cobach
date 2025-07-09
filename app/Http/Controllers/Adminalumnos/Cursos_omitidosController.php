<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Validation\Rule;

class Cursos_omitidosController extends Controller
{
    public function index()
    {
        //selecciona plantel----
        $planteles = obtenerPlanteles();
        $ciclos_esc = CicloEscModel::orderBy('id', 'DESC')->get();
        return view('adminalumnos.cursos_omitidos.seleccion_plantel', compact('planteles', 'ciclos_esc'));
    }

    public function index_post(Request $request)
    {
        $rules = [
            'plantel_id'    =>  'required',
            'ciclo_esc_id'  =>  'required',
        ];

        $request->validate($rules);

        $plantel = PlantelesModel::find($request->plantel_id);
        $ciclo_esc = CicloEscModel::find($request->ciclo_esc_id);

        $sql = "CREATE TEMPORARY TABLE tmp_alumnos_".Auth()->user()->id." SELECT esc_grupo_alumno.alumno_id, esc_grupo.plantel_id, ";
        $sql = $sql."MAX(esc_grupo.ciclo_esc_id) AS mcie ";
        $sql = $sql."FROM esc_grupo_alumno ";
        $sql = $sql."INNER JOIN esc_grupo ON esc_grupo_alumno.grupo_id = esc_grupo.id ";
        //$sql = $sql."INNER JOIN alu_alumno ON esc_grupo_alumno.alumno_id = alu_alumno.id ";
        $sql = $sql."WHERE esc_grupo.plantel_id <> 34 and esc_grupo.nombre != 'ActasExtemporaneas'";

        $sql = $sql."GROUP BY esc_grupo_alumno.alumno_id, esc_grupo.plantel_id ";
        $sql = $sql."HAVING ((plantel_id = ".$request->plantel_id.") AND (mcie = ".$request->ciclo_esc_id."))";
        //dd($sql);
        $alumnos = DB::select($sql);

        BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'Cursos_omitidosController',
            //'component'     =>  'FormComponent',
            'function'  =>  'index_post',
            'description'   =>  'Ingreso búsqueda de plantel: '.$request->plantel_id.' ciclo_esc:'.$request->ciclo_esc_id,
        ]);

        return view('adminalumnos.cursos_omitidos.seleccion_alumno', compact('plantel', 'ciclo_esc'));
    }
}
