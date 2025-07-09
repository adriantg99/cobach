<?php
namespace App\Http\Livewire\Estadisticas\Tablero;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use App\Models\Cursos\CursosModel;
use App\Models\Administracion\PerfilModel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Livewire\Component;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;


class FormComponent extends Component
{
    public $plantel, $plantel_seleccionado = '|',
    $periodo_seleccionado = 0, $periodos,
    $grupos, $grupo_seleccionar = 0, $ciclo, $ciclo_seleccionado,
    $cursos, $curso_seleccionado = '0', $docentes, $docente_seleccionado = 0,
    $turno_seleccionado = 0;

    public $rows = array("plantel", "grado", "turno", "grupo", "curso", "docente", "alumno", "noexpediente", "calificacion_tipo");

    //listado de variables
    public $variablesSel = ['plantel'];
    //habilita objeto select
    public $habvar = [false];
    //muestra variable seleccionada id
    public $varsel = ['plantel'];
    public $dashb;
    public $hoy;
    public $add = false;

    public $cols = 'plantel';
    protected $listeners = ['generar_dashboard'];
    public $es_docente = false;

    public $chk1 = false;
    public $chk2 = false;
    public $chk3 = false;
    public $chkr = false;
    public $chkf = false;

    public $carga = false;
    public $rend = true;

    public function mount()
    {
        $this->periodos = collect();
        $this->grupos = collect();
        $this->cursos = collect();
        $this->docentes = collect();
        $this->ciclo = CicloEscModel::orderBy('per_inicio', 'desc')->get();
        $this->ciclo_seleccionado = $this->ciclo[0]->id;
        $roles = Auth()->user()->getRoleNames()->toArray();
        foreach ($roles as $role) {
            if ($role === "control_escolar") {
                $todos_los_valores = 1;
                break;
            } elseif (strpos($role, "control_escolar_") === 0) {
                $validaciones[] = substr($role, 16);
                $todos_los_valores = 2;
                continue;
            } else {
                continue;
            }
        }

        if ($todos_los_valores == 1) {
            $this->plantel = PlantelesModel::orderBy('id')->get();
        } elseif ($todos_los_valores = 2) {
            $this->plantel = PlantelesModel::whereIn('abreviatura', $validaciones)
                ->orderBy('nombre')->get();
        } else {
            $this->plantel = collect();

        }

    }
    public function render()
    {
        if ($this->rend == false) {
            $this->rend = true;
            $this->dashb = session('dashb');
            return view('livewire.estadisticas.tablero.form-component');
        }
        if ($this->plantel_seleccionado != "|") {
            $this->periodos = GruposModel::select('periodo')
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_seleccionado)
                ->whereIn('periodo', ['1', '2', '3', '4', '5', '6'])
                ->distinct('periodo')
                ->orderBy('periodo')
                ->get();



            // $this->docentes = PerfilModel::select('emp_perfil.id', 'emp_perfil.apellido1', 'emp_perfil.apellido2', 'emp_perfil.nombre')
            // ->join('emp_perfil_plantele', 'emp_perfil_plantele.perfil_id', '=', 'emp_perfil.id')
            // ->distinct()
            // ->where('plantel_id', $this->plantel_seleccionado)->orderBy('apellido1', 'asc')->orderBy('apellido2', 'asc')->orderBy('nombre', 'asc')->get();
        } else
            $this->periodos = GruposModel::select('periodo')
                ->where('ciclo_esc_id', $this->ciclo_seleccionado)
                ->whereIn('periodo', ['1', '2', '3', '4', '5', '6'])
                ->distinct('periodo')
                ->orderBy('periodo')
                ->get();


        if (Auth()->user()->hasRole('docente')) {
            $this->docentes = PerfilModel::select('emp_perfil.id', 'emp_perfil.apellido1', 'emp_perfil.apellido2', 'emp_perfil.nombre')
                ->where('user_id', Auth()->user()->id)->orderBy('apellido2', 'asc')->orderBy('nombre', 'asc')->get();
            $this->es_docente = true;
        } else
            $this->docentes = PerfilModel::select('emp_perfil.id', 'emp_perfil.apellido1', 'emp_perfil.apellido2', 'emp_perfil.nombre')
                ->orderBy('apellido1', 'asc')->orderBy('apellido2', 'asc')->orderBy('nombre', 'asc')->get();


