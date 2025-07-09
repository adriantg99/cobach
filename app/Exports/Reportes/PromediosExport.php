<?php

namespace App\Exports\Reportes;


use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;


class PromediosExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $plantel;
    protected $periodo;
    protected $ciclo;


    public function __construct($plantel, $periodo, $ciclo =null)
    {
        $this->plantel = $plantel;
        $this->periodo = $periodo;
        $this->ciclo = $ciclo;
    }
    public function collection()
    {
        //ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes
        if ($this->ciclo === null || $this->ciclo == 0) {
            $ciclos = CicloEscModel::where('activo', '1')->first();
        } else {
            $ciclos = CicloEscModel::where('id', $this->ciclo)->first();
        }
        if($this->periodo == null){
            $this->periodo = 0;
        }
        
        if($this->plantel == 0){
            $alumnos = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
            ->join('alu_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
            ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
            ->where('esc_grupo.ciclo_esc_id', $ciclos->id)
            //->where('esc_grupo.periodo', $this->periodo)
            //->where('esc_grupo.plantel_id', $this->plantel)
            ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
            ->selectRaw("alu_alumno.id, alu_alumno.noexpediente, CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) AS nombre, 
            esc_grupo.periodo, 
            cat_plantel.nombre AS plantel,
            CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
            WHEN 1 THEN ' M'
            WHEN 2 THEN ' V'
            END) AS grupo")
            ->distinct('alu_alumno.id')
            ->get();
        }
        else{
            
            if ($this->periodo != 0) {
                $alumnos = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('alu_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                    ->where('esc_grupo.ciclo_esc_id', $ciclos->id)
                    ->where('esc_grupo.periodo', $this->periodo)
                    ->where('esc_grupo.plantel_id', $this->plantel)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->selectRaw("alu_alumno.id, alu_alumno.noexpediente, CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) AS nombre, 
                    esc_grupo.periodo, 
                    cat_plantel.nombre AS plantel,
                    CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                    WHEN 1 THEN ' M'
                    WHEN 2 THEN ' V'
                    END) AS grupo")
                    ->distinct('alu_alumno.id')
                    ->get();
            } else {
                $alumnos = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('alu_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                    ->where('esc_grupo.ciclo_esc_id', $ciclos->id)
                    ->where('esc_grupo.plantel_id', $this->plantel)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->selectRaw("alu_alumno.id, alu_alumno.noexpediente, CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) AS nombre, 
                    esc_grupo.periodo, cat_plantel.nombre AS plantel,
                    CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                    WHEN 1 THEN ' M'
                    WHEN 2 THEN ' V'
                    END) AS grupo")
                    ->distinct('alu_alumno.id')
                    ->get();
            }
        }
    



        $data = [];
        foreach ($alumnos as $alumno) {

            $query = "SELECT 
                ROW_NUMBER() OVER(PARTITION BY periodo ORDER BY clave DESC  ) AS ordenasiper,
                periodo,
                asignatura_id,
                materia,
                clave,
                MAX( CASE WHEN ordenasiper = 1 THEN ciclo ELSE NULL END ) AS ciclo1,
                MAX( CASE WHEN ordenasiper = 1 THEN calificacion ELSE NULL END ) AS calificacion1,
                MAX( CASE WHEN ordenasiper = 1 THEN calif ELSE NULL END ) AS calif1,
                MAX( CASE WHEN ordenasiper = 1 THEN calificacion_tipo_id ELSE NULL END ) AS tipo1,
                MAX( CASE WHEN ordenasiper = 2 THEN ciclo ELSE NULL END ) AS ciclo2,
                MAX( CASE WHEN ordenasiper = 2 THEN calificacion ELSE NULL END ) AS calificacion2,
                MAX( CASE WHEN ordenasiper = 2 THEN calif ELSE NULL END ) AS calif2,
                MAX( CASE WHEN ordenasiper = 2 THEN calificacion_tipo_id ELSE NULL END ) AS tipo2,
                MAX( CASE WHEN ordenasiper = 3 THEN ciclo ELSE NULL END ) AS ciclo3,
                MAX( CASE WHEN ordenasiper = 3 THEN calificacion ELSE NULL END ) as calificacion3,
                MAX( CASE WHEN ordenasiper = 3 THEN calif ELSE NULL END ) AS calif3,
                MAX( CASE WHEN ordenasiper = 3 THEN calificacion_tipo_id ELSE NULL END ) AS tipo3,
                MAX( CASE WHEN ordenasiper > 3 THEN ciclo ELSE NULL END ) AS ciclo,
                MAX( CASE WHEN ordenasiper > 3 THEN calificacion ELSE NULL END ) AS calificacion,
                MAX( CASE WHEN ordenasiper > 3 THEN calif ELSE NULL END ) AS calif,
                MAX( CASE WHEN ordenasiper > 3 THEN calificacion_tipo_id ELSE NULL END ) AS tipo,
                MAX(esc_curso_id) AS esc_curso_id 
            FROM(
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY asignatura_id ORDER BY per_inicio, calificacion_tipo_id ) AS ordenasiper,
                    periodo, 
                    materia,
                    clave,
                    ciclo,
                    calificacion,
                    calif,
                    calificacion_tipo_id,
                    asignatura_id,
                    esc_curso_id
                FROM (
                    SELECT 
                        esc_curso.id AS esc_curso_id, 
                        ROW_NUMBER() OVER(PARTITION BY esc_curso.asignatura_id, esc_curso.id, 
                            CASE WHEN NOT asi_politica_variable.esregularizacion IS NULL THEN 1 ELSE 0 END  
                            ORDER BY cat_ciclos_esc.per_inicio, asi_politica_variable.esregularizacion DESC) as sel,
                        asi_asignatura.periodo,
                        esc_curso.nombre AS materia,
                        asi_asignatura.clave,
                        cat_ciclos_esc.abreviatura AS ciclo,
                        esc_calificacion.calificacion,
                        esc_calificacion.calif,
                        esc_calificacion.calificacion_tipo_id,
                        asi_politica_variable.esregularizacion,
                        esc_curso.asignatura_id,
                        cat_ciclos_esc.per_inicio
                    FROM esc_calificacion 
                    JOIN esc_curso ON esc_curso.id = esc_calificacion.curso_id
                    JOIN asi_asignatura ON asi_asignatura.id = esc_curso.asignatura_id
                    JOIN esc_grupo ON esc_grupo.id = esc_curso.grupo_id
                    JOIN cat_ciclos_esc ON cat_ciclos_esc.id = esc_grupo.ciclo_esc_id
                    JOIN asi_areaformacion ON asi_asignatura.id_areaformacion = asi_areaformacion.id
                    JOIN asi_politica ON asi_politica.id_Areaformacion = asi_areaformacion.id
                    LEFT JOIN asi_politica_variable ON asi_politica_variable.id_politica = asi_politica.id  
                        AND asi_politica_variable.id = esc_calificacion.politica_variable_id
                    LEFT JOIN asi_variableperiodo ON asi_variableperiodo.id = asi_politica_variable.id_variableperiodo
                    WHERE ALUMNO_ID = " . $alumno->id . "
                    AND calificacion_tipo_id <> 0
                    ORDER BY asi_asignatura.clave, COALESCE(asi_politica_variable.esregularizacion,3)
                ) AS datos  
                WHERE sel = 1 
            ) AS kar
            GROUP BY periodo, materia, clave, asignatura_id 
            ORDER BY periodo, clave;";

            $promedios = DB::select($query);

            if ($promedios) {
                $cantidad = 0;
                $cal_acumulada = 0;
                $reprobados = 0;
                $aprobados = 0;
                $promedio = 0;
                foreach ($promedios as $cal) {
                    $asignatura = AsignaturaModel::find($cal->asignatura_id);
                    //if(($asignatura->periodo == 2) OR ($asignatura->periodo == 4) OR ($asignatura->periodo == 6)){
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



                    //}
                }
                if ($cal_acumulada == 0 || $cantidad == 0) {
                    continue;
                }
                $promedio = round($cal_acumulada / $cantidad, 2);
                if ($reprobados == 0) {
                    $reprobados = "0";
                }

                $data[] = [
                    'No expediente' => $alumno->noexpediente,
                    'Nombre' => $alumno->nombre,
                    'Semestre' => $alumno->periodo,
                    'Grupo' => $alumno->grupo,
                    //'Plantel' => $alumno->plantel,
                    'Promedio' => $promedio,
                    'Aprobados' => $aprobados,
                    'Reprobados' => $reprobados
                ];
            }
        }

        return collect($data);
    }

    public function startCell(): string
    {
        return 'A3'; // Configura el inicio de la tabla (fila y columna)
    }

    public function title(): string
    {
        return 'Promedios'; // Configura el título de la hoja Excel
    }

    public function columnFormats(): array
    {
        return [
            'A' => '0',
            'B' => '0',
            'C' => '0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('D2:E2');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);

        $ciclo_activo = CicloEscModel::where('activo', '1')->first();
        $datos_alumnos = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
            ->join('alu_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
            ->where('esc_grupo.ciclo_esc_id', $ciclo_activo->id)
            ->where('esc_grupo.plantel_id', $this->plantel)
            ->get();

        $plantel = PlantelesModel::find($this->plantel);

        $sheet->getStyle('A3:G3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        $sheet->setCellValue('A1', 'Colegio de Bachilleres del Estado de Sonora');

        $sheet->setCellValue('D2', 'PLANTEL: ' . $plantel->nombre);

        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach (range('B', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function headings(): array
    {
        return [
            'No expediente',
            'Nombre',
            'Semestre',
            'Grupo',
            //'Plantel',
            'Promedios',
            'Aprobados',
            'Reprobados',
        ];
    }
}
