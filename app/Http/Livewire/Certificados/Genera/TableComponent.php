<?php
// ANA MOLINA 06/03/2024
namespace App\Http\Livewire\Certificados\Genera;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use App\Models\Adminalumnos\AlumnoModel;

use App\Models\Certificados\CertificadoModel;
use App\Certificado\Certificado;

use App\EncryptDecrypt\Encrypt_Decrypt;
use App\PhpCfdi\Efirma;

use App\Models\Adminalumnos\ImagenesalumnoModel;

use App\Models\Catalogos\GeneralModel;


class TableComponent extends Component
{
    public $id_ciclo;
    public $id_plantel;

    public $id_grupo;
    public $apellidos;
    public $noexpediente;

    public $id_alumno_change = 0;
    public $calificaciones;

    public $chkplantel = false;
    public $id_plantelasigna = '';

    public $alumnos_sel = '';

    public $flagcalif = "";

    protected $listeners = ['genalumno', 'gengrupo', 'genbuscar'];

    public $flagautoridad = false;
    public $estatus = '';

    public $porbuscar = false;

    public $alumnos = array();
    public $count_alumnos;
    public $ciclos;
    public $planteles;
    public $grupos;
    public $getalumnos;

    public $message = "";
    public $message_alumno = "";


    public function render()
    {
        $gen = GeneralModel::where('directorgeneral', true)->count();
        if ($gen == 0)
            $this->flagautoridad = true;

        $this->ciclos = CicloEscModel::select('id', 'nombre', 'per_inicio')->orderBy('per_inicio', 'desc')
            ->get();
        $this->planteles = PlantelesModel::select('id', 'nombre')->orderBy('id')
            ->get();
        $this->grupos = GruposModel::select('id', 'nombre', 'turno_id', 'descripcion')->where('plantel_id', $this->id_plantel)->where(
            'ciclo_esc_id',
            $this->id_ciclo
        )->orderBy('nombre')
            ->get();

        if ($this->porbuscar == false) {
            $this->calificaciones = null;
            if (empty($this->id_grupo))
                $this->getalumnos = null;
            else {
                ini_set('max_execution_time', 600); // 5 minutes

                $this->getalumnos = DB::select('call pa_alumnos_certgenera(?)', array($this->id_grupo));


                //dd($this->getalumnos);
                //dd($this->id_grupo);
                //dd( $this->id_grupo);
            }
        } else {
            if ($this->noexpediente == null) {

                if (!empty($this->apellidos)) {
                    $alu = AlumnoModel::where('apellidos', 'LIKE', '%' . $this->apellidos . '%');

                } else {
                    $this->alumnos = array();
                    $this->count_alumnos = 0;
                }


                if (empty($this->id_plantel)) {

                    if (empty($this->id_ciclo)) {
                        if (!empty($this->apellidos)) {
                            $this->alumnos = $alu->orderby('apellidos', 'asc')->orderby('nombre', 'asc')->get();
                            $this->count_alumnos = AlumnoModel::where('apellidos', 'LIKE', '%' . $this->apellidos . '%')->count();
                        }

                    } else {
                        if (!empty($this->apellidos)) {
                            $this->alumnos = $alu->select(
                                'alu_alumno.id',
                                'alu_alumno.noexpediente',
                                'alu_alumno.apellidos',
                                'alu_alumno.nombre'
                            )->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', 'alu_alumno.id')->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                                ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos', 'asc')->orderby('nombre', 'asc')->distinct()->get();
                            $this->count_alumnos = AlumnoModel::where('apellidos', 'LIKE', '%' . $this->apellidos . '%')->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', 'alu_alumno.id')->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                                ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->distinct()->count();
                        } else {
                            /* $alumnos = AlumnoModel::select(
                                'alu_alumno.id',
                                'alu_alumno.noexpediente',
                                'alu_alumno.apellidos',
                                'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                            $count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count(); */
                            $this->alumnos = array();
                            $this->count_alumnos = 0;
                        }
                    }
                } else {
                    if (empty($this->id_ciclo)) {
                        if (!empty($this->apellidos)) {
                            $this->alumnos = $alu->where('plantel_id', $this->id_plantel)->orderby('apellidos', 'asc')->orderby('nombre', 'asc')->get();
                            $this->count_alumnos = AlumnoModel::where('apellidos', 'LIKE', '%' . $this->apellidos . '%')->where('plantel_id', $this->id_plantel)->count();

                        } else {
                            $this->alumnos = AlumnoModel::where('plantel_id', $this->id_plantel)->orderby('apellidos', 'asc')->orderby('nombre', 'asc')->get();
                            $this->count_alumnos = AlumnoModel::where('plantel_id', $this->id_plantel)->count();
                        }
                    } else {

                        if (!empty($this->apellidos)) {
                            $this->alumnos = $alu->select(
                                'alu_alumno.id',
                                'alu_alumno.noexpediente',
                                'alu_alumno.apellidos',
                                'alu_alumno.nombre'
                            )->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', 'alu_alumno.id')->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                                ->where('alu_alumno.plantel_id', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos', 'asc')->orderby('nombre', 'asc')->distinct()->get();
                            $this->count_alumnos = AlumnoModel::where('apellidos', 'LIKE', '%' . $this->apellidos . '%')->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', 'alu_alumno.id')->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                                ->where('alu_alumno.plantel_id', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->distinct()->count();

                        } else {
                            /* $alumnos = AlumnoModel::select(
                                'alu_alumno.id',
                                'alu_alumno.noexpediente',
                                'alu_alumno.apellidos',
                                'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                            $count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count(); */
                            $this->alumnos = array();
                            $this->count_alumnos = 0;
                        }
                    }
                }
            } else {
                $this->alumnos = AlumnoModel::where('noexpediente', $this->noexpediente)->get();
                $this->count_alumnos = AlumnoModel::where('noexpediente', $this->noexpediente)->count();
                if ($this->count_alumnos == 1) {
                    $this->id_alumno_change = $this->alumnos[0]->id;
                    /*  echo '<script type="text/javascript">
                        cargando('.$id_alumno.');
                            </script>'; */
                    $this->calificaciones = DB::select('call pa_certificado_visual(?)', array($this->id_alumno_change));
                    $this->flagcalif = $this->id_alumno_change;
                } else
                    $this->calificaciones = null;
            }

        }

        if ($this->flagautoridad)
            $this->dispatchBrowserEvent('efirmaautoridadeducativa');

        return view('livewire.certificados.genera.table-component');


    }
    function processMark()
    {
        if ($this->chkplantel)
            $this->chkplantel = false;
        else
            $this->chkplantel = true;

    }

    public function unificar_planes()
    {
        //dd($this->getalumnos);
        foreach ($this->getalumnos as $alumnos_grupo) {
            //$buscar_planes = DB::table('view_alumno_certifica')->where('alumno_id', $alumnos_grupo->id)->get();
            //dd();
            $buscar_planes = DB::table('view_alumno_certifica')
                ->where('alumno_id', $alumnos_grupo["id"])
                ->whereNotIn('plan_estudio_id', [0,2, 3, 4, 5, 6, 7, 8, 9])
                ->where('plan_estudio_id', '!=', '0')
                ->select('plan_estudio_id', DB::raw('SUM(asignaturas) as total_asignaturas'))
                ->groupBy('plan_estudio_id')
                ->orderBy('total_asignaturas', 'desc')
                ->first();
            //            dd($buscar_planes);
            if ($buscar_planes) {
                DB::table('esc_curso')
                    ->join('asi_asignatura', 'esc_curso.asignatura_id', '=', 'asi_asignatura.id')
                    ->join('asi_planestudio_asignatura', 'asi_asignatura.id', '=', 'asi_planestudio_asignatura.id_asignatura')
                    ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                    ->join('esc_calificacion', 'esc_calificacion.curso_id', '=', 'esc_curso.id')
                    ->where('esc_calificacion.calificacion_tipo_id', '<>', 0)
                    ->where('esc_calificacion.alumno_id', $alumnos_grupo["id"])
                    ->update(['esc_curso.plan_estudio_id' => $buscar_planes->plan_estudio_id]);
            } else {
                $planEstudioMasRepetido = DB::table('esc_curso')
                    ->join('esc_calificacion', 'esc_calificacion.curso_id', '=', 'esc_curso.id')
                    ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                    ->join('asi_asignatura', 'esc_curso.asignatura_id', '=', 'asi_asignatura.id')
                    ->leftJoin('asi_planestudio_asignatura', 'asi_planestudio_asignatura.id_asignatura', '=', 'asi_asignatura.id')
                    ->where('esc_calificacion.calificacion_tipo_id', '<>', 0)
                    ->where('esc_calificacion.alumno_id', $alumnos_grupo["id"])
                    ->whereNotIn('asi_planestudio_asignatura.id_planestudio', [2, 3, 4, 5, 6, 7, 8, 9])
                    ->select('asi_planestudio_asignatura.id_planestudio', DB::raw('COUNT(*) AS total_coincidencias'))
                    ->groupBy('asi_planestudio_asignatura.id_planestudio')
                    ->orderByDesc('total_coincidencias')
                    ->limit(1)
                    ->first();

                DB::table('esc_curso')
                    ->join('asi_asignatura', 'esc_curso.asignatura_id', '=', 'asi_asignatura.id')
                    ->join('asi_planestudio_asignatura', 'asi_asignatura.id', '=', 'asi_planestudio_asignatura.id_asignatura')
                    ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                    ->join('esc_calificacion', 'esc_calificacion.curso_id', '=', 'esc_curso.id')
                    ->where('esc_calificacion.calificacion_tipo_id', '<>', 0)
                    ->where('esc_calificacion.alumno_id', $alumnos_grupo["id"])
                    ->update(['esc_curso.plan_estudio_id' => $planEstudioMasRepetido->id_planestudio]);

            }
        }

        $this->getalumnos = DB::select('call pa_alumnos_certgenera(?)', array($this->id_grupo));
        $this->dispatchBrowserEvent('finish_unificacion', ['results' => true]);

        //$this->emit('unificacionCompleta');

    }

    public function changeEventPlantel($id_plantel)
    {
        //echo "<script>cargando(1);</script>";
        $this->message = "";
        $this->message_alumno = "";
        $this->id_plantelasigna = $id_plantel;

    }


    public function genalumno($id_alumno)
    {
        $this->message = "";
        $this->message_alumno = "";
        //decodificar
        $alumno_id = base64_decode($id_alumno);
        //codificar base64_encode();

        $resultados = self::genera($alumno_id, null);
        $this->calificaciones = null;

        //session()->flash('message', 'mensaje!');

        if ($resultados == '')
            $this->dispatchBrowserEvent('finish_gen', ['results' => true]);
        else {
            $this->dispatchBrowserEvent('finish_gen', ['results' => false]);

            $this->message = nl2br('NO se generó el proceso completamente:\n' . $resultados);

        }

    }
    public function genera($alumno_id, $datacarg)
    {
        // echo 'gen';
        // echo $alumno_id;
        $result = Certificado::find_certificado($alumno_id);
        $ban = '';
        $verifica = false;
        if (!isset($result) || $result->digital != null) {
            //fotografía
            //tipo = 2 es la foto de certificado del alumno
            $imagen_find = ImagenesalumnoModel::where('alumno_id', $alumno_id)->where('tipo', 2)->get();
            //si no tiene foto, no genera certificado
            if ($imagen_find->count() > 0) {
                $img = $imagen_find[0]['imagen'];

                //dd();
                if (!empty($datacarg)) {
                    $data = $datacarg;
                    //dd("Entre aqui");
                }
                    
                else {
                    //busca datos del último periodo escolar
                    $data = DB::select("SELECT concat(titulo,SPACE(1),cat_general.nombre) as directorgeneral
                ,efirma_password,efirma_file_certificate,efirma_file_key,numcertificado,sellodigital
                ,cat_general.id as general_id,esc_grupo.plantel_id
                FROM esc_grupo_alumno
                LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.GRUPO_ID
                left join cat_ciclos_esc on cat_ciclos_esc.id=esc_grupo.ciclo_esc_id
                left join cat_general on /*per_inicio>=fechainicio and directorgeneral=1 and*/ (per_inicio<=fechafinal or fechafinal is null)
                WHERE alumno_id =" . $alumno_id . "
                AND esc_grupo.plantel_id != '34'
                ORDER BY per_inicio DESC
                LIMIT 1");

                   //dd("aqui entre");
                }
                //dd($data);
                $director = $data[0]->directorgeneral;
                $general_id = $data[0]->general_id;
                $this->id_plantel = $data[0]->plantel_id;

                //$alumno_find = AlumnoModel::find($alumno_id);
                $alumnos = AlumnoModel::where('id', $alumno_id)->get();
                $alumno = $alumnos[0]->nombre . ' ' . $alumnos[0]->apellidos;
                $noexpediente = $alumnos[0]->noexpediente;
                $curp = $alumnos[0]->curp;

                //obtiene calificaciones y promedio
                $calificacionescer = DB::select('call pa_certificado(?)', array($alumno_id));
                $suma = 0;
                $materias = 0;
                foreach ($calificacionescer as $cal) {
                    if (isset($cal->periodo1)) {
                        if (is_numeric($cal->calificacion1) && $cal->calificacion1 >= 60) {
                            $materias = $materias + 1;
                            $suma = $suma + $cal->calificacion1;
                        } elseif ($cal->calificacion1 != "NA") {
                            $materias = $materias + 1;
                        }

                    }
                    if (isset($cal->periodo2)) {
                        if (is_numeric($cal->calificacion2) && $cal->calificacion2 >= 60) {
                            $materias = $materias + 1;
                            $suma = $suma + $cal->calificacion2;
                        } elseif ($cal->calificacion2 != "NA") {
                            $materias = $materias + 1;
                        }
                    }
                }
                $promedio = $suma / $materias;

                if ($verifica == true) {
                    $efirma_password = Encrypt_Decrypt::decrypt($data[0]->efirma_password);

                    //directorio de ubicacion .cer y .key
                    $direct = 'efirma-' . $general_id . '/';
                    //FIRMA ELECTRÓNICA (fiel) y Certificado Sello Digital (CSD)
                    //constante efirma

                    $csdarr = Efirma::get_certificado('Certificado-' . $alumno_id, $direct . $data[0]->efirma_file_certificate, $direct . $data[0]->efirma_file_key, $efirma_password);
                    //dd($csdarr);
                    $numcsd = $csdarr[0];
                    $csd = $csdarr[1];
                } else {
                    $numcsd = $data[0]->numcertificado;
                    $csd = $data[0]->sellodigital;
                }

                $plantel = '';
                if ($this->chkplantel == "checked")
                    if ($this->id_plantelasigna != '')
                        $plantel = $this->id_plantelasigna;
                    else
                        $plantel = $this->id_plantel;
                else
                    $plantel = $this->id_plantel;

                $resulmater = Certificado::generar_folio($alumno_id, $alumno, $img, $director, $general_id, $numcsd, $csd, $plantel, $curp);

                //$pdf = PDF::loadView('certificados.certificado.reporte', array('alumno_id'=>$alumno_id,'resulmater'=>$resulmater,'calificacionescer'=>$calificacionescer));
                //dd(Efirma::prueba());

                $certificado_id = $resulmater["certificado_id"];
                $numfol = $resulmater["folio"];
                $folio = 'CB-' . $curp . '-' . strval($numfol);
            } else {
                $alumnos = AlumnoModel::where('id', $alumno_id)->get();
                $noexpediente = $alumnos[0]->noexpediente;
                $ban = 'El alumno ' . $noexpediente . ' NO tiene imagen para Certificado.\n';
            }


        }
        return $ban;
    }
    public function gengrupo($id_al, $id_grupo)
    {
        $this->message = "";
        $this->message_alumno = "";
        $this->finicio = 'FI' . date("Y-m-d H:i:s");
        //decodificar
        $al = base64_decode($id_al);
        $grupo_id = base64_decode($id_grupo);

        //busca datos del último periodo escolar
        $data = DB::select("SELECT concat(titulo,SPACE(1),cat_general.nombre) as directorgeneral
    ,efirma_password,efirma_file_certificate,efirma_file_key,numcertificado,sellodigital
    ,cat_general.id as general_id,esc_grupo.plantel_id
    FROM esc_grupo_alumno
    LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.GRUPO_ID
    left join cat_ciclos_esc on cat_ciclos_esc.id=esc_grupo.ciclo_esc_id
    left join cat_general on /*per_inicio>=fechainicio and directorgeneral=1 and*/ (per_inicio<=fechafinal or fechafinal is null)
    WHERE esc_grupo_alumno.GRUPO_ID=" . $grupo_id . "
    ORDER BY per_inicio DESC
    LIMIT 1");

        $alArray = explode(',', $al);
        $resultados = '';

        foreach ($alArray as &$alumno_id) {
            //echo $alumno_id;
            $res = self::genera($alumno_id, $data);
            if ($res != '')
                $resultados = $resultados . ' ' . $res;
        }
        //session()->flash('message', 'mensaje!');

        if ($resultados == '')
            $this->dispatchBrowserEvent('finish_gen', ['results' => true]);
        else {
            $this->dispatchBrowserEvent('finish_gen', ['results' => false]);

            $this->message = nl2br('NO se generó el proceso completamente: \n' . $resultados);

        }
        $this->ffinal = 'FF' . date("Y-m-d H:i:s");
    }
    public function changeEventAlumno($id_alumno)
    {
        //echo "<script>cargando(1);</script>";
        $this->message = "";
        $this->message_alumno = "";
        $this->flagcalif = "";
        $this->id_alumno_change = $id_alumno;
        //variable alumno seleccionado

        if (empty($id_alumno)) {
            $this->calificaciones = DB::select('call pa_certificado_visual(?)', array(0));
        } else {
            $this->calificaciones = DB::select('call pa_certificado_visual(?)', array($id_alumno));
        }
        $this->flagcalif = $this->id_alumno_change;

        $cert = CertificadoModel::where('alumno_id', $id_alumno)->where('vigente', true)->first();
        $this->count_alumnos = CertificadoModel::where('alumno_id', $id_alumno)->count();

        if (isset($cert))
            if ($cert->digital == null)
                $this->estatus = 'Generado';
            else
                $this->estatus = 'Digital';
        else
            $this->estatus = '';
        //echo "<script>Swal.close()</script>";
    }
    public function changeEvent($id_grupo)
    {
        $this->porbuscar = false;
        $this->message = "";
        $this->message_alumno = "";
        $this->flagcalif = "";
        $this->calificaciones = null;
        //echo "<script>cargando(1);</script>";
        //dd( $grupospar);
    }

    public function genbuscar()
    {
        $this->porbuscar = true;
        $this->id_grupo = '';
        $this->getalumnos = null;
    }
    public function revisaimagen($alumno_id)
    {
        $ima = ImagenesalumnoModel::where('alumno_id', $alumno_id)->where('tipo', 2)->get();
        return $ima;
    }

}

