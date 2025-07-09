<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Escolares\ActaExtemporaneaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ActaExtemporaneaAlumnoController extends Controller
{

    /*
    Actas Extemporaneas
    */
    public function busqueda_plantel()
    {
        //Muestra select de planteles
        $planteles = obtenerPlanteles();

        return view('adminalumnos.acta_extemporanea.seleccion_plantel', compact('planteles'));
    }

    public function busqueda_plantel_post(Request $request)
    {
        //Muestra Select2 de alumnos
        $sql = "SELECT DISTINCT alu_alumno.id, alu_alumno.noexpediente, alu_alumno.nombre, alu_alumno.apellidos ";
        $sql = $sql."FROM alu_alumno ";
        $sql = $sql."INNER JOIN esc_grupo_alumno ON esc_grupo_alumno.alumno_id = alu_alumno.id ";
        $sql = $sql."INNER JOIN esc_grupo ON esc_grupo.id = esc_grupo_alumno.grupo_id ";
        $sql = $sql."WHERE (esc_grupo.plantel_id = ".$request->plantel_id." OR esc_grupo.plantel_id = 34 OR esc_grupo.plantel_id = 31) ";
        $sql = $sql."ORDER BY alu_alumno.apellidos";
        
        $alumnos = DB::select($sql);
        $plantel = PlantelesModel::find($request->plantel_id);

        return view('adminalumnos.acta_extemporanea.seleccion_alumno', compact('alumnos','plantel'));

    }

    public function alumno(Request $request)
    {
        //Muestra Datos de alumnos para crear el acta (livewire)
        $alumno_id = $request->alumno_id;
        return view('adminalumnos.acta_extemporanea.alumno_seleccionado', compact('alumno_id'));
    }

    public function listado_actas($plantel_id)
    {
        //Muestra el listado de actas creadas en el plantel, para su impresión
        //Livewire
        return view('adminalumnos.acta_extemporanea.lista_actas', compact('plantel_id'));
    }

    public function imprime_acta($act_id)
    {
        $acta = ActaExtemporaneaModel::find($act_id);
        $imp = $acta->impresiones;
        $imp++;
        $buscar_asignatura = AsignaturaModel::find($acta->asignatura_id);
        $acta->impresiones = $imp;
        $acta->fecha_impresion = date("Y-m-d H:i:s");
        $acta->update();


        switch ($buscar_asignatura->periodo) {
            case '1':
              $semestre_texto = "primer";
              break;
            case '2':
              $semestre_texto = "segundo";
              break;
            case '3':
              $semestre_texto = "tercer";
    
              break;
            case '4':
              $semestre_texto = "cuarto";
              break;
            case '5':
              $semestre_texto = "quinto";
              break;
            case '6':
              $semestre_texto = "sexto";
              break;
            default:
            
              break;
          }

        if($acta->tipo_acta <> "PASANTIA")
        {
            $pdf = PDF::loadView('adminalumnos.acta_extemporanea.pdfs.acta_extemporanea', compact('acta', 'semestre_texto'));
            $pdf->setPaper('A4');
            return $pdf->stream('Acta_'.$acta->id.'.pdf');
        }
        else
        {
            $pdf = PDF::loadView('adminalumnos.acta_extemporanea.pdfs.pasantia', compact('acta', 'semestre_texto'));
            $pdf->setPaper('A4');
            return $pdf->stream('Pasantia_'.$acta->id.'.pdf');
        }
        
    }
}
