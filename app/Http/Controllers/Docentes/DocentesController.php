<?php

namespace App\Http\Controllers\Docentes;

use App\Exports\Docentes\CalificacionCursoExport;
use App\Http\Controllers\Controller;
use App\Models\Administracion\PerfilModel;
use App\Models\Cursos\CursosModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;



class DocentesController extends Controller
{
    public function index()
    {
        return view('docentes.index');
    }

    public function imprime_calificaciones_curso($curso_id, $politica_variable_id)
    {
        $nombre_archivo = CursosModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
            ->where('esc_curso.id', $curso_id)
            ->select('esc_curso.nombre', 'esc_grupo.nombre as grupo', 'esc_grupo.turno_id')
            ->first();
        
        $turno = $nombre_archivo->turno_id == '1' ? 'MATUTINO' : 'VESPERTINO';
        return Excel::download(new CalificacionCursoExport($curso_id, $politica_variable_id), 'SICE-cobach-' . $nombre_archivo->nombre . '-' . 
        $nombre_archivo->grupo . ' ' . $turno . ' - ' . Carbon::now()->format('d-m-Y h:i A') . '.xlsx');
    }
    public function actas()
    {
        $docente = PerfilModel::where('user_id', Auth()->user()->id)->first();
        return view('docentes.actas');
    }

    public function generar_lista($curso_id, $parcial)
    {
        $diasHabiles = [];
        $logo = public_path('images/logocobachchico.png');

        $curso = CursosModel::find($curso_id);

        $determina_parcial = strtolower($curso->asignatura->determina_parcial($parcial));

        $fechas_ciclo = CursosModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
            ->join('cat_ciclos_esc', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
            ->join('cat_ciclos_configuraciones', 'cat_ciclos_configuraciones.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
            ->join('cat_plantel', 'cat_plantel.id', '=', 'esc_grupo.plantel_id')
            ->join('emp_perfil', 'esc_curso.docente_id', '=', 'emp_perfil.id')
            ->select(
                'cat_ciclos_configuraciones.*',
                'cat_plantel.nombre as plantel',
                'esc_grupo.nombre as grupo',
                'esc_grupo.turno_id',
                'emp_perfil.nombre as docente_nombre',
                'emp_perfil.apellido1 as docente_apellido_paterno',
                'emp_perfil.apellido2 as docente_apellido_materno',
                'cat_ciclos_esc.nombre as ciclo_escolar'
            )
            ->where('esc_curso.id', $curso_id)
            ->first();
        //dd($fechas_ciclo);
/*
        switch ($determina_parcial) {
            case 'p1':
                $inicio = $fechas_ciclo->inicio_semestre;
                $fin = $fechas_ciclo->fin_p1;
                break;
            case 'p2':
                $inicio = $fechas_ciclo->fin_p1;
                $fin = $fechas_ciclo->fin_p2;
                break;
            case 'p3':
                $inicio = $fechas_ciclo->fin_p2;
                $fin = $fechas_ciclo->fin_p3;
                break;

            default:
                # code...
                break;
        }
        $fecha_inicio = Carbon::parse($inicio);

        $fecha_fin = Carbon::parse($fin);
        // dd($fecha_inicio, $fecha_fin);

        while ($fecha_inicio->lte($fecha_fin)) {
            if ($fecha_inicio->isWeekday()) {
                $diasHabiles[] = [
                    'fecha' => $fecha_inicio->format('Y-m-d'),
                    'dia' => ucfirst($fecha_inicio->translatedFormat('l'))
                ];
            }

            $fecha_inicio->addDay();
        }*/

        $diasHabiles = collect([]);
        $alumnos_curso = DB::select('CALL calificaciones_cursos(?)', [$curso_id]);

        $pdf = \PDF::loadView('docentes.lista_asistencia', compact('diasHabiles', 'alumnos_curso', 'fechas_ciclo', 'logo'));
        $turno = $fechas_ciclo->turno_id == '1' ? 'MATUTINO' : 'VESPERTINO';
        return $pdf->download('Lista_de_asistencia_' . $alumnos_curso[0]->curso_nombre . '_' . $fechas_ciclo->grupo . ' ' . $turno . '.pdf');
    }
}
