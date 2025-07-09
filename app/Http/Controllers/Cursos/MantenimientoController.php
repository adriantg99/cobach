<?php

namespace App\Http\Controllers\Cursos;

use App\Exports\Grupos\CalificacionesGruposExport;
use App\Exports\Grupos\ConcentradoGruposExport;
use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Docentes\ActasModel;
use App\Models\Grupos\GruposModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use NumberFormatter;



class MantenimientoController extends Controller
{
    //
    public function consulta_cursos_gpo()
    {
        //if(Auth()->user()->hasPermissionTo('cursos-ver'))
        //{
        return view('cursos.mantenimiento_index');
        //}
        //else
        //{
        //    BitacoraModel::create([
        //        'user_id'   =>  Auth()->user()->id,
        //        'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
        //        'path'      =>  $_SERVER["REQUEST_URI"],
        //        'method'    =>  $_SERVER['REQUEST_METHOD'],
        //        //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
        //        'controller'    =>  'MantenimiendoController',
        //        //'component'     =>  'FormComponent',
        //        'function'  =>  'consulta_cursos_gpo',
        //        'description'   =>  'Usuario sin permisos',
        //    ]);

        //    return redirect()->route('dashboard',$aula->plantel_id)->with('danger','No tiene los permisos necesarios');
        //}
    }

    public function imprime_calif_grupo_p1($grupo_id)
    {
        $grupo = GruposModel::find($grupo_id);
        $alumnos = $grupo->alumnos;
        $cursos = $grupo->cursos;
        if ($grupo->turno_id == 1) {
            $turno = "Matutino";
        } else {
            $turno = "Vespertino";
        }
        if (count($alumnos) > 0) {
            if (count($cursos) > 0) {
                return Excel::download(new CalificacionesGruposExport($grupo_id, "P1"), 'SICE-cobach-' . $grupo->nombre . '-' . $turno . '_P1.xlsx');
            } else {
                return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene cursos asociados');
            }
        } else {
            return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene alumnos inscritos');
        }
    }

    public function imprime_calif_grupo_p2($grupo_id)
    {
        $grupo = GruposModel::find($grupo_id);
        $alumnos = $grupo->alumnos;
        $cursos = $grupo->cursos;
        if ($grupo->turno_id == 1) {
            $turno = "Matutino";
        } else {
            $turno = "Vespertino";
        }
        if (count($alumnos) > 0) {
            if (count($cursos) > 0) {
                return Excel::download(new CalificacionesGruposExport($grupo_id, "P2"), 'SICE-cobach-' . $grupo->nombre . '-' . $turno . '_P2.xlsx');
            } else {
                return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene cursos asociados');
            }
        } else {
            return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene alumnos inscritos');
        }
    }

    public function imprime_calif_grupo_p3($grupo_id)
    {
        $grupo = GruposModel::find($grupo_id);
        $alumnos = $grupo->alumnos;
        $cursos = $grupo->cursos;
        if ($grupo->turno_id == 1) {
            $turno = "Matutino";
        } else {
            $turno = "Vespertino";
        }
        if (count($alumnos) > 0) {
            if (count($cursos) > 0) {
                return Excel::download(new CalificacionesGruposExport($grupo_id, "P3"), 'SICE-cobach-' . $grupo->nombre . '-' . $turno . '_P3.xlsx');
            } else {
                return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene cursos asociados');
            }
        } else {
            return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene alumnos inscritos');
        }
    }

    public function imprime_concentrado_grupo($grupo_id)
    {
        $grupo = GruposModel::find($grupo_id);
        $alumnos = $grupo->alumnos;
        $cursos = $grupo->cursos;
        if ($grupo->turno_id == 1) {
            $turno = "Matutino";
        } else {
            $turno = "Vespertino";
        }

        if (count($alumnos) > 0) {
            if (count($cursos) > 0) {
                $export = new ConcentradoGruposExport($grupo_id);
                return $export->export();

            } else {
                return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene cursos asociados');
            }
        } else {
            return redirect()->route('cursos.consulta_cursos_gpo')->with('danger', 'El grupo seleccionado no tiene alumnos inscritos');
        }
     
    }
    public function generar_acta(Request $request)
    {
        $fechaHoy = Carbon::now();
        $nombreMes = $fechaHoy->locale('es')->monthName;


        $fechaTexto = "a los " . $fechaHoy->day . " días del mes de " . $nombreMes . " del año " . $fechaHoy->year . "";

        $acta = ActasModel::find($request->id_acta);
        $logo = public_path('images/logocobachchico.png');
        if ($acta->estado == 2) {
            $actas = ActasModel::join('esc_curso', 'esc_curso.id', 'esc_actas.curso_id')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_actas.grupo_id')
                ->join('emp_perfil', 'esc_actas.docente_id', '=', 'emp_perfil.user_id')
                ->join('alu_alumno', 'esc_actas.alumno_id', '=', 'alu_alumno.id')
                ->join('asi_asignatura', 'esc_curso.asignatura_id', '=', 'asi_asignatura.id')
                ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
                ->leftJoin('users', function ($join) {
                    $join->on('users.id', '=', 'esc_actas.user_aut_id');
                })
                ->leftjoin('esc_calificacion', function ($join) {
                    $join->on('esc_calificacion.id', '=', 'esc_actas.calificacion_id');
                })
                ->where('esc_actas.id', $request->id_acta)
                ->select(
                    'esc_actas.*',
                    'esc_grupo.nombre as grupo',
                    'esc_grupo.turno_id',
                    'esc_calificacion.calificacion',
                    'esc_calificacion.calificacion_tipo',
                    'esc_calificacion.faltas',
                    DB::raw("CONCAT(emp_perfil.nombre, ' ', emp_perfil.apellido1, ' ', emp_perfil.apellido2) as docente"),
                    'esc_curso.nombre as asignatura',
                    'asi_asignatura.clave',
                    'cat_plantel.nombre as plantel',
                    'cat_ciclos_esc.nombre as ciclo',
                    'cat_plantel.localidad',
                    'asi_asignatura.periodo as semestre',
                    'alu_alumno.noexpediente',
                    'users.name',
                    DB::raw("CONCAT(alu_alumno.nombre, ' ', alu_alumno.apellidos) as alumno")
                )
                ->orderBy('esc_actas.estado')
                ->get();
            $pdf = \PDF::loadView('estadisticas.cursos.actas.index', compact('logo', 'actas', 'fechaTexto'));
            return $pdf->stream('Acta especial Parcial.pdf');
        }




        // dd($acta);

    }

    public function actualizar_cursos()
    {
        //if(Auth()->user()->hasPermissionTo('cursos-editar'))
        //{
        return view('cursos.actualizar');
        //}
        //else
        //{
        //    BitacoraModel::create([
        //        'user_id'   =>  Auth()->user()->id,
        //        'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
        //        'path'      =>  $_SERVER["REQUEST_URI"],
        //        'method'    =>  $_SERVER['REQUEST_METHOD'],
        //        //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
        //        'controller'    =>  'MantenimiendoController',
        //        //'component'     =>  'FormComponent',
        //        'function'  =>  'actualizar_cursos',
        //        'description'   =>  'Usuario sin permisos',
        //    ]);

        //    return redirect()->route('dashboard',$aula->plantel_id)->with('danger','No tiene los permisos necesarios');
        //}
    }

    public function buscar_actas()
    {
        return view('cursos.actas');
    }
}
