<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;



class ReportesController extends Controller
{

    public function concentrado(){
        //Cambio su funcionalidad mas no el nombre de la vista
        return view('estadisticas.reportes.concentrado');
    }

    public function promedios()
    {
        return view('estadisticas.reportes.promedios');
    }

    public function catalogo()
    {
        return view('estadisticas.reportes.catalogo_alumnos');
    }
    public function plantel_fotos()
    {
        return view('estadisticas.reportes.reporte_fotos_plantel');
    }

    public function relacion_egreso_plantel()
    {
        return view('estadisticas.reportes.alumnos_egresos');

    }

    public function plantel_fotos_credenciales()
    {
        return view();
    }

    public function asignaturas_reprobadas(){
        return view('estadisticas.reportes.asignaturas_reprobadas');
    }

    public function nuevos_alumnos()
    {
        $ciclo_activo = CicloEscModel::where('activo', '1')
            ->orderBy('per_inicio', 'desc')
            ->where('tipo', 'N')->first();
        //dd($ciclo_activo);

        $roles = Auth()->user()->getRoleNames()->toArray();

        foreach ($roles as $role) {
            if ($role === "control_escolar") {
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
        if ($todos_los_valores == 1) {
            $planteles = PlantelesModel::all(); // Obtener todos los planteles
            $zip = new \ZipArchive();
            $zipFileName = storage_path('nuevos_alumnos_' . Carbon::now()->format('d_m_Y_H_i_s') . '.zip');

            if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
                foreach ($planteles as $plantel) {
                    $buscar_alumnos_grupo = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                        ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                        ->where('ciclo_esc_id', $ciclo_activo->id)
                        ->where('esc_grupo.periodo', '1')
                        ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                        ->where('cat_plantel.id', $plantel->id)
                        ->select(
                            'alu_alumno.nombre',
                            'alu_alumno.apellidos',
                            \DB::raw("CONCAT(esc_grupo.nombre, CASE WHEN esc_grupo.turno_id = 1 THEN 'M' ELSE 'V' END) as grupo")
                        )
                        ->orderBy('alu_alumno.apellidos')
                        ->get();

                    $fecha_actual = Carbon::now()->format('d/m/Y');
                    $pdf = Pdf::loadView('estadisticas.grupos.nuevos_alumnos', compact('buscar_alumnos_grupo', 'fecha_actual', 'plantel'));

                    // Guardar cada PDF en una ubicación temporal
                    $pdfFilePath = storage_path('nuevos_alumnos_' . $plantel->nombre . '.pdf');
                    $pdf->save($pdfFilePath);

                    // Agregar el PDF al ZIP
                    $zip->addFile($pdfFilePath, 'nuevos_alumnos_' . $plantel->nombre . '.pdf');
                }

                // Cerrar el archivo ZIP
                $zip->close();

                // Eliminar los archivos PDF individuales si no los necesitas más
                foreach ($planteles as $plantel) {
                    $pdfFilePath = storage_path('nuevos_alumnos_' . $plantel->nombre . '.pdf');
                    if (file_exists($pdfFilePath)) {
                        unlink($pdfFilePath);
                    }
                }

                // Descargar el archivo ZIP
                return response()->download($zipFileName)->deleteFileAfterSend(true);
            } else {
                return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
            }
        } else {
            $buscar_alumnos_grupo = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                ->where('ciclo_esc_id', $ciclo_activo->id)
                ->where('esc_grupo.periodo', '1')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->whereIn('cat_plantel.abreviatura', $validaciones)
                ->select(
                    'alu_alumno.nombre',
                    'alu_alumno.apellidos',
                    \DB::raw("CONCAT(esc_grupo.nombre, CASE WHEN esc_grupo.turno_id = 1 THEN 'M' ELSE 'V' END) as grupo")
                )
                ->orderBy('alu_alumno.apellidos')
                ->get();
            $plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->first();

            $fecha_actual = Carbon::now()->format('d/m/Y');
            $pdf = Pdf::loadView('estadisticas.grupos.nuevos_alumnos', compact('buscar_alumnos_grupo', 'fecha_actual', 'plantel'));
            return $pdf->download('nuevos_alumnos_' . $plantel->nombre . '.pdf');
        }

    }
    public function alumnosreprobados()
    {
        return view('estadisticas.reportes.alumnosreprobados');
    }


    public function materiasreprobadas($plantel, $grupos, $periodo, $docente, $curso)
    {
        $pdf = PDF::loadView('estadisticas.reportes.materiasreprobadas', array(
            'plantel' => base64_decode($plantel),
            'grupos' => base64_decode($grupos)
            ,
            'periodo' => base64_decode($periodo),
            'docente' => base64_decode($docente),
            'curso' => base64_decode($curso)
        ));
        return $pdf->stream('materiasreprobadas.pdf');
    }

    public function docentesmateriasreprobadas($plantel, $grupos, $periodo, $docente, $curso)
    {
        $pdf = PDF::loadView('estadisticas.reportes.docentesmateriasreprobadas', array(
            'plantel' => base64_decode($plantel),
            'grupos' => base64_decode($grupos)
            ,
            'periodo' => base64_decode($periodo),
            'docente' => base64_decode($docente),
            'curso' => base64_decode($curso)
        ));
        return $pdf->stream('docentesmateriasreprobadas.pdf');
    }

    public function alumnosmateriasreprobadas($plantel, $grupos, $periodo, $docente, $curso)
    {
        $pdf = PDF::loadView('estadisticas.reportes.alumnosmateriasreprobadas', array(
            'plantel' => base64_decode($plantel),
            'grupos' => base64_decode($grupos)
            ,
            'periodo' => base64_decode($periodo),
            'docente' => base64_decode($docente),
            'curso' => base64_decode($curso)
        ));
        return $pdf->stream('alumnosmateriasreprobadas.pdf');
    }
    public function alumnosenriesgo()
    {
        return view('estadisticas.reportes.alumnosenriesgo');
    }


    public function parmateriasreprobadas($plantel, $grupos, $periodo, $docente, $curso)
    {

        $pdf = PDF::loadView('estadisticas.reportes.parmateriasreprobadas', array(
            'plantel' => base64_decode($plantel),
            'grupos' => base64_decode($grupos),
            'periodo' => base64_decode($periodo),
            'docente' => base64_decode($docente),
            'curso' => base64_decode($curso)
        ));
        return $pdf->stream('parmateriasreprobadas.pdf');
    }
    public function paralumnosmateriasreprobadas($plantel, $grupos, $periodo, $docente, $curso)
    {

        $pdf = PDF::loadView('estadisticas.reportes.paralumnosmateriasreprobadas', array(
            'plantel' => base64_decode($plantel),
            'grupos' => base64_decode($grupos),
            'periodo' => base64_decode($periodo),
            'docente' => base64_decode($docente),
            'curso' => base64_decode($curso)
        ));
        return $pdf->stream('paralumnosmateriasreprobadas.pdf');
    }
    public function pardocentesmateriasreprobadas($plantel, $grupos, $periodo, $docente, $curso)
    {

        $pdf = PDF::loadView('estadisticas.reportes.pardocentesmateriasreprobadas', array(
            'plantel' => base64_decode($plantel),
            'grupos' => base64_decode($grupos)
            ,
            'periodo' => base64_decode($periodo),
            'docente' => base64_decode($docente),
            'curso' => base64_decode($curso)
        ));
        return $pdf->stream('pardocentesmateriasreprobadas.pdf');
    }

    public function mejorespromedios()
    {
        return view('estadisticas.reportes.mejorespromedios');
    }
    public function mejorespromediosalumno($plantel, $periodo)
    {

        $pdf = PDF::loadView('estadisticas.reportes.mejorespromediosalumno', array('plantel' => base64_decode($plantel), 'periodo' => base64_decode($periodo)));
        return $pdf->stream('mejorespromediosalumno.pdf');
    }
    public function mejorespromediosgrupo($plantel, $periodo)
    {

        $pdf = PDF::loadView('estadisticas.reportes.mejorespromediosgrupo', array('plantel' => base64_decode($plantel), 'periodo' => base64_decode($periodo)));
        return $pdf->stream('mejorespromediosgrupo.pdf');
    }
    public function movimientosmensuales()
    {

         return view('estadisticas.reportes.movimientosmensuales');

    }

    public function movmensuales($ciclo,$plantel)
    {
          $pdf = PDF::loadView('estadisticas.reportes.movmensuales', array('ciclo_esc'=>base64_decode($ciclo),'plantel'=>base64_decode($plantel)));
          $pdf->setPaper('letter', 'landscape');
        return $pdf->stream('movmensuales.pdf');
    }
    public function indicadordesercion()
    {

         return view('estadisticas.reportes.indicadordesercion');

    }

    public function indicadesercion($ciclo)
    {
          $pdf = PDF::loadView('estadisticas.reportes.indicadesercion', array('ciclo_esc'=>base64_decode($ciclo)));
          $pdf->setPaper('letter', 'landscape');
        return $pdf->stream('indicadesercion.pdf');
    }
    public function egresados()
    {

         return view('estadisticas.reportes.egresados');

    }

    public function egresadosciclo($ciclo)
    {
          $pdf = PDF::loadView('estadisticas.reportes.egresadosciclo', array('ciclo_esc'=>base64_decode($ciclo)));
          $pdf->setPaper('letter', 'landscape');
        return $pdf->stream('egresadosciclo.pdf');
    }
    public function ingresos()
    {

         return view('estadisticas.reportes.ingresos');

    }
    public function ingresosciclo($ciclo)
    {
          $pdf = PDF::loadView('estadisticas.reportes.ingresosciclo', array('ciclo_esc'=>base64_decode($ciclo)));
          $pdf->setPaper('letter', 'landscape');
        return $pdf->stream('ingresosciclo.pdf');
    }    public function documento_expediente(){
        return view('estadisticas.reportes.documentos_expediente');
    }


}
