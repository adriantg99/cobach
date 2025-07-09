<?php

namespace App\Http\Livewire\Adminalumnos\Alumnos;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Adminalumnos\PaisesModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\LocalidadesModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
use App\Models\Catalogos\PlandeEstudioModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Documentos\FotosModel;
use App\Models\Grupos\GruposModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads; // Importa el trait WithFileUploads


use Livewire\Component;

class Editar extends Component
{
    use WithFileUploads;
    public $imagen;
    public $documento;
    public $modulo_activo = 1;
    public $grupo_inscribir;
    public $alumnoId;
    public $alumno;
    public $ciclo_escolares;
    public $datos_grupo;
    public $grupo_del_alumno;
    public $imagen_find;


    public $datos_personales = [
        'tutor_email' => '0',
        'nombre' => '',
        'noexpediente' => '0',
        'apellidopaterno' => '',
        'apellidomaterno' => '',
        'apellidos' => '',
        'cicloesc_id' => '0',
        'id_planestudio' => '0',
        'id_periodo' => '0',
        'fechanacimiento' => '0',
        'edad' => '0',
        'sexo' => '0',
        'curp' => '',
        'id_nacionalidad' => '0',
        'id_paisnacimiento' => '0',
        'id_estadonacimiento' => '0',
        'id_municipionacimiento' => '0',
        'id_localidadnacimiento' => '0',
        'id_lugarnacimiento' => '0',
        'peso' => '0',
        'estatura' => '0',
        'alergias' => '0',
        'alergias_describe' => '0',
        'tiposangre' => '0',
        'id_discapacidad' => 0,
        'enfermedad' => '0',
        'id_etnia' => 0,
        'lengua_indigena' => '0',
        'lengua_indigena_desc' => '0',
        'empresa_nombre' => '0',
        'empresa_colonia' => '0',
        'empresa_domicilio' => '0',
        'empresa_telefono' => '0',
        'domicilio' => '0',
        'domicilio_entrecalle' => '0',
        'domicilio_noexterior' => '0',
        'domicilio_nointerior' => '0',
        'colonia' => '0',
        'codigopostal' => '0',
        'id_estadodomicilio' => '0',
        'id_municipiodomicilio' => '0',
        'id_localidaddomicilio' => '0',
        'telefono' => '0',
        'celular' => '0',
        'email' => '0',
        'tutor_nombre' => '0',
        'tutor_apellido1' => '',
        'tutor_apellido2' => '',
        'tutor_domicilio' => '0',
        'tutor_colonia' => '0',
        'tutor_ocupacion' => '0',
        'tutor_telefono' => '0',
        'tutor_celular' => '0',
        'familiar_nombre' => '0',
        'familiar_celular' => '0',
        'extranjero_padre_mexicano' => '0',
        'id_extranjero_paisnacimiento' => 0,
        'tutor_empresa_nombre' => '0',
        'tutor_empresa_colonia' => '0',
        'tutor_empresa_domicilio' => '0',
        'tutor_empresa_telefono' => '0',
        'plantel_id' => '0',
        'id_secundaria_procedencia' => '0',
        'secundaria_nombre' => '0',
        'secundaria_clave' => '0',
        'secundaria_promedio' => '0',
        'secundaria_fechaegreso' => '1900-01-01',
        'extranjero_grado_ems' => '0',
        'id_extranjero_paisestudio' => 0,
        'extranjero_habla_espanol' => '0',
        'extranjero_lee_espanol' => '0',
        'extranjero_escribe_espanol' => '0',
        'id_beca' => 0,
        'beca_otra' => '0',
        'turno_especial' => '0',
        'id_servicio_medico' => 0,
        'servicio_medico_otro' => '0',
        'fecharegistro' => '0',
        'servicio_medico_afiliacion' => '0',
        'deudas_finanzas' => '0',
        'deudas_biblioteca' => '0',
        'id_estatus' => '',
    ];

