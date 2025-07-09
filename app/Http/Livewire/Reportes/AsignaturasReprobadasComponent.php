<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Cursos\CursosModel;
use App\Models\Cursos\CursosOmitidosModel;
use App\Models\Grupos\GruposModel;
use Dompdf\Dompdf;
use Livewire\Component;

class AsignaturasReprobadasComponent extends Component
{
    public $plantel, $plantel_seleccionado,
    $periodo_seleccionado, $periodos, $semestres,
    $grupos, $grupo_seleccionar, $ciclo_activo,
    $alumnos_grupo, $alumno_seleccionar;

    public function render()
    {
        if ($this->plantel_seleccionado != "") {
            $this->semestres = GruposModel::select('periodo')
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->distinct()
                ->orderBy('periodo')
                ->get();

            $this->periodos = $this->semestres->filter(function ($item) {
                return is_numeric($item->periodo);
            });

            if ($this->periodo_seleccionado != "") {
                $this->grupos = GruposModel::where('periodo', $this->periodo_seleccionado)
                    ->where('ciclo_esc_id', $this->ciclo_activo->id)
                    ->where('plantel_id', $this->plantel_seleccionado)
                    ->whereNotIn('esc_grupo.nombre', ['ActasExtemporaneas', 'turno_especial', 'Migracion'])
                    ->selectRaw("id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' Matutino'
                WHEN 2 THEN ' Vespertino'                
                END) as nombre")
                    ->orderBy('esc_grupo.nombre')
                    ->get();
            }

            if ($this->grupo_seleccionar != "") {

                $buscar_cursos = CursosModel::where('grupo_id', $this->grupo_seleccionar)->pluck('id');

                $cursos_omitidos = CursosOmitidosModel::whereIn('curso_id', $buscar_cursos)->get();

                //Alumnos con cursos omitidos en el grupo seleccionado, esto para que no se muestren en el reporte si no es su grupo base
                $omitidos_alumno_ids = $cursos_omitidos->pluck('alumno_id')->toArray();

                $this->alumnos_grupo = AlumnoModel::join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                    ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->where('esc_grupo.id', $this->grupo_seleccionar)
                    ->whereNotIn('alu_alumno.id', $omitidos_alumno_ids)
                    ->selectRaw('alu_alumno.id, alu_alumno.nombre, alu_alumno.apellidos, noexpediente')
                    ->orderBy('alu_alumno.apellidos')
                    ->get();

                foreach ($this->alumnos_grupo as $buscar_reprobados) {
                    $asignaturas = \DB::select("CALL pa_kardex_aprob_reprob(?)", [$buscar_reprobados->id]);
                    if ($asignaturas[0]->reprobadas == 0) {
                        $this->alumnos_grupo = $this->alumnos_grupo->reject(function ($item) use ($buscar_reprobados) {
                            return $item->id === $buscar_reprobados->id;
                        });
                    }
                }



            } else {
                $this->alumnos_grupo = collect();
            }
        }
        return view('livewire.reportes.asignaturas-reprobadas-component');
    }

    public function mount()
    {
        $this->plantel = obtenerPlanteles();
        
        $this->permisos = permisos();
        $this->periodos = collect();
        $this->grupos = collect();
        $this->alumnos_grupo = collect();
        $this->ciclo_activo = CicloEscModel::where('activo', '1')->orderBy('per_inicio', 'desc')->first();

    }

