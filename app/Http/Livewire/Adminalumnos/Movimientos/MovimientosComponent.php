<?php

namespace App\Http\Livewire\Adminalumnos\Movimientos;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use App\Traits\BitacoraTrait;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Clase MovimientosComponent
 *
 * Esta clase se encarga de manejar los movimientos de los alumnos en el sistema.
 */
class MovimientosComponent extends Component
{

    /* Traits */

    use BitacoraTrait;

    /* Propiedades */

    /**
     * @var string $path
     * Ruta actual.
     */
    public string $path = '';

    /**
     * @var int $ciclo_activo
     * Ciclo activo.
     */
    public array $ciclo_activo = [];

    /**
     * @var int $ciclo_ultimo
     * Último ciclo.
     */
    public array $ciclo_ultimo = [];

    /**
     * @var int $alumno_id
     * ID del alumno.
     */
    public ?int $alumno_id = null;

    /**
     * @var array $alumno
     * Información del alumno.
     */
    public array $alumno = [];

    /**
     * @var bool $alumno_inscrito Indica si el alumno está inscrito o no.
     */
    public bool $alumno_inscrito = false;

    /**
     * @var array $alumno_imagen
     * Imagen del alumno.
     */
    public array $alumno_imagen = [];

    /**
     * @var array $plantel_origen
     * Información del plantel de origen.
     */
    public array $plantel_origen = [];

    /**
     * @var array $planteles_destino
     * Información de los planteles de destino.
     */
    public array $planteles_destino = [];

    /**
     * @var int $plantel_destino_selected
     * ID del plantel de destino seleccionado.
     */
    public ?int $plantel_destino_selected = null;

    /**
     * @var array $grupos_historicos
     * Información de los grupos históricos.
     */
    public array $grupos_historicos = [];

    /**
     * @var array $grupos_origen
     * Información de los grupos de origen.
     */
    public array $grupos_origen = [];

    /**
     * @var array $grupos_destino
     * Información de los grupos de destino.
     */
    public array $grupos_destino = [];

    /**
     * @var array $grupos_destino_selected
     * IDs de los grupos de destino seleccionados.
     */
    public array $grupos_destino_selected = [];

    /**
     * @var array $cursos_destino
     * Información de los cursos de destino.
     */
    public array $cursos_destino = [];

    /**
     * @var array $cursos_destino_selected
     * IDs de los cursos de destino seleccionados.
     */
    public array $cursos_destino_selected = [];

    /**
     * @var array $cursos_origen
     * Variable que almacena un array de asignaturas de origen.
     */
    public array $cursos_origen = [];

    /**
     * @var array $asignaturas_destino_claves
     * Claves de asignatura de los cursos de destino seleccionados.
     */
    public array $asignaturas_destino_claves = [];

    /**
     * @var string $periodo_actual
     * Periodo actual.
     */
    public ?int $periodo_actual = null;

    /**
     * Clase MovimientosComponent
     *
     * Esta clase es responsable de manejar los movimientos de los alumnos en el componente de administración de alumnos.
     * Contiene un arreglo de listeners que escuchan el evento 'refreshComponent' y ejecutan la acción '$refresh'.
     */
    //protected $listeners = ['refreshComponent' => 'handleRefreshComponent'];
    //protected $listeners = ['refreshComponent' => '$refresh'];

    /* Métodos Primarios */

    /**
     * Método render
     *
     * Este método se encarga de renderizar la vista del componente.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.adminalumnos.movimientos.movimientos-component');
    }

    /**
     * Método mount
     *
     * Este método se ejecuta cuando se monta el componente.
     *
     * @param int $alumno_id
     * ID del alumno.
     */
    public function mount(int $alumno_id): void
    {
        $this->path = request()->path();
        $this->initializeComponent($alumno_id);
    }

    /**
     * Método handleRefreshComponent
     *
     * Este método se encarga de refrescar el componente.
     */
    public function handleRefreshComponent()
    {
        $this->refresh();
    }


    /**
     * Inicializa el componente MovimientosComponent.
     *
     * @param int $alumno_id El ID del alumno.
     * @return void
     */
    protected function initializeComponent(int $alumno_id)
    {

        /* Ciclos */
        $this->ciclo_activo = $this->getCicloActivo();

        /* Alumno */
        $this->alumno_id = $alumno_id;
        $this->alumno = $this->getAlumno();

        /* Destino */
        $this->planteles_destino = $this->getPlantelesDestino();
        $this->plantel_destino_selected = 0;
        $this->grupos_destino = [];
        $this->grupos_destino_selected = [];
        $this->cursos_destino = [];
        $this->cursos_destino_selected = [];
        $this->asignaturas_destino_claves = [];

        $this->initializeSelectedProperties();
    }

