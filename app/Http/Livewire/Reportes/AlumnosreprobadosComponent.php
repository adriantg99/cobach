<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\CicloEscModel;
use App\Exports\Reportes\AlumnosreprobadosExport;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Administracion\PerfilModel;
use App\Models\Cursos\CursosModel;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
class AlumnosreprobadosComponent extends Component
{
    public $plantel, $plantel_seleccionado, $periodo_seleccionado = 'P1';
    public $grupos;
    public $grusel = [];
    public $docentes, $docente_seleccionado = '';
    public $cursos, $curso_seleccionado = '';
    public $reporte_seleccionado = 'A';
    public function render()
    {
        $fechaHoy = Carbon::now();
        // $fechaHoy='2024-05-01';
        //$ciclos=CicloEscModel::where ('per_inicio','<=',$fechaHoy)->where ('per_final','>=',$fechaHoy)->orderBy('per_inicio', 'asc')->first();
        $ciclos = CicloEscModel::where('activo', '=', 1)->first();

        $this->grupos = GruposModel::where('ciclo_esc_id', $ciclos->id)
            ->where('plantel_id', $this->plantel_seleccionado)
            ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
        WHEN 1 THEN ' M'
        WHEN 2 THEN ' V'
        END) AS nombre")
            ->orderBy("nombre")->orderBy("turno_id")
            ->get();

        if (isset($this->grupos))
            foreach ($this->grupos as $grupo) {
                $this->grusel[] = true;
            }
        if ($this->plantel_seleccionado != "") {
            $this->cursos = CursosModel::join('esc_grupo', 'esc_curso.grupo_id', "=", "esc_grupo.id")
                ->where('ciclo_esc_id', $ciclos->id)
                ->where('plantel_id', $this->plantel_seleccionado)
                ->select('esc_curso.nombre')
                ->distinct()
                ->orderBy("esc_curso.nombre")
                ->get();

            $this->docentes = PerfilModel::join('esc_curso', 'esc_curso.docente_id', "=", "emp_perfil.id")
                ->join('esc_grupo', 'esc_curso.grupo_id', "=", "esc_grupo.id")
                ->where('ciclo_esc_id', $ciclos->id)
                ->where('plantel_id', $this->plantel_seleccionado)
                ->select('emp_perfil.id', 'emp_perfil.apellido1', 'emp_perfil.apellido2', 'emp_perfil.nombre')
                ->distinct()
                ->orderBy('apellido1', 'asc')->orderBy('apellido2', 'asc')->orderBy('nombre', 'asc')->get();

        }
        return view('livewire.reportes.alumnosreprobados-component');
    }
    public function changeEvent_seleccionado($index)
    {
        $this->grusel[$index] = !$this->grusel[$index];

    }
    public function changeEvent_docente()
    {
        if ($this->docente_seleccionado != '')
            ;
        $this->curso_seleccionado = '';
    }
    public function changeEvent_curso()
    {
        if ($this->curso_seleccionado != '')
            ;
        $this->docente_seleccionado = '';
    }
    public function mount()
    {
        $this->plantel = obtenerPlanteles();
    }


    public function get_grupos()
    {
        $contar = 0;
        $strgr = '';
        foreach ($this->grupos as $grupo) {
            if ($this->grusel[$contar]) {
                if ($strgr != '')
                    $strgr = $strgr . ',';
                $strgr = $strgr . $grupo->id;
            }
            $contar++;

        }
        return $strgr;
    }
    public function generar_excel()
    {

        //dd(Auth::user()->roles->pluck('name'));
        $plantel_sel = PlantelesModel::find($this->plantel_seleccionado);

        $strgr = self::get_grupos();
        return Excel::download(new AlumnosreprobadosExport($this->plantel_seleccionado, $this->periodo_seleccionado, $strgr, $this->docente_seleccionado, $this->curso_seleccionado), $plantel_sel->nombre . 'materias_reprobadas.xlsx');


    }

    public function selall()
    {
        $contar = 0;
        $strgr = '';
        foreach ($this->grupos as $grupo) {
            $this->grusel[$contar] = true;

            $contar++;

        }

    }
    public function deselall()
    {
        $contar = 0;
        $strgr = '';
        foreach ($this->grupos as $grupo) {
            $this->grusel[$contar] = !$this->grusel[$contar];

            $contar++;

        }
    }

}
