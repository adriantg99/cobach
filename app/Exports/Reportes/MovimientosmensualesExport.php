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
class MovimientosMensualesExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $plantel;
    protected $plantel_nombre;
    protected $cicloesc;
    public function __construct( $cicloesc,$plantel)
    {
        //dd('1 uno');
        $this->plantel = $plantel;
        $this->cicloesc=$cicloesc;

    }

    public function collection()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $fechaHoy = Carbon::now();
       // $fechaHoy='2024-05-01';
        //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();

      $datos = DB::select('call pa_mvtosmensuales_det (?,?)  ',array($this->cicloesc,$this->plantel));

        //dd($query);
        $data = [];
        foreach ($datos as $dat) {
            $data[] = [
                'PERIODO' => $dat->periodosel,
                'TURNO' => $dat->turno,
                'NO EXPEDIENTE' => $dat->noexpediente,
                'ALUMNO' => $dat->alumno,
                'SEXO' => $dat->sexo,
                'INICIO1' => $dat->ini1,
                'ALTA1' => $dat->alta1,
                'BAJA1' => $dat->baja1,
                'EXIS1' => $dat->ini1+$dat->alta1-$dat->baja1,
                'INICIO2' =>$dat->ini1+$dat->alta1-$dat->baja1,
                'ALTA2' => $dat->alta2,
                'BAJA2' => $dat->baja2,
                'EXIS2' => $dat->ini1+$dat->alta1-$dat->baja1+$dat->alta2-$dat->baja2,
                'INICIO3' => $dat->ini1+$dat->alta1-$dat->baja1+$dat->alta2-$dat->baja2,
                'ALTA3' => $dat->alta3,
                'BAJA3' => $dat->baja3,
                'EXIS3' => $dat->ini1+$dat->alta1-$dat->baja1+$dat->alta2-$dat->baja2+$dat->alta3-$dat->baja3,
                'INICIO4' =>  $dat->ini1+$dat->alta1-$dat->baja1+$dat->alta2-$dat->baja2+$dat->alta3-$dat->baja3,
                'ALTA4' => $dat->alta4,
                'BAJA4' => $dat->baja4,
                'EXIS4' =>  $dat->ini1+$dat->alta1-$dat->baja1+$dat->alta2-$dat->baja2+$dat->alta3-$dat->baja3+$dat->alta4-$dat->baja4,
                'INSC' => $dat->ini1+$dat->alta1+$dat->alta2+$dat->alta3+$dat->alta4,
                'EXIS' =>  $dat->ini1+$dat->alta1-$dat->baja1+$dat->alta2-$dat->baja2+$dat->alta3-$dat->baja3+$dat->alta4-$dat->baja4,
                'TOTALTAS' => $dat->alta1+$dat->alta2+$dat->alta3+$dat->alta4,
                'TOTBAJAS' => $dat->baja1+$dat->baja2+$dat->baja3+$dat->baja4,
                'APROBADO'=> $dat->aprobado,
                'REPROBADO' => $dat->reprobado

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
        return 'MOVIMIENTOS MENSUALES'; // Configura el título de la hoja Excel
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
        $sheet->mergeCells('A1:AA1');
        $sheet->mergeCells('A2:AA2');
        $sheet->mergeCells('A3:D3');

        $sheet->mergeCells('E3:G3');

        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);
        $sheet->freezePane('A5');

        $ciclo_activo = CicloEscModel::where('activo', '1')->first();

        $plantel = PlantelesModel::where('id',$this->plantel)->first();
         $sheet->getStyle('A4:AA4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);



        $sheet->setCellValue('A1', 'COLEGIO DE BACHILLERES DEL ESTADO DE SONORA');
        $sheet->setCellValue('A2', 'MOVIMIENTOS MENSUALES');
        $sheet->setCellValue('A3', 'CICLO ESCOLAR: ' . $ciclo_activo->nombre);

        $sheet->setCellValue('E3', 'PLANTEL: ' . $plantel->nombre);

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
            'PERIODO',
            'TURNO',
            'NO EXPEDIENTE',
            'ALUMNO',
            'SEXO',
            'INICIO',
            '+ALTA',
            '-BAJA',
            'EXIS',
            'INICIO',
            '+ALTA',
            '-BAJA',
            'EXIS',
            'INICIO',
            '+ALTA',
            '-BAJA',
            'EXIS',
            'INICIO',
            '+ALTA',
            '-BAJA',
            'EXIS',
            'INS.',
            'EXIS',
            'TOTAL ALTAS',
            'TOTAL BAJAS',
            'APROB','REPROB'
        ];
    }
}
