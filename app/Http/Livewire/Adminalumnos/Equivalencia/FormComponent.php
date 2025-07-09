<?php
// ANA MOLINA 10/07/2024
namespace App\Http\Livewire\Adminalumnos\Equivalencia;

use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Administracion\BitacoraModel;

use App\Models\Adminalumnos\EquivalenciacalifModel;
use App\Models\Adminalumnos\EquivalenciaModel;

use App\Models\Adminalumnos\AlumnoModel;

use App\Models\Adminalumnos\RevalidacioncalifModel;
use App\Models\Adminalumnos\RevalidacionModel;

use App\Models\Catalogos\PoliticaModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Catalogos\PlandeEstudioModel;
use App\Models\Catalogos\PlandeEstudioAsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Grupos\GruposModel;
use Carbon\Carbon;
use DateTime;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDF;

class FormComponent extends Component
{
    use WithFileUploads;

    public $equivalencia_id, $tipo_archivo;
    public $tipo;
    public $alumno_id = 0;
    public $alumno;
    public $folio;
    public $emitidopor;
    public $fecha;
    public $semestres;
    public $institucion;
    public $grados;
    public $periodo_escolar;
    public $lugar;
    public $expediente;
    public $firmadcto;
    public $cct;
    public $tipopa = '';
    public $s1 = false;
    public $s2 = false;
    public $s3 = false;
    public $s4 = false;
    public $s5 = false;
    public $prom1 = null;
    public $prom2 = null;
    public $prom3 = null;
    public $prom4 = null;
    public $prom5 = null;
    public $calif1 = '';
    public $calif2 = '';
    public $calif3 = '';
    public $calif4 = '';
    public $calif5 = '';
    public $ciclo1 = '';
    public $ciclo2 = '';
    public $ciclo3 = '';
    public $ciclo4 = '';
    public $ciclo5 = '';

    public $tipoe = true;
    public $tipor = false;
    public $tipop = false;
    public $tipoa = false;
    public $tipos = false;

    public $tipoer = 'E';
    public $fecha_aut;
    protected $listeners = ['autorizar'];


    public $validaciones, $todos_los_valores, $file;

    //datos para captura de calificaciones
    public $plantel_id;
    public $planestudio_id, $documento;

    //listado de asignaturas
    public $lista_asignaturas = [];

    public function mount()
    {
        $roles = session('roles');
        //dd($roles);
        /*
        foreach ($roles as $role) {
            if ($role === "alumno") {
                $saltar = 1;
            }

            if (
                $role === "control_escolar" || $role === "credenciales" || $role === "orientacion_educativa" || $role === "asistencia_educativa"
                || $role === "inclusion_educativa" || $role === "tutoria_grupal"
            ) {
                $this->todos_los_valores = 1;
                break;
            } elseif (strpos($role, "control_escolar_") === 0) {
                $this->validaciones[] = substr($role, 16);
                $this->todos_los_valores = 2;
                continue;
            } else {
                continue;
            }
        }
*/
        if ($this->equivalencia_id) {
            if ($this->tipo == 'E' || $this->tipos) {
                $equivalencia = EquivalenciaModel::find($this->equivalencia_id);
                $this->alumno_id = $equivalencia->alumno_id;
                $this->tipoer = 'E';
                $this->alumno = $equivalencia->alumno;
                $this->folio = $equivalencia->folio;
                $this->fecha = $equivalencia->fecha;
                $this->institucion = $equivalencia->institucion;
                $this->expediente = $equivalencia->expediente;
                $this->firmadcto = $equivalencia->firmadcto;
                $this->cct = $equivalencia->cct;
                $this->tipopa = $equivalencia->tipopa;
                if ($equivalencia->ciclo1 <> 0)
                    $this->s1 = true;
                if ($equivalencia->ciclo2 <> 0)
                    $this->s2 = true;
                if ($equivalencia->ciclo3 <> 0)
                    $this->s3 = true;
                if ($equivalencia->ciclo4 <> 0)
                    $this->s4 = true;
                if ($equivalencia->ciclo5 <> 0)
                    $this->s5 = true;
                $this->prom1 = $equivalencia->prom1;
                $this->prom2 = $equivalencia->prom2;
                $this->prom3 = $equivalencia->prom3;
                $this->prom4 = $equivalencia->prom4;
                $this->prom5 = $equivalencia->prom5;
                $this->calif1 = $equivalencia->calif1;
                $this->calif2 = $equivalencia->calif2;
                $this->calif3 = $equivalencia->calif3;
                $this->calif4 = $equivalencia->calif4;
                $this->calif5 = $equivalencia->calif5;
                $this->ciclo1 = $equivalencia->ciclo1;
                $this->ciclo2 = $equivalencia->ciclo2;
                $this->ciclo3 = $equivalencia->ciclo3;
                $this->ciclo4 = $equivalencia->ciclo4;
                $this->ciclo5 = $equivalencia->ciclo5;
                $this->plantel_id = $equivalencia->plantel_id;
                $this->planestudio_id = $equivalencia->planestudio_id;
                $this->fecha_aut = $equivalencia->fecha_aut;
                
                if($equivalencia->expediente == null){
                    $this->tipos = true;
                    $this->tipoe = false;
                    $this->tipor = false;
                }else{
                    $this->tipoe = true;
                    $this->tipor = false;
                }
                


                if ($equivalencia->tipopa == 'P') {
                    $this->tipop = true;
                    $this->tipoa = false;
                } else
                    if ($equivalencia->tipopa == 'A') {
                        $this->tipop = false;
                        $this->tipoa = true;
                    }

                $asignaturas = EquivalenciacalifModel::where('id', $this->equivalencia_id)->get();
            } else
                if ($this->tipo == 'R') {
                    $equivalencia = RevalidacionModel::find($this->equivalencia_id);
                    $this->alumno_id = $equivalencia->alumno_id;
                    $this->tipoer = 'R';
                    $this->alumno = $equivalencia->alumno;
                    $this->folio = $equivalencia->folio;
                    $this->emitidopor = $equivalencia->emitidopor;
                    $this->fecha = $equivalencia->fecha;
                    $this->semestres = $equivalencia->semestres;
                    $this->institucion = $equivalencia->institucion;
                    $this->grados = $equivalencia->grados;
                    $this->periodo_escolar = $equivalencia->periodo_escolar;
                    $this->lugar = $equivalencia->lugar;
                    $this->expediente = $equivalencia->expediente;
                    $this->firmadcto = $equivalencia->firmadcto;
                    if ($equivalencia->ciclo1 <> 0)
                        $this->s1 = true;
                    if ($equivalencia->ciclo2 <> 0)
                        $this->s2 = true;
                    if ($equivalencia->ciclo3 <> 0)
                        $this->s3 = true;
                    if ($equivalencia->ciclo4 <> 0)
                        $this->s4 = true;
                    if ($equivalencia->ciclo5 <> 0)
                        $this->s5 = true;

                    $this->ciclo1 = $equivalencia->ciclo1;
                    $this->ciclo2 = $equivalencia->ciclo2;
                    $this->ciclo3 = $equivalencia->ciclo3;
                    $this->ciclo4 = $equivalencia->ciclo4;
                    $this->ciclo5 = $equivalencia->ciclo5;
                    $this->plantel_id = $equivalencia->plantel_id;
                    $this->planestudio_id = $equivalencia->planestudio_id;
                    $this->fecha_aut = $equivalencia->fecha_aut;

                    $this->tipoe = false;
                    $this->tipor = true;
                    $asignaturas = RevalidacioncalifModel::where('id', $this->equivalencia_id)->get();

                }
            self::changeEventPlan(false);
            foreach ($this->lista_asignaturas as $index => $asig) {
                $dat = $asignaturas->where('asignatura_id', $asig['id_asignatura'])->first();

                if ($dat) {
                    $this->lista_asignaturas[$index]['sel'] = true;
                    $this->lista_asignaturas[$index]['calificacion'] = $dat->calificacion;
                    $this->lista_asignaturas[$index]['calif'] = $dat->calif;
                }

            }

        }
    }

