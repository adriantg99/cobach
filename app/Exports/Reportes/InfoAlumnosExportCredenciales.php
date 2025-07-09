<?php

namespace App\Exports\Reportes;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Grupos\GruposModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

class InfoAlumnosExportCredenciales implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $plantel_seleccionado;
    protected $periodo_seleccionado;
    protected $grupo_seleccionar;
    protected $ciclo_activo;
    protected $plantel;


    public function __construct($plantel_seleccionado, $periodo_seleccionado, $grupo_seleccionar, $ciclo_activo, $plantel)
    {
        $this->plantel_seleccionado = $plantel_seleccionado;
        $this->periodo_seleccionado = $periodo_seleccionado;
        $this->grupo_seleccionar = $grupo_seleccionar;
        $this->ciclo_activo = $ciclo_activo;
        $this->plantel = $plantel;
    }

    public function collection()
    {
        $alumnos = collect();
        //dd($this->plantel_seleccionado, $this->periodo_seleccionado, $this->grupo_seleccionar);
        if ($this->plantel_seleccionado != "") {
            if ($this->periodo_seleccionado != "" && $this->grupo_seleccionar != 0) {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.id', $this->grupo_seleccionar)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->selectRaw("alu_alumno.noexpediente, alu_alumno.nombre, alu_alumno.apellidos, 
                    MIN(CONCAT(
            REGEXP_SUBSTR(esc_grupo.nombre, '[0-9]+'), -- Solo extrae los números del nombre del grupo
            CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
            END
        )) AS grupo")
                    ->groupBy('alu_alumno.id', 'alu_alumno.noexpediente', 'alu_alumno.nombre', 'alu_alumno.apellidos') // Agrupar por ID del alumno y grupo
                    ->orderBy('apellidos', 'asc')
                    ->get();

                $nombre = GruposModel::where('id', $this->grupo_seleccionar)
                    ->selectRaw("CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                     WHEN 1 THEN ' M'
                     WHEN 2 THEN ' V'
                     END) AS grupo")
                    ->first();
                //$archivo = $this->plantel->nombre . '_' . $nombre->grupo;
            } elseif ($this->periodo_seleccionado != 0 && $this->grupo_seleccionar == 0) {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.periodo', $this->periodo_seleccionado)
                    ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->where('ciclo_esc_id', $this->ciclo_activo->id)
                    ->selectRaw("alu_alumno.noexpediente, 
                    MIN(alu_alumno.nombre) as nombre, 
                    MIN(alu_alumno.apellidos) as apellidos, 
                     MIN(CONCAT(
            REGEXP_SUBSTR(esc_grupo.nombre, '[0-9]+'), -- Solo extrae los números del nombre del grupo
            CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
            END
        )) AS grupo")
                    ->groupBy('alu_alumno.noexpediente') // Agrupar por noexpediente para evitar duplicados
                    ->orderBy('grupo', 'asc')
                    ->orderBy('apellidos', 'asc')
                    ->get();


                //$archivo = $this->plantel->nombre . '_' . $this->periodo_seleccionado . '_Semestre';
            } elseif ($this->plantel_seleccionado != 0 && $this->grupo_seleccionar == 0 && $this->periodo_seleccionado == 0) {
                /*   $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                   ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                   ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
                   ->where('ciclo_esc_id', $this->ciclo_activo->id)
                   ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                   ->selectRaw("alu_alumno.noexpediente, 
                       MIN(alu_alumno.nombre) as nombre, 
                       MIN(alu_alumno.apellidos) as apellidos, 
                       MIN(esc_grupo.periodo) as periodo,
                       MIN(CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                           WHEN 1 THEN ' M'
                           WHEN 2 THEN ' V'
                       END)) AS grupo")
                   ->groupBy('alu_alumno.noexpediente') // Agrupar por noexpediente para evitar duplicados
                   ->orderBy('grupo', 'asc')
                   ->orderBy('apellidos', 'asc')
                   ->get();
   */
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
                    ->where('ciclo_esc_id', $this->ciclo_activo->id)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->selectRaw("
        alu_alumno.noexpediente, 
        MIN(alu_alumno.nombre) as nombre, 
        MIN(alu_alumno.apellidos) as apellidos, 
        MIN(esc_grupo.periodo) as periodo,
        MIN(CONCAT(
            REGEXP_SUBSTR(esc_grupo.nombre, '[0-9]+'), -- Solo extrae los números del nombre del grupo
            CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
            END
        )) AS grupo")
                    ->groupBy('alu_alumno.noexpediente') // Agrupar por noexpediente para evitar duplicados
                    ->orderBy('grupo', 'asc')
                    ->orderBy('apellidos', 'asc')
                    ->get();
                //                    dd($alumnos->toSql(), $alumnos->getBindings());

                //$archivo = $this->plantel->nombre;
            }
        }

        return $alumnos;
    }

    public function startCell(): string
    {
        return 'A3'; // Configura el inicio de la tabla (fila y columna)
    }

    public function title(): string
    {
        return 'Catalogo de alumnos'; // Configura el título de la hoja Excel
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
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach (range('B', $sheet->getHighestColumn()) as $column) {
            $sheet->getStyle($column)->getAlignment()->setHorizontal('left');
        }

        foreach (range('C', $sheet->getHighestColumn()) as $column) {
            $sheet->getStyle($column)->getAlignment()->setHorizontal('left');
        }

        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getStyle($column)->getAlignment()->setHorizontal('left');
        }

        foreach (range('B', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);

        $sheet->getStyle('A3:G3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);


        $sheet->setCellValue('A1', 'Colegio de Bachilleres del Estado de Sonora');

        $sheet->setCellValue('A2', 'PLANTEL: ' . $this->plantel->nombre);

    }

    public function headings(): array
    {
        if ($this->plantel_seleccionado != 0 && $this->grupo_seleccionar == 0 && $this->periodo_seleccionado == 0) {
            return [
                'No Expediente',
                'Nombre',
                'Apellidos',
                'Semestre',
                'Grupo'
            ];
        } else {
            return [
                'No Expediente',
                'Nombre',
                'Apellidos',
                'Grupo'
            ];
        }

    }
}