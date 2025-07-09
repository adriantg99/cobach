<?php
namespace App\Http\Livewire\Estadisticas\Explorar;

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

    public $dashb;
    public $hoy;


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

        if($todos_los_valores == 1){
            $this->plantel = PlantelesModel::orderBy('id')->get();
        }
        elseif($todos_los_valores ==2){
            $this->plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->orderBy('nombre')->get();
        } else {
            $this->plantel = collect();
        }
    }
    public function render()
    {
        if ($this->plantel_seleccionado != "|") {
            $this->periodos = GruposModel::select('periodo')
            ->where('plantel_id', $this->plantel_seleccionado)
            ->where('ciclo_esc_id', $this->ciclo_seleccionado)
            ->whereIn('periodo', ['1', '2', '3', '4', '5', '6'])
            ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
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
                ->distinct('periodo')
                ->orderBy('periodo')
                ->get();

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
        return view('livewire.estadisticas.explorar.form-component');
    }




}
