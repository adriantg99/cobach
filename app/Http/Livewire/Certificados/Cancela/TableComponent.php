<?php
//ANA MOLINA 04/06/2024
namespace App\Http\Livewire\Certificados\Cancela;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use App\Models\Adminalumnos\AlumnoModel;

use App\Models\Certificados\CertificadoModel;
use App\Certificado\Certificado;

class TableComponent extends Component
{
    public $id_ciclo;
    public $id_plantel;

    public $id_grupo;
    public $apellidos;
    public $noexpediente;

    public $id_alumno_change=0;

    public $alumnos_sel='';

    protected $listeners = [ 'cangrupo','canalumno','canbuscar'];
    public $estatus='';

    public $porbuscar=false;

    public $alumnos=array();
    public $count_alumnos;
    public $ciclos;
    public $planteles;
    public $grupos;
    public $getalumnos;
    public function render()
    {
        $this->ciclos = CicloEscModel::select('id','nombre','per_inicio')->orderBy('per_inicio','desc')
        ->get();
        $this->planteles = PlantelesModel::select('id','nombre')->orderBy('id')
        ->get();
        $this->grupos = GruposModel::select('id','nombre','turno_id')->where('plantel_id', $this->id_plantel)->where('ciclo_esc_id',
        $this->id_ciclo)->orderBy('nombre')
        ->get();


        if ($this->porbuscar==false)
        {
            if (empty( $this->id_grupo) )
                $this->getalumnos=null;
           else
            {
                ini_set('max_execution_time', 600); // 5 minutes

                $this->getalumnos = DB::select('call pa_alumnos_certcancela(?)',array( $this->id_grupo));

                //dd( $this->id_grupo);
            }
        }
        else
        {
            if($this->noexpediente == null)
            {

                if (!empty ($this->apellidos))
                {
                    $alu = AlumnoModel::select(
                        'alu_alumno.id',
                        'alu_alumno.noexpediente',
                        'alu_alumno.apellidos',
                        'alu_alumno.nombre')->join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)->where('apellidos', 'LIKE','%'.$this->apellidos.'%');

                }
                else{
                    $this->alumnos=array();
                    $this->count_alumnos=0;
                }


                if(empty($this->id_plantel))
                {

                    if(empty($this->id_ciclo))
                    {
                        if (!empty ($this->apellidos))
                        {
                            $this->alumnos=$alu->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                            $this->count_alumnos= AlumnoModel::join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)->where('apellidos', 'LIKE','%'.$this->apellidos.'%')->count();
                        }

                    }
                    else
                    {
                        if (!empty ($this->apellidos))
                        {
                            $this->alumnos =$alu->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->distinct()->get();
                            $this->count_alumnos= AlumnoModel::where('apellidos', 'LIKE','%'.$this->apellidos.'%')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')->join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)
                            ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->distinct()->count();
                        }
                        else
                        {
                            /* $alumnos = AlumnoModel::select(
                                'alu_alumno.id',
                                'alu_alumno.noexpediente',
                                'alu_alumno.apellidos',
                                'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                            $count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count(); */
                            $this->alumnos=array();
                            $this->count_alumnos=0;
                        }
                    }
                }
                else
                {
                    if(empty($this->id_ciclo))
                    {
                        if (!empty ($this->apellidos))
                        {
                            $this->alumnos =$alu->where('alu_alumno.plantel_id', $this->id_plantel)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                            $this->count_alumnos= AlumnoModel::join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)->where('apellidos', 'LIKE','%'.$this->apellidos.'%')->where('alu_alumno.plantel_id', $this->id_plantel)->count();

                        }
                            else
                        {
                            $this->alumnos =AlumnoModel::select(
                                'alu_alumno.id',
                                'alu_alumno.noexpediente',
                                'alu_alumno.apellidos',
                                'alu_alumno.nombre')->join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)->where('plantel_id', $this->id_plantel)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                            $this->count_alumnos= AlumnoModel::join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)->where('plantel_id', $this->id_plantel)->count();
                        }
                    }
                    else
                    {
                        if (!empty ($this->apellidos))
                        {
                            $this->alumnos = $alu->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('alu_alumno.plantel_id', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->distinct()->get();
                            $this->count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')->join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)
                            ->where('apellidos', 'LIKE','%'.$this->apellidos.'%')->where('alu_alumno.plantel_id', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->distinct()->count();

                        }
                        else{
                            /* $alumnos = AlumnoModel::select(
                                'alu_alumno.id',
                                'alu_alumno.noexpediente',
                                'alu_alumno.apellidos',
                                'alu_alumno.nombre')->join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->orderby('apellidos','asc')->orderby('nombre','asc')->get();
                            $count_alumnos= AlumnoModel::join('esc_grupo_alumno','esc_grupo_alumno.alumno_id','alu_alumno.id')->join('esc_grupo','esc_grupo.id','=','esc_grupo_alumno.grupo_id')
                            ->where('id_plantel', $this->id_plantel)->where('esc_grupo.ciclo_esc_id', $this->id_ciclo)->count(); */
                            $this->alumnos=array();
                            $this->count_alumnos=0;
                        }
                    }
                }
            }
            else
            {
                $this->alumnos = AlumnoModel::select(
                    'alu_alumno.id',
                    'alu_alumno.noexpediente',
                    'alu_alumno.apellidos',
                    'alu_alumno.nombre')->join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)->where('noexpediente', $this->noexpediente)->get();
                $this->count_alumnos= AlumnoModel::join('esc_certificado','esc_certificado.alumno_id','=','alu_alumno.id')->where('vigente',true)->where('noexpediente', $this->noexpediente)->count();
                if ($this->count_alumnos==1)

                    $this->id_alumno_change=$this->alumnos[0]->id;
                /*  echo '<script type="text/javascript">
                    cargando('.$id_alumno.');
                        </script>'; */


            }

        }
        return view('livewire.certificados.cancela.table-component');
    }

  public function cangrupo($id_al,$observa)
  {

    //decodificar
    $al =  base64_decode($id_al);

    $alArray = explode(',', $al);

    foreach ($alArray as &$alumno_id) {
        //echo $alumno_id;
        Certificado::cancelar($alumno_id,$observa);

    }
    $this->dispatchBrowserEvent('finish_can');
    //session()->flash('message', 'mensaje!');

    // session()->flash('message', 'Proceso finalizado correctamente!');
    // $this->dispatchBrowserEvent('alert',
    // ['type' => 'info',  'message' => 'Proceso finalizado correctamente!']);

  }

  public function canalumno($id_alumno,$observa)
  {
     //decodificar
    $alumno_id =  base64_decode($id_alumno);
    //codificar base64_encode();

    Certificado::cancelar($alumno_id,$observa);
    $this->dispatchBrowserEvent('finish_can');

  }
  public function changeEventAlumno($id_alumno)
  {
      //echo "<script>cargando(1);</script>";
      $this->id_alumno_change=$id_alumno;
       //variable alumno seleccionado

      $cert=CertificadoModel::where('alumno_id', $id_alumno)->where('vigente', true)->first();
      $this->count_alumnos= CertificadoModel::where('alumno_id', $id_alumno)->count();

      if (isset ($cert))
          if ($cert->digital==null)
              $this->estatus='Generado';
          else
              $this->estatus='Digital';
      else
       $this->estatus='';
      //echo "<script>Swal.close()</script>";
 }

 public function changeEvent($id_grupo)
  {
    $this->porbuscar=false;
 }
 public function canbuscar()
 {
    $this->porbuscar=true;
    $this->id_grupo='';
 }


}
