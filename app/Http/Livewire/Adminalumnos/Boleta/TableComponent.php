<?php
// ANA MOLINA 08/11/2023
namespace App\Http\Livewire\Adminalumnos\Boleta;


use App\Models\Adminalumnos\AlumnoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableComponent extends Component
{
    public $id_ciclo="";
    public $id_plantel="";
    public $apellidos="";
    public $noexpediente="";

    public $id_alumno_change=0;
    public $calificaciones;
    public $calificacionesex;

    public $calificacionesgrupo;
    public $grupos_sel='';

    public $flagcalif="";

    public function render()
    {
        if (empty ($this->noexpediente ))
        {

            if (!empty ($this->apellidos))
            {
                $alu = AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%');
            }
            else{
                $alumnos=array();
                $count_alumnos=0;
            }


            if(empty($this->id_plantel))
            {

                if(empty($this->id_ciclo))
                {
                    if (!empty ($this->apellidos))
                    {
                        $alumnos=$alu->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->count();
                    }

                }
                else
                {
                    if (!empty ($this->apellidos))
                    {
                    $alumnos =$alu->select(
                        'alu_alumno.id',
                        'alu_alumno.noexpediente',
                        'alu_alumno.apellidos',
                        'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                    ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                    $count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                    ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count();
                    }
                    else
                    {
                        $alumnos = AlumnoModel::select(
                            'alu_alumno.id',
                            'alu_alumno.noexpediente',
                            'alu_alumno.apellidos',
                            'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                        ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                        ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count();
                        }
                }
            }
            else
            {
                if(empty($this->id_ciclo))
                {
                    if (!empty ($this->apellidos))
                    {
                        $alumnos =$alu->where('id_plantel', $this->id_plantel)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->where('id_plantel', $this->id_plantel)->count();

                    }
                          else
                    {
                        $alumnos =AlumnoModel::where('id_plantel', $this->id_plantel)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::where('id_plantel', $this->id_plantel)->count();
                    }
                }
                else
                {
                   if (!empty ($this->apellidos))
                    {
                        $alumnos = $alu->select(
                            'alu_alumno.id',
                            'alu_alumno.noexpediente',
                            'alu_alumno.apellidos',
                            'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                        ->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                        ->where('apellidos', 'LIKE','%'.$this->apellidos.'%')->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count();

                    }
                    else{
                        $alumnos = AlumnoModel::select(
                            'alu_alumno.id',
                            'alu_alumno.noexpediente',
                            'alu_alumno.apellidos',
                            'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                        ->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                        $count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                        ->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count();
                    }
                }
            }
        }
        else
        {
            $alumnos = AlumnoModel::where('noexpediente', $this->noexpediente)->get();
            $count_alumnos= AlumnoModel::where('noexpediente', $this->noexpediente)->count();
            if ($count_alumnos==1)
            {
                $this->id_alumno_change=$alumnos[0]->id;
               /*  echo '<script type="text/javascript">
                cargando('.$id_alumno.');
                    </script>'; */
                $this->calificaciones = DB::select('call pa_boletaex(?,?,?)',array( $this->id_alumno_change,$this->id_ciclo,0));
                $this->calificacionesex = DB::select('call pa_boletaex(?,?,?)',array( $this->id_alumno_change,$this->id_ciclo,1));
                $this->flagcalif=$this->id_alumno_change;
            }
        }
        $calificaciones=$this->calificaciones;
        $calificacionesex=$this->calificacionesex;

        return view('livewire.adminalumnos.boleta.table-component',compact('alumnos', 'count_alumnos','calificaciones','calificacionesex'));
    }

    public function changeEventAlumno($id_alumno)
    {
        //echo "<script>cargando(1);</script>";
        $this->flagcalif="";
        $this->id_alumno_change=$id_alumno;
         //variable alumno seleccionado

        if (empty($id_alumno))
        {
            $this->calificaciones = DB::select('call pa_boletaex(?,?,?)',array(0,0,0)) ;
            $this->calificacionesex = DB::select('call pa_boletaex(?,?,?)',array(0,0,1)) ;
        }
        else
        {

            $this->calificaciones = DB::select('call pa_boletaex(?,?,?)',array($id_alumno,$this->id_ciclo,0));
            $this->calificacionesex = DB::select('call pa_boletaex(?,?,?)',array($id_alumno,$this->id_ciclo,1));
        }
        session()->put('calificaciones', $this->calificaciones);
        session(['calificaciones' =>  $this->calificaciones ]);

        session()->put('calificacionesex', $this->calificacionesex);
        session(['calificacionesex' =>  $this->calificacionesex ]);
        $this->flagcalif=$this->id_alumno_change;

        //echo "<script>Swal.close()</script>";
   }

   public function changeEvent($grupospar)
   {
      $this->flagcalif="";
       //echo "<script>cargando(1);</script>";
    //dd( $grupospar);
       $this->grupos_sel=$grupospar;

  }


}

