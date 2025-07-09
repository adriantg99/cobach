<?php
//ANA MOLINA 07/08/2023
namespace App\Http\Livewire\Adminalumnos\Kardex;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReporteComponent extends Component
{
    public $alumno_id, $imagen_logo, $vista_alumno;
    public $calificacioneska;

    public function render()
    {
        //if(empty($this->calificacioneska))
        //{
        $this->calificacioneska = DB::select('call pa_kardex(?)', array($this->alumno_id));
        session()->put('calificacioneska', $this->calificacioneska);


        $alumno_find = AlumnoModel::find($this->alumno_id);
        $alumno = $alumno_find->noexpediente . ' ' . $alumno_find->apellidos . ' ' . $alumno_find->nombre;

        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller' => 'Kardex-ReporteComponent',
            //'component'     =>  'FormComponent',
            'function' => 'render',
            'description' => 'Generó Kardek para alumno Exp:' . $alumno_find->noexpediente,
        ]);

        //busca datos del último periodo escolar
        /*
        $data=DB::select("SELECT esc_grupo.nombre AS grupo, case turno_id when 1 then 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno, cat_plantel.nombre AS plantel, cat_plantel.id AS plantel_id FROM esc_grupo_alumno 
        LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.GRUPO_ID 
        LEFT JOIN cat_ciclos_Esc ON cat_ciclos_Esc.id=esc_grupo.ciclo_esc_id 
        LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id 
        WHERE ALUMNO_ID=".$this->alumno_id." 
        LIMIT 1");
        */
        $sql = "SELECT esc_grupo.nombre AS grupo,  ";
        $sql = $sql . "CASE turno_id WHEN 1 THEN 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno, ";
        $sql = $sql . "cat_plantel.nombre AS plantel, cat_plantel.id AS plantel_id ";
        $sql = $sql . "FROM esc_grupo_alumno ";
        $sql = $sql . "LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id ";
        $sql = $sql . "LEFT JOIN cat_ciclos_esc ON cat_ciclos_esc.id=esc_grupo.ciclo_esc_id ";
        $sql = $sql . "LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id ";
        $sql = $sql . "WHERE ((esc_grupo_alumno.alumno_id=" . $alumno_find->id . ") AND (cat_plantel.id <> 34) AND (esc_grupo.nombre <> 'ActasExtemporaneas') ";
        if($this->vista_alumno == true){
            $sql = $sql . "AND (cat_ciclos_esc.id = 250)) ";
        }else{
            $sql = $sql . ") ";
        }

        $sql = $sql . "ORDER BY cat_ciclos_esc.per_inicio DESC, esc_grupo.periodo DESC ";
        
        $sql = $sql . "LIMIT 1";
        
        //dd($sql);
        $data = DB::select($sql);

        $datos = $data[0];

        $cantidad = 0;
        $cal_acumulada = 0;
        $reprobados = 0;
        $aprobados = 0;
        //dd($this->calificacioneska);
        foreach ($this->calificacioneska as $cal) {
            $asignatura = AsignaturaModel::find($cal->asignatura_id);
            if (
                (isset($asignatura->clave) && (substr($asignatura->clave, 1, 2) === "15" || substr($asignatura->clave, 1, 2) === "16")) 
            ) {
                if(stripos($asignatura->nombre, "CURRICULUM AMPLIADO") !== false){
                    
                }
                else{
                    continue;
                }
                
                
            }
            if ($asignatura->kardex) {
                if ((is_null($cal->calificacion) == false) or (is_null($cal->calif) == false)) {
                    if ($asignatura->afecta_promedio) {
                        if ($cal->calif == "REV") {
                            //$cal_acumulada = $cal_acumulada + 100;
                            $cantidad--;
                        } else {
                            $cal_acumulada = $cal_acumulada + (int) $cal->calificacion;
                        }
                        $cantidad++;
                    }
                    if (($cal->calificacion >= 60) or ($cal->calif == "AC") or ($cal->calif == "REV")) {
                        $aprobados++;
                    } else {
                        $reprobados++;
                    }
                } elseif ((is_null($cal->calificacion3) == false) or (is_null($cal->calif3) == false)) {
                    if ($asignatura->afecta_promedio) {
                        if ($cal->calif3 == "REV") {
                            // $cal_acumulada = $cal_acumulada + 100;
                            $cantidad--;
                        } else {
                            $cal_acumulada = $cal_acumulada + (int) $cal->calificacion3;
                        }
                        $cantidad++;
                    }
                    if (($cal->calificacion3 >= 60) or ($cal->calif3 == "AC") or ($cal->calif3 == "REV")) {
                        $aprobados++;
                    } else {
                        $reprobados++;
                    }
                } elseif ((is_null($cal->calificacion2) == false) or (is_null($cal->calif2) == false)) {
                    if ($asignatura->afecta_promedio) {
                        if ($cal->calif2 == "REV") {
                            // $cal_acumulada = $cal_acumulada + 100;
                            $cantidad--;
                        } else {
                            $cal_acumulada = $cal_acumulada + (int) $cal->calificacion2;
                        }
                        $cantidad++;
                    }
                    if (($cal->calificacion2 >= 60) or ($cal->calif2 == "AC") or ($cal->calif2 == "REV")) {
                        $aprobados++;
                    } else {
                        $reprobados++;
                    }
                } elseif ((is_null($cal->calificacion1) == false) or (is_null($cal->calif1) == false)) {
                    if ($asignatura->afecta_promedio) {
                        if ($cal->calif1 == "REV") {
                            // $cal_acumulada = $cal_acumulada + 100;
                            $cantidad--;
                        } else {
                            $cal_acumulada = $cal_acumulada + (int) $cal->calificacion1;
                        }
                        $cantidad++;
                    }
                    if (($cal->calificacion1 >= 60) or ($cal->calif1 == "AC") or ($cal->calif1 == "REV")) {
                        $aprobados++;
                    } else {
                        $reprobados++;
                    }
                }

            }

        }
        //dd($cal_acumulada." - ".$cantidad);
        if ($this->calificacioneska) {
            $promedio = $cal_acumulada / $cantidad;
        } else {
            $promedio = 0;
        }

        //CALCULA PROMEDIO_F

        //busca número de aprobadas y reprobadas
        //$materias = DB::select('call pa_kardex_Aprob_Reprob(?)',array( $this->alumno_id));
        //$resulmater=$materias[0];

        return view('livewire.adminalumnos.kardex.reporte-component', compact('alumno', 'datos', 'promedio', 'aprobados', 'reprobados'));
    }
    public function mount()
    {
        if ($this->calificacioneska == false) {
            $this->vista_alumno = true;
        } else {
            $this->vista_alumno = false;
        }
    }
}
