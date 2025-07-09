<?php
// ANA MOLINA 13/06/2024
namespace App\Certificado;
use App\Models\Catalogos\PlantelesModel;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Certificado\Certificado;

use App\Certificado\Certificadoemail;

use App\Certificado\Oficioemail;
use App\Models\Adminalumnos\AlumnoModel;

use App\Models\Certificados\CertificadovalModel;
use Mail;
use App\Models\Certificados\CertificadodigitalModel;
class Envioemail
{
    public static function email($email, $alumno, $alumno_id, $file_pdf)
    {
        try {
            $cert = Certificado::find_certificado($alumno_id);
            if (!$cert) {
                throw new Exception("Certificado no encontrado para el alumno ID: $alumno_id");
            }

            $busqueda = PlantelesModel::find($cert->plantel_id);
            if (!$busqueda) {
                throw new Exception("Plantel no encontrado para el ID: $cert->plantel_id");
            }
            $buscar_plantel = $busqueda->nombre;
            $certificado_estatus = $cert->estatus;
            $certificado_tipo = $cert->original;

            if ($cert->email == null) {
                $certifica_update = [
                    'emailtime' => date("Y-m-d H:i:s"),
                    'email' => $email,
                ];
                $cert->update($certifica_update);
            }

            // Datos del correo
            $mailData = [
                'title' => 'Certificado Electrónico',
                'body' => 'Colegio de Bachilleres del Estado de Sonora.',
                'alumno' => $alumno,
                'file_pdf' => $file_pdf,
                'plantel' => $buscar_plantel,
                'alumno_id' => $alumno_id,
                'certificado_estatus' => $certificado_estatus,
                'certificado_tipo' => $certificado_tipo,
            ];
            $correo_alumno = AlumnoModel::find($alumno_id);
            // Enviar correo

            if (!empty($correo_alumno->email)) {
                Mail::to($email)
                    ->cc($correo_alumno->email) // Dirección de correo en copia visible
                    ->bcc('cola.certificados@bachilleresdesonora.edu.mx')
                    ->send(new Certificadoemail($mailData));
            } else {
                Mail::to($email)

                    ->cc($correo_alumno->email) // Dirección de correo en copia visible
                    ->bcc('cola.certificados@bachilleresdesonora.edu.mx')
                    ->send(new Certificadoemail($mailData));
            }
        } catch (Exception $e) {
            // Aquí puedes registrar el error o notificarlo de alguna forma
            error_log("Error al enviar el correo: " . $e->getMessage());
            error_log("Último alumno enviado: " . $alumno);

            return response()->json(['success' => false, 'message' => "Error al enviar el correo para el alumno: " . $alumno, 'error' => $e->getMessage()]);
        }
    }
    public static function nuevosalumnos($ciclo, $email)
    {
        $mailData = [
            'title' => 'Nuevo Ingreso',
            'body' => 'Colegio de Bachilleres del Estado de Sonora.',
            'alumno' => $alumno,
            'file_pdf' => $file_pdf,
            'plantel' => $buscar_plantel,
            'alumno_id' => $alumno_id,
            'certificado_estatus' => $certificado_estatus,
        ];

        Mail::to($email)
            ->bcc('cola.certificados@bachilleresdesonora.edu.mx')
            ->send(new Certificadoemail($mailData));
    }
    public static function enviargrupo($id_al)
    {
        //decodificar
        $sel_alumnos = base64_decode($id_al);
        //codificar base64_encode();

        $arralumno = explode(",", $sel_alumnos);

        //dd($arralumno);
        if (!is_dir('certificados')){
            mkdir('certificados', 0775, true);
        }
            

        if (!is_dir('certificados/temp')){
            mkdir('certificados/temp', 0700, true);

        }
        foreach ($arralumno as $alumno) {
            //echo $alumno;
            //descarga en temporal

            $idcert = Certificado::find_certificado($alumno);


            if ($idcert->emailtime != null) {
                continue;
            }

            $cert = CertificadodigitalModel::where('id', $idcert->id)->first();

            //dd($cert);

            file_put_contents('certificados/temp/certificado.pdf', $cert->certificado_digital);

            //envía correo
            $alumn = AlumnoModel::where('id', $alumno)->first();
            self::email($alumn->correo_institucional, $alumn->nombre . ' ' . $alumn->apellidos, $alumn->id, 'certificados/temp/certificado.pdf');
        }

        //decodificar
        // $al =  base64_decode($id_al);
        // $grupo_id= base64_decode($id_grupo);
        // //return view('certificados.certificado.reportegrupo',  array('alumnos_sel'=>$al,'grupo_id'=>$grupo_id));//->render();
        //  $pdf = PDF::loadView('certificados.certificado.reportegrupo', array('alumnos_sel'=>$al,'grupo_id'=>$grupo_id));
        //  return $pdf->stream('certificagrupos.pdf');
    }

    public static function email_oficio($oficio_id, $email, $fileaut_pdf, $fileapo_pdf)
    {
        $cert = CertificadovalModel::where('id', $oficio_id)->first();
        //dd($cert);
        if (isset($cert)) {
            $solicita = $cert->solicitante;
            $certifica_enviado =
                [
                    'enviado' => date("Y-m-d H:i:s")
                ];
            $cert->update($certifica_enviado);

            //enviar correo
            $mailData = [
                'title' => 'Oficio de validación',
                'body' => 'Colegio de Bachilleres del Estado de Sonora.',
                'fileaut_pdf' => $fileaut_pdf,
                'fileapo_pdf' => $fileapo_pdf,
                'solicitante' => $solicita
            ];


            Mail::to($email)->send(new Oficioemail($mailData));
        }

        //dd("Email is sent successfully.");
    }
    public static function enviar_oficio($email, $oficio_id)
    {


        if (!is_dir('certificados'))
            mkdir('certificados', 0700);

        if (!is_dir('certificados/temp'))
            mkdir('certificados/temp', 0700);

        //descarga en temporal

        $pdf_auto = Oficio::oficio_autenticidad($oficio_id);
        $pdf_apo = Oficio::oficio_apocrifo($oficio_id);

        $pdf_auto->save('certificados/temp/' . 'oficio_autent_' . $oficio_id . '.pdf');
        $pdf_auto->download('oficio_autent_' . $oficio_id . '.pdf');

        $pdf_apo->save('certificados/temp/' . 'oficio_apocri_' . $oficio_id . '.pdf');
        $pdf_apo->download('oficio_apocri_' . $oficio_id . '.pdf');

        self::email_oficio($oficio_id, $email, 'certificados/temp/oficio_autent_' . $oficio_id . '.pdf', 'certificados/temp/oficio_apocri_' . $oficio_id . '.pdf');
    }
}

