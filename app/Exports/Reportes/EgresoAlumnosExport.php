<?php

namespace App\Exports\Reportes;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Grupos\GruposModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;


class EgresoAlumnosExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    protected $ciclo_buscado;
    protected $plantel_seleccionado;

    public function __construct($ciclo_buscado, $plantel_seleccionado)
    {
        $this->ciclo_buscado = $ciclo_buscado;
        $this->plantel_seleccionado = $plantel_seleccionado;
    }

    public function collection()
    {
        $alumnos = GruposModel::join('esc_grupo_alumno', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
            ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
            ->leftJoin('esc_certificado', 'alu_alumno.id', '=', 'esc_certificado.alumno_id')
            ->where('esc_grupo.ciclo_esc_id', $this->ciclo_buscado->id)
            ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
            ->where('esc_grupo.periodo', '6')
            ->select(
                'alu_alumno.id as alumno_id',
                'noexpediente',
                'alu_alumno.nombre',
                'alu_alumno.apellidopaterno',
                'alu_alumno.apellidomaterno',
                'alu_alumno.correo_institucional',
                'alu_alumno.email',
                'esc_certificado.digital',
                'esc_certificado.emailtime',
                'alu_alumno.deudas_finanzas_desc'
            )
            ->groupBy(
                'alumno_id',
                'noexpediente',
                'alu_alumno.nombre',
                'alu_alumno.apellidopaterno',
                'alu_alumno.apellidomaterno',
                'esc_certificado.digital',
                'esc_certificado.emailtime',
                'deudas_finanzas_desc'
            )
            ->orderBy('alu_alumno.apellidopaterno')
            ->get();

            $filteredAlumnos = $alumnos->unique('alumno_id');


        $data = $alumnos->map(function ($alumno) {
            $asignaturas = DB::table('view_alumno_certifica')
                ->where('alumno_id', $alumno->alumno_id)
                ->sum('asignaturas');
            $buscar_alumno = AlumnoModel::find($alumno->alumno_id);
            $determina_capacitacion = $buscar_alumno->capacitacion();

            if ($determina_capacitacion == "DES_MICR") {
                $estatus = ($asignaturas == 59) ? "Egresado" : "SIN EGRESAR";
            } else {
                $estatus = ($asignaturas == 60) ? "Egresado" : "SIN EGRESAR";
            }

            return [
                'No Expediente' => $alumno->noexpediente,
                'Nombre' => $alumno->nombre,
                'Apellido Paterno' => $alumno->apellidopaterno,
                'Apellido Materno' => $alumno->apellidomaterno,
                'Materias acreditadas' => $asignaturas,
                'Estatus' => $estatus,
                'Certificado Digital' => $alumno->digital,
                'Email Certificado' => $alumno->emailtime,
                'Correo Institucional' => $alumno->correo_institucional,
                'Correo Personal' => $alumno->email,
                'Deudas Finanzas' => $alumno->deudas_finanzas_desc,
            ];
        });

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'NO EXPEDIENTE',
            'NOMBRE',
            'APELLIDO PATERNO',
            'APELLIDO MATERNO',
            'MATERIAS ACREDITADAS',
            'ESTATUS',
            'FECHA DE LA GENERACIÓN DEL CERTIFICADO',
            'FECHA ENVIO DEL CERTIFICADO',
            'CORREO INSTITUCIONAL',
            'CORREO PERSONAL',
            'ADEUDO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true); // Encabezados en negrita
        $sheet->getStyle('A:Z')->getAlignment()->setWrapText(true); // Ajustar texto al contenido
        // Ajustar ancho automático para cada columna
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        return [];
    }
}
