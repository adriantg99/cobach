<?php

namespace App\Exports\Estadisticas;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CalificacionesExport implements FromCollection
{
    protected $calificaciones;
    public function __construct($calificaciones)
    {
        $this->calificaciones = $calificaciones;
    }

    public function collection()
    {
        return $this->calificaciones;
    }

    public function headings(): array
    {
        return [
            'No. Expediente',
            'NOMBRE ALUMNO',
            'APELLIDOS ALUMNO',
            'NOMBRE CURSO',
            'P1',
            'FALTAS P1',
            'P2',
            'FALTAS P2',
            'P3',
            'FALTAS P3',
            'CALIFICACION FINAL',
            'GRUPO NOMBRE',
            'GRUPO DESCRIPCIÓN',
            'GRADO',
            'TURNO',
            'PLANTEL',
            'DOCENTE NOMBRE',
            'DOCENTE APELLIDO PATERNO',
            'DOCENTE APELLIDO MATERNO',
        ];
    }
}