    public $idCicloEscolar;
    public $idplantel;
    public $id_plantel;
    public $planteles;

    public $paises;
    public $estatus;

    public $grupos_posibles;
    public $plan_estudio;
    public $grupo_pasar;

    public $estados, $municipios;
    public $localidades;

    public $seleccionado;


    public function render()
    {
        if (!empty($this->alumno)) {
            $this->ciclo_escolares = CicloEscModel::
                join('esc_grupo', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
                ->join('esc_grupo_alumno', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->where('cat_ciclos_esc.activo', '1')
                ->where('esc_grupo_alumno.alumno_id', $this->alumnoId)
                ->get();
            //dd($this->ciclo_escolares);
            $this->planteles = PlantelesModel::select('cat_plantel.id', 'cat_plantel.nombre')
                ->join('esc_grupo', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                ->where('alumno_id', $this->alumnoId)
                ->orderByDesc('esc_grupo.id')
                ->take(1)
                ->get();
            $this->plan_estudio = PlandeEstudioModel::where('id', $this->alumno->id_planestudio)->get();

            $this->grupo_del_alumno = GruposModel::select('cat_ciclos_esc.id as id_ciclo', 'cat_ciclos_esc.nombre as nombre_ciclo', 'esc_grupo.nombre', 'esc_grupo.turno_id', 'esc_grupo.descripcion', 'esc_grupo.id')
                ->join('esc_grupo_alumno', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
                ->where('esc_grupo_alumno.alumno_id', $this->alumnoId)
                ->where('esc_grupo.gpo_base', '1')
                ->where('cat_ciclos_esc.activo', '1')
                ->get();

            if ($this->grupo_del_alumno->isEmpty()) {
                // Consulta adicional
                $this->grupo_del_alumno = GruposModel::select('cat_ciclos_esc.id as id_ciclo', 'cat_ciclos_esc.nombre as nombre_ciclo', 'esc_grupo.nombre', 'esc_grupo.turno_id', 'esc_grupo.descripcion', 'esc_grupo.id')
                    ->join('esc_grupo_alumno', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
                    ->where('esc_grupo_alumno.alumno_id', $this->alumnoId)
                    ->orderByDesc('esc_grupo.id') // Ordenar por el ID del grupo de manera descendente para obtener el último
                    ->take(1) // Tomar solo el primer resultado
                    ->get();
            }




        } else {
            $this->ciclo_escolares = CicloEscModel::orderBy('id', 'desc')
                ->get();
            $this->planteles = PlantelesModel::get();
            $this->plan_estudio = PlandeEstudioModel::where('activo', '1')->get();

        }

        $this->paises = PaisesModel::get();
        //dd($this->paises);
        //$this->datos_grupo = GrupoAlumnoModel::select('0');
        $this->estatus = true;
        /*$this->estados = LocalidadesModel::select('nom_ent','cve_ent', 'id')
            ->groupBy('nom_ent', 'id', 'cve_ent')
            ->get();*/

        if ($this->datos_personales["id_estadodomicilio"] != 0) {
            /*$this->municipios = LocalidadesModel::select('nom_mun', 'cve_mun')
            ->where('cve_ent', $this->datos_personales["id_estadodomicilio"])
            ->groupBy();*/
        }
        return view('livewire.adminalumnos.alumnos.editar');
    }


    public function mount(Request $request)
    {
        $this->alumnoId = $request->route('alumno_id');
        $this->alumno = AlumnoModel::find($this->alumnoId);
        $fechaActual = new DateTime();
        if (!empty($this->alumno)) {
            $this->seleccionado = 1;
            $fecha_nacimiento = new DateTime($this->alumno->fechanacimiento);

            $diferencia = $fecha_nacimiento->diff($fechaActual);

            $this->datos_personales = [
                'noexpediente' => $this->alumno->noexpediente,
                'apellidopaterno' => $this->alumno->apellidopaterno,
                'apellidomaterno' => $this->alumno->apellidomaterno,
                'apellidos' => $this->alumno->apellidopaterno . ' ' . $this->alumno->apellidomaterno,
                'cicloesc_id' => $this->alumno->cicloesc_id,
                'nombre' => $this->alumno->nombre,
                'plantel_id' => $this->alumno->plantel_id,
                'fechanacimiento' => $this->alumno->fechanacimiento,
                'edad' => $diferencia->y,
                'sexo' => $this->alumno->sexo,
                'curp' => $this->alumno->curp,
                'id_nacionalidad' => $this->alumno->id_nacionalidad,
                'id_paisnacimiento' => $this->alumno->id_paisnacimiento,
                'id_estadonacimiento' => $this->alumno->id_estadodomicilio,
                'id_municipionacimiento' => $this->alumno->id_municipiodomicilio,
                'id_localidadnacimiento' => $this->alumno->id_localidadnacimiento,
                'id_lugarnacimiento' => $this->alumno->id_lugarnacimiento,
                'peso' => $this->alumno->peso,
                'estatura' => $this->alumno->estatura,
                'alergias' => $this->alumno->alergias,
                'alergias_describe' => $this->alumno->alergias_describe,
                'tiposangre' => $this->alumno->tiposangre,
                'discapacidad_describe' => $this->alumno->discapacidad_describe,
                'enfermedad' => $this->alumno->enfermedad,
                'id_etnia' => $this->alumno->id_etnia,
                'lengua_indigena' => $this->alumno->lengua_indigena,
                'lengua_indigena_desc' => $this->alumno->lengua_indigena_desc,
                'empresa_nombre' => $this->alumno->empresa_nombre,
                'empresa_colonia' => $this->alumno->empresa_colonia,
                'empresa_domicilio' => $this->alumno->empresa_domicilio,
                'empresa_telefono' => $this->alumno->empresa_telefono,
                'domicilio' => $this->alumno->domicilio,
                'domicilio_entrecalle' => $this->alumno->domicilio_entrecalle,
                'domicilio_noexterior' => $this->alumno->domicilio_noexterior,
                'domicilio_nointerior' => $this->alumno->domicilio_nointerior,
                'colonia' => $this->alumno->colonia,
                'codigopostal' => $this->alumno->codigopostal,
                'id_estadodomicilio' => $this->alumno->id_estadodomicilio,
                'id_municipiodomicilio' => $this->alumno->id_municipiodomicilio,
                'id_localidaddomicilio' => $this->alumno->id_localidaddomicilio,
                'telefono' => $this->alumno->telefono,
                'celular' => $this->alumno->celular,
                'email' => $this->alumno->email,
                'tutor_nombre' => $this->alumno->tutor_nombre,
                'tutor_apellido1' => $this->alumno->tutor_apellido1,
                'tutor_apellido2' => $this->alumno->tutor_apellido2,
                'tutor_domicilio' => $this->alumno->tutor_domicilio,
                'tutor_colonia' => $this->alumno->tutor_colonia,
                'tutor_ocupacion' => $this->alumno->tutor_ocupacion,
                'tutor_telefono' => $this->alumno->tutor_telefono,
                'tutor_celular' => $this->alumno->tutor_celular,
                'tutor_email' => $this->alumno->tutor_email,
                'familiar_nombre' => $this->alumno->familiar_nombre,
                'familiar_apellido1' => $this->alumno->familiar_apellido1,
                'familiar_apellido2' => $this->alumno->familiar_apellido2,
                'familiar_celular' => $this->alumno->familiar_celular,
                'extranjero_padre_mexicano' => 0,
                'id_extranjero_paisnacimiento' => 0,
                'tutor_empresa_nombre' => $this->alumno->tutor_empresa_nombre,
                'tutor_empresa_colonia' => $this->alumno->tutor_empresa_colonia,
                'tutor_empresa_domicilio' => $this->alumno->tutor_empresa_domicilio,
                'tutor_empresa_telefono' => $this->alumno->tutor_empresa_telefono,
                'secundaria_nombre' => $this->alumno->secundaria_nombre,
                'secundaria_clave' => $this->alumno->secundaria_clave,
                'secundaria_promedio' => $this->alumno->secundaria_promedio,
                'secundaria_fechaegreso' => $this->alumno->secundaria_fechaegreso,
                'extranjero_grado_ems' => $this->alumno->extranjero_grado_ems,
                'id_extranjero_paisestudio' => $this->alumno->id_extranjero_paisestudio,
                'extranjero_habla_espanol' => $this->alumno->extranjero_habla_espanol,
                'extranjero_lee_espanol' => $this->alumno->extranjero_lee_espanol,
                'extranjero_escribe_espanol' => $this->alumno->extranjero_escribe_espanol,
                'id_beca' => $this->alumno->id_beca,
                'beca_otra' => $this->alumno->beca_otra,
                'turno_especial' => $this->alumno->turno_especial,
                'id_servicio_medico' => $this->alumno->id_servicio_medico,
                'servicio_medico_otro' => $this->alumno->servicio_medico_otro,
                'servicio_medico_afiliacion' => $this->alumno->servicio_medico_afiliacion,
                'observaciones' => $this->alumno->observaciones,
                'fecharegistro' => $this->alumno->fecharegistro,
                'fechabaja' => $this->alumno->fechabaja
            ];
            $todos_los_valores = 0;

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

                $ciclo = CicloEscModel::where('activo', '1')->first();
                $buscar_alumnos_ciclo = GrupoAlumnoModel::join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->where('alumno_id', $this->alumno->id)
                    ->where('esc_grupo.ciclo_esc_id', $ciclo->id)
                    ->first();

                if ($buscar_alumnos_ciclo) {
                    empty($this->grupos_posibles);
                } else {
                    $this->grupos_posibles = GruposModel::where('ciclo_esc_id', $ciclo->id)
                        ->where('plantel_id', $this->alumno->plantel_id)
                        ->where('descripcion', '!=', 'turno_especial')
                        //->where('periodo', '1')
                        ->orderBy('nombre')
                        ->get();
                }


            } else {
                empty($this->grupos_posibles);
            }


        } else {
            $this->datos_personales = [
                'tutor_email' => '0',
                'noexpediente' => '',
                'apellidopaterno' => '',
                'apellidomaterno' => '',
                'apellidos' => strtoupper($this->datos_personales["apellidopaterno"]) . ' ' . strtoupper($this->datos_personales["apellidomaterno"]),
                'nombre' => '',
                'plantel_id' => '0',
                'cicloesc_id' => '0',
                'edad' => '',
                'sexo' => '',
                'curp' => '',
                'id_periodo' => '0',
                'id_nacionalidad' => '0',
                'id_paisnacimiento' => '0',
                'id_estadonacimiento' => '0',
                'id_municipionacimiento' => '0',
                'id_localidadnacimiento' => '0',
                'id_lugarnacimiento' => '0',
                'peso' => '0',
                'estatura' => '0',
                'alergias' => '0',
                'alergias_describe' => '0',
                'tiposangre' => '0',
                'id_discapacidad' => '0',
                'enfermedad' => '0',
                'id_etnia' => '0',
                'lengua_indigena' => '0',
                'lengua_indigena_desc' => '0',
                'empresa_nombre' => '0',
                'empresa_colonia' => '0',
                'empresa_domicilio' => '0',
                'empresa_telefono' => '0',
                'domicilio' => '',
                'domicilio_entrecalle' => '0',
                'domicilio_noexterior' => '',
                'domicilio_nointerior' => '0',
                'colonia' => '0',
                'codigopostal' => '',
                'id_estadodomicilio' => '0',
                'id_municipiodomicilio' => '0',
                'id_localidaddomicilio' => '0',
                'telefono' => '',
                'celular' => '',
                'email' => '',
                'tutor_nombre' => '0',
                'tutor_domicilio' => '0',
                'tutor_colonia' => '0',
                'tutor_ocupacion' => '0',
                'tutor_telefono' => '0',
                'tutor_celular' => '0',
                'familiar_nombre' => '0',
                'familiar_celular' => '0',
                'extranjero_padre_mexicano' => '0',
                'id_extranjero_paisnacimiento' => '0',
                'tutor_empresa_nombre' => '0',
                'tutor_empresa_colonia' => '0',
                'tutor_empresa_domicilio' => '0',
                'tutor_empresa_telefono' => '0',
                'secundaria_nombre' => '0',
                'secundaria_clave' => '0',
                'secundaria_promedio' => '0',
                'extranjero_grado_ems' => '0',
                'id_extranjero_paisestudio' => '0',
                'extranjero_habla_espanol' => '0',
                'extranjero_lee_espanol' => '0',
                'extranjero_escribe_espanol' => '0',
                'id_beca' => '0',
                'beca_otra' => '0',
                'turno_especial' => '0',
                'id_servicio_medico' => '0',
                'servicio_medico_otro' => '0',
                'servicio_medico_afiliacion' => '0',
                'observaciones' => '0',
                'fechabaja' => null,
                'fechanacimiento' => now()->format('Y-m-d'),
                'secundaria_fechaegreso' => now()->format('Y-m-d'),
                'fecharegistro' => now()->format('Y-m-d'),
                'id_secundaria_procedencia' => '0',
                'deudas_finanzas' => '0',
                'deudas_biblioteca' => '0',
                'id_estatus' => '1',
            ];


        }



    }

    public function datospersonales()
    {

        $this->modulo_activo = 1;
        //modulo_activa = 1; es el de datos personales;
    }

    public function direccion()
    {
        $this->modulo_activo = 2;
        //modulo_activa = 2; es el de Dirección;
    }

    public function datostutor()
    {
        $this->modulo_activo = 3;
        //modulo_activa = 3; es el de datos tutor;
    }

    public function datosescolares()
    {
        $this->modulo_activo = 4;
        //modulo_activa = 4; es el de datos escolares;
    }

    public function visualizar_documentos()
    {
        $this->modulo_activo = 6;
    }

    public function detallesgrupo()
    {
        $this->modulo_activo = 5;
        //modulo_activa = 5; es el de datos de grupo;
    }

    public function apellidos()
    {
        $this->datos_personales["apellidos"] = strtoupper($this->datos_personales["apellidopaterno"]) . ' ' . strtoupper($this->datos_personales["apellidomaterno"]);
    }

    public function asignacion()
    {
        //ss  dd($this->ciclo_escolares);
        if (!empty($this->idCicloEscolar) && !empty($this->idplantel) && empty($this->alumno)) {

            $this->grupos_posibles = GruposModel::where('ciclo_esc_id', $this->idCicloEscolar)
                ->where('plantel_id', $this->idplantel)->where('esc_grupo.gpo_base', '1')->get();
            /* Este es para cambios de grupo
            $this->grupos_posibles = GruposModel::join('esc_curso', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
            ->join('esc_calificaciones', 'esc_calificaciones.curso_id', '=', 'esc_curso')
            ->where('0');
            */
            $cicloSeleccionado = $this->ciclo_escolares->where('id', $this->idCicloEscolar)->first();
            $ciclo_nombre = $cicloSeleccionado->nombre;

            if (strlen($ciclo_nombre) >= 4) {
                $id_plantel = str_pad($this->idplantel, 2, "0", STR_PAD_LEFT);
                $inicio_expediente = substr($ciclo_nombre, 2, 2);

                $ultimo_expediente = AlumnoModel::select('noexpediente')
                    ->orderBy('noexpediente', 'desc')
                    ->where('noexpediente', 'like', $inicio_expediente . $id_plantel . '%')
                    ->first();

                if ($ultimo_expediente) {
                    $ultimosCuatroDigitos = substr($ultimo_expediente->noexpediente, -4);
                    $continuacion = intval($ultimosCuatroDigitos) + 1;
                } else {
                    $continuacion = 1;
                }

                // Asegura que siempre haya 4 dígitos, rellenando con ceros a la izquierda si es necesario.
                $continuacion = str_pad($continuacion, 4, "0", STR_PAD_LEFT);

                $this->datos_personales['noexpediente'] = $inicio_expediente . $id_plantel . $continuacion;
            } else {
                echo "El ciclo escolar no es valido";
                // Manejar el caso en el que la cadena no tenga al menos 4 caracteres
                // Puedes mostrar un mensaje de error o tomar alguna otra acción
            }

        } elseif (!empty($this->alumno)) {

        }
    }

    private function cambiar_expediente()
    {
        //ss  dd($this->ciclo_escolares);
        if (!empty($this->idCicloEscolar) && !empty($this->idplantel) && empty($this->alumno)) {

            $this->grupos_posibles = GruposModel::where('ciclo_esc_id', $this->idCicloEscolar)
                ->where('plantel_id', $this->idplantel)->where('esc_grupo.gpo_base', '1')->get();
            /* Este es para cambios de grupo
            $this->grupos_posibles = GruposModel::join('esc_curso', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
            ->join('esc_calificaciones', 'esc_calificaciones.curso_id', '=', 'esc_curso')
            ->where('0');
            */
            $cicloSeleccionado = $this->ciclo_escolares->where('id', $this->idCicloEscolar)->first();
            $ciclo_nombre = $cicloSeleccionado->nombre;

            if (strlen($ciclo_nombre) >= 4) {
                $id_plantel = str_pad($this->idplantel, 2, "0", STR_PAD_LEFT);
                $inicio_expediente = substr($ciclo_nombre, 2, 2);

                $ultimo_expediente = AlumnoModel::select('noexpediente')
                    ->orderBy('noexpediente', 'desc')
                    ->where('noexpediente', 'like', $inicio_expediente . $id_plantel . '%')
                    ->first();

                if ($ultimo_expediente) {
                    $ultimosCuatroDigitos = substr($ultimo_expediente->noexpediente, -4);
                    $continuacion = intval($ultimosCuatroDigitos) + 1;
                } else {
                    $continuacion = 1;
                }

                // Asegura que siempre haya 4 dígitos, rellenando con ceros a la izquierda si es necesario.
                $continuacion = str_pad($continuacion, 4, "0", STR_PAD_LEFT);

                return $inicio_expediente . $id_plantel . $continuacion;
            } else {
                echo "El ciclo escolar no es valido";
                // Manejar el caso en el que la cadena no tenga al menos 4 caracteres
                // Puedes mostrar un mensaje de error o tomar alguna otra acción
            }

        } elseif (!empty($this->alumno)) {

        }
    }

    public function guardarDatos()
    {

        if (empty($this->alumno)) {
            $rules = [
                'datos_personales.nombre' => 'required|max:100',
                'datos_personales.edad' => 'required|max:100',
                'datos_personales.noexpediente' => 'required|numeric',
                'datos_personales.email' => 'required|max:300',
                'datos_personales.curp' => 'required|max:18',
                'datos_personales.apellidopaterno' => 'required|max:100',
                'datos_personales.fechanacimiento' => 'required|date',
                'datos_personales.sexo' => 'nullable|max:1',
                'datos_personales.domicilio' => 'required|max:100',
                'datos_personales.domicilio_noexterior' => 'required|max:20',
                'datos_personales.colonia' => 'required|max:100',
                'datos_personales.codigopostal' => 'required|max:10',
                'datos_personales.secundaria_nombre' => 'required|max:250',
                'datos_personales.secundaria_promedio' => 'required|max:20',
                'datos_personales.secundaria_fechaegreso' => 'required|date',
                //'grupo_inscribir' => 'required',
            ];
            $this->validate($rules);
            $this->datos_personales['plantel_id'] = $this->idplantel;
            $this->datos_personales["cicloesc_id"] = $this->idCicloEscolar;

        }

        //  dd($this->datos_personales);
        //dd($this->alumno);/*
        /*
        $buscar_alumno_antes = AlumnoModel::where('noexpediente', $this->datospersonales["noexpediente"])->first();
        if($buscar_alumno_antes){
            $this->asignacion();
            $this->guardarDatosEnBaseDeDatos($this->datos_personales);
        }else{
            $this->guardarDatosEnBaseDeDatos($this->datos_personales);
        }
*/

        $this->guardarDatosEnBaseDeDatos($this->datos_personales);

        // Guardar datos personales


        // Puedes agregar más lógica aquí, como emitir un mensaje de éxito
        //session()->flash('mensaje', 'Datos guardados correctamente');
    }
    public function cambiar_doc($numero_asignado)
    {
        $this->documento = $numero_asignado;
    }

    protected function guardarDatosEnBaseDeDatos($datos)
    {

        // Valida que tengas el ID necesario para la actualización
        if (isset($this->alumnoId)) {
            // Encuentra el modelo en la base de datos
            $modelo = AlumnoModel::find($this->alumnoId);

            // Actualiza los campos con los nuevos valores
            $modelo->update($datos);

            //$modelo->id_estatus = 1;
            //dd($datos);
            $modelo->save();
            /*
                        $valida_calif = GruposModel::
                            join('esc_curso', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                            ->leftJoin('esc_calificacion', 'esc_curso.id', '=', 'esc_calificacion.curso_id')
                            ->select('esc_calificacion.*') // Selecciona los campos que necesites verificar
                            ->first();

                        $valida_calif = GrupoAlumnoModel::where('esc_grupo_alumno.alumno_id', $this->alumnoId)
                            ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                            ->join('esc_curso', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                            ->leftJoin('esc_calificacion', 'esc_curso.id', '=', 'esc_calificacion.curso_id')
                            ->whereNull('esc_calificacion.id') // Verifica que no haya calificaciones registradas
                            ->exists();

            */
            if ($this->grupo_inscribir) {
                $nuevo_alumno_en_grupo = [
                    'grupo_id' => $this->grupo_inscribir,
                    'alumno_id' => $modelo->id,
                ];
                GrupoAlumnoModel::create($nuevo_alumno_en_grupo);

            }



            redirect()->route('adminalumnos.alumnos.index')->with('success', 'Alumno cargado con exito con el NoExpediente: ' . $this->datos_personales["noexpediente"]);


        } else {
            // Si no hay ID, crea un nuevo registro
            //$this->datos_personales["plantel_id"] = intval($this->idplantel, 10);
            //dd($this->idplantel);
            //dd($datos);
            //dd($datos["noexpediente"]);
            $buscar_expediente = AlumnoModel::where('noexpediente', $datos["noexpediente"])->first();
            if ($buscar_expediente) {
                $datos["noexpediente"] = $this->cambiar_expediente();
            }
            $nuevo_alumno = AlumnoModel::create($datos);
            $letra1_nombre = substr($nuevo_alumno->nombre, 0, 1);
            $letra1_apellido_materno = substr($nuevo_alumno->apellidopaterno, 0, 1);
            $todos_los_valores = 0;




            $nuevo_alumno->correo_institucional = $letra1_nombre . $letra1_apellido_materno . $nuevo_alumno->noexpediente . "@bachilleresdesonora.edu.mx";
            $nuevo_alumno->save();

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
                $nuevo_alumno_en_grupo = [
                    'grupo_id' => $this->grupo_inscribir,
                    'alumno_id' => $nuevo_alumno->id,
                ];

                GrupoAlumnoModel::create($nuevo_alumno_en_grupo);
            }

            redirect()->route('adminalumnos.alumnos.index')->with('success', 'Alumno cargado con exito con el NoExpediente ' . $nuevo_alumno->noexpediente);


        }
    }
}