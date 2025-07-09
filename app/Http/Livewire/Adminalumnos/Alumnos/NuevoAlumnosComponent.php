<?php

namespace App\Http\Livewire\Adminalumnos\Alumnos;

use App\Exports\nuevos_alumnos;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;



use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;

class NuevoAlumnosComponent extends Component
{
    use WithFileUploads;

    public $alumnos_nuevos_plantel, $plantel_seleccionado, $administrador,
    $alumno_seleccionado, $grupos_plantel, $descargando, $alumnos_en_plantel = [], $errores,
    $capacidad_turno_1, $capacidad_turno_2, $ya_asignado, $capacidades_grupos, $busca_grupos,
    $fecha;

    public $file;
    protected $listeners = [
        'saveFile',
        'nuevos_alumnos_admin',
        'guardar_grupo_especial',
        'guardar_cambio_plantel',
        'cambio_capacidad',
        'emitir_busqueda',
        'guardar_estatus'
    ];

    public function render()
    {
        return view('livewire.adminalumnos.alumnos.nuevo-alumnos-component');
    }

    public function updatedPlantelSeleccionado($value)
    {
        $this->alumnos_nuevos_plantel = null;
    }

    public function mount()
    {
        // Inicialización de variables
        $this->periodos = collect();
        $this->grupos = collect();

        $this->fecha = true;

        $this->ciclo_activo = CicloEscModel::where('activo', '1')->orderBy('per_inicio', 'desc')->first();
        $roles = auth()->user()->getRoleNames()->toArray();
        foreach ($roles as $role) {
            if ($role === "control_escolar") {
                $todos_los_valores = 1;
                break;
            } elseif ($role === "super_admin") {
                $this->administrador = 1;
                $todos_los_valores = 1;

            } elseif (strpos($role, "control_escolar_") === 0) {
                $validaciones[] = substr($role, 16);
                $todos_los_valores = 2;
                $this->administrador = 0;
                continue;
            } else {
                continue;
            }
        }

        if ($todos_los_valores == 1) {
            $this->plantel = PlantelesModel::get();
        } elseif ($todos_los_valores == 2) {
            $this->plantel = PlantelesModel::whereIn('abreviatura', $validaciones)->get();
        } else {
            $this->plantel = collect();
        }
    }