    /**
     * Método initializeSelectedProperties
     *
     * Este método se encarga de inicializar las propiedades seleccionadas.
     */
    public function initializeSelectedProperties()
    {
        foreach ($this->cursos_origen as $curso_origen) {
            $this->cursos_destino_selected[$curso_origen['id']] = null;
            $this->asignaturas_destino_claves[$curso_origen['id']] = null;
        }
    }

    /* Eventos */

    /**
     * Evento que se dispara cuando se actualiza el plantel de destino seleccionado.
     *
     * @param int $plantel_destino_selected
     * ID del plantel de destino seleccionado.
     */
    public function updatedPlantelDestinoSelected(int $plantel_destino_selected): void
    {
        // Obtiene los grupos de destino basados en el plantel de destino seleccionado.
        $this->grupos_destino = $this->getGruposDestino($plantel_destino_selected);
    }

    /**
     * Evento que se dispara cuando se actualizan los grupos de destino seleccionados.
     *
     * @param array $grupos_destino_selected
     * IDs de los grupos de destino seleccionados.
     */
    public function updatedGruposDestinoSelected(array $grupos_destino_selected): void
    {

        // Inicializa el array de cursos de destino, que es el total de los cursos de todos los grupos seleccionados
        $this->cursos_destino = [];
        // Obtiene los cursos de destino para cada grupo de destino seleccionado y los agrega al array de cursos de destino.
        foreach ($grupos_destino_selected as $grupo_id) {
            $cursos = $this->getCursosDestino($grupo_id);
            $this->cursos_destino = $this->cursos_destino + $cursos;
        }
        // Asigna las claves de asignatura y cursos de destino seleccionados basándose en la coincidencia de las claves de asignatura.
        foreach ($this->cursos_origen as $curso_origen) {
            foreach ($this->cursos_destino as $curso_destino) {
                if ($curso_origen['asignatura']['clave'] == $curso_destino['asignatura']['clave']) {
                    $this->cursos_destino_selected[$curso_origen['id']] = (int) $curso_destino['id'];
                    $this->asignaturas_destino_claves[$curso_origen['id']] = (int) $curso_destino['asignatura']['clave'];
                    break;
                }
            }
        }
        $this->emit('$refresh');
    }

    /**
     * Evento que se dispara cuando se actualiza el curso de destino seleccionado mediante el <select correspondiente.
     *
     * @param int $curso_destino_id
     * ID del curso de destino seleccionado.
     *
     * @param int $curso_origen_id
     * ID del curso de origen correspondiente.
     *
     * @example public function updatedCursosDestinoSelected($value, $key) { }
     */
    public function updatedCursosDestinoSelected($curso_destino_selected_id, $curso_origen_id): void
    {
        // Asigna el curso de destino seleccionado al curso de origen correspondiente.
        $this->cursos_destino_selected[$curso_origen_id] = intval($curso_destino_selected_id);
        $this->asignaturas_destino_claves[$curso_origen_id] = $this->getAsignaturaDestinoClave($curso_destino_selected_id);
        $this->emit('$refresh');
    }

    /* Methods */

    /**
     * Obtiene el ciclo activo.
     *
     * @return array
     * Ciclo activo.
     */
    public function getCicloActivo(): array
    {
        $ciclo_activo = CicloEscModel::select('id', 'nombre', 'activo', 'abreviatura', 'per_inicio', 'per_final')
            ->where('activo', 1)
            ->first();
        return $ciclo_activo ? $ciclo_activo->toArray() : [];
    }

    /**
     * Obtiene el último ciclo.
     *
     * @return array
     * Último ciclo.
     */
    public function getCicloUltimo(AlumnoModel $alumno): array
    {
        $ciclo_ultimo = $alumno->grupos()
            ->with([
                'ciclo' => function ($query) {
                    $query->select('id', 'nombre', 'activo', 'abreviatura', 'per_inicio', 'per_final');
                }
            ])
            ->orderBy('ciclo_esc_id', 'desc')
            ->first();
        return $ciclo_ultimo && $ciclo_ultimo->ciclo ? $ciclo_ultimo->ciclo->toArray() : [];
    }

