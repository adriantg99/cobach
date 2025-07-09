<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
class AlumnosmateriasreprobadasComponent extends Component
{
    public $plantel,  $periodo;
    public $grupos ;
    public $docente;
    public $curso;
    public function render()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $plantel_sel = PlantelesModel::find($this->plantel);
        $cicloesc=CicloEscModel::where ('activo','=',1)->first();
        $docente='';
        $curso='';
        if($this->docente!='|')
            $docente=" and esc_curso.docente_id=".$this->docente;
        if($this->curso!='|')
            $curso=" and esc_curso.nombre='".$this->curso."'";

         $query="SELECT  alu_alumno.noexpediente,CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) AS alumno,
                asi_asignatura.clave,
                esc_curso.nombre AS materia,
                esc_calificacion.faltas,emp_perfil.expediente,
                concat(emp_perfil.apellido1,' ',emp_perfil.apellido2,' ',emp_perfil.nombre) as docente
                ,CASE WHEN calif = '' OR calif IS null then esc_calificacion.calificacion else esc_calificacion.calif end as califica
                ,esc_grupo.nombre as grupo,cat_turno.NOMBRE AS turno
            FROM esc_calificacion
            JOIN esc_curso ON esc_curso.id = esc_calificacion.curso_id
            JOIN asi_asignatura ON asi_asignatura.id = esc_curso.asignatura_id
            JOIN esc_grupo ON esc_grupo.id = esc_curso.grupo_id
            join alu_alumno on alu_alumno.id=esc_calificacion.alumno_id
            left join emp_perfil on emp_perfil.id=esc_curso.docente_id
            JOIN cat_turno ON cat_turno.ID=esc_grupo.TURNO_ID
            WHERE esc_grupo.ciclo_esc_id = " .  $cicloesc->id . " and esc_calificacion.calificacion_tipo ='".$this->periodo."' ".$docente.$curso." and
            esc_curso.grupo_id in(".$this->grupos.")   AND  CASE WHEN calif = '' OR calif IS null THEN CASE WHEN calificacion < 60 THEN 1 ELSE 0 END WHEN calif <> 'AC'THEN 1 ELSE 0 END=1 ORDER BY esc_grupo.nombre,cat_turno.nombre, CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) ,asi_asignatura.clave";

        $datos = DB::select($query);

        if ($this->periodo=='P1')
            $titulo='EL PARCIAL 1';
        else
        if ($this->periodo=='P2')
            $titulo='EL PARCIAL 2';
        else
        if ($this->periodo=='P3')
            $titulo='EL PARCIAL 3';
        else
            $titulo='REGULARIZACION';

        $cicloesc=$cicloesc->nombre;
        $plantelnom=$plantel_sel->nombre;
        return view('livewire.reportes.alumnosmateriasreprobadas-component',compact('datos' ,'cicloesc', 'plantelnom', 'titulo'));


    }

}
