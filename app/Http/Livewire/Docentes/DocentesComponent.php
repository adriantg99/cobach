<?php

namespace App\Http\Livewire\Docentes;

use App\Models\Administracion\BitacoraModel;
use App\Models\Administracion\PerfilModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\CiclosConfigModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Docentes\ActasModel;
use App\Models\Docentes\AperturaModel;
use App\Models\Escolares\CalificacionesModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Log;


class DocentesComponent extends Component
{
    public $Grupos;
    public $Ciclos;
    public $select_ciclo_;
    public $Plantel;
    public $Curso;
    public $Parcial;
    public $select_plantel;
    public $ciclos_escolares;
    public $select_parcial;
    public $docente;
    public $select_curso;
    public $alumnos_en_curso;
    public $activa_grupo;
    public $alumnoCalificaciones = [];
    public $alumnoFaltas = [];
    public $copia;
    public $parcial_activo;
    public $parciales;
    public $valida_fecha;
    public $actas_activas;
    public $modal;
    protected $listeners = ['solicitudActa', 'guardarDatosPorTiempo', 'ver_calificaciones'];
    public $parcial_nombre, $politica_variable_final;
    public $guardadoPorTiempo = false;
    public $cursoChanged = false;


    public function render()
    {
        if ($this->ciclos_escolares != null && $this->select_plantel != null) {
            $this->Curso = CursosModel::select('esc_curso.id', 'esc_curso.nombre', 'esc_grupo.descripcion as descripcion_grupo', 'esc_grupo.turno_id')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                ->where('esc_curso.docente_id', $this->docente->id)
                ->where('esc_grupo.plantel_id', $this->select_plantel)
                ->where('esc_grupo.ciclo_esc_id', $this->ciclos_escolares)
                ->orderBy('periodo', 'asc')
                ->orderBy('esc_curso.nombre')
                ->orderBy('turno_id')
                ->get();
        }
        if ($this->select_curso != null) {
            $this->parciales = CursosModel::join('asi_asignatura', 'asi_asignatura.id', '=', 'esc_curso.asignatura_id')
                ->join('asi_areaformacion', 'asi_areaformacion.id', '=', 'asi_asignatura.id_areaformacion')
                ->join('asi_politica', 'asi_politica.id_areaformacion', '=', 'asi_areaformacion.id')
                ->join('asi_politica_variable', 'asi_politica_variable.id_politica', '=', 'asi_politica.id')
                ->join('asi_variableperiodo', 'asi_variableperiodo.id', '=', 'asi_politica_variable.id_variableperiodo')
                ->select('asi_variableperiodo.*', 'asi_politica_variable.id AS politica_variable_id', 'id_variabletipo')
                ->where('esc_curso.id', $this->select_curso)
                ->orderBy('asi_variableperiodo.id', 'asc')
                ->get();
            //dd($this->parciales->toSql(), $this->parciales->getBindings());

        }

        if ($this->ciclos_escolares != null) {
            $this->validafecha = CiclosConfigModel::where('ciclo_esc_id', $this->ciclos_escolares)
                ->get();

            foreach ($this->validafecha as $aux) {
                $fecha_p1 = $aux->p1;
                $fecha_p2 = $aux->p2;
                $fecha_p3 = $aux->p3;
                $fecha_fin_p1 = $aux->fin_p1;
                $fecha_fin_p2 = $aux->fin_p2;
                $fecha_fin_p3 = $aux->fin_p3;
                $fecha_r = $aux->inicio_repeticion;
                $fecha_fin_r = $aux->fin_repeticion;
                $fecha_fin_semestre = $aux->fin_semestre;
            }

            //
            $hoy = Carbon::now();
            $valida_fecha_inicio_P3 = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_p3);
            $valida_fecha_inicio_r = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_r);