    /**
     * Obtiene la información del alumno.
     *
     * @return array|null
     * Información del alumno.
     */
    public function getAlumno(): array
    {
        $alumno = AlumnoModel::select('id', 'nombre', 'apellidos', 'noexpediente', 'correo_institucional', 'telefono', 'celular', 'id_estatus')
            ->findOrFail($this->alumno_id);

        /* Ciclo */
        $this->ciclo_ultimo = $this->getCicloUltimo($alumno);

        /* Alumno */
        $this->alumno_inscrito = $this->getAlumnoInscrito($alumno);
        $this->alumno_imagen = $this->getAlumnoImagen($alumno);
        $this->grupos_historicos = $this->getGruposHistoricos($alumno);

        /* Origen */
        $this->plantel_origen = $this->getPlantelOrigen($alumno);
        $this->grupos_origen = $this->getGruposOrigen($alumno);
        $this->cursos_origen = $this->getCursosOrigen($alumno);
        $this->periodo_actual = $this->getPeriodoActual();

        return $alumno ? $alumno->toArray() : [];
    }

    /**
     * Obtiene el periodo actual.
     *
     * @return int
     * Periodo actual.
     */
    public function getPeriodoActual(): ?int
    {
        $primer_curso = collect($this->cursos_origen)->first();
        return $primer_curso ? $primer_curso['asignatura']['periodo'] : null;
    }

    /**
     * Obtiene si el alumno está inscrito o no.
     *
     * @param AlumnoModel $alumno
     * Modelo del alumno.
     *
     * @return bool
     * Indica si el alumno está inscrito o no.
     */
    public function getAlumnoInscrito(AlumnoModel $alumno): bool
    {
        $alumno_inscrito = $alumno->grupos()
            ->where('ciclo_esc_id', $this->ciclo_activo['id'])
            ->exists();
        if (!$alumno_inscrito) {
            session()->flash('message', 'No se puede realizar traslado en alumno no inscrito');
            session()->flash('alert-type', 'info');
        }
        return $alumno_inscrito;
    }

    /**
     * Obtiene la imagen del alumno.
     *
     * @param AlumnoModel $alumno
     * Modelo del alumno.
     *
     * @return array
     * Imagen del alumno.
     */
    public function getAlumnoImagen(AlumnoModel $alumno): array
    {
        $alumno_imagen = $alumno->imagenes()
            ->where('alumno_id', $this->alumno_id)
            ->where('tipo', 1)
            ->first();
        if (!$alumno_imagen) {
            $alumno_imagen = ImagenesalumnoModel::findorFail(1);
        }
        $alumno_imagen->imagen = base64_encode($alumno_imagen->imagen);
        return $alumno_imagen->toArray();
    }

    /**
     * Obtiene los grupos históricos del alumno.
     *
     * @param AlumnoModel $alumno
     * Modelo del alumno.
     *
     * @return array|null
     * Grupos históricos del alumno.
     */
    public function getGruposHistoricos(AlumnoModel $alumno): array
    {
        $grupos_historicos = $alumno->grupos()
            ->with([
                'ciclo' => function ($query) {
                    $query->select('id', 'nombre', 'activo', 'abreviatura', 'per_inicio', 'per_final');
                },
                'plantel' => function ($query) {
                    $query->select('id', 'nombre', 'abreviatura');
                },
                'turno' => function ($query) {
                    $query->select('id', 'nombre', 'abreviatura');
                },
            ])
            ->withCount('cursos')
            ->get()
            ->sortByDesc(function ($grupo) { // ordena los grupos por el periodo de inicio del ciclo
                return $grupo->ciclo->per_inicio;
            })
            ->values(); // restablece los índices de la colección
        return $grupos_historicos ? $grupos_historicos->toArray() : [];
    }

    /**
     * Obtiene el plantel de origen del alumno.
     *
     * @param AlumnoModel $alumno
     * Modelo del alumno.
     *
     * @return array|null
     * Plantel de origen del alumno.
     */
    public function getPlantelOrigen(AlumnoModel $alumno): array
    {
        $plantel_origen = $alumno->grupos()
            ->where('ciclo_esc_id', $this->ciclo_ultimo['id'])
            ->with([
                'plantel' => function ($query) {
                    $query->select('id', 'nombre', 'abreviatura');
                }
            ])
            ->first();
        return $plantel_origen && $plantel_origen->plantel ? $plantel_origen->plantel->toArray() : [];
    }

    /**
     * Obtiene los planteles de destino.
     *
     * @return array|null
     * Planteles de destino.
     */
    public function getPlantelesDestino(): array
    {
        $planteles_destino = PlantelesModel::select('id', 'nombre', 'abreviatura')
            ->get();
        return $planteles_destino ? $planteles_destino->toArray() : [];
    }

