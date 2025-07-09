<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class AlumnosExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $alumnos;


    public function __construct(Collection $alumnos, $grupo, $turnoNombre, $nombre_plantel)
    {
        $this->alumnos = $alumnos;
        $this->grupo = $grupo;
        $this->turnoNombre = $turnoNombre;
        $this->nombre_plantel = $nombre_plantel;
    }

    public function collection()
    {
        return $this->alumnos->map(function ($alumno) {
            return [
                'No expediente' => $alumno['noexpediente'],
                'Apellidos' => $alumno['apellidos'],
                'Nombre' => $alumno['nombre'],

            ];
        });
    }

    public function headings(): array
    {
        return [
            'No expediente',
            'Apellidos',
            'Nombre',

        ];
    }

    public function startCell(): string
    {
        return 'A5'; // Configura el inicio de la tabla (fila y columna)
    }

    public function title(): string
    {
        return 'Alumnos'; // Configura el título de la hoja Excel
    }
    public function columnFormats(): array
    {
        return [
            'A' => '0', // Cambia el ancho de la columna A (No expediente)
            'B' => '0', // Cambia el ancho de la columna B (Nombre)
            'C' => '0', // Cambia el ancho de la columna C (Apellidos)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:J1'); // Unifica las celdas desde A1 hasta J1
        $sheet->mergeCells('B3:D3'); // Unifica las celdas desde A1 hasta J1
        $sheet->getStyle('B3')->getAlignment()->setHorizontal('center'); // Centra el texto
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center'); // Centra el texto
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);

        $sheet->getStyle('A5:C5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);


        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        $sheet->setCellValue('A1', 'Colegio de Bachilleres del Estado de Sonora'); // Agrega el texto a la celda A1
        $sheet->setCellValue('D2', 'Plantel'); // Agrega el texto a la celda A1
        $sheet->setCellValue('E2', $this->nombre_plantel); // Agrega el texto a la celda A1
        $sheet->setCellValue('B3', 'Relación de alumnos'); // Agrega el texto a la celda A1
        $sheet->setCellValue('B4', 'Grupo'); // Agrega el texto a la celda A1
        $sheet->setCellValue('C4', $this->grupo); // Agrega el texto a la celda A1
        $sheet->setCellValue('D4', 'Turno'); // Agrega el texto a la celda A1
        $sheet->setCellValue('E4', $this->turnoNombre); // Agrega el texto a la celda A1

        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach (range('B', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