            /*
             if (!empty($this->parciales)) {
                                foreach ($this->parciales as $parcial) {
                                    if ($parcial->politica_variable_id == $this->select_parcial) {
                                        $tipo_parcial = $parcial->nombre;
                                        $parcial_numerico = $parcial->id_variabletipo;
                                    }

                                    if ($parcial->nombre == "F") {
                                        $this->politica_variable_final = $parcial->politica_variable_id;
                                    }
                                }

                                $this->parcial_nombre = $tipo_parcial;
                            }
            */
            if ($this->select_parcial != null) {
                if (!empty($this->parciales)) {
                    foreach ($this->parciales as $parcial) {
                        if ($parcial->politica_variable_id == $this->select_parcial) {
                            $this->parcial_nombre = $parcial->nombre;
                            $parcial_numerico = $parcial->id_variabletipo;
                        }
                        if ($parcial->nombre == "F") {
                            $this->politica_variable_final = $parcial->politica_variable_id;
                        }
                    }

                    $this->parcial_actual = $this->parcial_nombre;
                    if ($this->parcial_actual == "F") {
                        $this->parcial_actual = "Final";
                    }
                    $valida_fecha_inicio = null;
                    $valida_fecha_fin = null;

                    switch ($this->parcial_nombre) {
                        case 'P1':
                            $valida_fecha_inicio = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_p1);
                            $valida_fecha_fin = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_fin_p1);
                            break;
                        case 'P2':
                            $valida_fecha_inicio = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_p2);
                            $valida_fecha_fin = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_fin_p2);
                            break;
                        case 'P3':
                            $valida_fecha_inicio = $valida_fecha_inicio_P3;
                            $valida_fecha_fin = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_fin_p3);
                            break;
                        case 'R':
                            $valida_fecha_inicio = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_r);
                            $valida_fecha_fin = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_fin_r);
                            $this->actas_activas = false;

                            break;
                        case 'F':
                            $valida_fecha_inicio = $valida_fecha_inicio_P3;
                            $valida_fecha_fin = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_fin_p3);
                            $this->actas_activas = false;

                            break;
                        default:
                            $this->parcial_activo = false;
                            $this->actas_activas = false;
                            return;
                    }

                    BitacoraModel::create([
                        'user_id' => Auth()->user()->id,
                        'ip' => request()->ip(),
                        'path' => request()->getRequestUri(),
                        'method' => request()->method(),
                        'controller' => "Ingreso al {$this->parcial_nombre}",
                        'function' => "Se ingresó al {$this->parcial_nombre} del curso: {$this->select_curso}",
                        'description' => '',
                    ]);

                    //Aqui esta la parte donde busca si la asignatura es numerica o no
                    if ($hoy->isAfter($valida_fecha_inicio) && $hoy->isBefore($valida_fecha_fin)) {
                        $this->parcial_activo = true;
                        $this->actas_activas = false;
                        $this->es_numerico = !($this->parcial_nombre === 'F' && $parcial_numerico == '2');
                    } else {
                        $excepciones = buscar_excepciones($this->docente->id, $this->select_curso, $this->parcial_actual);
                        if (!empty($excepciones) && $hoy->isBefore($excepciones->nuevo_cierre)) {
                            $this->parcial_activo = true;
                            $this->es_numerico = !($this->parcial_nombre === 'F' && $parcial_numerico == '2');
                        } else {
                            $this->parcial_activo = false;
                            //Linea inicial acorde al reglamento, modificado para que se puedan abrir actas de parciales previos al 3
                            //$this->actas_activas = $this->parcial_nombre === 'R' || ($hoy->isAfter($valida_fecha_fin) && $hoy->isBefore($valida_fecha_inicio_P3));

                            //Linea para que sean antes del cierre de R
                            $this->actas_activas = (($this->parcial_nombre !== 'R') && !($this->parcial_nombre === 'F')) && ($hoy->isAfter($valida_fecha_fin) && $hoy->isBefore($fecha_fin_semestre));
                            $this->es_numerico = !($this->parcial_nombre === 'F' && $parcial_numerico == '2');
                            //dd("llegue aqui");
                        }
                    }
                    if ($this->parcial_nombre == "F" && $parcial_numerico != '2') {
                        $this->parcial_activo = false;

                    }
                }
            }

        }
        return view('livewire.docentes.docentes-component');
    }
    public function mount()
    {
        $this->modal = false;
        $this->parcial_activo = false;
        $this->Curso = [];
        $this->parciales = [];
        $this->activa_grupo = false;
        $this->es_numerico = true;
        $this->docente = PerfilModel::where('user_id', Auth()->user()->id)->first();

        if ($this->docente) {
            $this->Ciclos = CicloEscModel::select('cat_ciclos_esc.id', 'cat_ciclos_esc.nombre')
                ->distinct()
                ->join('esc_grupo', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
                ->join('esc_curso', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                ->where('docente_id', $this->docente->id)
                ->get();
            $this->Plantel = PlantelesModel::select('cat_plantel.id', 'cat_plantel.nombre')
                ->join('emp_perfil_plantele', 'cat_plantel.id', '=', 'emp_perfil_plantele.plantel_id')
                ->join('emp_perfil', 'emp_perfil.id', '=', 'emp_perfil_plantele.perfil_id')
                ->where('emp_perfil.user_id', Auth()->user()->id)
                ->get();



        } else {
            return redirect('');


        }

    }

    public function realizarBusqueda()
    {

        if ($this->ciclos_escolares != null && $this->select_plantel != null && $this->select_parcial != null && $this->select_curso != null) {
            if ($this->parcial_nombre != "R") {
                $this->alumnos_en_curso = CursosModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                    ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                    ->leftJoin('esc_actas', function ($join) {
                        $join->on('esc_grupo_alumno.alumno_id', '=', 'esc_actas.alumno_id')
                            ->where('curso_id', '=', $this->select_curso)
                            ->where('esc_actas.estado', '!=', '2')
                            ->whereRaw('esc_actas.id = 
                (SELECT id 
                 FROM esc_actas 
                 WHERE alumno_id = esc_grupo_alumno.alumno_id 
                 AND curso_id = ? 
                 AND estado != ? 
                 ORDER BY created_at DESC LIMIT 1)', [$this->select_curso, '2']);
                    })
                    ->leftjoin('esc_calificacion', function ($join) {
                        $join->on('esc_calificacion.curso_id', '=', 'esc_curso.id')
                            ->on('esc_calificacion.alumno_id', '=', 'alu_alumno.id')
                            ->where('esc_calificacion.calificacion_tipo', $this->parcial_actual);
                    })
                    ->leftjoin('esc_cursos_omitidos', function ($join) {
                        $join->on('esc_cursos_omitidos.curso_id', '=', 'esc_curso.id')
                            ->on('esc_cursos_omitidos.alumno_id', '=', 'alu_alumno.id');
                    })
                    ->where('esc_curso.id', $this->select_curso)
                    ->whereNull('esc_cursos_omitidos.motivo')
                    ->select(
                        'esc_curso.nombre as curso_nombre',
                        'esc_curso.id as curso_id',
                        'alu_alumno.id',
                        'alu_alumno.noexpediente',
                        'alu_alumno.nombre',
                        'alu_alumno.apellidos',
                        DB::raw('COALESCE(esc_calificacion.calif, esc_calificacion.calificacion) as calificacion'),
                        'esc_calificacion.faltas',
                        'esc_calificacion.curso_id',
                        'esc_calificacion.id as id_calif',
                        'esc_actas.id as id_acta',
                        'esc_actas.estado',
                        'esc_cursos_omitidos.motivo',
                        // Nueva columna: 1 si existe calificacion_tipo_id=2, 0 si no
                        DB::raw('EXISTS (
            SELECT 1 
            FROM esc_calificacion ec2 
            WHERE ec2.alumno_id = alu_alumno.id 
              AND ec2.curso_id = esc_curso.id 
              AND ec2.calificacion_tipo_id = 2
        ) AS calificacion_r_encontrada') // Nombre de la nueva columna
                    )
                    ->orderBy('alu_alumno.apellidos', 'asc')
                    ->get();
                /*
                $this->alumnos_en_curso = CursosModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                    ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                    ->leftJoin('esc_actas', function ($join) {
                        $join->on('esc_grupo_alumno.alumno_id', '=', 'esc_actas.alumno_id')
                            ->where('curso_id', '=', $this->select_curso)
                            //->where('parcial', $this->select_parcial)
                            ->where('esc_actas.estado', '!=', '2')
                            ->whereRaw('esc_actas.id = 
                        (SELECT id 
                        FROM esc_actas 
                        WHERE alumno_id = esc_grupo_alumno.alumno_id 
                        AND curso_id = ? 
                        AND estado != ? 
                        ORDER BY created_at DESC LIMIT 1)', [$this->select_curso, /*$this->select_parcial, '2']);

                    })

                    /* Consulta inicial sin comentarios: "->whereRaw('esc_actas.id = 
                        (SELECT id 
                        FROM esc_actas 
                        WHERE alumno_id = esc_grupo_alumno.alumno_id 
                        AND curso_id = ? 
                        -- AND parcial = ? 
                        AND estado != ? 
                        ORDER BY created_at DESC LIMIT 1)', [$this->select_curso, /*$this->select_parcial, '2']); "
                    ->leftjoin('esc_calificacion', function ($join) {
                        $join->on('esc_calificacion.curso_id', '=', 'esc_curso.id')
                            ->on('esc_calificacion.alumno_id', '=', 'alu_alumno.id')
                            ->where('esc_calificacion.calificacion_tipo', $this->parcial_actual);
                        //->where('esc_calificacion.politica_variable_id', $this->select_parcial);
                    })
                    ->leftjoin('esc_cursos_omitidos', function ($join) {
                        $join->on('esc_cursos_omitidos.curso_id', '=', 'esc_curso.id')
                            ->on('esc_cursos_omitidos.alumno_id', '=', 'alu_alumno.id');
                    })
                    ->where('esc_curso.id', $this->select_curso)
                    ->whereNull('esc_cursos_omitidos.motivo')
                    ->select(
                        'esc_curso.nombre as curso_nombre',
                        'esc_curso.id as curso_id',
                        'alu_alumno.id',
                        'alu_alumno.noexpediente',
                        'alu_alumno.nombre',
                        'alu_alumno.apellidos',
                        DB::raw('COALESCE(esc_calificacion.calif, esc_calificacion.calificacion) as calificacion'),
                        'esc_calificacion.faltas',
                        'esc_calificacion.curso_id',
                        'esc_calificacion.id as id_calif',
                        'esc_actas.id as id_acta',
                        'esc_actas.estado',
                        'esc_cursos_omitidos.motivo'
                    )
                    ->orderBy('alu_alumno.apellidos', 'asc')
                    ->get();*/
                //dd($this->alumnos_en_curso->toSql(), $this->alumnos_en_curso->getBindings());
                //Cambio para filtrar los alumnos que estan en cursos omitidos 15abr24
                //dd($this->alumnos_en_curso);
                if (!empty($this->alumnos_en_curso)) {
                    $this->activa_grupo = true;
                    $this->copia = $this->alumnos_en_curso->toArray();
                    //dd($this->copia);
                    foreach ($this->alumnos_en_curso as $alumno) {
                        $this->alumnoCalificaciones[$alumno->id] = $alumno->calificacion;
                        $this->alumnoFaltas[$alumno->id] = $alumno->faltas;
                    }
                    //dd($this->alumnos_en_curso);
                }
            } else {
                $this->alumnos_en_curso = CursosModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                    ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                    ->leftJoin('esc_actas', function ($join) {
                        $join->on('esc_grupo_alumno.alumno_id', '=', 'esc_actas.alumno_id')
                            ->where('curso_id', '=', $this->select_curso)
                            ->where('parcial', $this->select_parcial)
                            ->where('esc_actas.estado', '!=', '2')
                            ->whereRaw('esc_actas.id = 
                (SELECT id 
                 FROM esc_actas 
                 WHERE alumno_id = esc_grupo_alumno.alumno_id 
                 AND curso_id = ? 
                 AND parcial = ? 
                 AND estado != ? 
                 ORDER BY created_at DESC LIMIT 1)', [$this->select_curso, $this->select_parcial, '2']);
                    })
                    ->leftJoin('esc_calificacion', function ($join) {
                        $join->on('esc_calificacion.curso_id', '=', 'esc_curso.id')
                            ->on('esc_calificacion.alumno_id', '=', 'alu_alumno.id')
                            ->where('esc_calificacion.politica_variable_id', $this->select_parcial);
                    })
                    ->leftJoin('esc_cursos_omitidos', function ($join) {
                        $join->on('esc_cursos_omitidos.curso_id', '=', 'esc_curso.id')
                            ->on('esc_cursos_omitidos.alumno_id', '=', 'alu_alumno.id');
                    })
                    ->where('esc_curso.id', $this->select_curso)
                    ->whereNull('esc_cursos_omitidos.motivo')
                    ->where(function ($query) {
                        $query->where(function ($query) {
                            $query->where('esc_calificacion.calificacion_tipo', '=', 'Final')
                                ->where('esc_calificacion.calificacion', '<', 60);
                        })
                            ->orWhere('esc_calificacion.calif', '=', 'NA');
                    })
                    ->select(
                        'esc_curso.nombre as curso_nombre',
                        'esc_curso.id as curso_id',
                        'alu_alumno.id',
                        'alu_alumno.noexpediente',
                        'alu_alumno.nombre',
                        'alu_alumno.apellidos',
                        'esc_calificacion.calificacion',
                        'esc_calificacion.faltas',
                        'esc_calificacion.curso_id',
                        'esc_calificacion.id as id_calif',
                        'esc_actas.id as id_acta',
                        'esc_actas.estado',
                        'esc_cursos_omitidos.motivo'
                    )
                    ->orderBy('alu_alumno.apellidos', 'asc')
                    ->get();
                //dd($this->alumnos_en_curso->toSql(), $this->alumnos_en_curso->getBindings());
                $this->alumnos_en_curso = CursosModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                    ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                    ->leftJoin('esc_actas', function ($join) {
                        $join->on('esc_grupo_alumno.alumno_id', '=', 'esc_actas.alumno_id')
                            ->where('curso_id', '=', $this->select_curso)
                            ->where('parcial', $this->select_parcial)
                            ->where('esc_actas.estado', '!=', '2')
                            ->whereRaw('esc_actas.id = 
    (SELECT id 
    FROM esc_actas 
    WHERE alumno_id = esc_grupo_alumno.alumno_id 
    AND curso_id = ? 
    AND parcial = ? 
    AND estado != ? 
    ORDER BY created_at DESC LIMIT 1)', [$this->select_curso, $this->select_parcial, '2']);
                    })
                    ->leftjoin('esc_calificacion', function ($join) {
                        $join->on('esc_calificacion.curso_id', '=', 'esc_curso.id')
                            ->on('esc_calificacion.alumno_id', '=', 'alu_alumno.id')
                            ->where('esc_calificacion.politica_variable_id', $this->select_parcial);
                    })
                    ->leftjoin('esc_cursos_omitidos', function ($join) {
                        $join->on('esc_cursos_omitidos.curso_id', '=', 'esc_curso.id')
                            ->on('esc_cursos_omitidos.alumno_id', '=', 'alu_alumno.id');
                    })
                    ->where('esc_curso.id', $this->select_curso)
                    ->whereNull('esc_cursos_omitidos.motivo')
                    ->select(
                        'esc_curso.nombre as curso_nombre',
                        'esc_curso.id as curso_id',
                        'alu_alumno.id',
                        'alu_alumno.noexpediente',
                        'alu_alumno.nombre',
                        'alu_alumno.apellidos',
                        DB::raw('COALESCE(esc_calificacion.calif, esc_calificacion.calificacion) as calificacion'),
                        'esc_calificacion.faltas',
                        'esc_calificacion.id as id_calif',
                        'esc_actas.id as id_acta',
                        'esc_actas.estado',
                        'esc_cursos_omitidos.motivo'
                    )
                    ->orderBy('alu_alumno.apellidos', 'asc')
                    ->get();
                if ($this->parcial_nombre == "R") {
                    //Actas false cuando es R
                    $alumnosConCalificacionBaja = CalificacionesModel::where('curso_id', $this->select_curso)
                        ->where('calificacion_tipo', 'Final')
                        ->where('calificacion', '<', 60)
                        ->pluck('alumno_id')
                        ->toArray();
                    $this->alumnos_en_curso = $this->alumnos_en_curso->filter(function ($alumno) use ($alumnosConCalificacionBaja) {
                        return in_array($alumno->id, $alumnosConCalificacionBaja);
                    })->values();
                }
                //dd($this->alumnos_en_curso->toSql(), $this->alumnos_en_curso->getBindings());
                //Cambio para filtrar los alumnos que estan en cursos omitidos 15abr24
                //dd($this->alumnos_en_curso);
                if (!empty($this->alumnos_en_curso)) {
                    $this->activa_grupo = true;
                    $this->copia = $this->alumnos_en_curso->toArray();
                    //dd($this->copia);
                    foreach ($this->alumnos_en_curso as $alumno) {
                        $this->alumnoCalificaciones[$alumno->id] = $alumno->calificacion;
                        $this->alumnoFaltas[$alumno->id] = $alumno->faltas;
                    }

                }


            }
        }
    }


    public function guardarDatos()
    {
        if (!$this->parcial_activo) {
            return;
        }
        // dd($this->alumnoCalificaciones);
        //Para evitar que se traspasen las calificaciones a otros cursos de alumnos queno tengan inscritos
        DB::beginTransaction();
        try {
            $calificacionesExistentes = CalificacionesModel::where('curso_id', $this->select_curso)
                //->where('politica_variable_id', $this->select_parcial)
                ->where('calificacion_tipo', $this->parcial_actual)
                ->get()
                ->keyBy('alumno_id');
            foreach ($this->copia as $alumno) {
                if ($this->alumnoCalificaciones[$alumno["id"]] === null) {
                    continue;
                }
                try {
                    $calificacionActual = $this->alumnoCalificaciones[$alumno["id"]];
                    $faltasActuales = max(0, $this->alumnoFaltas[$alumno["id"]] ?? 0);

                    // Saltar alumnos sin cambios
                    if ($calificacionActual == $alumno["calificacion"] && $faltasActuales == $alumno["faltas"]) {
                        Log::info("Alumno {$alumno["id"]} omitido: calificación actual: {$calificacionActual}, faltas actuales: {$faltasActuales}");
                        continue;
                    }

                    $datosCalificacion = $this->obtenerDatosCalificacion($alumno);

                    // Insertar o actualizar según corresponda
                    if ($alumno["id_calif"] == null) {
                        $valida_alumno_curso_calif = $calificacionesExistentes->get($alumno["id"]);
                        //dd($valida_alumno_curso_calif);
                        if ($valida_alumno_curso_calif) {
                            // Crear bitácora si se detecta duplicado (opcional)
                            $this->guardarOActualizarCalificacion($datosCalificacion, $alumno["id_calif"]);
                            continue;
                        } else {
                            $calififacion_update = CalificacionesModel::create($datosCalificacion);
                            $this->registrarBitacora("Creación de calificación del curso {$this->select_curso} para el alumno {$alumno["id"]} con la calificacion : " . $calififacion_update->calificacion);
                        }
                    } else {
                        $this->guardarOActualizarCalificacion($datosCalificacion, $alumno["id_calif"]);
                        $this->registrarBitacora("Actualización de calificación del curso {$this->select_curso} para el alumno {$alumno["id"]}: Calificacion anterior: " . $calificacionesExistentes->get($alumno["id"])?->calificacion);
                    }
                } catch (\Exception $e) {
                    Log::error("Error al guardar calificación para el alumno {$alumno["id"]}: " . $e->getMessage());
                }

            }
            DB::commit();

            if ($this->guardadoPorTiempo) {
                $this->guardadoPorTiempo = false;
                $this->emit('calificacionesGuardadas');
            } else {
                //Cuando no son por tiempo
                $this->emit('guardadas_correctamente');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar calificaciones: ' . $e->getMessage());
            // Podrías emitir un evento para notificar el error en la UI
            $this->emit('errorGuardando', $e->getMessage());
        }




    }


    public function guardarDatosPorTiempo()
    {
        $this->guardadoPorTiempo = true;
        $this->guardarDatos();
    }

    public function solicitudActa($alumno_id, $nueva_calificacion, $nuevas_faltas, $motivos, $calificacion_id)
    {
        if (!empty($motivos)) {
        } else {
            $motivos = "Sin motivo";
        }
        //$this->parciales
        $datos_curso = CursosModel::find($this->select_curso);
        if ($calificacion_id == "") {

            $guardar_acta = ActasModel::create([
                'alumno_id' => $alumno_id,
                'calificacion_id' => "0",
                'docente_id' => Auth()->user()->id,
                'grupo_id' => $datos_curso->grupo_id,
                'curso_id' => $datos_curso->id,
                'parcial' => $this->select_parcial,
                'nueva_calif' => $nueva_calificacion,
                'nueva_falta' => $nuevas_faltas,
                'motivo' => $motivos,
                'tipo_acta' => 1
            ]);

            if (!empty($guardar_acta->id)) {
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller' => 'Actas especiales',
                    //'component'     =>  'FormComponent',
                    'function' => 'Se creo un acta especial',
                    'description' => 'Se creo un acta con el id: ' . $guardar_acta->id . ' Para una nueva calificación',
                ]);
                $this->emit('solicitudActaCompleta', $guardar_acta ? 1 : 0);

            } else {
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller' => 'Actas especiales',
                    //'component'     =>  'FormComponent',
                    'function' => 'Se intento crear un acta especial',
                    'description' => 'Problemas al intentar crear acta especial en el curso: ' .
                        $datos_curso->id . 'Del alumno ID: ' . $alumno_id,
                ]);
                $this->emit('solicitudActaCompleta', $guardar_acta ? 0 : 0);

            }

        } else {
            $guardar_acta = ActasModel::create([
                'alumno_id' => $alumno_id,
                'calificacion_id' => $calificacion_id,
                'docente_id' => Auth()->user()->id,
                'grupo_id' => $datos_curso->grupo_id,
                'curso_id' => $datos_curso->id,
                'parcial' => $this->select_parcial,
                'nueva_calif' => $nueva_calificacion,
                'nueva_falta' => $nuevas_faltas,
                'motivo' => $motivos,
                'tipo_acta' => 1
            ]);

            if (!empty($guardar_acta->id)) {
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller' => 'Actas especiales',
                    //'component'     =>  'FormComponent',
                    'function' => 'Se creo un acta especial',
                    'description' => 'Se creo un acta con el id: ' . $guardar_acta->id . ' Para una nueva calificación',
                ]);
                $this->emit('solicitudActaCompleta', $guardar_acta ? 1 : 0);

            } else {
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller' => 'Actas especiales',
                    //'component'     =>  'FormComponent',
                    'function' => 'Se intento crear un acta especial',
                    'description' => 'Problemas al intentar crear acta especial en el curso: ' .
                        $datos_curso->id . 'Del alumno ID: ' . $alumno_id,
                ]);
                $this->emit('solicitudActaCompleta', $guardar_acta ? 0 : 0);
            }

        }


    }
    public function ver_calificaciones()
    {
        //dd($this->es_numerico, $this->parcial_nombre);
        dd($this->alumnoCalificaciones, $this->parcial_actual, $this->select_curso);
    }

    private function guardarOActualizarCalificacion($datos, $idCalificacion = null)
    {
        $calificacion_busqueda = CalificacionesModel::where('curso_id', $datos['curso_id'])
            ->where('alumno_id', $datos['alumno_id'])
            ->where('calificacion_tipo', $this->parcial_actual)
            ->first();
        if ($this->parcial_actual == "R") {
            $datos['calificacion_tipo_id'] = "2";
        }
        if ($calificacion_busqueda) {
            $calificacion_busqueda->update($datos);
        } else {
            $calificacion_busqueda = CalificacionesModel::create($datos);
        }

        if ($this->parcial_actual != "R") {
            DB::select('CALL actualizar_o_insertar_calificacion_final(?, ?, ?)', [$datos["alumno_id"], $this->select_curso, $this->politica_variable_final]);
        }


    }

    private function obtenerDatosCalificacion($alumno)
    {

        if ($this->es_numerico == false && $this->parcial_nombre == "F") {

            return [
                'alumno_id' => $alumno["id"],
                'calificacion' => null,
                'calif' => $this->alumnoCalificaciones[$alumno["id"]],
                'politica_variable_id' => $this->select_parcial,
                'calificacion_tipo_id' => $this->parcial_nombre == "F" ? '1' : ($this->parcial_nombre == "R" ? '2' : '0'),
                'curso_id' => $this->select_curso,
                'faltas' => max(0, $this->alumnoFaltas[$alumno["id"]] ?? 0),
                'calificacion_tipo' => $this->parcial_nombre == "F" ? "Final" : $this->parcial_nombre,
                'created_at' => now(),
            ];

        } else {


            return [
                'alumno_id' => $alumno["id"],
                'calificacion' => max(0, $this->es_numerico ? $this->alumnoCalificaciones[$alumno["id"]] : null ?? 0),
                'calif' => null,
                'politica_variable_id' => $this->select_parcial,
                'calificacion_tipo_id' => $this->parcial_nombre == "F" ? '1' : ($this->parcial_nombre == "R" ? '2' : '0'),
                'curso_id' => $this->select_curso,
                'faltas' => max(0, $this->alumnoFaltas[$alumno["id"]] ?? 0),
                'calificacion_tipo' => $this->parcial_nombre == "F" ? "Final" : $this->parcial_nombre,
                'created_at' => now(),
            ];
        }

    }

    private function registrarBitacora($descripcion, $controller = 'Calificaciones', $function = 'Guardar')
    {
        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => request()->ip(),
            'path' => request()->getRequestUri(),
            'method' => request()->method(),
            'controller' => $controller,
            'function' => $function,
            'description' => $descripcion,
        ]);
    }
    #region Eventos
    public function handleCambioSelect()
    {
        if (!empty($this->alumnos_en_curso)) {
            $this->message = "Favor de realizar la búsqueda nuevamente";
            // Reiniciamos el array de alumnos en curso
            $this->alumnos_en_curso = [];
            $this->activa_grupo = false;

            // Solo reiniciamos el parcial si el curso cambió
            if ($this->cursoChanged) {
                $this->select_parcial = null;
                $this->parcial_nombre = null;
                $this->cursoChanged = false; // Reseteamos la bandera
                $this->alumnoCalificaciones = []; // Reiniciamos el array de calificaciones
                $this->alumnoFaltas = []; //Falto reiniciar el array de faltas
            }

            $this->emit('actualizarEstadoBoton', false);
        }
    }

    public function actualizarCalificaciones()
    {
        //dd($this->alumnoCalificaciones); // Muestra los valores actuales y detiene la ejecución
        dd($this->alumnoFaltas);
    }

    public function updatedSelectCurso()
    {
        $this->cursoChanged = true;
        // Aquí puedes, si es necesario, cargar los parciales correspondientes.
    }



}
#endregion

