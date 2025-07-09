<?php

namespace App\Http\Livewire\Cursos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class ReplicacionComponent extends Component
{
    public $plantel_seleccionado, $ciclo_activo,
    $periodo_seleccionado, $plantel, $periodos,
    $grupo_seleccionar, $buscar_cursos, $curso_origen, $curso_destino, $parcial;
    public function render()
    {
        if ($this->plantel_seleccionado != "") {
            $this->periodos = GruposModel::select('periodo')
                ->where('plantel_id', $this->plantel_seleccionado)
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
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
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->orderBy("nombre")
                ->get();
        }

        if ($this->grupo_seleccionar != "") {
            $this->buscar_cursos = CursosModel::where('grupo_id', $this->grupo_seleccionar)->get();
        }


        return view('livewire.cursos.replicacion-component');
    }

    public function mount()
    {
        $this->periodos = collect();
        $this->grupos = collect();
        $this->ciclo_activo = CicloEscModel::where('id', '250')->orderBy('per_inicio', 'desc')->first();
        $this->parcial = "P1"; // Default value for the partial, can be changed as needed
        $roles = Auth()->user()->getRoleNames()->toArray();
        foreach ($roles as $role) {

            if ($role === "super_admin") {
                $todos_los_valores = 1;
                $this->plantel = PlantelesModel::get();

                break;
            } else {
                continue;
            }
        }

        if ($todos_los_valores != 1) {
            return redirect('/');
        }





    }

    public function replicar_calificaciones()
    {
        if ($this->curso_destino != "" && $this->curso_origen != "") {
            $calificaciones_curso = DB::select('CALL calificaciones_cursos(?)', [$this->curso_origen]);
            foreach ($calificaciones_curso as $replicaciones) {
                $buscar_calificacion = CalificacionesModel::where('curso_id', $replicaciones->curso_id)
                    ->where('alumno_id', $replicaciones->id)
                    ->where('calificacion_tipo', $this->parcial)
                    ->first();

                if ($buscar_calificacion) {
                    $buscar_calificacion_nuevo_curso = CalificacionesModel::where('curso_id', $this->curso_destino)
                        ->where('alumno_id', $replicaciones->id)
                        ->where('calificacion_tipo', $this->parcial)
                        ->first();

                    if ($buscar_calificacion_nuevo_curso) {
                        $buscar_calificacion_nuevo_curso->update([
                            'alumno_id' => $replicaciones->id,
                            'politica_variable_id' => $buscar_calificacion->politica_variable_id,
                            'calificacion_tipo_id' => $buscar_calificacion->calificacion_tipo_id,
                            'calificacion_tipo' => $buscar_calificacion->calificacion_tipo,
                            'curso_id' => $this->curso_destino,
                            'faltas' => $buscar_calificacion->faltas,
                            'calificacion' => $buscar_calificacion->calificacion,
                            'calif' => $buscar_calificacion->calif
                        ]);

                        BitacoraModel::create([
                            'user_id' => Auth()->user()->id,
                            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                            'path' => $_SERVER["REQUEST_URI"],
                            'method' => $_SERVER['REQUEST_METHOD'],
                            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                            'controller' => 'Replicacion de calificacion',
                            'component' => 'Replica de calificaciones',
                            'function' => 'Repicacion del alumno: ' . $replicaciones->id,
                            'description' => 'Replicacion del curso: ' . $this->curso_origen . ' Al curso destino: ' . $this->curso_destino,
                        ]);

                        DB::select('CALL actualizar_o_insertar_calificacion_final(?, ?, ?)', [$replicaciones->id, $this->curso_destino, 2]);


                    } else {
                        $nueva_calif = CalificacionesModel::create([
                            'alumno_id' => $replicaciones->id,
                            'politica_variable_id' => $buscar_calificacion->politica_variable_id,
                            'calificacion_tipo_id' => $buscar_calificacion->calificacion_tipo_id,
                            'calificacion_tipo' => $buscar_calificacion->calificacion_tipo,
                            'curso_id' => $this->curso_destino,
                            'faltas' => $buscar_calificacion->faltas,
                            'calificacion' => $buscar_calificacion->calificacion,
                            'calif' => $buscar_calificacion->calif
                        ]);

                        BitacoraModel::create([
                            'user_id' => Auth()->user()->id,
                            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                            'path' => $_SERVER["REQUEST_URI"],
                            'method' => $_SERVER['REQUEST_METHOD'],
                            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                            'controller' => 'Replicacion de calificacion',
                            'component' => 'Replica de calificaciones',
                            'function' => 'Repicacion del alumno: ' . $replicaciones->id,
                            'description' => 'Replicacion del curso: ' . $this->curso_origen . ' Al curso destino: ' . $this->curso_destino,
                        ]);
                        DB::select('CALL actualizar_o_insertar_calificacion_final(?, ?, ?)', [$replicaciones->id, $this->curso_destino, 2]);
                    }
                }
            }
        }
    }
}