    protected function eliminar_acentos($cadena)
    {

        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );

        return $cadena;
    }

    public function changeEventPlan($sel)
    {
        if ($this->equivalencia_id == null) {
            $plan_est_asignaturas = PlandeEstudioAsignaturaModel::where('id_planestudio', $this->planestudio_id)->get()
                ->sortBy([
                    fn($query) => $query->asignatura->periodo,
                    fn($query) => $query->asignatura->clave,
                ]);
        } else {
            $plan_est_asignaturas = PlandeEstudioAsignaturaModel::where('id_planestudio', $this->planestudio_id)->get()
                ->sortBy(function ($query) {
                    return $query->asignatura->periodo;
                });
        }

        $this->lista_asignaturas = [];
        if (isset($plan_est_asignaturas)) {

            foreach ($plan_est_asignaturas as $asig) {
                $nombres_existentes = array_column($this->lista_asignaturas, 'nombre');
                $this->lista_asignaturas[] = [
                    'id_asignatura' => $asig->id_asignatura,
                    'periodo' => $asig->asignatura->periodo,
                    'clave' => $asig->asignatura->clave,
                    'nombre' => $this->eliminar_acentos($asig->asignatura->nombre),
                    'calificacion' => null,
                    'calif' => '',
                    'sel' => $sel
                ];
                // Aquí hacemos la comparación exacta, incluyendo acentos
                /*
                if (in_array($this->eliminar_acentos($asig->asignatura->nombre), $nombres_existentes, false)) {
                    $sel = false;
                } else {
                    $sel = !(substr($asig->asignatura->clave, 1, 2) === "02" || substr($asig->asignatura->clave, 1, 2) === "13");
                    if ($this->equivalencia_id == null) {

                    } else {
                        if ($this->tipoe) {
                            $buscar_asignatura = EquivalenciacalifModel::where('asignatura_id', $asig->id_asignatura)->where('id', $this->equivalencia_id)->first();
                            if ($buscar_asignatura) {
                                $sel = true;
                            } else {
                                $sel = false;
                            }
                        }

                        if ($this->tipor) {
                            $buscar_asignatura = RevalidacioncalifModel::where('asignatura_id', $asig->id_asignatura)->where('id', $this->equivalencia_id)->first();
                            if ($buscar_asignatura) {
                                $sel = true;
                            } else {
                                $sel = false;
                            }
                        }

                    }

                  

                }*/


            }
        }

        // Ordena la lista por clave
        usort($this->lista_asignaturas, function ($a, $b) {
            return strcmp($a['clave'], $b['clave']);
        });
    }
    public function render()
    {
        $dat_alumno = AlumnoModel::find($this->alumno_id);
        if ($this->tipor == false) {
            /*$fecha_hoy = Carbon::now();
            dd($fecha_hoy->day);
            $this->fecha = $fecha_hoy->year . "-" . $fecha_hoy->month . "-" . $fecha_hoy->day;*/

            $fecha_hoy = new DateTime();
            $this->fecha = $fecha_hoy->format('Y-m-d');
        } else {

            //$this->fecha = today();
        }

        $planteles = obtenerPlanteles();
        $ciclos_esc = CicloEscModel::select('id', 'nombre', 'abreviatura', 'rango', 'activo', 'per_inicio', 'per_final')
            ->whereIn('id', function ($query) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('cat_ciclos_esc')
                    ->whereNotIn('nombre', [
                        'FEB-JUL PRUEBA 2022A',
                        'CICLO EVALUACIÓN EXTRA',
                        'PRUEBA_REINS',
                        'REVALIDACIÓN',
                        'TITULACIÓN'
                    ])
                    ->groupBy('nombre'); // Agrupar por nombre
            })
            ->orderBy('per_inicio', 'desc')
            ->get();


        $planes_estudio = PlandeEstudioModel::get();

        if ($dat_alumno) {
            $this->alumno = $dat_alumno->apellidos . " " . $dat_alumno->nombre;
        }
        #region Vista
        #endregion
        return view('livewire.adminalumnos.equivalencia.form-component', compact('dat_alumno', 'planteles', 'ciclos_esc', 'planes_estudio'));
    }

    public function cambiar_doc($numero_asignado, $alumno_id)
    {
        $this->documento = $numero_asignado;

        $archivo = ImagenesalumnoModel::where('tipo', $numero_asignado)
            ->where('alumno_id', $alumno_id)
            ->select('tipo', 'filename')
            ->first();

        if ($archivo) {
            // Obtiene la extensión del archivo del campo 'filename'
            $ext = strtolower(pathinfo($archivo->filename, PATHINFO_EXTENSION));

            // Mapea las extensiones a sus respectivos tipos MIME
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];

            // Asigna el tipo MIME a $this->tipo_archivo; si la extensión no se encuentra, asigna un tipo genérico
            $this->tipo_archivo = $mimeTypes[$ext] ?? 'application/octet-stream';

        }

    }

    public function guardarequidet($equi_id)
    {
        //inserta calificaciones
        foreach ($this->lista_asignaturas as $asigna) {
            if ($asigna['sel']) {
                $calificacion = null;
                $calif = '';
                $cicloesc_id = "";
                $guarda = false;
                $buscar_tipo = AsignaturaModel::find($asigna['id_asignatura']);

                if ($this->tipop) {
                    if ($this->s1 && $asigna['periodo'] == 1) {
                        if ($this->prom1 != null)
                            $calificacion = $this->prom1;
                        else
                            if ($this->calif1 != '')
                                $calif = $this->calif1;
                        $guarda = true;
                        $cicloesc_id = $this->ciclo1;
                    }
                    if ($this->s2 && $asigna['periodo'] == 2) {
                        if ($this->prom2 != null)
                            $calificacion = $this->prom2;
                        else
                            if ($this->calif2 != '')
                                $calif = $this->calif2;
                        $guarda = true;
                        $cicloesc_id = $this->ciclo2;
                    }
                    if ($this->s3 && $asigna['periodo'] == 3) {
                        if ($this->prom3 != null)
                            $calificacion = $this->prom3;
                        else
                            if ($this->calif3 != '')
                                $calif = $this->calif3;
                        $guarda = true;
                        $cicloesc_id = $this->ciclo3;
                    }
                    if ($this->s4 && $asigna['periodo'] == 4) {
                        if ($this->prom4 != null)
                            $calificacion = $this->prom4;
                        else
                            if ($this->calif4 != '')
                                $calif = $this->calif4;
                        $guarda = true;
                        $cicloesc_id = $this->ciclo4;
                    }
                    if ($this->s5 && $asigna['periodo'] == 5) {
                        if ($this->prom5 != null)
                            $calificacion = $this->prom5;
                        else
                            if ($this->calif5 != '')
                                $calif = $this->calif5;
                        $guarda = true;
                        $cicloesc_id = $this->ciclo5;
                    }
                } else
                    if ($this->tipoa) {
                        if ($this->s1 && $asigna['periodo'] == 1) {
                            $cicloesc_id = $this->ciclo1;
                            $guarda = true;
                        }
                        if ($this->s2 && $asigna['periodo'] == 2) {
                            $cicloesc_id = $this->ciclo2;
                            $guarda = true;
                        }
                        if ($this->s3 && $asigna['periodo'] == 3) {
                            $cicloesc_id = $this->ciclo3;
                            $guarda = true;
                        }
                        if ($this->s4 && $asigna['periodo'] == 4) {
                            $cicloesc_id = $this->ciclo4;
                            $guarda = true;
                        }
                        if ($this->s5 && $asigna['periodo'] == 5) {
                            $cicloesc_id = $this->ciclo5;
                            $guarda = true;
                        }

                        if ($guarda) {
                            if ($asigna["calificacion"] != null)
                                $calificacion = $asigna["calificacion"];
                            else
                                if ($asigna["calif"] != '')
                                    $calif = $asigna["calif"];

                        }
                    }

                if ($guarda) {
                    //$buscar_tipo->dsadsad;
                    $politica = PoliticaModel::where('id_areaformacion', $buscar_tipo->id_areaformacion)->first();

                    switch ($politica->id_variabletipo) {
                        case '1':
                            $numerica = true;
                            break;
                        case '2':
                            $numerica = false;
                            break;
                        default:
                            $numerica = null;
                            break;
                    }


                    if ($numerica) {
                        $datacalif = [
                            'id' => $equi_id,
                            'cicloesc_id' => $cicloesc_id,
                            'asignatura_id' => $asigna['id_asignatura'],
                            'calificacion' => $calificacion,
                            'calif' => null
                        ];
                    } else {
                        if ($calificacion && $calificacion > 60 || $calif == "AC") {
                            //dd($calificacion);
                            $datacalif = [
                                'id' => $equi_id,
                                'cicloesc_id' => $cicloesc_id,
                                'asignatura_id' => $asigna['id_asignatura'],
                                'calificacion' => null,
                                'calif' => 'AC'
                            ];
                        } else {
                            $datacalif = [
                                'id' => $equi_id,
                                'cicloesc_id' => $cicloesc_id,
                                'asignatura_id' => $asigna['id_asignatura'],
                                'calificacion' => null,
                                'calif' => 'NA'
                            ];
                        }
                    }

                    EquivalenciacalifModel::create($datacalif);
                }
            }

        }
    }

    public function guardarequi()
    {
        if ($this->tipop)
            $this->tipopa = 'P';
        else
            if ($this->tipoa)
                $this->tipopa = 'A';

        if ($this->ciclo1 == '')
            $this->ciclo1 = 0;
        if ($this->ciclo2 == '')
            $this->ciclo2 = 0;
        if ($this->ciclo3 == '')
            $this->ciclo3 = 0;
        if ($this->ciclo4 == '')
            $this->ciclo4 = 0;
        if ($this->ciclo5 == '')
            $this->ciclo5 = 0;

        if ($this->equivalencia_id == null) {
            $rules = [
                'alumno_id' => 'required',
                'alumno' => 'required|max:150',
                // 'folio' => 'required|max:20',
                'fecha' => 'required',
                'institucion' => 'required|max:150',
                //'expediente' => 'required|max:20',
                //'firmadcto' => 'required|max:150',
                'cct' => 'required|max:20',
                'tipopa' => 'required|max:1',
                'ciclo1' => 'required',
                'ciclo2' => 'required',
                'ciclo3' => 'required',
                'ciclo4' => 'required',
                'ciclo5' => 'required',
                'plantel_id' => 'required',
                'planestudio_id' => 'required',
                'file' => 'required'
            ];

        } else {
            $rules = [
                'alumno_id' => 'required',
                'alumno' => 'required|max:150',
                // 'folio' => 'required|max:20',
                'fecha' => 'required',
                'institucion' => 'required|max:150',
                'expediente' => 'required|max:20',
                'firmadcto' => 'required|max:150',
                'cct' => 'required|max:20',
                'tipopa' => 'required|max:1',
                'ciclo1' => 'required',
                'ciclo2' => 'required',
                'ciclo3' => 'required',
                'ciclo4' => 'required',
                'ciclo5' => 'required',
                'plantel_id' => 'required',
                'planestudio_id' => 'required'
            ];
        }


        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);


        if ($this->ciclo1 == '' || $this->s1 == false) {

            $this->ciclo1 = null;
            $this->calif1 = null;
            $this->prom1 = null;
        }


        if ($this->ciclo2 == '' || $this->s2 == false) {
            $this->ciclo2 = null;
            $this->calif2 = null;
            $this->prom2 = null;
        }

        if ($this->ciclo3 == '' || $this->s3 == false) {
            $this->ciclo3 = null;
            $this->calif3 = null;
            $this->prom3 = null;
        }

        if ($this->ciclo4 == '' || $this->s4 == false) {
            $this->ciclo4 = null;
            $this->calif4 = null;
            $this->prom4 = null;
        }

        if ($this->ciclo5 == '' || $this->s5 == false) {
            $this->ciclo5 = null;
            $this->calif5 = null;
            $this->prom5 = null;
        }

        //arreglo para ingresarlo a la tabla
        $data = [
            'alumno_id' => $this->alumno_id,
            'alumno' => $this->alumno,
            // 'folio' => $this->folio,
            'fecha' => $this->fecha,
            'institucion' => $this->institucion,
            'expediente' => $this->expediente,
            'firmadcto' => $this->firmadcto,
            'cct' => $this->cct,
            'tipopa' => $this->tipopa,
            'ciclo1' => $this->ciclo1,
            'ciclo2' => $this->ciclo2,
            'ciclo3' => $this->ciclo3,
            'ciclo4' => $this->ciclo4,
            'ciclo5' => $this->ciclo5,
            'prom1' => $this->prom1,
            'prom2' => $this->prom2,
            'prom3' => $this->prom3,
            'prom4' => $this->prom4,
            'prom5' => $this->prom5,
            'calif1' => $this->calif1,
            'calif2' => $this->calif2,
            'calif3' => $this->calif3,
            'calif4' => $this->calif4,
            'calif5' => $this->calif5,
            'plantel_id' => $this->plantel_id,
            'planestudio_id' => $this->planestudio_id,
            'user_id' => Auth()->user()->id
        ];
        if ($this->equivalencia_id == null) {
            //Crear registro
            if (Auth()->user()->hasPermissionTo('equivalencia-crear')) {
                EquivalenciaModel::create($data);
                $equivalencia = EquivalenciaModel::latest('id')->first();

                self::guardarequidet($equivalencia->id);

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Creó Equivalencia id:' . $equivalencia->id,
                ]);
                if (!Auth()->user()->hasPermissionTo('autorizar_rev')) {
                    if ($this->file) {
                        $buscar_alumno = AlumnoModel::find($this->alumno_id);
                        $nombre = "EQ_";
                        $fp = fopen($this->file->getRealPath(), 'r+b');
                        $data_f = fread($fp, filesize($this->file->getRealPath()));
                        fclose($fp);
                        $data = [
                            'imagen' => $data_f,
                            'no_expediente' => $buscar_alumno->noexpediente,
                            'alumno_id' => $this->alumno_id,
                            'filename' => $nombre . $buscar_alumno->noexpediente . substr($this->file->getRealPath(), -4),
                            'filesize' => filesize($this->file->getRealPath()),
                            'tipo' => '8',
                        ];

                        $imagen = ImagenesalumnoModel::where('alumno_id', $buscar_alumno->id)
                            ->where('tipo', '8')->first();
                        if ($imagen) {
                            //dd($imagen);
                            $imagen->update($data);
                        } else {

                            ImagenesalumnoModel::create($data);
                        }
                    }
                }


                redirect()->route('adminalumnos.equivalencia.index')->with('success', 'Equivalencia creado correctamente');
            } else {
                //No tiene los permisos necesarios

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Usuario sin permisos',
                ]);

                redirect()->route('adminalumnos.equivalencia.index')->with('danger', 'No tiene los permisos necesarios');
            }
        } else {
            //Editar registro
            if (Auth()->user()->hasPermissionTo('equivalencia-editar')) {
                $equivalencia = EquivalenciaModel::find($this->equivalencia_id);

                $equivalencia->update($data);

                EquivalenciacalifModel::where('id', $this->equivalencia_id)->delete();

                self::guardarequidet($this->equivalencia_id);


                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Editó Equivalencia id:' . $equivalencia->id,
                ]);
                if (!Auth()->user()->hasPermissionTo('autorizar_rev')) {
                    if ($this->file) {
                        $buscar_alumno = AlumnoModel::find($this->alumno_id);
                        $nombre = "EQ_";
                        $fp = fopen($this->file->getRealPath(), 'r+b');
                        $data_f = fread($fp, filesize($this->file->getRealPath()));
                        fclose($fp);
                        $data = [
                            'imagen' => $data_f,
                            'no_expediente' => $buscar_alumno->noexpediente,
                            'alumno_id' => $this->alumno_id,
                            'filename' => $nombre . $buscar_alumno->noexpediente . substr($this->file->getRealPath(), -4),
                            'filesize' => filesize($this->file->getRealPath()),
                            'tipo' => '8',
                        ];

                        $imagen = ImagenesalumnoModel::where('alumno_id', $buscar_alumno->id)
                            ->where('tipo', '8')->first();
                        if ($imagen) {
                            //dd($imagen);
                            $imagen->update($data);
                        } else {

                            ImagenesalumnoModel::create($data);
                        }
                    }

                }


                redirect()->route('adminalumnos.equivalencia.index')->with('success', 'Equivalencia editado correctamente');
            } else {
                //No tiene los permisos necesarios

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Usuario sin permisos',
                ]);

                redirect()->route('adminalumnos.equivalencia.index')->with('danger', 'No tiene los permisos necesarios');
            }
        }
    }

    public function guardarsinequi()
    {       
        
        #region Validaciones tipo calificacion, ciclo y reglas para cargar


        if ($this->tipop)
            $this->tipopa = 'P';
        else
            if ($this->tipoa)
                $this->tipopa = 'A';

        if ($this->ciclo1 == '')
            $this->ciclo1 = 0;
        if ($this->ciclo2 == '')
            $this->ciclo2 = 0;
        if ($this->ciclo3 == '')
            $this->ciclo3 = 0;
        if ($this->ciclo4 == '')
            $this->ciclo4 = 0;
        if ($this->ciclo5 == '')
            $this->ciclo5 = 0;

        if ($this->equivalencia_id == null) {
            $rules = [
                'alumno_id' => 'required',
                'alumno' => 'required|max:150',
                // 'folio' => 'required|max:20',
                'cct' => 'required|max:20',
                'tipopa' => 'required|max:1',
                'ciclo1' => 'required',
                'ciclo2' => 'required',
                'ciclo3' => 'required',
                'ciclo4' => 'required',
                'ciclo5' => 'required',
                'plantel_id' => 'required',
                'planestudio_id' => 'required',
            ];

        } else {
            $rules = [
                'alumno_id' => 'required',
                'alumno' => 'required|max:150',
                'tipopa' => 'required|max:1',
                'ciclo1' => 'required',
                'ciclo2' => 'required',
                'ciclo3' => 'required',
                'ciclo4' => 'required',
                'ciclo5' => 'required',
                'plantel_id' => 'required',
                'planestudio_id' => 'required'
            ];
        }

        $this->validate($rules);
        #endregion
        #region IF feos
        if ($this->ciclo1 == '' || $this->s1 == false) {

            $this->ciclo1 = null;
            $this->calif1 = null;
            $this->prom1 = null;
        }


        if ($this->ciclo2 == '' || $this->s2 == false) {
            $this->ciclo2 = null;
            $this->calif2 = null;
            $this->prom2 = null;
        }

        if ($this->ciclo3 == '' || $this->s3 == false) {
            $this->ciclo3 = null;
            $this->calif3 = null;
            $this->prom3 = null;
        }

        if ($this->ciclo4 == '' || $this->s4 == false) {
            $this->ciclo4 = null;
            $this->calif4 = null;
            $this->prom4 = null;
        }

        if ($this->ciclo5 == '' || $this->s5 == false) {
            $this->ciclo5 = null;
            $this->calif5 = null;
            $this->prom5 = null;
        }


        #endregion

        //arreglo para ingresarlo a la tabla
        $data = [
            'alumno_id' => $this->alumno_id,
            'alumno' => $this->alumno,
            // 'folio' => $this->folio,
            'institucion' => $this->institucion,
            'cct' => $this->cct,
            'tipopa' => $this->tipopa,
            'ciclo1' => $this->ciclo1,
            'ciclo2' => $this->ciclo2,
            'ciclo3' => $this->ciclo3,
            'ciclo4' => $this->ciclo4,
            'ciclo5' => $this->ciclo5,
            'prom1' => $this->prom1,
            'prom2' => $this->prom2,
            'prom3' => $this->prom3,
            'prom4' => $this->prom4,
            'prom5' => $this->prom5,
            'calif1' => $this->calif1,
            'calif2' => $this->calif2,
            'calif3' => $this->calif3,
            'calif4' => $this->calif4,
            'calif5' => $this->calif5,
            'plantel_id' => $this->plantel_id,
            'planestudio_id' => $this->planestudio_id,
            'user_id' => Auth()->user()->id
        ];

        if ($this->equivalencia_id == null) {
            //Crear registro
            if (Auth()->user()->hasPermissionTo('equivalencia-crear')) {
                EquivalenciaModel::create($data);
                $equivalencia = EquivalenciaModel::latest('id')->first();

                self::guardarequidet($equivalencia->id);

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Creó Equivalencia id:' . $equivalencia->id,
                ]);
                redirect()->route('adminalumnos.equivalencia.index')->with('success', 'Equivalencia creado correctamente');
            } else {
                //No tiene los permisos necesarios

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Usuario sin permisos',
                ]);

                redirect()->route('adminalumnos.equivalencia.index')->with('danger', 'No tiene los permisos necesarios');
            }
        } else {
            //Editar registro
            if (Auth()->user()->hasPermissionTo('equivalencia-editar')) {
                $equivalencia = EquivalenciaModel::find($this->equivalencia_id);

                $equivalencia->update($data);

                EquivalenciacalifModel::where('id', $this->equivalencia_id)->delete();

                self::guardarequidet($this->equivalencia_id);


                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Editó Equivalencia id:' . $equivalencia->id,
                ]);



                redirect()->route('adminalumnos.equivalencia.index')->with('success', 'Equivalencia editado correctamente');
            } else {
                //No tiene los permisos necesarios

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Usuario sin permisos',
                ]);

                redirect()->route('adminalumnos.equivalencia.index')->with('danger', 'No tiene los permisos necesarios');
            }
        }
    }
    public function guardarrevdet($rev_id)
    {
        //inserta calificaciones
        $cicloesc_id = "";
        foreach ($this->lista_asignaturas as $asigna) {
            if ($asigna['sel']) {

                $guarda = false;
                if ($this->s1 && $asigna['periodo'] == 1) {
                    $cicloesc_id = $this->ciclo1;
                    $guarda = true;
                }
                if ($this->s2 && $asigna['periodo'] == 2) {
                    $cicloesc_id = $this->ciclo2;
                    $guarda = true;
                }
                if ($this->s3 && $asigna['periodo'] == 3) {
                    $cicloesc_id = $this->ciclo3;
                    $guarda = true;
                }
                if ($this->s4 && $asigna['periodo'] == 4) {
                    $cicloesc_id = $this->ciclo4;
                    $guarda = true;
                }
                if ($this->s5 && $asigna['periodo'] == 5) {
                    $cicloesc_id = $this->ciclo5;
                    $guarda = true;
                }
                if ($guarda) {
                    $datacalif = [
                        'id' => $rev_id,
                        'cicloesc_id' => $cicloesc_id,
                        'asignatura_id' => $asigna['id_asignatura']
                    ];
                    RevalidacioncalifModel::create($datacalif);
                }
            }

        }
    }

    public function guardarrev()
    {

        if ($this->ciclo1 == '')
            $this->ciclo1 = 0;
        if ($this->ciclo2 == '')
            $this->ciclo2 = 0;
        if ($this->ciclo3 == '')
            $this->ciclo3 = 0;
        if ($this->ciclo4 == '')
            $this->ciclo4 = 0;
        if ($this->ciclo5 == '')
            $this->ciclo5 = 0;

        if ($this->equivalencia_id == null) {
            $rules = [
                'alumno_id' => 'required',
                'alumno' => 'required|max:150',
                //'folio' => 'required|max:20',
                'emitidopor' => 'required|max:150',
                'fecha' => 'required',
                'semestres' => 'required|max:100',
                'institucion' => 'required|max:150',
                'grados' => 'required|max:100',
                'periodo_escolar' => 'required|max:50',
                'lugar' => 'required|max:100',
                'expediente' => 'required|max:20',
                'firmadcto' => 'required|max:150',
                'ciclo1' => 'required',
                'ciclo2' => 'required',
                'ciclo3' => 'required',
                'ciclo4' => 'required',
                'ciclo5' => 'required',
                'plantel_id' => 'required',
                'planestudio_id' => 'required',
                'file' => 'required'


            ];
        } else {
            $rules = [
                'alumno_id' => 'required',
                'alumno' => 'required|max:150',
                //'folio' => 'required|max:20',
                'emitidopor' => 'required|max:150',
                'fecha' => 'required',
                'semestres' => 'required|max:100',
                'institucion' => 'required|max:150',
                'grados' => 'required|max:100',
                'periodo_escolar' => 'required|max:50',
                'lugar' => 'required|max:100',
                'expediente' => 'required|max:20',
                'firmadcto' => 'required|max:150',
                'ciclo1' => 'required',
                'ciclo2' => 'required',
                'ciclo3' => 'required',
                'ciclo4' => 'required',
                'ciclo5' => 'required',
                'plantel_id' => 'required',
                'planestudio_id' => 'required'
            ];
        }

        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

        if ($this->ciclo1 == '' || $this->s1 == false) {

            $this->ciclo1 = null;
            $this->calif1 = null;
        }


        if ($this->ciclo2 == '' || $this->s2 == false) {
            $this->ciclo2 = null;
            $this->calif2 = null;
        }

        if ($this->ciclo3 == '' || $this->s3 == false) {
            $this->ciclo3 = null;
            $this->calif3 = null;
        }

        if ($this->ciclo4 == '' || $this->s4 == false) {
            $this->ciclo4 = null;
            $this->calif4 = null;
        }

        if ($this->ciclo5 == '' || $this->s5 == false) {
            $this->ciclo5 = null;
            $this->calif5 = null;
        }

        //arreglo para ingresarlo a la tabla
        $data = [
            'alumno_id' => $this->alumno_id,
            'alumno' => $this->alumno,
            'folio' => $this->folio,
            'emitidopor' => $this->emitidopor,
            'fecha' => $this->fecha,
            'semestres' => $this->semestres,
            'institucion' => $this->institucion,
            'grados' => $this->grados,
            'periodo_escolar' => $this->periodo_escolar,
            'lugar' => $this->lugar,
            'expediente' => $this->expediente,
            'firmadcto' => $this->firmadcto,
            'ciclo1' => $this->ciclo1,
            'ciclo2' => $this->ciclo2,
            'ciclo3' => $this->ciclo3,
            'ciclo4' => $this->ciclo4,
            'ciclo5' => $this->ciclo5,
            'plantel_id' => $this->plantel_id,
            'planestudio_id' => $this->planestudio_id,
            'user_id' => Auth()->user()->id
        ];

        if ($this->equivalencia_id == null) {
            //Crear registro
            if (Auth()->user()->hasPermissionTo('equivalencia-crear')) {
                RevalidacionModel::create($data);
                $equivalencia = RevalidacionModel::latest('id')->first();

                self::guardarrevdet($equivalencia->id);
                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Creó Revalidacion id:' . $equivalencia->id,
                ]);
                if (!Auth()->user()->hasPermissionTo('autorizar_rev')) {
                    if ($this->file) {
                        $buscar_alumno = AlumnoModel::find($this->alumno_id);
                        $nombre = "Rev_";
                        $fp = fopen($this->file->getRealPath(), 'r+b');
                        $data_f = fread($fp, filesize($this->file->getRealPath()));
                        fclose($fp);
                        $data = [
                            'imagen' => $data_f,
                            'no_expediente' => $buscar_alumno->noexpediente,
                            'alumno_id' => $this->alumno_id,
                            'filename' => $nombre . $buscar_alumno->noexpediente . substr($this->file->getRealPath(), -4),
                            'filesize' => filesize($this->file->getRealPath()),
                            'tipo' => '7',
                        ];

                        $imagen = ImagenesalumnoModel::where('alumno_id', $buscar_alumno->id)
                            ->where('tipo', '7')->first();
                        if ($imagen) {
                            $imagen->update($data);
                        } else {
                            ImagenesalumnoModel::create($data);
                        }
                    }
                }


                redirect()->route('adminalumnos.equivalencia.index')->with('success', 'Revalidación creado correctamente');
            } else {
                //No tiene los permisos necesarios

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Usuario sin permisos',
                ]);

                redirect()->route('adminalumnos.equivalencia.index')->with('danger', 'No tiene los permisos necesarios');
            }
        } else {
            //Editar registro
            if (Auth()->user()->hasPermissionTo('equivalencia-editar')) {
                $equivalencia = RevalidacionModel::find($this->equivalencia_id);
                $equivalencia->update($data);

                RevalidacioncalifModel::where('id', $this->equivalencia_id)->delete();

                self::guardarrevdet($this->equivalencia_id);

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Editó Revalidación id:' . $equivalencia->id,
                ]);
                if (!Auth()->user()->hasPermissionTo('autorizar_rev')) {
                    if ($this->file) {
                        $buscar_alumno = AlumnoModel::find($this->alumno_id);
                        $nombre = "Rev_";
                        $fp = fopen($this->file->getRealPath(), 'r+b');
                        $data_f = fread($fp, filesize($this->file->getRealPath()));
                        fclose($fp);
                        $data = [
                            'imagen' => $data_f,
                            'no_expediente' => $buscar_alumno->noexpediente,
                            'alumno_id' => $this->alumno_id,
                            'filename' => $nombre . $buscar_alumno->noexpediente . substr($this->file->getRealPath(), -4),
                            'filesize' => filesize($this->file->getRealPath()),
                            'tipo' => '7',
                        ];

                        $imagen = ImagenesalumnoModel::where('alumno_id', $buscar_alumno->id)
                            ->where('tipo', '7')->first();
                        if ($imagen) {
                            //dd($imagen);
                            $imagen->update($data);
                        } else {

                            ImagenesalumnoModel::create($data);
                        }
                    }
                }



                redirect()->route('adminalumnos.equivalencia.index')->with('success', 'Revalidación editado correctamente');
            } else {
                //No tiene los permisos necesarios

                BitacoraModel::create([
                    'user_id' => Auth()->user()->id,
                    'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path' => $_SERVER["REQUEST_URI"],
                    'method' => $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component' => 'FormComponent',
                    'function' => 'guardar',
                    'description' => 'Usuario sin permisos',
                ]);

                redirect()->route('adminalumnos.equivalencia.index')->with('danger', 'No tiene los permisos necesarios');
            }
        }
    }
    public function guardar()
    {
        if ($this->tipoe) {
            self::guardarequi();
        } elseif ($this->tipor) {
            self::guardarrev();
        } elseif ($this->tipos) {
            self::guardarsinequi();
        }




    }
    function processer($opcion)
    {
        switch ($opcion) {
            case 'E':
                $this->tipoe = true;
                $this->tipor = false;
                $this->tipos = false;
                break;
            case 'R':
                $this->tipoe = false;
                $this->tipor = true;
                $this->tipos = false;
                break;
            case 'S':
                $this->tipoe = false;
                $this->tipor = false;
                $this->tipos = true;
                break;
            default:
                # code...
                break;
        }
        /*
                if ($opcion == "E") {
                    if ($this->tipoe)
                        $this->tipor = false;
                    else
                        $this->tipor = true;
                } else
                    if ($opcion == "R") {
                        if ($this->tipor)
                            $this->tipoe = false;
                        else
                            $this->tipoe = true;
                    }
                            */
    }
    function processpa($opcion)
    {
        if ($opcion == "P") {
            if ($this->tipop)
                $this->tipoa = false;
            else
                $this->tipoa = true;
        } else
            if ($opcion == "A") {
                if ($this->tipoa)
                    $this->tipop = false;
                else
                    $this->tipop = true;
            }
    }
    function autorizar()
    {
        $data = [
            'user_id_aut' => Auth()->user()->id,
            'fecha_aut' => now()
        ];

        if ($this->tipo == 'E') {
            $equi = EquivalenciaModel::where('id', $this->equivalencia_id)->first();
            $asignaturas = EquivalenciacalifModel::where('id', $this->equivalencia_id)->get();
        } else {
            if ($this->tipo == 'R') {
                $equi = RevalidacionModel::where('id', $this->equivalencia_id)->first();
                $asignaturas = RevalidacioncalifModel::where('id', $this->equivalencia_id)->get();
            }
        }

        //dd($asignaturas);
        $equi->update($data);

        //graba en calificaciones
        foreach ($asignaturas as $asig) {
            $ciclo_esc = CicloEscModel::find($asig->cicloesc_id);

            $grupo = GruposModel::where('plantel_id', $this->plantel_id)
                ->where('ciclo_esc_id', $asig->cicloesc_id)
                ->where('nombre', 'Migracion')
                ->first();

            if ($grupo == null) {
                $data = [
                    'turno_id' => 1,
                    'plantel_id' => $this->plantel_id,
                    'ciclo_esc_id' => $asig->cicloesc_id,
                    'periodo' => $ciclo_esc->nombre,
                    'aula_id' => 0,
                    'nombre' => 'Migracion',
                    'descripcion' => 'Migracion ' . date('Y-m-d'),
                ];
                $grupo = GruposModel::create($data);
            }

            //Asignar Grupo al alumno

            $asignar_grupo_alumno = GrupoAlumnoModel::where('grupo_id', $grupo->id)
                ->where('alumno_id', $this->alumno_id)
                ->first();

            if ($asignar_grupo_alumno == null) {
                $data_grupo = [
                    'grupo_id' => $grupo->id,
                    'alumno_id' => $this->alumno_id
                ];
                $asignacion_grupo = GrupoAlumnoModel::create($data_grupo);
            }

            $asignatura = AsignaturaModel::find($asig->asignatura_id);

            $curso = CursosModel::where('asignatura_id', $asig->asignatura_id)
                ->where('grupo_id', $grupo->id)
                ->first();

            if ($curso == null) {
                $data = [
                    'plan_estudio_id' => $equi->planestudio_id,
                    'asignatura_id' => $asig->asignatura_id,
                    'docente_id' => 0,
                    'grupo_id' => $grupo->id,
                    'curso_tipo' => 0,
                    'nombre' => $asignatura->nombre,
                ];

                $curso = CursosModel::create($data);
            }
            //dd($curso);
            if ($asig->calif == "REV") {
                $tipo = "7";
            } else {
                $tipo = "5";
            }


            if ($this->tipo == 'E') {
                $data = [
                    'alumno_id' => $this->alumno_id,
                    'politica_variable_id' => $asignatura->politica_variable_id('F'),
                    'calificacion_tipo_id' => $tipo,
                    'curso_id' => $curso->id,
                    'calificacion' => $asig->calificacion,
                    'calif' => $asig->calif,
                    'calificacion_tipo' => 'Final',
                ];
            } else {
                $data = [
                    'alumno_id' => $this->alumno_id,
                    'politica_variable_id' => $asignatura->politica_variable_id('F'),
                    'calificacion_tipo_id' => "7",
                    'curso_id' => $curso->id,
                    'calificacion' => $asig->calificacion,
                    'calif' => "REV",
                    'calificacion_tipo' => 'Final',
                ];
            }



            $califica = CalificacionesModel::create($data);

            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'EquivalenciaComponent',
                //'component'     =>  'FormComponent',
                'function' => 'importar',
                'description' => 'Carga curso_id:' . $curso->id . ' - ' . $curso->nombre . '. Carga la calificacion: ' . $califica->id . ' Final:' . $califica->calificacion . $califica->calif,
            ]);

        }

        $this->emit('autorizado');

    }

}