function buscar_excepciones($docente, $curso_id, $parcial)
{
    $buscar_curso = CursosModel::where('id', $curso_id)->first();

    if (
        stripos($buscar_curso->nombre, "SERVICIO SOCIAL") !== false ||
        stripos($buscar_curso->nombre, "PRACTICAS PROFESIONALES") !== false
    ) {
        $buscar_excepciones = AperturaModel::join('esc_curso', 'cat_docentes_apertura.grupo_id', '=', 'esc_curso.grupo_id')
            ->join('cat_ciclos_esc', 'cat_ciclos_esc.id', '=', 'cat_docentes_apertura.ciclos_esc_id')
            ->where('cat_docentes_apertura.emp_perfil_id', '=', $docente)
            ->where('esc_curso.id', '=', $curso_id)
            ->where(function($query) {
                $query->where('parcial', 'P3')
                      ->orWhere('parcial', 'Final');
            })
            ->where('activado', '1')
            ->where('nuevo_cierre', '>=', now())
            ->first();
    } else {
        $buscar_excepciones = AperturaModel::join('esc_curso', 'cat_docentes_apertura.grupo_id', '=', 'esc_curso.grupo_id')
            ->join('cat_ciclos_esc', 'cat_ciclos_esc.id', '=', 'cat_docentes_apertura.ciclos_esc_id')
            ->where('cat_docentes_apertura.emp_perfil_id', '=', $docente)
            ->where('esc_curso.id', '=', $curso_id)
            ->where('parcial', $parcial)
            ->where('activado', '1')
            //->where('cat_ciclos_esc.activo', '1')
            ->where('nuevo_cierre', '>=', now())
            ->first();
    }


    return $buscar_excepciones;
}
