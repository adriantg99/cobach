<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Livewire\Component;
use Illuminate\Support\Facades\Response;


class CatalogoComponent extends Component
{
    public $plantel, $plantel_seleccionado,
    $periodo_seleccionado, $periodos,
    $grupos, $grupo_seleccionar, $ciclo_activo, $foto_credencial,
    $foto, $ciclo_seleccionado, $ciclos;

    public function mount()
    {
        $this->periodos = collect();
        $this->grupos = collect();
        $activo = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('activo', '1')
            ->first();

        $anterior = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('id', '!=', $activo?->id)
            ->where('per_inicio', '<', $activo?->per_inicio)
            ->where('tipo', '!=', 'V') // Excluir ciclos cuyo tipo sea 'V'
            ->orderBy('per_inicio', 'desc')
            ->first();

        $this->ciclos = collect([$activo, $anterior])->filter();
        //$this->ciclo_activo = CicloEscModel::where('activo', 1)->orderBy('per_inicio', 'desc')->first();
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
            $this->plantel = PlantelesModel::get();
        } elseif ($todos_los_valores = 2) {
            $this->plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->get();
        } else {
            $this->plantel = collect();
        }
    }
    public function render()
    {
        if($this->ciclo_seleccionado != ""){
            $this->ciclo_activo = CicloEscModel::find($this->ciclo_seleccionado);
        } else {
            $this->ciclo_activo = CicloEscModel::where('activo', '1')->orderBy('per_inicio', 'desc')->first();
        }
        if ($this->plantel_seleccionado != "") {
            $this->periodos = GruposModel::select('periodo')
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->distinct('periodo')
                ->orderBy('periodo')
                ->get();
        }

        if ($this->periodo_seleccionado != "") {
            $this->grupos = GruposModel::where('periodo', $this->periodo_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('plantel_id', $this->plantel_seleccionado)
                ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
            WHEN 1 THEN ' M'
            WHEN 2 THEN ' V'
            END) AS nombre")
                ->orderBy("nombre")
                ->get();
        }

        return view('livewire.reportes.catalogo-component');
    }

    public function generar_documento()
    {
        $ciclo_activo = CicloEscModel::orderBy('per_inicio', 'desc')->first();
        $hoy = Carbon::now();
        $plantel = PlantelesModel::find($this->plantel_seleccionado);
        if ($this->plantel_seleccionado != "") {
            if ($this->periodo_seleccionado != "" && $this->grupo_seleccionar != "") {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.id', $this->grupo_seleccionar)
                    ->selectRaw("alu_alumno.noexpediente, alu_alumno.id, alu_alumno.nombre, alu_alumno.apellidos, 
                CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
                END) AS grupo")
                    ->distinct('alu_alumno.id')
                    ->orderBy('apellidos', 'asc')
                    ->get();

                $nombre = GruposModel::where('id', $this->grupo_seleccionar)->selectRaw("CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
                END) AS grupo")->first();
                $archivo = $plantel->nombre . '_' . $nombre->grupo;
            } elseif ($this->periodo_seleccionado != "" && $this->grupo_seleccionar == "") {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.periodo', $this->periodo_seleccionado)
                    ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
                    ->where('ciclo_esc_id', $this->ciclo_activo->id)
                    ->selectRaw("alu_alumno.noexpediente, alu_alumno.id, alu_alumno.nombre, alu_alumno.apellidos, 
                CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
                END) AS grupo")
                    ->distinct('alu_alumno.id')
                    ->orderBy('apellidos', 'asc')
                    ->get();

                $archivo = $plantel->nombre . '_' . $this->periodo_seleccionado . '_Semestre';
                //dd($alumnos->toSql(), $alumnos->getBindings());
            } elseif ($this->plantel_seleccionado != "" && $this->grupo_seleccionar == "" && $this->periodo_seleccionado == "") {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
                    ->where('ciclo_esc_id', $this->ciclo_activo->id)
                    ->selectRaw("alu_alumno.noexpediente, alu_alumno.id, alu_alumno.nombre, alu_alumno.apellidos, esc_grupo.periodo,
                CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
                END) AS grupo")
                    ->distinct('alu_alumno.id')
                    ->orderBy('periodo', 'asc')
                    ->orderBy('grupo', 'asc')
                    ->orderBy('apellidos', 'asc')
                    ->get();

                $archivo = $plantel->nombre;
            }

        }
        $datos = [
            'alumnos' => $alumnos,
            'foto' => $this->foto,
            'ciclo' => $ciclo_activo,
            'hoy' => $hoy,
            'plantel' => $plantel,
            'foto_credencial' => $this->foto_credencial
        ];
        $html = view('estadisticas.documentos.catalogo_alumnos', $datos)->render();
        $dompdf = new Dompdf();

        // Carga el HTML
        $dompdf->loadHtml($html);

        // Renderiza el PDF
        $dompdf->render();

        // Obtiene el contenido del PDF
        $output = $dompdf->output();

        return response()->streamDownload(
            function () use ($output) {
                echo $output;
            },
            'Catalogo_certificados_' . $archivo . '.pdf'
        );
    }

    public function checkbox($campo)
    {
        if ($campo === 'foto_credencial') {
            $this->foto_credencial = true;
            $this->foto = false;
            $this->emit('logMessage', 'Valor de credenciales: ' . $this->foto_credencial . ' Valor de certificado: ' . $this->foto);

        } elseif ($campo === 'foto') {
            $this->foto = true;
            $this->foto_credencial = false;
            $this->emit('logMessage', 'Valor de credenciales: ' . $this->foto_credencial . ' Valor de certificado: ' . $this->foto);

        }
    }

}