    public function buscar_alumnos()
    {
        $buscar_ciclo_activo = CicloEscModel::where('activo', '1')->first();
        $this->alumnos_nuevos_plantel = AlumnoModel::
            leftJoin('esc_grupo_alumno', function ($join) {
                $join->on('alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.descripcion', '=', 'turno_especial');
            })
            ->where('alu_alumno.plantel_id', $this->plantel_seleccionado)
            ->where('cicloesc_id', $buscar_ciclo_activo->id)
            ->select('alu_alumno.*', 'esc_grupo_alumno.grupo_id as grupo_id')
            ->orderBy('noexpediente')
            ->get();
        //dd($this->alumnos_nuevos_plantel->toSql(), $this->alumnos_nuevos_plantel->getBindings());


        $this->grupos_plantel = buscar_grupos($this->plantel_seleccionado);

        if ($this->alumnos_nuevos_plantel) {
            $this->dispatchBrowserEvent('mostrar-alerta');
        }
        $this->busca_grupos = 1;
    }
    public function descargar_listado()
    {
        $this->descargando = true;
        $query = "SELECT 
            alu_alumno.noexpediente, 
            alu_alumno.nombre, 
            alu_alumno.apellidos, 
            COUNT(DISTINCT imagenes.tipo) AS tipos_unicos
        FROM 
            alu_alumno 
        LEFT JOIN 
            `sce-cobach-doctos_alumnos`.imagenes AS imagenes ON 
                CONVERT(alu_alumno.noexpediente USING utf8mb4) = CONVERT(imagenes.no_expediente USING utf8mb4)
        WHERE 
            alu_alumno.created_at >= '2024-07-16' 
            AND alu_alumno.plantel_id = ?
        GROUP BY 
            alu_alumno.noexpediente, 
            alu_alumno.nombre, 
            alu_alumno.apellidos
            ORDER BY 
            alu_alumno.noexpediente;";
        $logo = public_path('images/logocobachchico.png');

        $alumnos_nuevos_plantel = DB::select($query, [$this->plantel_seleccionado]);
        $plantel = PlantelesModel::find($this->plantel_seleccionado);
        $plantel_nombre = $plantel->nombre;
        $pdf = \PDF::loadView('exports.alumnos.nuevos_alumnos', compact('alumnos_nuevos_plantel', 'plantel_nombre', 'logo'));

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $plantel->nombre . '_Nuevo_Ingreso.pdf"',
        ]);


    }
    public function guardar_estatus($noexpediente)
    {
        $buscar_alumno = AlumnoModel::where('noexpediente', $noexpediente)->first();
        $buscar_alumno->id_estatus = 1;
        $buscar_alumno->save();

        $this->buscar_alumnos();
    }

    public function saveFile($noexpediente, $turno_id, $motivo)
    {
        //dd($this->alumno_seleccionado);
        //dd($this->file);
        /*
        $rules = [
            'file' => 'required|image|mimes:pdf,jpeg,png,jpg|max:2048',
        ];
        $this->validate($rules);*/

        // Reglas de validación
        $rules = [
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
        ];

        // Validar las reglas
        $this->validate($rules);


        if ($turno_id == "0") {
            $buscar_documento = ImagenesalumnoModel::where('no_expediente', $noexpediente)
                ->where('tipo', '3')
                ->first();
            if ($buscar_documento) {
                $buscar_documento->delete();
            }

            guardar_datos_alumno($noexpediente, $turno_id, $motivo, $this->administrador);

            $this->emit('fileSaved'); // Emitir un evento cuando el documento se haya guardado correctamente
            $this->buscar_alumnos();

        } else {
            $buscar_documento = ImagenesalumnoModel::where('no_expediente', $noexpediente)
                ->where('tipo', '3')
                ->first();


            $fp = fopen($this->file->getRealPath(), 'r+b');
            $data_f = fread($fp, filesize($this->file->getRealPath()));
            fclose($fp);
            $alumno = AlumnoModel::where('noexpediente', $noexpediente)->first();
            $data = [
                'imagen' => $data_f,
                'no_expediente' => $noexpediente,
                'alumno_id' => $alumno->id,
                'filename' => 'turno_especial' . $noexpediente . substr($this->file->getRealPath(), -4),
                'filesize' => filesize($this->file->getRealPath()),
                'tipo' => "3", //Archivos turno especial
            ];

            if (!$buscar_documento) {
                // Crear un nuevo registro si no se encuentra uno existente
                $documento_guardado = ImagenesalumnoModel::create($data);
            } else {
                // Actualizar el registro existente
                $buscar_documento->update($data);
                $documento_guardado = $buscar_documento; // Asignar $buscar_documento a $documento_guardado para consistencia

            }



            guardar_datos_alumno($noexpediente, $turno_id, $motivo, $this->administrador);
            $this->buscar_alumnos();
            if ($documento_guardado) {
                $this->reset(['file']);
                $this->emit('fileSaved'); // Emitir un evento cuando el documento se haya guardado correctamente
            }

        }



    }

    public function nuevos_alumnos_admin($noexpediente, $turno_id)
    {
        $motivo = "";

        guardar_datos_alumno($noexpediente, $turno_id, $motivo, $this->administrador);
        $this->buscar_alumnos();
    }

    public function guardar_grupo_especial($noexpediente, $id_grupo)
    {
        $buscar_alumno = AlumnoModel::where('noexpediente', $noexpediente)->first();
        if ($id_grupo == "-1") {
            $buscar_alumno_en_grupo = GrupoAlumnoModel::select('esc_grupo_alumno.id')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                ->where('alumno_id', $buscar_alumno->id)
                ->where('descripcion', 'turno_especial')
                ->first();

            GrupoAlumnoModel::where('id', $buscar_alumno_en_grupo->id)
                ->delete();

        } else {
            $grupo_seleccionado = GruposModel::find($id_grupo);



            $buscar_grupo = GruposModel::where('descripcion', 'turno_especial')
                ->where('plantel_id', $grupo_seleccionado->plantel_id)
                ->where('nombre', $grupo_seleccionado->nombre)
                ->where('turno_id', $grupo_seleccionado->turno_id)
                ->where('periodo', '1')
                ->where('ciclo_esc_id', $grupo_seleccionado->ciclo_esc_id)
                ->first();

            $buscar_alumno_en_grupo = GrupoAlumnoModel::select('esc_grupo_alumno.id')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                ->where('alumno_id', $buscar_alumno->id)
                ->where('descripcion', 'turno_especial')
                ->first();

            if ($buscar_grupo) {
                //existe el grupo
                if ($buscar_alumno_en_grupo) {
                    GrupoAlumnoModel::where('id', $buscar_alumno_en_grupo->id)
                        ->update(['grupo_id' => $buscar_grupo->id]);
                } else {
                    //dd($buscar_grupo);
                    $data_grupo_alumno = [
                        'alumno_id' => $buscar_alumno->id,
                        'grupo_id' => $buscar_grupo->id,
                    ];
                    GrupoAlumnoModel::create($data_grupo_alumno);
                }
            } else {
                $data = [
                    'turno_id' => $grupo_seleccionado->turno_id,
                    'plantel_id' => $grupo_seleccionado->plantel_id,
                    'ciclo_esc_id' => $grupo_seleccionado->ciclo_esc_id,
                    'periodo' => $grupo_seleccionado->periodo,
                    'aula_id' => $grupo_seleccionado->aula_id,
                    'nombre' => $grupo_seleccionado->nombre,
                    'descripcion' => 'turno_especial',
                    'gpo_base' => '0',
                ];
                $nuevo_grupo = GruposModel::create($data);

                if ($buscar_alumno_en_grupo) {
                    GrupoAlumnoModel::where('id', $buscar_alumno_en_grupo->id)
                        ->update(['grupo_id' => $nuevo_grupo->id]);
                } else {
                    $data_grupo_alumno = [
                        'alumno_id' => $buscar_alumno->id,
                        'grupo_id' => $nuevo_grupo->id,
                    ];
                    GrupoAlumnoModel::create($data_grupo_alumno);
                }

            }

        }

        $this->buscar_alumnos();
    }

    public function guardar_cambio_plantel($noexpediente, $plantel_id)
    {
        if ($this->administrador == 1) {
            $cambio_alumno = AlumnoModel::where('noexpediente', $noexpediente)->first();
            $buscar_alumno_en_grupo = GrupoAlumnoModel::select('esc_grupo_alumno.id')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                ->where('alumno_id', $cambio_alumno->id)
                ->where('descripcion', 'turno_especial')
                ->first();

            if ($buscar_alumno_en_grupo) {
                // YA ESTA EN UN GRUPO, QUITARLO DEL GRUPO ANTES DE HACER CUALQUIER CAMBIO
                $resultado = 3; // Ejemplo de resultado

            } elseif ($cambio_alumno->turno_especial >= 1 && $cambio_alumno->turno_especial < 9) {
                $cambio_alumno->plantel_id = $plantel_id;
                $cambio_alumno->save();
                $resultado = 1; // Ejemplo de resultado

            } else {
                $cambio_alumno->plantel_id = $plantel_id;
                $cambio_alumno->save();
                $resultado = 1; // Ejemplo de resultado

                // Emitir un evento a JavaScript con el resultado
            }
            $this->buscar_alumnos();

            $this->emit('resultadoGuardarCambioPlantel', $resultado);




        }
    }

    public function cambio_capacidad($grupo_id, $nueva_capacidad)
    {
        $grupo = GruposModel::find($grupo_id);
        $grupo->capacidad = $nueva_capacidad;
        $grupo->save();
    }

    public function emitir_busqueda()
    {
        $this->buscar_alumnos();
    }



    public function asignar_alumnos()
    {

        $grupos = GruposModel::where('plantel_id', $this->plantel_seleccionado)
            ->where('ciclo_esc_id', '248')
            ->where('descripcion', '!=', 'turno_especial')
            ->where('periodo', '1')
            ->get();

        //dd($grupos);

        $error = false;

        foreach ($grupos as $grupo) {
            if (is_null($grupo->capacidad) || $grupo->capacidad == 0 || $grupo->capacidad === '') {
                $error = true;
                break;
            }


        }

        if ($error) {
            $this->errores = "Alguno de los grupos tiene el campo capacidad sin definir.";
            $this->dispatchBrowserEvent('mostrar-error', ['mensaje' => 'Alguno de los grupos tiene el campo capacidad en null, 0 o vacío.']);
            $this->buscar_alumnos();
            $this->ya_asignado = false;
            return;
        }

        $this->capacidad_turno_1 = $grupos->where('turno_id', 1)->sum('capacidad');
        $this->capacidad_turno_2 = $grupos->where('turno_id', 2)->sum('capacidad');

        $alumnos_turno_especial = AlumnoModel::leftJoin('esc_grupo_alumno', function ($join) {
            $join->on('alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('esc_grupo.descripcion', '=', 'turno_especial');
        })
            ->where('alu_alumno.plantel_id', $this->plantel_seleccionado)
            ->where('cicloesc_id', '248')
            ->where('id_estatus', '1')
            ->select(
                'alu_alumno.id',
                'alu_alumno.noexpediente',
                'alu_alumno.nombre',
                'alu_alumno.apellidos',
                'alu_alumno.turno_especial',
                'esc_grupo_alumno.grupo_id as grupo_id'
            )
            ->orderByRaw('CASE WHEN esc_grupo_alumno.grupo_id IS NULL THEN 1 ELSE 0 END')
            ->orderByRaw("FIELD(turno_especial, 1,2,12, 3, 4, 5, 6, 7, 8) DESC")
            ->orderByRaw('RAND()')
            ->distinct('alu_alumno.id')
            ->get();
        //dd($alumnos_turno_especial->toSql(), $alumnos_turno_especial->getBindings());


        //dd($this->capacidad_turno_1 + $this->capacidad_turno_2);
        if ($alumnos_turno_especial->count() > ($this->capacidad_turno_1 + $this->capacidad_turno_2)) {

            $this->errores = "La cantidad de alumnos excede la capacidad de los grupos";
            $this->dispatchBrowserEvent('mostrar-error', ['mensaje' => 'La capacidad de alumnos excede la capacidad de los grupos.']);
            //$this->buscar_alumnos();
            $this->ya_asignado = false;
            return;
        }


        foreach ($alumnos_turno_especial as $alumno_turno_especial) {
            if ($alumno_turno_especial->grupo_id != null) {
                $buscar_grupo = GruposModel::find($alumno_turno_especial->grupo_id);

                $grupo_encontrado = $grupos->first(function ($grupo) use ($buscar_grupo) {
                    return $grupo->plantel_id == $buscar_grupo->plantel_id &&
                        $grupo->periodo == $buscar_grupo->periodo &&
                        $grupo->nombre == $buscar_grupo->nombre &&
                        $grupo->ciclo_esc_id == $buscar_grupo->ciclo_esc_id &&
                        $grupo->descripcion != $buscar_grupo->descripcion &&
                        $grupo->turno_id == $buscar_grupo->turno_id;
                });

                if ($grupo_encontrado) {
                    if ($grupo_encontrado->capacidad == 0) {
                        $turno = $grupo_encontrado->turno_id == 1 ? "Matutino" : "Vespertino";
                        $this->errores = "El grupo ya se encuentra lleno. " . $grupo_encontrado->nombre . " Turno: " . $turno;

                    } else {
                        $grupo_encontrado->capacidad -= 1;
                        $this->agregarAlumnoAlGrupo($alumno_turno_especial->id, $grupo_encontrado->id);
                    }
                }
            } else {
                $max_attempts = ($grupos->count() * 3);
                $attempts = 0;
                $asignado = "0";

                while ($attempts < $max_attempts && $asignado == "0") {
                    $turno_id = $this->obtenerTurnoId($alumno_turno_especial->turno_especial) ?? $this->random_turno();
                    $asignado = $this->actualizarGrupoConTurnoId($grupos, $turno_id, $alumno_turno_especial);
                    $attempts++;
                }

                if ($asignado == "0") {
                    $this->errores = "No se pudo asignar el alumno con ID: " . $alumno_turno_especial->noexpediente . " a un grupo.";
                    $this->ya_asignado = false;
                    return;
                    //break;
                }
            }

        }
        $this->ya_asignado = true;
        $this->buscar_alumnos();


    }

    public function descargar_excel_nuevos_alumnos()
    {
        $alumnos_nuevos_plantel = collect($this->alumnos_en_plantel)->sortBy('grupo_id')->values()->all();
        $plantel = PlantelesModel::find($this->plantel_seleccionado);
        $plantel_nombre = $plantel->nombre;
        $logo = public_path('images/logocobachchico.png');



        //$this->dispatchBrowserEvent('alerta-final');
        return \Excel::download(new nuevos_alumnos($alumnos_nuevos_plantel, $plantel_nombre, $logo), 'Alumnos_' . $plantel_nombre . '.xlsx');
    }


    public function asignacion_final()
    {
        $this->errores = "";
        foreach ($this->alumnos_en_plantel as $alumnos) {
            $buscar_alumno = GrupoAlumnoModel::where('alumno_id', $alumnos["alumno_id"])
                ->first();

            if ($buscar_alumno) {
                $buscar_grupo = GruposModel::find($buscar_alumno->grupo_id);
                if ($buscar_grupo) {
                    if ($buscar_grupo->descripcion == "turno_especial") {
                        $buscar_alumno->grupo_id = $alumnos["grupo_id"];
                        $buscar_alumno->save();
                    } else {
                        $buscar = AlumnoModel::find($alumnos["alumno_id"]);
                        $this->errores = $this->errores.$buscar->noexpediente . ", ";
                    }
                }

            } else {
                $data = [
                    "alumno_id" => $alumnos["alumno_id"],
                    "grupo_id" => $alumnos["grupo_id"],
                ];
                GrupoAlumnoModel::create($data);
            }
        }

        if ($this->errores != "") {
            return 0;
        } else {
            return 1;
        }
    }
    private function obtenerTurnoId($turno_especial)
    {
        //Este if es en caso de que se llene un turno con turnos especiales los manda al aleatorio que ya valida las capacidades
        //Y manda el turno que sobra 
        if ($this->capacidad_turno_1 > 0 && $this->capacidad_turno_2 > 0) {

            switch ($turno_especial) {
                case '3':
                case '5':
                case '8':
                case '1':
                case '12':
                    return 1;
                case '4':
                case '6':
                case '7':
                case '2':
                    return 2;
                default:
                    return null;
            }
        } else {
            $valor = $this->random_turno();
            return $valor;
        }
    }

    private function random_turno()
    {
        if ($this->capacidad_turno_1 > 0 && $this->capacidad_turno_2 > 0) {
            return rand(1, 2); // Seleccionar aleatoriamente entre 1 y 2
        }
        if ($this->capacidad_turno_1 == 0) {
            return 2;
        }
        if ($this->capacidad_turno_2 == 0) {
            return 1;
        }


    }

    private function intentarAsignarGrupo($grupos, $turnoId, $alumno)
    {
        // Filtra los grupos por el turno especificado
        $grupos_filtrados = $grupos->filter(function ($grupo) use ($turnoId) {
            return $grupo->turno_id == $turnoId;
        });
    
        // Ordena los grupos por la cantidad de alumnos ya asignados, de menor a mayor
        $grupos_ordenados = $grupos_filtrados->sortBy(function ($grupo) {
            return $grupo->alumnos_asignados; // Asegúrate de tener esta propiedad o lógica para contar los alumnos asignados
        })->values();
    
        if ($grupos_ordenados->isNotEmpty()) {
            // Encuentra el primer grupo disponible con capacidad
            $grupo_seleccionado = $grupos_ordenados->first(function ($grupo) {
                return $grupo->capacidad > 0;
            });
    
            if ($grupo_seleccionado) {
                // Reduce la capacidad del grupo seleccionado y actualiza el conteo de alumnos asignados
                $grupo_seleccionado->capacidad -= 1;
                $grupo_seleccionado->alumnos_asignados += 1; // Actualiza el número de alumnos asignados
    
                if ($turnoId == 1) {
                    $this->capacidad_turno_1 -= 1;
                } elseif ($turnoId == 2) {
                    $this->capacidad_turno_2 -= 1;
                }
    
                // Agrega al alumno al grupo
                $valida_funcion = $this->agregarAlumnoAlGrupo($alumno->id, $grupo_seleccionado->id);
    
                if ($valida_funcion == "1") {
                    return "1";
                } else {
                    return "0";
                }
            }
        }
    
        return "0"; // Retorna "0" si no se pudo asignar un grupo
    }


    private function actualizarGrupoConTurnoId($grupos, $turnoId, $alumno)
    {
         // Primero, intenta asignar al turno especificado
    $asignado = $this->intentarAsignarGrupo($grupos, $turnoId, $alumno);

    // Si no se pudo asignar, intenta asignar al turno alternativo
    if ($asignado == "0") {
        // Verifica si el turno actual es matutino y hay capacidad en el vespertino
        if ($turnoId == 1 && $this->capacidad_turno_2 > 0) {
            $asignado = $this->intentarAsignarGrupo($grupos, 2, $alumno);
        } 
        // Verifica si el turno actual es vespertino y hay capacidad en el matutino
        elseif ($turnoId == 2 && $this->capacidad_turno_1 > 0) {
            $asignado = $this->intentarAsignarGrupo($grupos, 1, $alumno);
        }
    }

    // Maneja la situación si el alumno no pudo ser asignado
    if ($asignado == "0") {
        $this->errores = "No se pudo asignar el alumno con ID: " . $alumno->noexpediente . " a un grupo.";
        $this->ya_asignado = false;
        return;
    }

    return $asignado;
        /*
         // Mantén un índice para el seguimiento de la asignación escalonada
         $grupoIndice = 0;

         // Filtra los grupos por el turno especificado
         $grupos_filtrados = $grupos->filter(function ($grupo) use ($turnoId) {
             return $grupo->turno_id == $turnoId;
         });

         // Ordena los grupos para asegurarte de que el orden sea consistente
         $grupos_ordenados = $grupos_filtrados->values();

         if ($grupos_ordenados->isNotEmpty()) {
             // Obtén el grupo actual según el índice
             $grupo_seleccionado = $grupos_ordenados->get($grupoIndice);

             // Reduce la capacidad del grupo seleccionado
             if ($grupo_seleccionado->capacidad) {
                 $grupo_seleccionado->capacidad -= 1;

                 if ($turnoId == 1) {
                     $this->capacidad_turno_1 -= 1;
                 } elseif ($turnoId == 2) {
                     $this->capacidad_turno_2 -= 1;
                 }

                 // Agrega al alumno al grupo
                 $valida_funcion = $this->agregarAlumnoAlGrupo($alumno->id, $grupo_seleccionado->id);

                 if ($valida_funcion == "1") {
                     // Incrementa el índice para la próxima asignación
                     $grupoIndice = ($grupoIndice + 1) % $grupos_ordenados->count();
                     return "1";
                 } else {
                     return "0";
                 }
             } else {
                 return "0";
             }
         }


         // Filtra los grupos por el turno especificado
         $grupos_filtrados = $grupos->filter(function ($grupo) use ($turnoId) {
             return $grupo->turno_id == $turnoId;
         });

         // Ordena los grupos por el valor numérico del campo 'nombre'
         $grupos_ordenados = $grupos_filtrados->sortBy(function ($grupo) {
             return (int) $grupo->nombre; // Convierte el 'nombre' a entero para asegurar la correcta ordenación numérica
         })->values();

         if ($grupos_ordenados->isNotEmpty()) {
             // Encuentra el primer grupo disponible con capacidad
             $grupo_seleccionado = $grupos_ordenados->first(function ($grupo) {
                 return $grupo->capacidad > 0;
             });

             if ($grupo_seleccionado) {
                 // Reduce la capacidad del grupo seleccionado
                 $grupo_seleccionado->capacidad -= 1;

                 if ($turnoId == 1) {
                     $this->capacidad_turno_1 -= 1;
                 } elseif ($turnoId == 2) {
                     $this->capacidad_turno_2 -= 1;
                 }

                 // Agrega al alumno al grupo
                 $valida_funcion = $this->agregarAlumnoAlGrupo($alumno->id, $grupo_seleccionado->id);

                 if ($valida_funcion == "1") {
                     return "1";
                 } else {
                     return "0";
                 }
             } else {
                 return "0";
             }
         }
         
         // Funciona

 /*
           $grupos_filtrados = $grupos->filter(function ($grupo) use ($turnoId) {
             return $grupo->turno_id == $turnoId;
         });

         if ($grupos_filtrados->isNotEmpty()) {
             $grupo_aleatorio = $grupos_filtrados->random();

             if ($grupo_aleatorio->capacidad) {
                 $grupo_aleatorio->capacidad -= 1;
                 if ($turnoId == 1) {
                     $this->capacidad_turno_1 -= 1;
                 }
                 if ($turnoId == 2) {
                     $this->capacidad_turno_2 -= 1;
                 }
                 //dd($grupo_aleatorio, $turnoId, $alumno);
                 $valida_funcion = $this->agregarAlumnoAlGrupo($alumno->id, $grupo_aleatorio->id);
                 if ($valida_funcion == "1") {
                     return "1";
                 } else {
                     return "0";
                 }

             } else {
                 return "0";
             }
         }
         */
        // return "0"; // Añadir esta línea para asegurar que siempre retorna algo.

    }

    private function agregarAlumnoAlGrupo($alumno_id, $grupo_id)
    {
        $existeAlumno = collect($this->alumnos_en_plantel)->contains('alumno_id', $alumno_id);

        if (!$existeAlumno) {
            $this->alumnos_en_plantel[] = [
                'alumno_id' => $alumno_id,
                'grupo_id' => $grupo_id
            ];
            return "1";
        } else {
            return "0";
        }

    }
}

