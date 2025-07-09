<?php
// ANA MOLINA 03/05/2024
namespace App\Http\Livewire\Certificados\Valida;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Certificados\CertificadoModel;

use App\Models\Certificados\CertificadovalModel;

use App\Models\Certificados\CertificadovalaModel;

use  App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Certificado\Oficio;

class FormComponent extends Component
{
    public $add_alumnos=false;

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
    public $guarda=false;
    public $agrega=false;

    public $oficio_id;
    public $fecha_solicitud;
    public $entidad;
    public $puesto;
    public $solicitante;
    public $email;
    public $oficio;
    public $numoficio;

    public $alumno_id;
    public $busca=false;
    public $sexo='el';

    public $id_alumno_change;
    public $alumn;
    public $Calumn;

    public $alumnos;
    public $count_alumnos;
    public $alumnosa;
    public $count_alumnosa;
    protected $listeners = ['borrarde_oficio'];

    public function mount()
    {

        if($this->oficio_id)
        {
            $ofi = CertificadovalModel::find($this->oficio_id);
            $this->fecha_solicitud = $ofi->fecha_solicitud;
            $this->oficio = $ofi->oficio;
            $this->entidad = $ofi->entidad;
            $this->puesto = $ofi->puesto;
            $this->solicitante = $ofi->solicitante;
            $this->email = $ofi->email;
            $this->numoficio = $ofi->numoficio;


            $this->guarda=true;


        }
        self::refresh();

    }
    public function buscar()
    {
        $this->message='';
        $this->certificado_id=0;
        $this->alumno_id=null;
        $this->nombrealumno='';
        $this->sexo='el';

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
            $this->alumno_id=$cert->alumno_id;
            $this->certificado_id=$cert->id;
            $this->nombrealumno=$cert->nombrealumno;
            $this->fecha_certificado=$newfc;
            $this->img=$cert->img;
            $this->plantel=$cert->nombre;
            $this->digital=$newd;
            $this->asignaturas=$cert->asignaturas;
            $this->promedio=$cert->promedio;
            if ($cert->sexo=='F')
                $this->sexo='la';
            else
                $this->sexo='el';
            if($cert->estatus=="T")

            $this->estatus="TOTAL";
            else
            $this->estatus="PARCIAL";
            if ($cert->vigente)
            $this->vigente="SI";
            else
            {
            $this->vigente="CANCELADO";
            $this->certificado_id=0;
            }
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
        $this->agrega=true;
        self::refresh();
    }

    public function enviar_validacion()
    {
        Oficio::enviar_oficio($this->email,$this->oficio_id);
        $this->dispatchBrowserEvent('finish_email');
    }
    public function guardar()
    {
        $this->oficio_id=Oficio::guarda_oficio($this->fecha_solicitud,
        $this->oficio,$this->entidad,$this->solicitante,$this->puesto,$this->email,$this->numoficio);
        $this->add_alumnos=true;
        $this->guarda=true;
        self::refresh();
    }

    public function agregara_oficio()
    {
        Oficio::agregara_oficio($this->oficio_id,$this->curp,$this->folio,$this->certificado_id,$this->alumno_id,$this->nombrealumno,$this->sexo);

        $this->agrega=false;
        $this->busca=false;
        self::refresh();

    }
    public function refresh()
    {
        $this->alumnos = CertificadovalaModel::where('id', $this->oficio_id)->where('certificado_id','!=','0')->get();
        $this->count_alumnos = CertificadovalaModel::where('id', $this->oficio_id)->where('certificado_id','!=','0')->count();

        $this->alumnosa = CertificadovalaModel::where('id', $this->oficio_id)->where('certificado_id','0')->get();
        $this->count_alumnosa = CertificadovalaModel::where('id', $this->oficio_id)->where('certificado_id','0')->count();
    }
    public function borrarde_oficio($curp,$folio)
    {
        Oficio::borrarde_oficio($this->oficio_id,$curp,$folio);
        $this->dispatchBrowserEvent('finish_borrar');
        self::refresh();
    }
    public function editar()
    {
        $this->add_alumnos=false;
        self::refresh();
    }
    public function cerrar()
    {
        $this->add_alumnos=true;
        self::refresh();
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