    public function generar_documento()
    {
        $plantel = collect($this->plantel)->firstWhere('id', $this->plantel_seleccionado);
        $nombre_grupo = 0;
        $nombre_plantel = 0;
        
        if ($this->plantel_seleccionado == 0) {
            $archivo = 'AsignaturasReprobadasTodosPlanteles';
            $nombre_pdf = 'Todos los planteles';
            $nombre_grupo = 1;
            $nombre_plantel = 1;
        }else{
            if ($this->plantel_seleccionado != 0 && $this->periodo_seleccionado == 0 && $this->grupo_seleccionar == 0 && $this->alumno_seleccionar == 0) {
                $archivo = 'AsignaturasReprobadasPlantel_' . $plantel->abreviatura;
                $nombre_pdf = 'reprobadas plantel ' . $plantel->nombre;
                $nombre_grupo = 1;
            } elseif ($this->periodo_seleccionado != 0 && $this->grupo_seleccionar == 0 && $this->alumno_seleccionar == 0) {
                $archivo = 'AsignaturasReprobadasSemestre_' . $plantel->abreviatura . '_' . $this->periodo_seleccionado . 'Semestre';
                $nombre_pdf = 'reprobadas plantel ' . $plantel->nombre. ' ' . $this->periodo_seleccionado . ' Semestre ' ;
                $nombre_grupo = 1;
            } elseif ($this->grupo_seleccionar != 0 && $this->alumno_seleccionar == 0) {
                $grupo = $this->grupos->firstWhere('id', $this->grupo_seleccionar);
                $archivo = 'AsignaturasReprobadasGrupo_' . $plantel->abreviatura . "_" . ($grupo ? $grupo->nombre : '').'_'. ($grupo->turno_id == 1 ? ' Matutino' : ' Vespertino');
                $nombre_pdf = 'reprobadas plantel ' . $plantel->nombre. ' '. $grupo->nombre.($grupo->turno_id == 1 ? ' Matutino' : ' Vespertino');
            } else {
                $alumno = $this->alumnos_grupo->firstWhere('id', $this->alumno_seleccionar);
                $archivo = 'AsignaturasReprobadasAlumno_' . $plantel->abreviatura . "_" . ($alumno ? $alumno->noexpediente : '');
                $nombre_pdf = 'reprobadas del alumno  ' . $alumno->noexpediente;
            }
        }
  
        $datos = $this->alumnos_solicitados($this->alumno_seleccionar, $this->grupo_seleccionar, $this->periodo_seleccionado, $this->plantel_seleccionado);

        
        $datos = ['datos' => $datos, 'nombre_pdf' => $nombre_pdf, 'nombre_grupo' => $nombre_grupo, 'nombre_plantel' => $nombre_plantel]; // Asegúrate de pasar los datos como un array asociativo
        $html = view('estadisticas.reportes.reprobadas_pdf', $datos)->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        return response()->streamDownload(
            function () use ($output) {
            echo $output;
            },
            $archivo . '.pdf'
        );
    }

    private function alumnos_solicitados($alumno_seleccionado, $grupo_seleccionar, $periodo_seleccionado, $plantel_seleccionado)
    {
        $reporte = GrupoAlumnoModel::join('alu_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
            ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
            ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
            // Filtros para el reporte con las opciones seleccionadas
            ->when($alumno_seleccionado != 0, function ($query) use ($alumno_seleccionado) {
            return $query->where('alu_alumno.id', $alumno_seleccionado);
            })
            ->when($grupo_seleccionar != 0, function ($query) use ($grupo_seleccionar) {
            return $query->where('esc_grupo.id', $grupo_seleccionar);
            })
            ->when($periodo_seleccionado != 0, function ($query) use ($periodo_seleccionado) {
            return $query->where('esc_grupo.periodo', $periodo_seleccionado);
            })
            ->when($plantel_seleccionado != 0, function ($query) use ($plantel_seleccionado) {
            return $query->where('esc_grupo.plantel_id', $plantel_seleccionado);
            })

            ->where('esc_grupo.ciclo_esc_id', $this->ciclo_activo->id)
            ->whereNotIn('esc_grupo.nombre', ['ActasExtemporaneas', 'turno_especial', 'Migracion'])

            ->selectRaw("
            MIN(esc_grupo_alumno.id) as grupo_alumno_id, 
            alu_alumno.id, 
            alu_alumno.noexpediente, 
            alu_alumno.nombre, 
            alu_alumno.apellidos, 
            MIN(esc_grupo.nombre) as grupo,
            CASE esc_grupo.turno_id
            WHEN 1 THEN 'Matutino'
            ELSE 'Vespertino'
            END as turno,
            cat_plantel.nombre as plantel,
            cat_plantel.id as plantel_id
        ")
            ->groupBy('alu_alumno.id', 'alu_alumno.noexpediente', 'alu_alumno.nombre', 'alu_alumno.apellidos', 'cat_plantel.nombre', 'cat_plantel.id', 'esc_grupo.turno_id')
            ->orderBy('plantel_id')
            ->orderBy('grupo')
            ->orderBy('alu_alumno.apellidos')
         ->get();

        //dd($reporte->toSql(), $reporte->getBindings());
            foreach ($reporte as $buscar_reprobados) {
                $asignaturas = \DB::select("CALL pa_kardex_aprob_reprob(?)", [$buscar_reprobados->id]);
                //dd($asignaturas[0]->reprobadas);
                if ($asignaturas[0]->reprobadas == 0) {
                    $reporte = $reporte->reject(function ($item) use ($buscar_reprobados) {
                        return $item->id === $buscar_reprobados->id;
                    });
                }
            }
        return $reporte;
    }


 
}
