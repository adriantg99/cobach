<?php
namespace App\Exports\Reportes;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;

use App\Models\Grupos\GruposModel;
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
use Carbon\Carbon;
class AlumnosreprobadosExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $plantel;
    protected $periodo;
    protected $grupo;
    protected $docente;
    protected $curso;


    public function __construct($plantel, $periodo, $grupo,$docente,$curso)
    {
        //dd('1 uno');
        $this->plantel = $plantel;
        $this->periodo = $periodo;
        $this->grupo = $grupo;
        $this->docente=$docente;
        $this->curso=$curso;
    }

    public function collection()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $fechaHoy = Carbon::now();
       // $fechaHoy='2024-05-01';
        //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();
        $ciclos=CicloEscModel::where ('activo',1)->first();

        $docente='';
        $curso='';
        if($this->docente!='')
            $docente=" and esc_curso.docente_id=".$this->docente;
        if($this->curso!='')
            $curso=" and esc_curso.nombre='".$this->curso."'";

        $query="SELECT  alu_alumno.noexpediente,CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) AS alumno,
                asi_asignatura.clave,
                esc_curso.nombre AS materia, esc_grupo.nombre as grupo,
                esc_calificacion.faltas,emp_perfil.expediente,
                concat(emp_perfil.apellido1,' ',emp_perfil.apellido2,' ',emp_perfil.nombre) as docente
                ,CASE WHEN calif = '' OR calif IS null then esc_calificacion.calificacion else esc_calificacion.calif end as califica
                ,esc_grupo.nombre as grupo,cat_turno.nombre AS turno
            FROM esc_calificacion
            JOIN esc_curso ON esc_curso.id = esc_calificacion.curso_id
            JOIN asi_asignatura ON asi_asignatura.id = esc_curso.asignatura_id
            JOIN esc_grupo ON esc_grupo.id = esc_curso.grupo_id
            join alu_alumno on alu_alumno.id=esc_calificacion.alumno_id
            left join emp_perfil on emp_perfil.id=esc_curso.docente_id
            JOIN cat_turno ON cat_turno.id=esc_grupo.TURNO_ID
            WHERE esc_grupo.ciclo_esc_id = " .  $ciclos->id . " and esc_calificacion.calificacion_tipo ='".$this->periodo."' ".$docente .$curso." and
            esc_curso.grupo_id in(".$this->grupo.")   AND  CASE WHEN calif = '' OR calif IS null THEN CASE WHEN calificacion < 60 THEN 1 ELSE 0 END WHEN calif <> 'AC'THEN 1 ELSE 0 END=1 ORDER BY esc_grupo.nombre,cat_turno.nombre, CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) ,asi_asignatura.clave";

        $datos = DB::select($query);
        //dd($query);
        $data = [];
        foreach ($datos as $dat) {
            $data[] = [
                'GRUPO' => $dat->grupo,
                'TURNO' => $dat->turno,
                'NO EXPEDIENTE' => $dat->noexpediente,
                'ALUMNO' => $dat->alumno,
                'CLAVE'=> $dat->clave,
                'MATERIA REPROBADA' => $dat->materia,
                'NUMERO'=> $dat->expediente,
                'DOCENTE' => $dat->docente,
                'CALIFICACION' => $dat->califica,
                 'INASISTENCIA' => $dat->faltas

            ];
        }
//dd($data);
        //dd('5 cinco');
        return collect($data);
    }

    public function startCell(): string
    {
        //dd('3 tres');
        return 'A4'; // Configura el inicio de la tabla (fila y columna)
    }

    public function title(): string
    {
        //dd('2 dos');
        return 'Materias reprobadas'; // Configura el título de la hoja Excel
    }

    public function columnFormats(): array
    {
        //dd('6 seis');
        return [
            'A' => '0',
            'B' => '0',
            'C' => '0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        //dd('7 siete');
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:E3');

        $sheet->mergeCells('F3:J3');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);
        $sheet->freezePane('A5');

        $ciclo_activo = CicloEscModel::where('activo', '1')->first();

        $plantel = PlantelesModel::find($this->plantel);

        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        if ($this->periodo=='P1')
            $tit='EL PARCIAL 1';
        else
        if ($this->periodo=='P2')
            $tit='EL PARCIAL 2';
        else
        if ($this->periodo=='P3')
            $tit='EL PARCIAL 3';
        else
            $tit='REGULARIZACION';
        $sheet->setCellValue('A1', 'COLEGIO DE BACHILLERES DEL ESTADO DE SONORA');
        $sheet->setCellValue('A2', 'MATERIAS REPROBADAS POR ALUMNO EN '.$tit);
        $sheet->setCellValue('A3', 'CICLO ESCOLAR: ' . $ciclo_activo->nombre);

        $sheet->setCellValue('F3', 'PLANTEL: ' . $plantel->nombre);

        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach (range('B', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

    }

    public function headings(): array
    {
        //dd('4 cuatro');
        return [
            'GRUPO',
            'TURNO',
            'NO EXPEDIENTE',
            'ALUMNO',
            'CLAVE',
            'MATERIA REPROBADA',
            'NUMERO',
            'DOCENTE',
            'CALIFICACION',
            'INASISTENCIA'
        ];
    }
}
