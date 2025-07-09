<?php

namespace App\Exports\Grupos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Response;

class ConcentradoGruposExport
{
    public $grupo_id;

    public function __construct($grupo_id)
    {
        $this->grupo_id = $grupo_id;
        
    }

    public function export()
    {
        $grupo = GruposModel::find($this->grupo_id);
        
        $sql = "SELECT 
                    alu_alumno.noexpediente,
                    alu_alumno.nombre AS NOMBRE_ALUMNO,
                    alu_alumno.apellidos AS APELLIDOS_ALUMNO,
                    c.nombre AS nombre_curso,
                    a.clave AS Clave_asig,
                    MAX(CASE WHEN ec.calificacion_tipo = 'P1' THEN ec.calificacion ELSE NULL END) AS parcial1,
                    MAX(CASE WHEN ec.calificacion_tipo = 'P2' THEN ec.calificacion ELSE NULL END) AS parcial2,
                    MAX(CASE WHEN ec.calificacion_tipo = 'P3' THEN ec.calificacion ELSE NULL END) AS parcial3,
                    MAX(CASE WHEN ec.calificacion_tipo = 'Final' THEN COALESCE(ec.calificacion, ec.calif) ELSE NULL END) AS calificacion_final,
                    MAX(CASE WHEN ec.calificacion_tipo = 'R' THEN ec.calificacion ELSE NULL END) AS calificacion_r
                FROM 
                    esc_grupo_alumno ga
                INNER JOIN esc_grupo g ON g.id = ga.grupo_id 
                INNER JOIN esc_curso c ON g.id = c.grupo_id
                INNER JOIN asi_asignatura a ON c.asignatura_id = a.id
                INNER JOIN alu_alumno ON ga.alumno_id = alu_alumno.id
                LEFT JOIN esc_calificacion ec ON c.id = ec.curso_id AND ga.alumno_id = ec.alumno_id
                WHERE 
                    g.nombre != 'ActasExtemporaneas'
                    AND g.id = ?
                GROUP BY
                    alu_alumno.noexpediente,
                    alu_alumno.nombre,
                    alu_alumno.apellidos,
                    c.nombre,
                    a.clave
                ORDER BY
                    alu_alumno.noexpediente,
                    c.nombre";

        $result = DB::select($sql, [$this->grupo_id]);

        // Crear nuevo objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Definir estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFCCCCCC',
                ],
            ],
        ];

        // Encabezados personalizados para materias y calificaciones
        $headers = [
            'No Expediente', 'Nombre Alumno', 'Apellidos Alumno'
        ];

        // Encabezados dinámicos para cada curso
        $cursoHeaders = [];
        $uniqueCourses = [];
        foreach ($result as $row) {
            if (!in_array($row->nombre_curso, $uniqueCourses)) {
                $uniqueCourses[] = $row->nombre_curso;
                $cursoHeaders[] = $row->nombre_curso . ' P1';
                $cursoHeaders[] = $row->nombre_curso . ' P2';
                $cursoHeaders[] = $row->nombre_curso . ' P3';
                $cursoHeaders[] = $row->nombre_curso . ' Final';
                $cursoHeaders[] = $row->nombre_curso . ' R';
            }
        }

        $headers = array_merge($headers, $cursoHeaders);

        // Escribir encabezados en la primera fila
        foreach ($headers as $index => $header) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($index + 1) . '1', $header);
        }

        // Aplicar estilos a los encabezados
        $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1')->applyFromArray($styleArray);

        // Escribir datos en el archivo Excel
        $rowIndex = 2;
        $alumnos = [];

        foreach ($result as $row) {
            if (!isset($alumnos[$row->noexpediente])) {
                $alumnos[$row->noexpediente] = [
                    'noexpediente' => $row->noexpediente,
                    'NOMBRE_ALUMNO' => $row->NOMBRE_ALUMNO,
                    'APELLIDOS_ALUMNO' => $row->APELLIDOS_ALUMNO,
                    'cursos' => []
                ];
            }
            $alumnos[$row->noexpediente]['cursos'][$row->nombre_curso] = [
                'P1' => $row->parcial1,
                'P2' => $row->parcial2,
                'P3' => $row->parcial3,
                'Final' => $row->calificacion_final,
                'R' => $row->calificacion_r,
            ];
        }

        foreach ($alumnos as $alumno) {
            $sheet->setCellValue('A' . $rowIndex, $alumno['noexpediente']);
            $sheet->setCellValue('B' . $rowIndex, $alumno['NOMBRE_ALUMNO']);
            $sheet->setCellValue('C' . $rowIndex, $alumno['APELLIDOS_ALUMNO']);

            $currentColumnIndex = 4;
            foreach ($uniqueCourses as $curso) {
                $calificaciones = $alumno['cursos'][$curso] ?? ['P1' => '', 'P2' => '', 'P3' => '', 'Final' => '', 'R' => ''];
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColumnIndex) . $rowIndex, $calificaciones['P1']);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColumnIndex + 1) . $rowIndex, $calificaciones['P2']);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColumnIndex + 2) . $rowIndex, $calificaciones['P3']);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColumnIndex + 3) . $rowIndex, $calificaciones['Final']);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColumnIndex + 4) . $rowIndex, $calificaciones['R']);
                $currentColumnIndex += 5;
            }

            $rowIndex++;
        }

        // Ajustar automáticamente el ancho de las columnas
        for ($col = 1; $col <= count($headers); $col++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        // Crear un objeto Writer para guardar la hoja de cálculo
        $writer = new Xlsx($spreadsheet);
        $grupo = GruposModel::find($this->grupo_id);
        $turno = $grupo->turno_id;
        if($turno == 1){
            $turno = "Matutino";
        }
        else{
            $turno = "Vespertino";
        }
        // Crear un archivo temporal en el servidor
        $filePath = tempnam(sys_get_temp_dir(), 'concentrado_grupo'.$grupo->nombre.'_'.$turno) . '.xlsx';
        $writer->save($filePath);

        // Registrar en bitácora
        BitacoraModel::create([
            'user_id' => auth()->user()->id,
            'ip' => request()->ip(),
            'path' => request()->path(),
            'method' => request()->method(),
            'component' => 'CalificacionesGruposExport',
            'function' => 'export',
            'description' => 'Generó reporte de calificaciones del grupo: ' . $grupo->id . ' - ' . $grupo->nombre,
        ]);

        // Devolver el archivo como respuesta de descarga
        return Response::download($filePath, 'resultados.xlsx')->deleteFileAfterSend(true);
    
    }
}
