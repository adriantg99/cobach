<?php
// ANA MOLINA 13/06/2024
namespace App\Certificado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Certificados\CertificadoModel;
use App\Models\Certificados\CertificadovalModel;
use App\Models\Certificados\CertificadovalaModel;
use App\Models\Documentos\OficiosModel;
use App\Models\Catalogos\GeneralModel;

use App\Certificado\Envioemail;

use App\Models\Adminalumnos\AlumnoModel;
use DOMDocument;
use DOMAttr;

use App\EncryptDecrypt\Encrypt_Decrypt;
use Dompdf\Dompdf;
use PDF;
class Oficio
{

    public static function oficio($oficio_id)
    {

            $certval=CertificadovalModel::select('fecha_solicitud','oficio','entidad','solicitante','puesto','email','numoficio')
            ->where('id',$oficio_id)->first();

            $doficio=OficiosModel::select('atentamente','puesto','concopia')
            ->where('id',1)->first();
            //    validación_certificado=1


               $solicitante=$certval->solicitante;
               $fecha_solicitud=$certval->fecha_solicitud;
               $oficio=$certval->oficio;
               $numoficio=$certval->numoficio;
               $entidad= $certval->entidad;
               $puesto=$certval->puesto;

               $jefeservesc=$doficio->atentamente;
               $puestojefeservesc=$doficio->puesto;
               $concopia=str_replace('|', '<br>', $doficio->concopia);
               $con1=nl2br($concopia);
               $result= [
                "oficio"=> $oficio,
                "solicitante"=> $solicitante,
                'entidad'=>$entidad,
                'numoficio'=>$numoficio,
                "puesto"=> $puesto,
                "fecha_solicitud"=> $fecha_solicitud,
                "jefeservesc"=>$jefeservesc,
                "concopia"=>$con1,
                'puestojefeservesc'=>$puestojefeservesc
            ];

            return $result;

    }
    public static function oficio_certifica($certificado_id)
    {

           $cert=CertificadoModel::select('fecha_certificado','estatus','nombrealumno','vigente','alu_alumno.noexpediente','alumno_id')
           ->join('alu_alumno', 'esc_certificado.alumno_id', '=', 'alu_alumno.id')
           ->where('esc_certificado.id',$certificado_id)->where('esc_certificado.vigente','1')
          // ->where('esc_certificado.folio',$folio)->where('esc_certificado.curp',$curp)
           ->first();
           if (isset($cert))
           {
               $timed = strtotime($cert->fecha_certificado);
               $newfc = date('d-m-Y',$timed);

              if($cert->estatus=="T")

               $estatus="TOTAL";
               else
               $estatus="PARCIAL";
               if ($cert->vigente)
               $vigente="SI";
               else
               $vigente="CANCELADO";

               $result= [
                "nombrealumno"=> $cert->nombrealumno,
                "fecha_certificado"=> $newfc,
                "estatus"=> $estatus,
                "vigente"=> $vigente,
                "noexpediente"=> $cert->noexpediente
            ];

            return $result;
           }
    }
    public static function oficio_autenticidad($oficio_id)
    {

           $detalle=CertificadovalaModel::select('curp','folio','certificado_id', 'alumno_id','nombrealumno', 'el_la')
          ->where('id',$oficio_id)->where('certificado_id','!=','0')->get();

          $datos=self::oficio($oficio_id);

            // return view('certificados.valida.oficioaut', array( 'datos'=>$datos,'detalle'=>$detalle));
            $pdf = PDF::loadView('certificados.valida.oficioaut', array( 'datos'=>$datos,'detalle'=>$detalle));

            return $pdf;

    }
    public static function oficio_apocrifo($oficio_id)
    {

           $detalle=CertificadovalaModel::select('curp','folio','certificado_id', 'alumno_id','nombrealumno', 'el_la')
          ->where('id',$oficio_id)->where('certificado_id', '0')->get();

           $datos=self::oficio($oficio_id);

            // return view('certificados.valida.oficioapo', array( 'datos'=>$datos,'detalle'=>$detalle));
            $pdf = PDF::loadView('certificados.valida.oficioapo', array( 'datos'=>$datos,'detalle'=>$detalle));

         return $pdf;

    }
    public static function guarda_oficio($fecha_solicitud,$oficio,$entidad,$solicitante,$puesto,$email,$numoficio)
    {
         try {
            $exis = CertificadovalModel::where('oficio',$oficio)->where('fecha_solicitud',$fecha_solicitud)->first();
            if (!isset($exis))
            {
                $data = [
                    'fecha_solicitud'=>$fecha_solicitud,
                    'oficio'=>$oficio,
                    'entidad'=>$entidad,
                    'solicitante'=>$solicitante,
                    'puesto'=>$puesto,
                    'email'=>$email,
                    'numoficio'=>$numoficio,
                    'user_id' => Auth()->user()->id,
                ];
                CertificadovalModel::create($data);
                $id = CertificadovalModel::latest('id')->first()->id;
            }
            else
            {
                $id=$exis->id;

                $data = [
                    'fecha_solicitud'=>$fecha_solicitud,
                    'oficio'=>$oficio,
                    'entidad'=>$entidad,
                    'solicitante'=>$solicitante,
                    'puesto'=>$puesto,
                    'email'=>$email,
                    'numoficio'=>$numoficio
                ];

                $exis->update($data);
            }
             return $id;
        } catch (\Exception $e) {
        }
    }
    public static function agregara_oficio($id,$curp,$folio,$certificado_id,$alumno_id,$nombrealumno,$el_la)
    {
         try {

            $exis = CertificadovalaModel::where('id',$id)->where('curp',$curp)->where('folio',$folio)->first();
            if (!isset($exis))
            {
                $data = [
                    'id'=>$id,
                    'curp'=>$curp,
                    'folio'=>$folio,
                    'certificado_id'=>$certificado_id,
                    'alumno_id'=>$alumno_id,
                    'nombrealumno'=>$nombrealumno,
                    'el_la'=>$el_la
                ];

                CertificadovalaModel::create($data);
            }


        } catch (\Exception $e) {

        }
    }
    public static function borrarde_oficio($id,$curp,$folio)
    {
         try {

            $certoficio = CertificadovalaModel::where('id',$id)->where('curp',$curp)->where('folio',$folio);

            $certoficio->delete();

        } catch (\Exception $e) {

        }
    }
    public static function enviar_oficio($email,$oficio_id)
    {
        Envioemail::enviar_oficio($email,$oficio_id);
    }

}
