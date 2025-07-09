<?php
// ANA MOLINA 16/09/2024
namespace App\Http\Livewire\Estadisticas\Credenciales;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use App\Models\Adminalumnos\AlumnoModel;

use App\EncryptDecrypt\Encrypt_Decrypt;


class FormComponent extends Component
{
    public $id_ciclo;
    public $id_plantel;

    public $id_grupo;
    public $apellidos;
    public $noexpediente;

    public $id_alumno_change = 0;

    public $alumnos_sel = '';


    protected $listeners = ['genbuscar'];


    public $porbuscar = false;

    public $alumnos = array();
    public $count_alumnos;
    public $ciclos;
    public $planteles;
    public $grupos;
    public $getalumnos;

    public function render()
    {


        // Obtener ciclos activos y el último ciclo cuyo tipo no sea 'V'
        // Obtener solo el ciclo activo y el ciclo anterior más cercano (solo 2)
        $activo = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('activo', '1')
            ->first();

        $anterior = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('id', '!=', $activo?->id)
            ->where('per_inicio', '<', $activo?->per_inicio)
            ->where('tipo', '!=', 'V') // Excluir ciclos cuyo tipo sea 'V'
            ->orderBy('per_inicio', 'desc')
            ->first();

        $this->ciclos = collect([$activo, $anterior])->filter();
        $this->planteles = obtenerPlanteles();
        $this->grupos = GruposModel::select('id', 'nombre', 'turno_id')->where('plantel_id', $this->id_plantel)->where(
            'ciclo_esc_id',
            $this->id_ciclo
        )->orderBy('nombre')
            ->get();

        if ($this->porbuscar == false) {
            if (empty($this->id_grupo))
                $this->getalumnos = null;
            else {
                ini_set('max_execution_time', 600); // 5 minutes

                $this->getalumnos = AlumnoModel::where('apellidos', 'LIKE', '%' . $this->apellidos . '%')->select(
                    'alu_alumno.id',
                    'alu_alumno.noexpediente',
                    'alu_alumno.apellidos',
                    'alu_alumno.nombre'
                )->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', 'alu_alumno.id')->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->where('esc_grupo_alumno.grupo_id', $this->id_grupo)->orderby('apellidos', 'asc')->orderby('nombre', 'asc')->distinct()->get();
                $this->count_alumnos = AlumnoModel::where('apellidos', 'LIKE', '%' . $this->apellidos . '%')->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', 'alu_alumno.id')->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                    ->where('esc_grupo_alumno.grupo_id', $this->id_grupo)->distinct()->count();

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

                }
            }

        }


        return view('livewire.estadisticas.credenciales.form-component');


    }



    public function changeEvent($id_grupo)
    {
        $this->porbuscar = false;
        //echo "<script>cargando(1);</script>";
        //dd( $grupospar);
    }

    public function genbuscar()
    {
        $this->porbuscar = true;
        $this->id_grupo = '';
        $this->getalumnos = null;

    }


}

