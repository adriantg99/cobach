<?php

namespace App\Http\Livewire\Adminalumnos\Promocion;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
use App\Models\Catalogos\PlandeEstudioModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesCicloAnterior;
use App\Models\Escolares\CalificacionesNoEncontrados;
use App\Models\Finanzas\FichasModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PromocionAlumnos extends Component
{
    public $Ciclos;
    public $mensaje_error;
    public $Plantel;
    public $ciclos_escolares;
    public $select_plantel;
    public $select_grupo;
    public $grupos;
    public $listado_alumnos;
    public $alumnos_en_grupo;  // Array para alumnos con calificaciones reprobadas    ;
    public $detallesAlumno;
    protected $listeners = ['calificacion_alumno', 'seleccionarAlumno', 'quitar_del_array'];
    public $grupos_pasar;
    public $grupo_seleccionado;
    public $alumnos_no_inscritos;
    public $calificaciones_alumnos;
    public $message;
    /* Variables para poner en la consulta */
    public $alumons_inscritos, $alumnos_sin_culminar_proceso, $alumnos_no_aparecen;

    public function handleCambioSelect()
    {
        if (!empty($this->alumnos_en_grupo)) {
            $this->message = "Favor de realizar la busqueda nuevamente";
            // Reinicia el valor de $alumnos_en_grupo a un array vacío
            $this->alumnos_en_grupo = [];
            $this->grupo_seleccionado = "";


        }



    }

    public function mount()
    {
        $this->Ciclos = CicloEscModel::select('cat_ciclos_esc.id', 'cat_ciclos_esc.nombre')
            ->distinct()
            ->join('esc_grupo', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
            /*
            ->where(function ($query) {
                $query->whereDate('cat_ciclos_esc.per_inicio', '=', now()->toDateString())
                    ->orwhere('cat_ciclos_esc.id', '=', '186');
            })*/
            ///Cambiar la ultima linea del subquery cuando ya tengan las calificaciones finales
            ->orderByDesc('id') // Utilizar orderByDesc para ordenar de forma descendente
            ->where('cat_ciclos_esc.id', '250')
            ->get();
        $this->Plantel = obtenerPlanteles();
        $this->grupos = collect();
        $this->detallesAlumno = []; // Inicializa los detalles del alumno
        $this->alumnos_no_inscritos = [];
    }

    public function updated($field)
    {
        if ($field == 'ciclos_escolares' && $this->ciclos_escolares && $this->select_plantel) {
            $this->cargarGrupos();
        } elseif ($field == 'select_plantel' && $this->ciclos_escolares && $this->select_plantel) {
            $this->cargarGrupos();
        }
    }

    public function cargarGrupos()
    {
        $this->grupos = GruposModel::where('ciclo_esc_id', $this->ciclos_escolares)
            ->where('plantel_id', $this->select_plantel)
            ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
            ->where('esc_grupo.nombre', '!=', 'Migracion')
            ->where('esc_grupo.descripcion', '!=', 'turno_especial')
            ->orderBy('nombre', 'asc')
            ->orderBy('turno_id', 'asc')
            ->get(); // Inicializar la colección de grupos
    }

    public function realizarBusqueda()
    {
        $this->message = "";
        $this->alumnos_en_grupo = AlumnoModel::select(
            'alu_alumno.id as id_alumno',
            'noexpediente',
            'alu_alumno.nombre',
            'alu_alumno.apellidos',
            'esc_grupo.ciclo_esc_id as ciclo_esc_id'
        )
            ->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
            ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
            ->where('esc_grupo.id', '=', $this->select_grupo)
            ->orderBy('apellidos', 'asc')
            ->get();

        $grupo_seleccionado = GruposModel::find($this->select_grupo);

        if (!empty($this->alumnos_en_grupo)) {

            if ($this->alumnos_en_grupo) {
                foreach ($this->alumnos_en_grupo as $key => $alumno) {
                    $reprobados = contador_reprobadas($alumno->id_alumno, $alumno->ciclo_esc_id, $grupo_seleccionado->periodo);
                    if ($reprobados == 0) {
                        //$this->alumnos_con_reprobadas[] = $alumno;  // Mueve el alumno al array de con reprobadas
                        $alumno->reprobado = 0;
                    } else {
                        $alumno->reprobado = 1;

                    }

                    if ($alumno->noexpediente == "23190224") {
                        //dd($alumno);
                    }
                }

                // Re-indexar el array para eliminar los índices vacíos
            }
        }
        // dd($this->alumnos_en_grupo);


        $todos_los_valores = permisos();



        if ($todos_los_valores != 1) {
            $this->grupos_pasar = GruposModel::select('esc_grupo.nombre', 'esc_grupo.id', 'turno_id', 'esc_grupo.descripcion')
                ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
                //->where('cat_ciclos_esc.id', '>', $this->ciclos_escolares)
                ->where('cat_ciclos_esc.nombre', '!=', 'REVALIDACIÃ“N')
                ->where('cat_ciclos_esc.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->where('cat_ciclos_esc.activo', '=', 1)
                ->where('esc_grupo.periodo', '!=', '1')
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.nombre', '!=', 'Migracion')
                ->where('plantel_id', $this->select_plantel)
                ->orderBy('periodo', 'asc')
                ->orderBy('esc_grupo.nombre', 'asc')
                ->get();
        } else {
            $this->grupos_pasar = GruposModel::select('esc_grupo.nombre', 'esc_grupo.id', 'turno_id', 'esc_grupo.descripcion')
                ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
                //->where('cat_ciclos_esc.id', '>', $this->ciclos_escolares)
                ->where('cat_ciclos_esc.nombre', '!=', 'REVALIDACIÃ“N')
                ->where('cat_ciclos_esc.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.nombre', '!=', 'Migracion')
                ->where('cat_ciclos_esc.activo', '=', 1)
                ->where('esc_grupo.turno_id', $grupo_seleccionado->turno_id)
                ->where('plantel_id', $this->select_plantel)
                ->orderBy('periodo', 'asc')
                ->orderBy('esc_grupo.nombre', 'asc')
                ->get();
        }

        // dd($this->grupos_pasar);
        //dd($query->toSql(), $query->getBindings());
    }

    // Dentro de tu componente Livewire
    public function quitar_del_array($alumnoId)
    {
        $this->detallesAlumno = collect($this->detallesAlumno)->reject(function ($alumno) use ($alumnoId) {
            return $alumno['alumno_id'] == $alumnoId;
        })->values()->all();
        // dd($this->detallesAlumno);

    }
    public function calificacion_alumno($id_alumno, $ciclo_esc_id)
    {
        $this->calificaciones_alumnos = CalificacionesCicloAnterior::select(
            'esc_curso.asignatura_id',
            'esc_curso.nombre',
            'esc_calificacion.calificacion',
            'esc_calificacion.calif',
            'esc_calificacion.calificacion_tipo'
        )
            ->join('esc_curso', 'esc_calificacion.curso_id', '=', 'esc_curso.id')
            ->join('asi_asignatura', 'esc_curso.asignatura_id', '=', 'asi_asignatura.id')
            ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
            ->where('esc_calificacion.calificacion_tipo', 'Final')
            ->where('esc_calificacion.alumno_id', $id_alumno)
            ->where('esc_grupo.ciclo_esc_id', '=', $ciclo_esc_id)
            ->get();
    }

    public function seleccionarAlumno($alumnoId, $expediente)
    {
        $this->detallesAlumno[] = [
            'alumno_id' => $alumnoId,
            'expediente' => $expediente,
        ];

    }

    public function TodosSeleccionados($seleccionado, $expediente)
    {
        foreach ($seleccionado as $auxiliar) {
            $this->detallesAlumno[] = [
                'alumno_id' => $auxiliar,
                'expediente' => $expediente,
            ];
        }
    }

    public function guardar_alumnos_grupos()
    {
        //dd($this->detallesAlumno);
        $contador_inscritos = 0;
        foreach ($this->detallesAlumno as $auxiliar) {
            //dd($auxiliar["alumno_id"]);
            $data = [
                'grupo_id' => $this->grupo_seleccionado,
                'alumno_id' => $auxiliar["alumno_id"],
            ];
            //dd($this->grupo_seleccionado);
            /*
             $existe = GrupoAlumnoModel::
                 join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                 ->where('alumno_id', '=', $auxiliar["alumno_id"])
                 ->where('esc_grupo.id', '=', $this->grupo_seleccionado)
                 ->where('esc_grupo.gpo_base', '=', '1')
                 ->get();
            */
            //sdd($existe->toSql(), $existe->getBindings());

            $buscar_grupo = GruposModel::find($this->grupo_seleccionado);

            if ($buscar_grupo->gpo_base == 1) {
                $existe_alumno_en_grupo = GrupoAlumnoModel::
                    join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
                    ->where('alumno_id', '=', $auxiliar["alumno_id"])
                    ->where('esc_grupo.gpo_base', '=', '1')
                    ->where('cat_ciclos_esc.activo', '=', 1)                    //->where('cat_ciclos_esc.activo', 250)
                    ->get();
            } else {
                $existe_alumno_en_grupo = GrupoAlumnoModel::
                    join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
                    ->where('alumno_id', '=', $auxiliar["alumno_id"])
                    ->where('esc_grupo.id', '=', $this->grupo_seleccionado)
                    ->where('cat_ciclos_esc.activo', '=', 1)
                    //->where('cat_ciclos_esc.activo', 1)
                    ->get();
            }

            //dd($existe_alumno_en_grupo->toSql(), $existe_alumno_en_grupo->getBindings());
            //dd($existe_alumno_en_grupo);

            /* Aqui va el codigo de la bitacora */
            if (count($existe_alumno_en_grupo) == 0) {
                GrupoAlumnoModel::create($data);
                $actualizar_alumno = [
                    'id_estatus' => '1'
                ];

                $actualizar_registro_alumno = AlumnoModel::find($auxiliar["alumno_id"]);
                $actualizar_registro_alumno->update($actualizar_alumno);

                $contador_inscritos += 1;

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller' => 'PromocionController',
                    //'component'     =>  'FormComponent',
                    'function' => 'Agregar a grupo',
                    'description' => 'Se agrego el alumno_id: ' . $auxiliar["alumno_id"] . ' Al Grupo:' . $this->grupo_seleccionado,
                ]);
            } else {
                $this->alumnos_no_inscritos[] = [
                    'alumno_id' => $auxiliar["alumno_id"],
                    'no_expediente' => $auxiliar["expediente"],
                ];


                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller' => 'PromocionController',
                    //'component'     =>  'FormComponent',
                    'function' => 'Agregar a grupo',
                    'description' => 'NO SE AGREGO el alumno_id: ' . $auxiliar["alumno_id"] . ' Al Grupo:' . $this->grupo_seleccionado,
                ]);
            }


        }





        if (!empty($this->alumnos_no_inscritos) && $contador_inscritos == 0) {
            return redirect()->route('alumnos.promocion')->with('error', 'No se pudieron promocionar a los alumnos');
        } elseif (!empty($this->alumnos_no_inscritos) && $contador_inscritos != 0) {
            return redirect()->route('alumnos.promocion')->with([
                'success_half' => 'Algunos alumnos no pudieron ser promocionados',
                'alumnos_no_inscritos' => $this->alumnos_no_inscritos
            ]);
        } else {
            return redirect()->route('alumnos.promocion')->with('success', 'Alumnos promocionados correctamente');
        }
    }

    public function render()
    {

        return view('livewire.adminalumnos.promocion.promocion-alumnos');
    }
}

