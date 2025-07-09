<?php
//ANA MOLINA 08/11/2023
namespace App\Http\Livewire\Adminalumnos\Boleta;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReporteComponent extends Component
{
    public $alumno_id;
    public $ciclo_id;
    public $calificaciones;
    public $calificacionesex;
      public function render()
    { 
        if(empty($this->calificaciones))
        {
            $this->calificaciones=DB::select('call pa_boletaex(?,?,?)',array( $this->alumno_id,$this->ciclo_id,0));
            session()->put('calificaciones', $this->calificaciones);
            session(['calificaciones' =>  $this->calificaciones ]);
        }
        if(empty($this->calificacionesex))
        {
            $this->calificacionesex=DB::select('call pa_boletaex(?,?,?)',array( $this->alumno_id,$this->ciclo_id,1));
            session()->put('calificacionesex', $this->calificacionesex);
            session(['calificacionesex' =>  $this->calificacionesex ]);
        }

        $alumno_find = AlumnoModel::find($this->alumno_id);
        $alumno=$alumno_find->apellidos.' '.$alumno_find->nombre;
        $noexpediente=$alumno_find->noexpediente;
        $curp=$alumno_find->curp;

        $dataplan=DB::select("SELECT asi_planestudio.nombre AS plan
 	    FROM esc_grupo_alumno
        join esc_grupo on esc_Grupo.id=esc_grupo_alumno.grupo_id
         join esc_curso on esc_grupo_alumno.grupo_id=esc_curso.grupo_id
    	JOIN asi_planestudio ON asi_planestudio.id=esc_curso.plan_estudio_id
        WHERE  esc_grupo_alumno.alumno_id=".$this->alumno_id." AND esc_grupo.ciclo_esc_id=".$this->ciclo_id."  LIMIT 1");

        $datadomi=DB::select("SELECT domicilio
        FROM alu_alumno
         WHERE ID=".$this->alumno_id);


        //busca datos del último periodo escolar
        $data=DB::select("SELECT esc_grupo.nombre AS grupo, case turno_id when 1 then 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno, cat_plantel.NOMBRE AS plantel
        ,'".$dataplan[0]->plan."' as plan,".$noexpediente." as noexpediente,'".$curp."' as curp,'".$datadomi[0]->domicilio."' as domi,1 as periodo,cat_ciclos_Esc.nombre as ciclo
        ,cct,director
        FROM esc_grupo_alumno
        LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.GRUPO_ID
        LEFT JOIN cat_ciclos_Esc ON cat_ciclos_Esc.id=esc_grupo.ciclo_esc_id
        LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id
        WHERE ALUMNO_ID=".$this->alumno_id."
        ORDER BY per_inicio DESC
        LIMIT 1");
        $datos=$data[0];

        return view('livewire.adminalumnos.boleta.reporte-component',compact('alumno' ,'datos' ));
    }
}
