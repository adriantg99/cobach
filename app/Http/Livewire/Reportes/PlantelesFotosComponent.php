<?php

namespace App\Http\Livewire\Reportes;

use App\Exports\Reportes\InfoAlumnosExport;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Storage;

class PlantelesFotosComponent extends Component
{
    public $plantel, $plantel_seleccionado,
    $periodo_seleccionado, $periodos,
    $grupos, $grupo_seleccionar, $ciclo_activo,
    $foto;
    public function render()
    {

        if ($this->plantel_seleccionado != "") {
            $periodos = GruposModel::select('periodo')
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->distinct()
                ->orderBy('periodo')
                ->get();

            // Filtrar para mantener solo valores numéricos
            $this->periodos = $periodos->filter(function ($item) {
                return is_numeric($item->periodo);
            });
            //dd($this->periodos);
        }

        if ($this->periodo_seleccionado != "") {

            $this->grupos = GruposModel::where('periodo', $this->periodo_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->selectRaw(" id, CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
        WHEN 1 THEN ' M'
        WHEN 2 THEN ' V'
        END) AS nombre")
                ->orderBy("nombre")
                ->get();

        }

        return view('livewire.reportes.planteles-fotos-component');
    }
    public function mount()
    {

        $this->periodos = collect();
        $this->grupos = collect();

        $this->ciclo_activo = CicloEscModel::where('activo', '1')->orderBy('per_inicio', 'desc')->first();
        $roles = Auth()->user()->getRoleNames()->toArray();
        foreach ($roles as $role) {
            if ($role === "control_escolar" || $role === "credenciales") {
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


    public function generar_documento()
    {
        $plantel = PlantelesModel::find($this->plantel_seleccionado);

        if ($this->periodo_seleccionado != "" && $this->grupo_seleccionar != 0) {
            $grupo = GruposModel::find($this->grupo_seleccionar);
            $turno = $grupo->turno_id == 1 ? "M" : "V";
            $archivo = $plantel->nombre . '_' . $grupo->nombre . $turno;
        } elseif ($this->periodo_seleccionado != 0 && $this->grupo_seleccionar == 0) {
            $archivo = $plantel->nombre . '_' . $this->periodo_seleccionado . '_Semestre';
        } elseif ($this->plantel_seleccionado != 0 && $this->grupo_seleccionar == 0 && $this->periodo_seleccionado == 0) {
            $archivo = $plantel->nombre;
        }
        else{
            $archivo = "Todos";
        }

        $excelFileName = $archivo . '_' . $this->ciclo_activo->nombre . '.xlsx';
        Excel::store(
            new InfoAlumnosExport(
                $this->plantel_seleccionado,
                $this->periodo_seleccionado,
                $this->grupo_seleccionar,
                $this->ciclo_activo,
                $plantel
            ),
            $excelFileName,
            'local'
        );

        if ($this->foto) {
            $zipFileName = $archivo . '.zip';
            $zip = new ZipArchive;
            if ($zip->open(storage_path('app/' . $zipFileName), ZipArchive::CREATE) === TRUE) {
                $zip->addFile(storage_path('app/' . $excelFileName), $excelFileName);

                $alumnos = $this->alumnos_plantel($this->periodo_seleccionado, $this->grupo_seleccionar, $this->plantel_seleccionado, $this->ciclo_activo->id);
                foreach ($alumnos as $alumno) {
                    $fotoContent = ImagenesalumnoModel::where('alumno_id', $alumno->id)
                        ->where('tipo', '1')
                        ->value('imagen');

                    if ($fotoContent) {
                        $fileName = $alumno->noexpediente . '.jpg';
                        $zip->addFromString($fileName, $fotoContent);
                    }
                }
                $zip->close();
            }

            $response = response()->download(storage_path('app/' . $zipFileName))->deleteFileAfterSend(true);
            Storage::delete($excelFileName);
            return $response;

        } else {
            return response()->download(storage_path('app/' . $excelFileName))->deleteFileAfterSend(true);
        }
    }

    private function alumnos_plantel($periodo, $grupo, $plantel, $ciclo)
    {

        if ($periodo != "" && $grupo != 0) {
            $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('esc_grupo.id', $grupo)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->selectRaw("alu_alumno.id, alu_alumno.noexpediente")
                ->distinct('alu_alumno.id')
                ->get();

            $nombre = GruposModel::where('id', $grupo)
                ->selectRaw("CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
                END) AS grupo")
                ->first();
            //$archivo = $this->plantel->nombre . '_' . $nombre->grupo;
        } elseif ($periodo != 0 && $grupo == 0) {
            $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('esc_grupo.periodo', $periodo)
                ->where('esc_grupo.plantel_id', $plantel)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('ciclo_esc_id', $ciclo)
                ->selectRaw("alu_alumno.id, alu_alumno.noexpediente")
                ->distinct('alu_alumno.id')
                ->get();

            //$archivo = $this->plantel->nombre . '_' . $this->periodo_seleccionado . '_Semestre';
        } elseif ($plantel != 0 && $grupo == 0 && $periodo == 0) {
            $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('esc_grupo.plantel_id', $plantel)
                ->where('ciclo_esc_id', $ciclo)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->selectRaw("alu_alumno.id, alu_alumno.noexpediente, esc_grupo.periodo, esc_grupo.nombre as grupo")
                ->distinct('alu_alumno.id')
                ->orderBy('periodo', 'asc')
                ->orderBy('grupo', 'asc')
                ->get();

            //$archivo = $this->plantel->nombre;
        }

        return $alumnos;

        /*
        private function alumnos_plantel($periodo, $grupo, $plantel, $ciclo){
        if ($periodo != "" && $grupo != 0) {
            $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('esc_grupo.id', $grupo)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->selectRaw("alu_alumno.id, alu_alumno.noexpediente")
                ->distinct('alu_alumno.id')
                ->get();
    
            $nombre = GruposModel::where('id', $grupo)
                ->selectRaw("CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
                END) AS grupo")
                ->first();
            //$archivo = $this->plantel->nombre . '_' . $nombre->grupo;
        } elseif ($periodo != 0 && $grupo == 0) {
            $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('esc_grupo.periodo', $periodo)
                ->where('esc_grupo.plantel_id', $plantel)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('ciclo_esc_id', $ciclo)
                ->selectRaw("alu_alumno.id, alu_alumno.noexpediente")
                ->distinct('alu_alumno.id')
                ->get();
    
            //$archivo = $this->plantel->nombre . '_' . $this->periodo_seleccionado . '_Semestre';
        } elseif ($plantel != 0 && $grupo == 0 && $periodo == 0) {
            $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('esc_grupo.plantel_id', $plantel)
                ->where('ciclo_esc_id', $ciclo)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->selectRaw("alu_alumno.id, alu_alumno.noexpediente, esc_grupo.periodo, esc_grupo.nombre as grupo")
                ->distinct('alu_alumno.id')
                ->orderBy('periodo', 'asc')
                ->orderBy('grupo', 'asc')
                ->get();
    
            //$archivo = $this->plantel->nombre;
        }
    
        return $alumnos;
    }
        */
    }
}