    /**
     * Obtiene los grupos de origen del alumno.
     *
     * @param AlumnoModel $alumno
     * Modelo del alumno.
     *
     * @return array|null
     * Grupos de origen del alumno.
     */
    public function getGruposOrigen(AlumnoModel $alumno): array
    {
        $grupos_origen = $alumno->grupos()
            ->where('ciclo_esc_id', $this->ciclo_activo['id'])
            ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
            ->with([
                'ciclo' => function ($query) {
                    $query->select('id', 'nombre', 'activo', 'abreviatura', 'per_inicio', 'per_final');
                },
                'plantel' => function ($query) {
                    $query->select('id', 'nombre', 'abreviatura');
                },
                'turno' => function ($query) {
                    $query->select('id', 'nombre', 'abreviatura');
                },
            ])
            ->get();
        return $grupos_origen ? $grupos_origen->toArray() : [];
    }

    /**
     * Obtiene los grupos de destino basados en el ID del plantel.
     *
     * @param int $plantel_id
     * ID del plantel.
     *
     * @return array|null
     * Grupos de destino.
     */
    public function getGruposDestino(int $plantel_id): array
    {
        $grupos = GruposModel::select('id', 'nombre', 'turno_id', 'gpo_base', 'ciclo_esc_id', 'plantel_id')
            ->where('plantel_id', $plantel_id)
            ->where('ciclo_esc_id', $this->ciclo_activo['id'])
            ->where('esc_grupo.plantel_id', '!=', '34')
            ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
            ->whereHas('cursos.asignatura', function ($query) {
                $query->where('periodo', $this->periodo_actual);
            })
            ->get();

        return $grupos ? $grupos->toArray() : [];
    }

    /**
     * Obtiene los cursos de origen del alumno.
     *
     * @param AlumnoModel $alumno
     * Modelo del alumno.
     *
     * @return array|null
     * Cursos de origen del alumno.
     */