        //GRUPOS
        if ($this->periodo_seleccionado != "0" && $this->turno_seleccionado != "0")
            $this->grupos = GruposModel::where('periodo', $this->periodo_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_seleccionado)
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('esc_grupo.turno_id', $this->turno_seleccionado)
                ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
                END) AS nombre")
                ->orderBy("nombre")
                ->get();
        else
            if ($this->turno_seleccionado != "0")

                $this->grupos = GruposModel::where('ciclo_esc_id', $this->ciclo_seleccionado)
                    ->where('plantel_id', $this->plantel_seleccionado)
                    ->where('esc_grupo.turno_id', $this->turno_seleccionado)
                    ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
            WHEN 1 THEN ' M'
            WHEN 2 THEN ' V'
            END) AS nombre")
                    ->orderBy("nombre")
                    ->get();
            else
                if ($this->periodo_seleccionado != "0")

                    $this->grupos = GruposModel::where('ciclo_esc_id', $this->ciclo_seleccionado)
                        ->where('plantel_id', $this->plantel_seleccionado)
                        ->where('periodo', $this->periodo_seleccionado)
                        ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
               WHEN 1 THEN ' M'
               WHEN 2 THEN ' V'
               END) AS nombre")
                        ->orderBy("nombre")
                        ->get();
                else
                    $this->grupos = GruposModel::where('ciclo_esc_id', $this->ciclo_seleccionado)
                        ->where('plantel_id', $this->plantel_seleccionado)
                        ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
            WHEN 1 THEN ' M'
            WHEN 2 THEN ' V'
            END) AS nombre")
                        ->orderBy("nombre")
                        ->get();

        //CURSOS
        if ($this->plantel_seleccionado != "|")
            if ($this->grupo_seleccionar != "")
                $this->cursos = CursosModel::join('esc_grupo', 'esc_curso.grupo_id', "=", "esc_grupo.id")
                    ->where('ciclo_esc_id', $this->ciclo_seleccionado)
                    ->where('plantel_id', $this->plantel_seleccionado)
                    ->where('grupo_id', $this->grupo_seleccionar)
                    ->select('esc_curso.nombre')
                    ->distinct()
                    ->orderBy("esc_curso.nombre")
                    ->get();
            else
                $this->cursos = CursosModel::join('esc_grupo', 'esc_curso.grupo_id', "=", "esc_grupo.id")
                    ->where('ciclo_esc_id', $this->ciclo_seleccionado)
                    ->where('plantel_id', $this->plantel_seleccionado)
                    ->select('esc_curso.nombre')
                    ->distinct()
                    ->orderBy("esc_curso.nombre")
                    ->get();
        else
            $this->cursos = CursosModel::join('esc_grupo', 'esc_curso.grupo_id', "=", "esc_grupo.id")
                ->where('ciclo_esc_id', $this->ciclo_seleccionado)
                ->select('esc_curso.nombre')
                ->distinct()
                ->orderBy("esc_curso.nombre")
                ->get();

        return view('livewire.estadisticas.tablero.form-component');
    }
    public function changeEvent($variable, $idx)
    {
        //dd($variable);
        $vars = collect($this->varsel);
        //dd($vars);
        if ($vars->contains($variable)) {
            $this->variablesSel[$idx] = '';

            session()->flash('message', 'Variable agregada anteriormente!');
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'info', 'message' => 'Variable agregada anteriormente!']
            );
        } else {
            $this->add = false;
            $this->habvar[$idx] = false;
            $this->varsel[$idx] = $variable;
            $this->cols = implode(',', $this->varsel);

        }

    }
    public function agregarVariable()
    {
        //valida que la variable no se encuentre seleccionada previamente
        if (count($this->variablesSel) < count($this->rows)) {
            $this->add = true;
            $this->variablesSel[] = '';
            $this->habvar[] = true;
            $this->varsel[] = '';
        }
    }
    public function eliminarVariable($index)
    {

        //borra en memoria
        unset($this->variablesSel[$index]);
        $this->variablesSel = array_values($this->variablesSel);

        unset($this->habvar[$index]);
        $this->habvar = array_values($this->habvar);

        unset($this->varsel[$index]);
        $this->varsel = array_values($this->varsel);
        $this->cols = implode(',', $this->varsel);

    }

    public function generar_dashboard()
    {
        $this->hoy = Carbon::now();
        $cols = implode(",", $this->varsel);

        // dd($this->curso_seleccionado);
        ini_set('max_execution_time', 600); // 5 minutes
        //dd(array($this->ciclo_seleccionado,$this->plantel_seleccionado,$this->grupo_seleccionar,$this->curso_seleccionado,$this->docente_seleccionado,$this->turno_seleccionado,$this->periodo_seleccionado,$cols));
        $this->dashb = DB::select('call pa_dashboard(?,?,?,?,?,?,?,?)', array($this->ciclo_seleccionado, $this->plantel_seleccionado, $this->grupo_seleccionar, $this->curso_seleccionado, $this->docente_seleccionado, $this->turno_seleccionado, $this->periodo_seleccionado, $cols));
        
        $this->dispatchBrowserEvent('finish_carga');

        session(['dashb' => $this->dashb]);
        $this->carga = true;

        $p1 = 0;
        $p2 = 0;
        $p3 = 0;
        $r = 0;
        $final = 0;
        foreach ($this->dashb as $item) {
            $p1 += $item->p1_count;
            $p2 += $item->p2_count;
            $p3 += $item->p3_count;
            $r += $item->r_count;
            $final += $item->final_count;
        }
        if ($p1 > 0)
            $this->chk1 = true;
        else
            $this->chk1 = false;
        if ($p2 > 0)
            $this->chk2 = true;
        else
            $this->chk2 = false;
        if ($p3 > 0)
            $this->chk3 = true;
        else
            $this->chk3 = false;
        if ($r > 0)
            $this->chkr = true;
        else
            $this->chkr = false;
        if ($final > 0)
            $this->chkf = true;
        else
            $this->chkf = false;


    }
    public function processMark()
    {
        $this->rend = false;
    }

}
