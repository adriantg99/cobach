<?php
// ANA MOLINA 03/05/2024
namespace App\Http\Livewire\Certificados\Valida;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Certificados\CertificadoModel;

use  App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Certificado\Certificado;

class ValidarComponent extends Component
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
    public $certificado_id;
    public $message='';
    public $genoficio=false;
    public $busca=false;

    public $id_alumno_change;
    public $alumn;
    public $Calumn;
    public function render()
    {

        $folio=$this->folio;
        $curp=$this->curp;

        $img=$this->img;

        return view('livewire.certificados.valida.validar-component',compact('curp', 'folio','img'));
    }
    public function buscar()
    {
        $this->message='';
        $this->certificado_id=0;
        $cert=CertificadoModel::select('fecha_certificado','estatus','nombrealumno','fotocertificado','vigente','asignaturas','promedio','digital','cat_plantel.nombre','alu_alumno.noexpediente','alumno_id','esc_certificado.curp','esc_certificado.id','alu_alumno.sexo')
        ->join('alu_alumno', 'esc_certificado.alumno_id', '=', 'alu_alumno.id')
        ->join('cat_plantel', 'esc_certificado.plantel_id', '=', 'cat_plantel.id')
        ->where('esc_certificado.folio',$this->folio)->where('esc_certificado.curp',$this->curp)
        ->first();
        if (isset($cert))
        {
            $timed = strtotime($cert->fecha_certificado);
            $newfc = date('d-m-Y',$timed);
            $timed = strtotime($cert->digital);
            $newd = date('d-m-Y',$timed);
            $this->certificado_id=$cert->id;
            $this->nombrealumno=$cert->nombrealumno;
            $this->fecha_certificado=$newfc;
            $this->img=$cert->img;
            $this->plantel=$cert->nombre;
            $this->digital=$newd;
            $this->asignaturas=$cert->asignaturas;
            $this->promedio=$cert->promedio;

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
            $this->genoficio=true;
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
            $this->genoficio=false;
            $this->message='Certificado NO existe.';

            $this->dispatchBrowserEvent('noencuentra');

        }
        $this->busca=true;

    }
    public function changeEventAlumno($id_alumno)
    {
        $this->id_alumno_change=$id_alumno;
        $alumn=AlumnoModel::select('curp')
        ->where('id',$id_alumno)->first();
        if (isset($alumn))
            $this->curp=$alumn->curp;
    }
    public function changeParam($param)
    {

        $this->alumn=AlumnoModel::select('noexpediente','nombre','apellidos','curp','id')
        ->where('curp','like','%'.$param.'%')->orwhere('apellidos','like','%'.$param.'%')->get();

        $this->Calumn= $this->alumn->count();

    }

    public function limpiar()
    {
        $this->id_alumno_change=null;
        $this->alumn=null;
        $this->Calumn=null;
        $this->curp='';
        $this->folio='';

    }
}