function buscar_grupos($plantel_seleccionado)
{
    $grupos_plantel = GruposModel::join('esc_curso', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
        ->where('plantel_id', $plantel_seleccionado)
        ->where('ciclo_esc_id', '248')
        ->where('periodo', '1')
        ->select('esc_grupo.*')
        ->selectRaw('COUNT(esc_curso.grupo_id) as total_coincidencias')
        ->groupBy('esc_grupo.id')  // Agrupando por el ID del grupo para que el COUNT funcione correctamente
        ->get();
    //dd($grupos_plantel->toSql(), $grupos_plantel->getBindings());
    return $grupos_plantel;
}

function guardar_datos_alumno($noexpediente, $turno_id, $motivo, $administrador)
{
    $alumno = AlumnoModel::where('noexpediente', $noexpediente)->first();

    if ($turno_id == 0) {
        if ($administrador == 1) {

            if (in_array($alumno->turno_especial, ['1', '5', '7'])) {
                $alumno->turno_especial = 9;
                $alumno->observaciones = null;
                $alumno->save();
            }

            if (in_array($alumno->turno_especial, ['2', '6', '8'])) {

                $alumno->turno_especial = 10;
                $alumno->observaciones = null;
                $alumno->save();
            }

            if (in_array($alumno->turno_especial, ['3', '4'])) {
                $alumno->turno_especial = null;
                $alumno->observaciones = null;
                $alumno->save();
            }
        } else {
            if (in_array($alumno->turno_especial, ['1', '2'])) {
                $alumno->turno_especial = null;
                $alumno->observaciones = null;
                $alumno->save();
            }
            if (in_array($alumno->turno_especial, ['3', '4'])) {

            }
            if ($alumno->turno_especial == "5") {
                $alumno->turno_especial = 3;
                $alumno->observaciones = null;
                $alumno->save();
            }

            if (in_array($alumno->turno_especial, ['6', '7'])) {
                $alumno->turno_especial = 4;
                $alumno->observaciones = null;
                $alumno->save();
            }

            if (in_array($alumno->turno_especial, ['5', '8'])) {
                $alumno->turno_especial = 3;
                $alumno->observaciones = null;
                $alumno->save();
            }

            if (in_array($alumno->turno_especial, ['9', '10'])) {
                $alumno->turno_especial = null;
                $alumno->observaciones = null;
                $alumno->save();
            }

        }


    } else {
        if ($administrador == 1) {
            if ($alumno->turno_especial == NULL) {

                switch ($turno_id) {
                    case '1':
                        $turno_admin = 3;
                        break;
                    case '2':
                        $turno_admin = 4;
                        break;

                    default:
                        # code...
                        break;
                }

                $alumno->turno_especial = $turno_admin; // Asignar el nuevo valor al campo turno_especial

                $alumno->save();

            } else {
                if ($alumno->turno_especial == 1) {
                    if ($turno_id == 1) {
                        $alumno->turno_especial = 3;
                        $alumno->save();
                    } else {
                        $alumno->turno_especial = 7;
                        $alumno->save();
                    }
                }

                if ($alumno->turno_especial == 2) {
                    if ($turno_id == 1) {
                        $alumno->turno_especial = 8;
                        $alumno->save();
                    } else {
                        $alumno->turno_especial = 6;
                        $alumno->save();
                    }
                }
                if (in_array($alumno->turno_especial, ['3', '4'])) {
                    if ($turno_id == "1") {
                        $alumno->turno_especial = 3;
                        $alumno->save();
                    }
                    if ($turno_id == "2") {
                        $alumno->turno_especial = 4;
                        $alumno->save();
                    }



                }


            }
        } else {
            if ($alumno->turno_especial == NULL) {

                switch ($turno_id) {
                    case '1':
                        $turno_admin = 1;
                        break;
                    case '2':
                        $turno_admin = 2;
                        break;

                    default:
                        # code...
                        break;
                }

                $alumno->turno_especial = $turno_admin; // Asignar el nuevo valor al campo turno_especial

                $alumno->save();

            }

            if ($alumno->turno_especial == 3) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 5;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 8;
                    $alumno->save();
                }
            }
            if ($alumno->turno_especial == 4) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 7;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 6;
                    $alumno->save();
                }
            }

            if ($alumno->turno_especial == 5) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 5;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 6;
                    $alumno->save();
                }
            }

            if ($alumno->turno_especial == 6) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 5;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 6;
                    $alumno->save();
                }
            }

            if ($alumno->turno_especial == 7) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 7;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 6;
                    $alumno->save();
                }
            }

            if ($alumno->turno_especial == 8) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 3;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 8;
                    $alumno->save();
                }
            }

            if ($alumno->turno_especial == 4) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 7;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 6;
                    $alumno->save();
                }
            }


            if ($alumno->turno_especial == 9) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 9;
                    $alumno->save();
                } else {
                    $alumno->turno_especial = 10;
                    $alumno->save();
                }
            }



            if ($alumno->turno_especial == 1) {
                if ($turno_id == 1) {

                } else {
                    $alumno->turno_especial = 2;
                    $alumno->save();
                }
            }
            if ($alumno->turno_especial == 2) {
                if ($turno_id == 1) {
                    $alumno->turno_especial = 1;
                    $alumno->save();
                }
                if ($turno_id == 2) {
                    $alumno->turno_especial = 1;
                    $alumno->save();
                }
            }



        }

        if (empty($alumno->observaciones)) {
            // Si el campo observaciones está vacío, solo se guarda el motivo
            $alumno->observaciones = $motivo;
        } else {
            // Si ya tiene contenido, se agrega el motivo al final
            $alumno->observaciones .= " " . $motivo;
        }


    }


    BitacoraModel::create([
        'user_id' => Auth()->user()->id,
        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
        'path' => $_SERVER["REQUEST_URI"],
        'method' => $_SERVER['REQUEST_METHOD'],
        //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
        'controller' => 'Turno especial',
        //'component'     =>  'FormComponent',
        'function' => 'cambio',
        'description' => 'Se agrego el turno especial alumnos :' . $alumno->turno_especial,
    ]);

}
