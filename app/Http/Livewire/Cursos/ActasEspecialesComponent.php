<?php

namespace App\Http\Livewire\Cursos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Administracion\PerfilModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\HorarioModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Docentes\ActasModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class ActasEspecialesComponent extends Component
{
    public $Ciclos, $plantel, $select_plantel, $ciclos_escolares, $actas, $acta, $plantel_abrev, $politica_variable_final;

    protected $listeners = ["cambioacta"];
    public function mount()
    {
        $this->Ciclos = CicloEscModel::select('cat_ciclos_esc.id', 'cat_ciclos_esc.nombre', 'cat_ciclos_esc.abreviatura')
            ->whereNotIn('cat_ciclos_esc.nombre', ['REVALIDACIÓN', 'Titulación'])
            ->where('id', '>=', '247')
            ->orderByDesc('cat_ciclos_esc.id')
            ->get();

        $this->plantel = obtenerPlanteles();
        $this->actas = collect();
    }


    public function render()
    {
        return view('livewire.cursos.actas-especiales-component');
    }

    public function realizarBusqueda()
    {
        $this->actas = collect();
        $this->actas = ActasModel::join('esc_curso', 'esc_curso.id', 'esc_actas.curso_id')
            ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_actas.grupo_id')
            ->join('emp_perfil', 'esc_actas.docente_id', '=', 'emp_perfil.user_id')
            ->join('alu_alumno', 'esc_actas.alumno_id', '=', 'alu_alumno.id')
            ->leftjoin('esc_calificacion', function ($join) {
                $join->on('esc_calificacion.id', '=', 'esc_actas.calificacion_id');
            })
            ->join('asi_politica_variable', 'esc_actas.parcial', '=', 'asi_politica_variable.id')
            ->join('asi_variableperiodo', 'asi_variableperiodo.id', '=', 'asi_politica_variable.id_variableperiodo')
            ->where('esc_grupo.plantel_id', $this->select_plantel)
            ->where('esc_grupo.ciclo_esc_id', $this->ciclos_escolares)
            ->select(
                'esc_actas.*',
                'esc_grupo.nombre as grupo',
                'esc_calificacion.calificacion',
                'asi_variableperiodo.nombre as parcial_nombre',
                'esc_calificacion.calificacion_tipo',
                'esc_calificacion.faltas',
                DB::raw("CONCAT(emp_perfil.nombre, ' ', emp_perfil.apellido1, ' ', emp_perfil.apellido2) as docente"),
                'esc_curso.nombre as asignatura',
                DB::raw("CONCAT(alu_alumno.nombre, ' ', alu_alumno.apellidos) as alumno")
            )
            ->orderBy('esc_actas.estado')
            ->get();
        //dd($query->toSql(), $query->getBindings());

        $this->plantel_abrev = PlantelesModel::select('abreviatura')->find($this->select_plantel);



        //dd($query->toSql(), $query->getBindings());

    }

    public function cambioacta($id_cambio, $valor)
    {
        $cambio_acta = ActasModel::find($id_cambio);

        $nueva_calif = CalificacionesModel::find($cambio_acta->calificacion_id);

        $politica = CursosModel::join('asi_asignatura', 'asi_asignatura.id', '=', 'esc_curso.asignatura_id')
            ->join('asi_areaformacion', 'asi_areaformacion.id', '=', 'asi_asignatura.id_areaformacion')
            ->join('asi_politica', 'asi_politica.id_areaformacion', '=', 'asi_areaformacion.id')
            ->join('asi_politica_variable', 'asi_politica_variable.id_politica', '=', 'asi_politica.id')
            ->join('asi_variableperiodo', 'asi_variableperiodo.id', '=', 'asi_politica_variable.id_variableperiodo')
            ->select('asi_variableperiodo.*', 'asi_politica_variable.id AS politica_variable_id')
            ->where('esc_curso.id', $cambio_acta->curso_id)
            ->orderBy('asi_variableperiodo.id', 'asc')
            ->get();

        foreach ($politica as $aux) {
            if ($aux->politica_variable_id == $cambio_acta->parcial) {
                $calificacion_tipo = $aux->nombre;
            }
            if ($aux->nombre == "F") {
                $this->politica_variable_final = $aux->politica_variable_id;
            }
        }
        if ($valor == 2) {

            if ($nueva_calif) {
                // Se encontró la calificación, ahora puedes actualizarla
                $cambio_acta->calificacion_anterior = $nueva_calif->calificacion;
                $cambio_acta->save();
                $calificacion_anterior = $nueva_calif->calificacion;
                $faltas_anterior = $nueva_calif->faltas;

                $nueva_calif->calificacion = $cambio_acta->nueva_calif;
                $nueva_calif->faltas = $cambio_acta->nueva_falta;

                $saved_nueva_calif = $nueva_calif->save(); // Guardar y obtener el resultado del guardado

                if ($saved_nueva_calif) {
                    // Se realizó el update de la nueva calificación con éxito

                    BitacoraModel::create([
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                        'path' => $_SERVER["REQUEST_URI"],
                        'method' => $_SERVER['REQUEST_METHOD'],
                        'controller' => 'Actas especiales',
                        'function' => 'Se Modifico la calificacion de un alumno',
                        'description' => 'Se creo un acta con el id calif : ' . $nueva_calif->id . ' Con la calificacion anterior: ' . $calificacion_anterior . ' Y la nueva Calificación: ' . $nueva_calif->calificacion . ' Con las faltas anterior: ' . $faltas_anterior . ' Y nuevas faltas: ' . $nueva_calif->faltas,
                    ]);
                } else {
                    // Error al guardar la nueva calificación
                }
            } else {




                $calificacion_crear = CalificacionesModel::create(
                    [
                        'alumno_id' => $cambio_acta->alumno_id,
                        'calificacion' => $cambio_acta->nueva_calif,
                        'politica_variable_id' => $cambio_acta->parcial,
                        'calificacion_tipo_id' => '0',
                        'curso_id' => $cambio_acta->curso_id,
                        'faltas' => $cambio_acta->nueva_falta,
                        'calif' => '',
                        'calificacion_tipo' => $calificacion_tipo,
                        'created_at' => now()
                    ],
                );

                if ($calificacion_crear) {
                    BitacoraModel::create([
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                        'path' => $_SERVER["REQUEST_URI"],
                        'method' => $_SERVER['REQUEST_METHOD'],
                        'controller' => 'Actas especiales',
                        'function' => 'Se creo una calificación por Acta',
                        'description' => 'Se creo una calificacion al alumno: ' . $cambio_acta->alumno_id . ' con el id calif : ' . $calificacion_crear->id . ' Con calificacion:' . $calificacion_crear->calificacion . ' Del curso:' . $calificacion_crear->curso_id,
                    ]);
                }
            }



        }

        $cambio_acta->estado = $valor;
        if (isset($calificacion_crear)) {
            $cambio_acta->calificacion_id = $calificacion_crear->id;
        }
        $cambio_acta->user_aut_id = Auth()->user()->id;
        $acta_guardada = $cambio_acta->save();

        DB::select('CALL actualizar_o_insertar_calificacion_final(?, ?, ?)', [$cambio_acta->alumno_id, $cambio_acta->curso_id, $this->politica_variable_final]);


        if ($acta_guardada) {
            // Estado guardado exitosamente, emitir evento al frontend
            $this->emit('cambio_acta_completo', $cambio_acta->estado, $id_cambio);
        } else {
            // Manejar el caso en que el estado no se guardó correctamente
            $this->emit('cambio_acta_error');
        }

    }
}