    public function getCursosOrigen(AlumnoModel $alumno): array
    {
        /* Con validación de cursos omitidos */
        $cursos_origen = $alumno->grupos()
            ->where('ciclo_esc_id', $this->ciclo_activo['id'])
            ->with([
                'cursos' => function ($query) use ($alumno) {
                    $query->select('esc_curso.id', 'grupo_id', 'asignatura_id')
                        // Left Join con esc_grupo para obtener la relación con los grupos
                        ->leftJoin('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                        // Excluir los grupos donde el nombre sea igual a 'actasExtemporanea'
                        ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                        ->where('esc_grupo.plantel_id', '!=', '34')
                        // Left Join con esc_cursos_omitidos para filtrar cursos omitidos
                        ->leftJoin('esc_cursos_omitidos', function ($join) use ($alumno) {
                            $join->on('esc_cursos_omitidos.curso_id', '=', 'esc_curso.id')
                                ->where('esc_cursos_omitidos.alumno_id', '=', $alumno->id);
                        })
                        // Excluir los resultados que tengan coincidencias en esc_cursos_omitidos
                        ->whereNull('esc_cursos_omitidos.curso_id') // Asegúrate de que no haya coincidencia en la tabla omitidos
                        ->with([
                            'asignatura' => function ($query) {
                                $query->select('id', 'clave', 'nombre', 'periodo');
                            },
                            'calificaciones' => function ($query) use ($alumno) {
                                $query->select('id', 'curso_id', 'politica_variable_id', 'calificacion_tipo', 'calificacion', 'calif')
                                    ->where('alumno_id', $alumno->id);
                            },
                        ]);
                },
            ])
            ->get()
            ->pluck('cursos')
            ->flatten()
            ->mapWithKeys(function ($curso) {
                $curso = $curso->toArray();
                $curso['calificaciones'] = collect($curso['calificaciones'])
                    ->mapWithKeys(function ($calificacion) {
                        return [strtoupper($calificacion['calificacion_tipo']) => $calificacion];
                    })
                    ->toArray();
                return [$curso['id'] => $curso];
            })
            ->sortBy('asignatura.clave');
        /*
        Sin cursos omitidos, asi estaba original:

                $cursos_origen = $alumno->grupos()
                ->where( 'ciclo_esc_id', $this->ciclo_activo['id'] )
                ->with( [
                    'cursos' => function ( $query ) {
                        $query->select( 'id', 'grupo_id', 'asignatura_id' )
                            ->with( [
                                'asignatura' => function ( $query ) {
                                    $query->select( 'id', 'clave', 'nombre', 'periodo' );
                                },
                                'calificaciones' => function ( $query ) {
                                    $query->select( 'id', 'curso_id', 'politica_variable_id', 'calificacion_tipo', 'calificacion', 'calif' )
                                        ->where( 'alumno_id', $this->alumno_id );
                                },
                            ] );
                    },
                ] )
                ->get()
                ->pluck( 'cursos' )
                ->flatten()
                ->mapWithKeys( function ( $curso ) {
                    $curso = $curso->toArray();
                    $curso['calificaciones'] = collect( $curso['calificaciones'] )
                        ->mapWithKeys( function ( $calificacion ) {
                            return [strtoupper( $calificacion['calificacion_tipo'] ) => $calificacion];
                        } )
                        ->toArray();
                    return [$curso['id'] => $curso];
                } )
                ->sortBy( 'asignatura.clave' );
        */
        return $cursos_origen ? $cursos_origen->toArray() : [];
    }

    /**
     * Obtiene los cursos de destino basados en el ID del grupo.
     *
     * @param int $grupo_id
     * ID del grupo.
     *
     * @return array|null
     * Cursos de destino.
     */
    public function getCursosDestino(int $grupo_id): array
    {
        $cursos_destino = GruposModel::find($grupo_id)
            ->cursos()
            ->select('id', 'grupo_id', 'asignatura_id')
            ->with([
                'asignatura' => function ($query) {
                    $query->select('id', 'clave', 'nombre', 'periodo');
                },
            ])
            ->get()
            ->flatten()
            ->mapWithKeys(function ($curso) {
                return [$curso['id'] => $curso];
            })
            ->sortBy('asignatura.clave');
        return $cursos_destino ? $cursos_destino->toArray() : [];
    }

    /**
     * Obtiene el ID del grupo de destino basado en el ID del curso de destino.
     *
     * @param int $curso_destino_id
     * ID del curso de destino.
     *
     * @return int
     * ID del grupo de destino.
     */
    public function getGrupoDestinoId(int $curso_destino_id): ?int
    {
        $curso_destino = CursosModel::find($curso_destino_id);
        return $curso_destino ? $curso_destino->grupo_id : null;
    }

    /**
     * Obtiene la clave de asignatura del curso de destino basado en el ID del curso de destino.
     *
     * @param int $curso_destino_id
     * ID del curso de destino.
     *
     * @return string
     * Clave de asignatura del curso de destino.
     */
    public function getAsignaturaDestinoClave($curso_destino_selected_id): ?int
    {
        $curso_destino = CursosModel::find($curso_destino_selected_id);

        if ($curso_destino) {
            $curso_destino->load([
                'asignatura' => function ($query) {
                    $query->select('id', 'clave', 'nombre', 'periodo');
                }
            ]);
        }

        return $curso_destino && $curso_destino->asignatura ? intval($curso_destino->asignatura->clave) : null;
    }

    /**
     * Método trasladar
     *
     * Este método se encarga de realizar el traslado de los alumnos.
     */
    public function trasladar()
    {

        // Validar los campos
        $this->validate([
            'plantel_destino_selected' => 'required|integer',
            'grupos_destino_selected' => 'required|array',
            'cursos_destino_selected' => 'required|array',
        ]);

        $errorOccurred = false;

        // Verificar si el alumno está inscrito
        if (!$this->alumno_inscrito) {
            session()->flash('message', 'No se puede realizar traslado en alumno no inscrito.');
            session()->flash('alert-type', 'warning');
            return;
        }

        // Verificar que todos los valores en $this->cursos_destino_selected no sean null, vacíos o 0 (cero).
        if (in_array(null, $this->cursos_destino_selected) || in_array('', $this->cursos_destino_selected) || in_array(0, $this->cursos_destino_selected)) {
            session()->flash('message', 'Todos los cursos deben ser seleccionados antes de trasladar.');
            session()->flash('alert-type', 'warning');
            return;
        }

        //dd($this->cursos_destino_selected);
        /*
        $cursos_totales = collect();
        foreach ($this->cursos_destino_selected as $aux => $prueba) {
            $curso_base = CursosModel::find($prueba);
            $cursos_del_grupo_destino = GruposModel::join('esc_curso', 'esc_grupo.id','=', 'esc_curso.grupo_id')
            ->where('grupo_id', $curso_base->grupo_id)
            ->get();
            //dd($prueba, $aux,$cursos_del_grupo_destino, $this->cursos_destino_selected);  # code...
            //$grupo_id = $this->getGrupoDestinoId( $curso_destino_selected_id );
            
        }*/
        // Agregar las relaciones grupo_id y alumno_id en la tabla esc_grupo_alumno para los grupos destino seleccionados que deben obtenerse de sus respectivos cursos destino seleccionados en $this->cursos_destino_selected
        foreach ($this->cursos_destino_selected as $curso_origen_id => $curso_destino_selected_id) {
            try {
                $updatedRows = CalificacionesModel::where('curso_id', $curso_origen_id)
                    ->where('alumno_id', $this->alumno_id)
                    ->update(['curso_id' => $curso_destino_selected_id]);
                /* if ( $updatedRows == 0 ) {
                    $this->dispatchBrowserEvent( 'logToConsole', ['message' => 'Curso sin actualización de calificaciones', 'data' => $curso_origen_id] );
                } */
            } catch (\Exception $e) {
                session()->flash('message', 'Error al actualizar: ' . $e->getMessage());
                session()->flash('alert-type', 'danger');
                $errorOccurred = true;
                return;
            }
        }
        $guardar_grupo_id = collect();
        // Agregar las relaciones grupo_id y alumno_id en la tabla esc_grupo_alumno para los grupos destino seleccionados que deben obtenerse de sus respectivos cursos destino seleccionados en $this->cursos_destino_selected
        foreach ($this->cursos_destino_selected as $curso_origen_id => $curso_destino_selected_id) {
            $grupo_id = $this->getGrupoDestinoId($curso_destino_selected_id);
            // Verificar si ya existe un registro con el mismo grupo_id y alumno_id
            $exists = GrupoAlumnoModel::where('grupo_id', $grupo_id)
                ->where('alumno_id', $this->alumno_id)
                ->exists();
            // Si no existe, insertar un nuevo registro
            if (!$exists) {
                try {
                    $newRecord = GrupoAlumnoModel::create([
                        'grupo_id' => $grupo_id,
                        'alumno_id' => $this->alumno_id,
                    ]);
                    if ($newRecord == null) {
                        throw new \Exception("No se pudo crear el registro con grupo_id: $grupo_id y alumno_id: $this->alumno_id");
                        //$errorOccurred = true;
                    }
                } catch (\Exception $e) {
                    session()->flash('message', 'Error al crear: ' . $e->getMessage());
                    session()->flash('alert-type', 'danger');
                    $errorOccurred = true;
                    return;
                }
            } else {
                if (!$guardar_grupo_id->contains($grupo_id)) {
                    $guardar_grupo_id->push($grupo_id);
                }
            }

        }

        $grupo_ids_a_conservar = $guardar_grupo_id->toArray(); // Convertir a array para usar en whereNotIn

        // Eliminar las relaciones en la tabla esc_grupo_alumno para los grupos origen
        foreach ($this->grupos_origen as $grupo) {
            $grupo_id = $grupo['id'];
            try {

                $deletedRows = GrupoAlumnoModel::where('grupo_id', $grupo_id)
                    ->whereNotIn('grupo_id', $grupo_ids_a_conservar)
                    ->where('alumno_id', $this->alumno_id)
                    ->delete();
                if ($deletedRows == 0) {
                    // Verificar si hay registros que sí deberían ser eliminados (es decir, que no están en $guardar_grupo_id)
                    $existingRows = GrupoAlumnoModel::where('alumno_id', $this->alumno_id)
                        ->whereNotIn('grupo_id', $grupo_ids_a_conservar)
                        ->count();

                    // Solo lanzar la excepción si no hay registros existentes para eliminar
                    if ($existingRows > 0) {
                        throw new \Exception("No se pudo eliminar el registro con grupo_id: $grupo_id y alumno_id: $this->alumno_id");
                    }
                }
            } catch (\Exception $e) {
                session()->flash('message', 'Error al eliminar: ' . $e->getMessage());
                session()->flash('alert-type', 'danger');
                $errorOccurred = true;
                return;
            }
        }

        // Mostrar mensaje de éxito
        if (!$errorOccurred) {
            session()->flash('message', 'El alumno ha sido trasladado con éxito.');
            session()->flash('alert-type', 'success');
            $this->createBitacoraEntry(__FUNCTION__, 'Traslado exitoso de alumno: ' . $this->alumno_id);
            $this->initializeComponent($this->alumno_id);
        }
    }
}
