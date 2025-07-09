<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\CiclosConfigModel;
use App\Models\Catalogos\DocentesCorreosModel;
use App\Models\Escolares\CalificacionesCicloAnterior;
use App\Models\Escolares\CalificacionesNoEncontrados;
use App\Models\Finanzas\FichasModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class LoginUsuarios extends Controller
{

    public function iniciar_reinscripcion()
    {
        $alumno = AlumnoModel::where('correo_institucional', Auth()->user()->email)->first();

        $resultado = Session::get('datos_alumno_materias');
        $contador_reprobadas = 0;
        $reinscribe = 1;

        $pasar_ventanilla = false; //Esta variable aplica para los alumnos de nuevo ingreso que no tienen su certificado de secundaria cargado en el sistema para que pasen a cargarlo en caja
        $tieneficha = false;
        $semestre = null;
        $ficha = FichasModel::where('matricula', $alumno->noexpediente)->where('fecha_caducidad', '>=', now())->first();

        if ($ficha) {
            if ($ficha->semestre == 2) {
                $buscar_certificado_secundaria = ImagenesalumnoModel::where('alumno_id', $alumno->id)->where('tipo', '5')
                    ->select('tipo')
                    ->first();
                if ($buscar_certificado_secundaria) {
                    $pasar_ventanilla = true;
                }
                //$pasar_ventanilla = true; //solo prueba
            }
            $tieneficha = true;
            $semestre = $ficha->semestre;

        } else {
            $tieneficha = false;
        }

        $fecha = Carbon::now();

        // Definir el inicio y el final del rango
        $startDate = Carbon::create(2025, 1, 6, 0, 0, 0); // 2024-07-29 08:01:00
        $endDate = Carbon::create(2025, 1, 15, 23, 59, 59); // 2024-08-05 23:59:59

        $grupo = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
            ->join('cat_ciclos_esc', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
            ->where('esc_grupo_alumno.alumno_id', $alumno->id)
            ->where('cat_ciclos_esc.id', '250')
            ->where('gpo_base', '1')
            ->first();

        if (!$grupo) {
            $grupo = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                ->join('cat_ciclos_esc', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
                ->where('esc_grupo_alumno.alumno_id', $alumno->id)
                ->where('cat_ciclos_esc.id', '250')
                ->orderBy('cat_ciclos_esc.per_inicio', 'desc')
                ->first();
        }

        return view('adminalumnos.ingresoalumno.reinscripcion', compact('alumno', 'pasar_ventanilla', 'tieneficha', 'semestre', 'ficha', 'grupo'))
            ->with('reinscribe', $reinscribe)
            ->with('materias', $resultado);


        /*
        if ($fecha->greaterThanOrEqualTo($startDate) && $fecha->lessThanOrEqualTo($endDate)) {
          
            return view('adminalumnos.ingresoalumno.reinscripcion', compact('alumno', 'pasar_ventanilla', 'tieneficha', 'semestre', 'ficha', 'grupo'))
            ->with('reinscribe', $reinscribe)
            ->with('materias', $resultado);

          
        } else {

       

            if ($alumno->id_estatus == 1) {
                //return redirect('/logout');
         

                return view('adminalumnos.ingresoalumno.reinscripcion', compact('alumno', 'pasar_ventanilla', 'tieneficha', 'semestre', 'ficha', 'grupo'))->with('reinscribe', $reinscribe)->with('materias', $resultado);
            } else {
                Auth::logout();

                return redirect('/login')->with('error', 'Las inscripciones se encuentrar cerradas');
            }


        }

        
        return view('adminalumnos.ingresoalumno.reinscripcion', compact('alumno', 'pasar_ventanilla', 'tieneficha', 'semestre', 'ficha', 'grupo'))->with('reinscribe', $reinscribe)->with('materias', $resultado);

*/
    }

    public function valida_usuario()
    {

        $validador = Session::get('datos_alumno_materias');

        $docentes_correo = DocentesCorreosModel::where('email', Auth()->user()->email)->first();
        if ($docentes_correo) {
            Auth()->user()->assignRole('docente');
        }

        $alumno = AlumnoModel::where('correo_institucional', Auth()->user()->email)->first();
        if ($alumno) {
            Auth()->user()->assignRole('alumno');

            return redirect('/alumno/iniciar_reinscripcion');
        } else {

            if (
                Auth()->user()->hasRole('super_admin') || Auth()->user()->hasRole('docente') ||
                Auth()->user()->hasRole('asistencia_educativa') || str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar') ||
                str_contains(Auth()->user()->roles->pluck('name'), 'inclusion_educativa') ||
                str_contains(Auth()->user()->roles->pluck('name'), 'autorizar_rev')
                || str_contains(Auth()->user()->roles->pluck('name'), 'orientacion_educativa')
            ) {
                //Session::put('usuario_id', )
                $roles = Auth()->user()->getRoleNames()->toArray();
                //dd($roles);
                // Guardar roles en la sesión
                session(['roles' => $roles]);

                return view('dashboard');
            } else {
                return redirect('/logout');
            }
        }


    }
}
