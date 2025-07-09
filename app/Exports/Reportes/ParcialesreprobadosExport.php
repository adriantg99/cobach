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
class ParcialesreprobadosExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $plantel;
    protected $grupos;
    protected $docente;
    protected $curso;
    public function __construct($plantel, $grupos,$docente,$curso)
    {
        //dd('1 uno');
        $this->plantel = $plantel;
        $this->grupos = $grupos;
        $this->docente=$docente;
        $this->curso=$curso;
    }

    public function collection()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $fechaHoy = Carbon::now();
       // $fechaHoy='2024-05-01';
        //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();
        $plantel_sel = PlantelesModel::find($this->plantel);
        $cicloesc=CicloEscModel::where ('activo',1)->first();

        $docente='0';
        $curso='|';
        if($this->docente!='')
            $docente=$this->docente;
         if($this->curso!='')
            $curso=$this->curso;
      $datos = DB::select('call pa_cursosreprobados (?,?,?,?,?)  ',array($cicloesc->id,$plantel_sel->id,"'".$this->grupos."'","'".$curso."'",$docente));

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
                'CALIFICACION1' => $dat->p1,
                 'INASISTENCIA1' => $dat->f1,
                 'CALIFICACION2' => $dat->p2,
                 'INASISTENCIA2' => $dat->f2,
                 'CALIFICACION3' => $dat->p3,
                 'INASISTENCIA3' => $dat->f3



            ];
        }
//dd($data);
        //dd('5 cinco');
        return collect($data);
    }

    public function startCell(): string
    {
        //dd('3 tres');
        return 'A5'; // Configura el inicio de la tabla (fila y columna)
    }

    public function title(): string
    {
        //dd('2 dos');
        return 'ALUMNOS EN RIESGO DE REPROBACIÓN'; // Configura el título de la hoja Excel
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
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:E3');

        $sheet->mergeCells('F3:L3');
        $sheet->mergeCells('G4:H4');
        $sheet->mergeCells('I4:J4');
        $sheet->mergeCells('K4:L4');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);
        $sheet->freezePane('A6');

        $ciclo_activo = CicloEscModel::where('activo', '1')->first();

        $plantel = PlantelesModel::find($this->plantel);

        $sheet->getStyle('A5:L5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);
        $sheet->getStyle('G4:L4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);


        $sheet->setCellValue('A1', 'COLEGIO DE BACHILLERES DEL ESTADO DE SONORA');
        $sheet->setCellValue('A2', 'ALUMNOS EN RIESGO DE REPROBACIÓN');
        $sheet->setCellValue('A3', 'CICLO ESCOLAR: ' . $ciclo_activo->nombre);

        $sheet->setCellValue('F3', 'PLANTEL: ' . $plantel->nombre);
        $sheet->setCellValue('G4', 'PARCIAL 1' );
        $sheet->setCellValue('I4', 'PARCIAL 2' );
        $sheet->setCellValue('K4', 'PARCIAL 3' );
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
            'CALIFICACION',
            'INASISTENCIA',
            'CALIFICACION',
            'INASISTENCIA',
            'CALIFICACION',
            'INASISTENCIA'
        ];
    }
}
