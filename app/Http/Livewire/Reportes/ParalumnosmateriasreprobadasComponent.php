<?php

namespace App\Http\Livewire\Reportes;
use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use Excel;
use Livewire\Component;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class ParalumnosmateriasreprobadasComponent extends Component
{
    public $plantel;
    public $grupos;
    public $docente;
    public $curso;
    public function render()
    {
        ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes

        $plantel_sel = PlantelesModel::find($this->plantel);
        $cicloesc = CicloEscModel::where('activo', '=', 1)->first();
        $docente = '0';
        $curso = '|';
        if ($this->docente != '|') {
            $docente = $this->docente;
        }

        if ($this->curso != '') {
            $curso = $this->curso;
        }
        /*
        $prueba = DB::select('call pa_cursosreprobados(248, 10, 24240, "|", 0)');
        dd($prueba);*/
        $dat = DB::select('call pa_cursosreprobados (?,?,?,?,?)  ', array($cicloesc->id, $plantel_sel->id, $this->grupos, "" . $curso . "", $docente));
        //dd($dat, $cicloesc->id, $plantel_sel->id, "'" . $this->grupos . "'", "'" . $curso . "'", $docente);
        //dd($dat, "-----", $cicloesc->id, $plantel_sel->id, "'" . $this->grupos . "'", "'" . $curso . "'", $docente);
        $datos = collect($dat)->sortBy([['grupo', 'asc'], ['turno', 'asc'], ['alumno', 'asc']]);
        //dd($datos);
        /*dd($cicloesc->id.'-'.$plantel_sel->id.'-'."'".$this->grupos."'".'-'."'".$curso."'".'-'.$docente);*/

        $titulo = 'ALUMNOS EN RIESGO DE REPROBACIÓN';

        $cicloesc = $cicloesc->nombre;
        $plantelnom = $plantel_sel->nombre;
        return view('livewire.reportes.paralumnosmateriasreprobadas-component', compact('datos', 'cicloesc', 'plantelnom', 'titulo'));

    }
}