function contador_reprobadas($alumno, $ciclo, $periodo)
{
    $promedios = DB::select('call pa_kardex(?)', array($alumno));
    $obtener_ciclo = CicloEscModel::find($ciclo);


    $cantidad = 0;
    $cal_acumulada = 0;
    $reprobados = 0;
    $aprobados = 0;
    $promedio = 0;
    //dd($this->calificacioneska);\
    if ($promedios) {
        foreach ($promedios as $cal) {
            if ($periodo > $cal->periodo) {
                continue;
            }

            if ($cal->ciclo1 == $obtener_ciclo->abreviatura || $cal->ciclo2 == $obtener_ciclo->abreviatura || $cal->ciclo3 == $obtener_ciclo->abreviatura || $cal->ciclo == $obtener_ciclo->abreviatura) {

            } else {
                continue;
            }

            if (is_object($cal)) {
                $asignatura = AsignaturaModel::find($cal->asignatura_id);

                if ($asignatura->kardex) {

                    if ((is_null($cal->calificacion) == false) or (is_null($cal->calif) == false)) {
                        if ($asignatura->afecta_promedio) {
                            if ($cal->calif == "REV") {
                                //$cal_acumulada = $cal_acumulada + 100;
                                $cantidad--;
                            } else {
                                $cal_acumulada = $cal_acumulada + (int) $cal->calificacion;
                            }
                            $cantidad++;
                        }
                        if (($cal->calificacion >= 60) or ($cal->calif == "AC") or ($cal->calif == "REV")) {
                            $aprobados++;
                        } else {
                            if ($asignatura->afecta_promedio == 1) {
                                $reprobados++;
                            }
                        }
                    } elseif ((is_null($cal->calificacion3) == false) or (is_null($cal->calif3) == false)) {
                        if ($asignatura->afecta_promedio) {
                            if ($cal->calif3 == "REV") {
                                // $cal_acumulada = $cal_acumulada + 100;
                                $cantidad--;
                            } else {
                                $cal_acumulada = $cal_acumulada + (int) $cal->calificacion3;
                            }
                            $cantidad++;
                        }
                        if (($cal->calificacion3 >= 60) or ($cal->calif3 == "AC") or ($cal->calif3 == "REV")) {
                            $aprobados++;
                        } else {
                            if ($asignatura->afecta_promedio == 1) {
                                $reprobados++;
                            }
                        }
                    } elseif ((is_null($cal->calificacion2) == false) or (is_null($cal->calif2) == false)) {
                        if ($asignatura->afecta_promedio) {
                            if ($cal->calif2 == "REV") {
                                // $cal_acumulada = $cal_acumulada + 100;
                                $cantidad--;
                            } else {
                                $cal_acumulada = $cal_acumulada + (int) $cal->calificacion2;
                            }
                            $cantidad++;
                        }
                        if (($cal->calificacion2 >= 60) or ($cal->calif2 == "AC") or ($cal->calif2 == "REV")) {
                            $aprobados++;
                        } else {
                            if ($asignatura->afecta_promedio == 1) {
                                $reprobados++;
                            }
                        }
                    } elseif ((is_null($cal->calificacion1) == false) or (is_null($cal->calif1) == false)) {
                        if ($asignatura->afecta_promedio) {
                            if ($cal->calif1 == "REV") {
                                // $cal_acumulada = $cal_acumulada + 100;
                                $cantidad--;
                            } else {
                                $cal_acumulada = $cal_acumulada + (int) $cal->calificacion1;
                            }
                            $cantidad++;
                        }
                        if (($cal->calificacion1 >= 60) or ($cal->calif1 == "AC") or ($cal->calif1 == "REV")) {
                            $aprobados++;
                        } else {
                            if ($asignatura->afecta_promedio == 1) {
                                $reprobados++;
                            }
                        }
                    }

                }

            }


        }


        if ($reprobados > 3) {
            return 0;

        } else {
            return 1;
        }
    }

    return 1;
}
