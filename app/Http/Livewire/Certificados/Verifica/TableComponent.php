<?php
// ANA MOLINA 03/05/2024
namespace App\Http\Livewire\Certificados\Verifica;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Certificados\CertificadoModel;

use  App\Models\Adminalumnos\ImagenesalumnoModel;

class TableComponent  extends Component
{
    public $folio;
    public $curp;
    public $nombrealumno;
    public $fecha_certificado;
    public $img;
    public $plantel;
    public $digital;
    public $asignaturas;
    public $promedio;
    public $estatus;
    public $vigente;

    public $message='';


    public function render()
    {

        return view('livewire.certificados.verifica.table-component');
    }
    public function buscar()
    {
        $this->message='';
        $alumno = AlumnoModel::where('curp', $this->curp)->first();
        if($alumno){
            $cert=CertificadoModel::select('fecha_certificado','estatus','nombrealumno','fotocertificado','vigente','asignaturas','promedio','digital','cat_plantel.nombre','alu_alumno.noexpediente','alumno_id','esc_certificado.curp')
            ->join('alu_alumno', 'esc_certificado.alumno_id', '=', 'alu_alumno.id')
            ->join('cat_plantel', 'esc_certificado.plantel_id', '=', 'cat_plantel.id')
            ->where('esc_certificado.folio',$this->folio)->where('esc_certificado.curp',$this->curp)
            ->first();
    
            $view = DB::table('view_alumno_certifica')->where('alumno_id', $alumno->id)->first();
            $calificacionescer = DB::select('call pa_certificado(?)', array($alumno->id));
    
            $suma = 0;
            $materias = 0;
            foreach ($calificacionescer as $cal) {
                if (isset($cal->periodo1)) {
                    $materias = $materias + 1;
                    $suma = $suma + $cal->calificacion1;
                }
                if (isset($cal->periodo2)) {
                    $materias = $materias + 1;
                    $suma = $suma + $cal->calificacion2;
                }
            }
            $promedio = $suma / $materias;
             if (isset($cert))
            {
                $timed = strtotime($cert->fecha_certificado);
                $newfc = date('d-m-Y',$timed);
                $timed = strtotime($cert->digital);
                $newd = date('d-m-Y',$timed);
                $this->nombrealumno=$cert->nombrealumno;
                $this->fecha_certificado=$newfc;
                $this->img=$cert->img;
                $this->plantel=$cert->nombre;
                $this->digital=$newd;
                $this->asignaturas=$view->asignaturas;
                $this->promedio=number_format($promedio);
                if($cert->estatus=="T")
    
                $this->estatus="TOTAL";
                else
                $this->estatus="PARCIAL";
                if ($cert->vigente)
                $this->vigente="SI";
                else
                $this->vigente="CANCELADO";
                $imagen_find = ImagenesalumnoModel::where('alumno_id',$cert->alumno_id)->where('tipo',2)->get();
                //si no tiene foto, no genera certificado
                if ($imagen_find->count()>0)
                {
                    $this->img=$imagen_find[0]['imagen'];
                }
            }
            else
            {
                $this->nombrealumno='';
                $this->fecha_certificado='';
                $this->img=null;
                $this->plantel='';
                $this->digital='';
                $this->asignaturas='';
                $this->promedio='';
                $this->estatus='';
                $this->vigente='';
                $this->message='Certificado NO existe.';
    
                $this->dispatchBrowserEvent('noencuentra');
            }
    
        }
        else{
            
        }
        
    }


}

