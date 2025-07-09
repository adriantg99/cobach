<?php

namespace App\Exports\Docentes;

use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CalificacionCursoExport implements FromView, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $curso_id;
    public $politica_variable_id;

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la primera fila (encabezados)
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function __construct($curso_id, $politica_variable_id)
    {
        $this->curso_id = $curso_id;
        $this->politica_variable_id = $politica_variable_id;
    }

    public function view(): View
    {
        $sql = "CALL calificaciones_cursos($this->curso_id);";

        $calificaciones = DB::select('call calificaciones_cursos(?)', array($this->curso_id));
        $curso = CursosModel::find($this->curso_id);

        $obtener_parcial = CalificacionesModel::where('curso_id', $this->curso_id)
            ->where('politica_variable_id', $this->politica_variable_id)
            ->select('calificacion_tipo')
            ->first();

        return view('exports.docentes.calificacioncurso', compact('calificaciones', 'curso', 'obtener_parcial'));
    }
}
