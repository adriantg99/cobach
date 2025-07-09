<?php
namespace App\Exports\Reportes;
use App\Models\Catalogos\CicloEscModel;

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
class IndicadordesercionExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $cicloesc;

    public function __construct($cicloesc )
    {
        //dd('1 uno');
        $this->cicloesc = $cicloesc;

    }

    public function collection()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $fechaHoy = Carbon::now();
       // $fechaHoy='2024-05-01';
        //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();

      $datos = DB::select('call pa_indicadesercion_det (?)  ',array($this->cicloesc));

        //dd($query);
        $data = [];
        foreach ($datos as $dat) {
            $data[] = [
                'PLANTEL' => $dat->plantel,
                'TURNO' => $dat->turno,
               'NOEXPEDIENTE' => $dat->noexpediente,
               'ALUMNO' => $dat->alumno,
                'SEXO' => $dat->sexo,
                 'MES'=> $dat->mes,
                'SEMESTRE' => $dat->semestre,
                'ALTA' => $dat->alta,
                 'BAJA' => $dat->baja
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
        return 'INDICADOR DE DESERCIÓN'; // Configura el título de la hoja Excel
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
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');

        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);
        $sheet->freezePane('A5');

        $ciclo_activo = CicloEscModel::where('activo', '1')->first();

        $sheet->getStyle('A4:I4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        $sheet->setCellValue('A1', 'COLEGIO DE BACHILLERES DEL ESTADO DE SONORA');
        $sheet->setCellValue('A2', 'INDICADOR DE DESERCIÓN');
        $sheet->setCellValue('A3', 'CICLO ESCOLAR: ' . $ciclo_activo->nombre);


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
            'PLANTEL',
            'TURNO',
            'NO EXPEDIENTE',
            'ALUMNO',
            'SEXO',
            'MES',
            'SEMESTRE',
            'ALTA',
            'BAJA'
        ];
    }
}
