<?php

namespace App\Exports\Grupos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Cursos\CursosModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class CalificacionesGruposExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $grupo_id;
    public $tipo_calif;

    public function __construct($grupo_id, $tipo_calif)
    {
        $this->grupo_id = $grupo_id;
        $this->tipo_calif = $tipo_calif;
    }

    public function view(): View
    {
        $grupo = GruposModel::find($this->grupo_id);
        $cursos = $grupo->cursos;
        $array_calificaciones = [];
        foreach ($cursos as $curso) {
            //siempre es 1??? Aqui no se está asignando bien :()
            $politica_variable_p1 = CursosModel::join('asi_asignatura', 'asi_asignatura.id', '=', 'esc_curso.asignatura_id')
                ->join('asi_areaformacion', 'asi_areaformacion.id', '=', 'asi_asignatura.id_areaformacion')
                ->join('asi_politica', 'asi_politica.id_areaformacion', '=', 'asi_areaformacion.id')
                ->join('asi_politica_variable', 'asi_politica_variable.id_politica', '=', 'asi_politica.id')
                ->join('asi_variableperiodo', 'asi_variableperiodo.id', '=', 'asi_politica_variable.id_variableperiodo')
                ->select('asi_variableperiodo.*')
                ->where('esc_curso.id', $curso->id)
                ->where('asi_variableperiodo.nombre', $this->tipo_calif)
                //->orderBy('asi_variableperiodo.id', 'asc')
                //dd($query->toSql(), $query->getBindings());
                ->first();
            //dd($politica_variable_p1);

            $curso_nombre = CursosModel::find($curso->id);
            if (strcasecmp($curso_nombre->nombre, "SERVICIO SOCIAL") == 0 || strcasecmp($curso_nombre->nombre, "PRACTICAS PREPROFESIONALES") == 0) {
                $sql = "SELECT DISTINCT`esc_curso`.`id` AS `curso_id`, `alu_alumno`.`id` AS `alumno_id`, `alu_alumno`.`noexpediente`, ";
                $sql = $sql . "`alu_alumno`.`nombre`, `alu_alumno`.`apellidos`, COALESCE(`esc_calificacion`.`calificacion`, `esc_calificacion`.`calif`) AS calificacion, `esc_calificacion`.`faltas`, ";
                $sql = $sql . "`esc_calificacion`.`curso_id` ";
                $sql = $sql . "FROM `esc_curso` ";
                $sql = $sql . "INNER JOIN `esc_grupo` ON `esc_grupo`.`id` = `esc_curso`.`grupo_id` ";
                $sql = $sql . "INNER JOIN `esc_grupo_alumno` ON `esc_grupo`.`id` = `esc_grupo_alumno`.`grupo_id` ";
                $sql = $sql . "INNER JOIN `alu_alumno` ON `esc_grupo_alumno`.`alumno_id` = `alu_alumno`.`id` ";
                $sql = $sql . "LEFT JOIN `esc_calificacion` ON `esc_calificacion`.`curso_id` = `esc_curso`.`id` 
                and `esc_calificacion`.`alumno_id` = `alu_alumno`.`id` and `esc_calificacion`.`calificacion_tipo` = 'Final' ";
                $sql = $sql . "WHERE `esc_curso`.`id` = '" . $curso->id . "' ORDER BY `alu_alumno`.`apellidos` ASC";
            } else {
                $sql = "SELECT DISTINCT`esc_curso`.`id` AS `curso_id`, `alu_alumno`.`id` AS `alumno_id`, `alu_alumno`.`noexpediente`, ";
                $sql = $sql . "`alu_alumno`.`nombre`, `alu_alumno`.`apellidos`, COALESCE(`esc_calificacion`.`calificacion`, `esc_calificacion`.`calif`) AS calificacion, `esc_calificacion`.`faltas`, ";
                $sql = $sql . "`esc_calificacion`.`curso_id` ";
                $sql = $sql . "FROM `esc_curso` ";
                $sql = $sql . "INNER JOIN `esc_grupo` ON `esc_grupo`.`id` = `esc_curso`.`grupo_id` ";
                $sql = $sql . "INNER JOIN `esc_grupo_alumno` ON `esc_grupo`.`id` = `esc_grupo_alumno`.`grupo_id` ";
                $sql = $sql . "INNER JOIN `alu_alumno` ON `esc_grupo_alumno`.`alumno_id` = `alu_alumno`.`id` ";
                $sql = $sql . "LEFT JOIN `esc_calificacion` ON `esc_calificacion`.`curso_id` = `esc_curso`.`id` 
            and `esc_calificacion`.`alumno_id` = `alu_alumno`.`id` and `esc_calificacion`.`calificacion_tipo` = '" . $this->tipo_calif . "' ";
                $sql = $sql . "WHERE `esc_curso`.`id` = '" . $curso->id . "' ORDER BY `alu_alumno`.`apellidos` ASC";
            }


            //dd($sql);
            $calificaciones = DB::select($sql);
            $cant_alumn = count($calificaciones);

            //dd($calificaciones); 
            $array_calificaciones += [$curso->id => array($calificaciones)];
        }
        //dd($array_calificaciones);
        //dd($array_calificaciones['304403'][0]);

        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            //'controller'    =>  'UserController',
            'component' => 'CalificacionesGruposExport',
            'function' => 'view',
            'description' => 'Generó reporte de calificaciones del grupo:' . $grupo->id . ' - ' . $grupo->nombre,
        ]);
        $parci = $this->tipo_calif;
        return view('exports.grupos.calificacionesgrupos', compact('grupo', 'cursos', 'array_calificaciones', 'cant_alumn', 'parci'));
    }
}
