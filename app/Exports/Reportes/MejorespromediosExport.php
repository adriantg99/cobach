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
class MejorespromediosExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $plantel;
    protected $grupos;
    protected $periodo;
    protected $titulo;
    public function __construct($plantel, $periodo)
    {
        //dd('1 uno');
        $this->plantel = $plantel;
        $this->periodo=$periodo;
        if ($periodo=='P1')
            $this->titulo='PARCIAL 1';
        else
        if ($periodo=='P2')
            $this->titulo='PARCIAL 2';
        else
        if ($periodo=='P3')
            $this->titulo='PARCIAL 3';

    }

    public function collection()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $fechaHoy = Carbon::now();
       // $fechaHoy='2024-05-01';
        //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();
        $plantel_sel = PlantelesModel::find($this->plantel);
        $cicloesc=CicloEscModel::where ('activo',1)->first();

        if ($this->periodo=='P1')
        $periodo=1;
        else
        if ($this->periodo=='P2')
        $periodo=2;
        else
        if ($this->periodo=='P3')
        $periodo=3;

      $datos = DB::select('call pa_mejorespromedios (?,?,?)  ',array($cicloesc->id,$plantel_sel->id,$periodo));

        //dd($query);
        $data = [];
        foreach ($datos as $dat) {
            $data[] = [
                'GRUPO' => $dat->grupo,
                'TURNO' => $dat->turno,
                'NO EXPEDIENTE' => $dat->noexpediente,
                'ALUMNO' => $dat->alumno,
                'PROMEDIO'=> $dat->promedio,
                'MATERIAS CURSADAS' => $dat->cursadas,
                'MATERIAS APROBADAS' => $dat->aprobadas

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
        return 'MEJORES PROMEDIOS '.$this->titulo; // Configura el título de la hoja Excel
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
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
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

        $plantel = PlantelesModel::find($this->plantel);

        $sheet->getStyle('A4:G4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);



        $sheet->setCellValue('A1', 'COLEGIO DE BACHILLERES DEL ESTADO DE SONORA');
        $sheet->setCellValue('A2', 'MEJORES PROMEDIOS');
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
            'GRUPO',
            'TURNO',
            'NO EXPEDIENTE',
            'ALUMNO',
            'PROMEDIO',
            'MATERIAS CURSADAS',
            'MATERIAS APROBADAS'
        ];
    }
}
