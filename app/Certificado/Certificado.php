<?php
// ANA MOLINA 13/06/2024
namespace App\Certificado;

use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Certificados\CertificadoModel;
use App\Models\Certificados\CertificadorevModel;
use App\Models\Certificados\CertificadogenModel;
use App\Models\Certificados\CertificadocanModel;
use App\Models\Catalogos\GeneralModel;

use App\Models\Adminalumnos\ImagenesalumnoModel;

use App\Certificado\Envioemail;

use App\Models\Grupos\GruposModel;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Certificados\CertificadodigitalModel;
use DOMDocument;
use DOMAttr;

use App\EncryptDecrypt\Encrypt_Decrypt;
use App\PhpCfdi\Efirma;
use App\PhpQRcode\QRcodigo;
use Dompdf\Dompdf;
use NumberFormatter;
use PDF;

class Certificado
{

    public static function find_certificado($alumno_id)
    {
        $fol = CertificadoModel::where('alumno_id', $alumno_id)->where('vigente', '1')->orderBy('created_at', 'desc')->first();
        return $fol;
    }
    public static function generar_folio($alumno_id, $nombrealu, $fotocer, $nomauto, $general_id, $numcsd, $csd, $plantel_id, $curp)
    {
        //guarda en la bitácora la emisión del certificado
        $fecha = date('d-m-Y');
        $folio = 1;

        $esta = DB::table('view_alumno_certifica')->where('alumno_id', $alumno_id)->first();

        $alumno_busqueda = AlumnoModel::find($alumno_id);
        $determina_capacitacion = $alumno_busqueda->capacitacion();
        if (in_array($esta->plan_estudio_id, [11, 12, 13, 14, 15, 16])) {
            $buscar_reprobadas = DB::select('CALL pa_kardex_Aprob_Reprob(?)', [$alumno_id]);
            // Verificamos que haya resultados y luego accedemos a la columna 'reprobadas'
            if (isset($buscar_reprobadas[0]) && $buscar_reprobadas[0]->reprobadas == 0 && $buscar_reprobadas[0]->aprobadas >= 40) {
                $estatus = "T";
            }
            else{
                $estatus = "P";
            }
        } else {
            if ($determina_capacitacion == "DES_MICR_ANTERIOR") {
                if ($esta->asignaturas == 60 || $esta->asignaturas == 58) {
                    $estatus = "T";
                } else {
                    $estatus = "P";
                }
            } else if ($determina_capacitacion == "DES_MICR") {
                if ($esta->asignaturas == 59) {
                    $estatus = "T";
                } else {
                    $estatus = "P";
                }

            } else {
                if ($esta->asignaturas == 60) {
                    $estatus = "T";
                } else {
                    $estatus = "P";
                }
            }
        }



        //if ($esta->estatus == "T")
        $fecha = $esta->fechacertifica;
        //dd($fecha);

        // Verifica si $fecha no es nulo antes de intentar formatearlo
        if ($fecha !== null) {
            // Crea un objeto DateTime desde la fecha en formato dd-mm-yyyy
            $dateTime = DateTime::createFromFormat('d-m-Y', $fecha);

            // Verifica si la conversión fue exitosa
            if ($dateTime) {
                // Formatea la fecha al formato Año-Mes-Día
                $fecha = $dateTime->format('Y-m-d');
            } else {
                // Maneja el error de conversión, por ejemplo, registrando un mensaje de error
                error_log("Error al convertir la fecha: $fecha");
            }
        }
        //dd($fecha);


        $asi = $esta->asi;
        $promedio = $esta->promedio;

        DB::beginTransaction();

        try {
            //$fecha=$fol->created_at->format('d-m-Y');
            //dd($esta);
            $nextfol = DB::table('esc_certificado_folio')->first();
            $fecha_texto = Carbon::now()->toDate();
            if (isset($nextfol)) {
                $folio = $nextfol->ultimofolio + 1;
                DB::table('esc_certificado_folio')->update([
                    'ultimofolio' => $folio,
                    'updated_at' => $fecha_texto
                ]);

            } else {
                DB::insert('INSERT INTO esc_certificado_folio (ultimofolio, updated_at) VALUES (?, ?)', [
                    $folio,
                    $fecha_texto
                ]);
                /*DB::table('esc_certificado_folio')->insert(['ultimofolio'=>$folio,
                'updated_at' => $fecha_texto]);*/


            }
            if($plantel_id == 31){
                $plantel_id = 1;
            }
            $data = [
                'folio' => $folio,
                'alumno_id' => $alumno_id,
                'curp' => $curp,
                'user_id' => Auth()->user()->id,
                'fecha_certificado' => $fecha,
                'original' => true,
                'estatus' => $estatus,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'nombrealumno' => $nombrealu,
                //'fotocertificado'=>$fotocer,
                'vigente' => true,
                'general_id' => $general_id,
                'autoridadeducativa' => $nomauto,
                'numcertificadoautoridad' => $numcsd,
                'sellodigitalautoridad' => $csd,
                'plantel_id' => $plantel_id,
                'asignaturas' => $asi,
                'promedio' => intval($promedio)
            ];
            //dd($data);
            $certificado_id = CertificadoModel::create($data)->id;
            DB::commit();

            $resulmater = [
                "estatus" => $estatus,
                "fecha" => $fecha,
                "folio" => $folio,
                "original" => '',
                "certificado_id" => $certificado_id,
            ];
            $datag = [
                'certificado_id' => $certificado_id,
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'generado' => true,
            ];

            CertificadogenModel::create($datag);
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();

            $resulmater = null;

        }
        return $resulmater;
    }

    public static function cancelar($alumno_id, $observa)
    {

        DB::beginTransaction();

        try {

            $data = [
                'vigente' => false
            ];

            $certificado = self::find_certificado($alumno_id);

            $cert_id = $certificado->id;
            $certificado->update($data);

            $datac = [
                'certificado_id' => $certificado->id,
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'motivo' => $observa,
            ];

            CertificadocanModel::create($datac);

            $cert = CertificadodigitalModel::where('id', $cert_id)->first();
            if (isset($cert)) {
                $datc = [
                    'vigente' => false
                ];
                $cert->update($datc);
            }



            DB::commit();


        } catch (\Exception $e) {
            //dd($e);
            DB::rollback();

        }
    }


    public static function get_xml($filenameXML, $calificacionescer, $folio, $csd, $numcsd, $curp, $nomalumno, $datos)
    {
        // Set the content type to be XML, so that the browser will   recognise it as XML.
        //header( "content-type: application/xml; charset=utf-8" );

        // "Create" the document.
        //$xml = new DOMDocument( "1.0", "utf-8" );
        $xml = new DOMDocument();
        $xml->encoding = 'utf-8';
        $xml->xmlVersion = '1.0';
        $xml->formatOutput = true;

        // Create some elements.
        $xml_cert = $xml->createElement("CertificadoElectronico");
        //$xml_cert->setAttribute( "folio", $folio );
        //efirma
        $firma = $xml->createElement('FirmaAutoridadEducativa');
        $attr_firma = new DOMAttr('nombre', $datos['directorgeneral']);
        $firma->setAttributeNode($attr_firma);
        $attr_firma = new DOMAttr('certificado', $folio);
        $firma->setAttributeNode($attr_firma);
        $attr_firma = new DOMAttr('nocertificado', $numcsd);
        $firma->setAttributeNode($attr_firma);
        $attr_firma = new DOMAttr('sello', $csd);
        $firma->setAttributeNode($attr_firma);

        //plantel
        $plantel = $xml->createElement('Plantel');
        $attr_pl = new DOMAttr('clave', $datos['plantel_id']);
        $plantel->setAttributeNode($attr_pl);
        $attr_pl = new DOMAttr('nombre', $datos['plantel']);
        $plantel->setAttributeNode($attr_pl);

        //alumno
        $alumno = $xml->createElement('Alumno');
        $attr_al = new DOMAttr('noexpediente', $datos['noexpediente']);
        $alumno->setAttributeNode($attr_al);
        $attr_al = new DOMAttr('nombre', $nomalumno);
        $alumno->setAttributeNode($attr_al);

        $attr_al = new DOMAttr('curp', $curp);
        $alumno->setAttributeNode($attr_al);

        //asignaturas
        $asigna = $xml->createElement('Asignaturas');
        $attr_as = new DOMAttr('total', "0");
        $asigna->setAttributeNode($attr_as);

        foreach ($calificacionescer as $calif) {
            if (isset($calif->materia1)) {
                $asignadet1 = $xml->createElement('Asignatura');
                $attr_asi = new DOMAttr('nombre', $calif->materia1);
                $asignadet1->setAttributeNode($attr_asi);
                $attr_asi = new DOMAttr('ciclo', $calif->periodo1);
                $asignadet1->setAttributeNode($attr_asi);

                $attr_asi = new DOMAttr('calificacion', $calif->calificacion1);
                $asignadet1->setAttributeNode($attr_asi);

                $asigna->appendChild($asignadet1);
            }
            if (isset($calif->materia2)) {
                $asignadet2 = $xml->createElement('Asignatura');

                $attr_asi = new DOMAttr('nombre', $calif->materia2);
                $asignadet2->setAttributeNode($attr_asi);

                $attr_asi = new DOMAttr('ciclo', $calif->periodo2);
                $asignadet2->setAttributeNode($attr_asi);

                $attr_asi = new DOMAttr('calificacion', $calif->calificacion2);
                $asignadet2->setAttributeNode($attr_asi);

                $asigna->appendChild($asignadet2);
            }
        }

        $xml_cert->appendChild($firma);
        $xml_cert->appendChild($plantel);
        $xml_cert->appendChild($alumno);
        $xml_cert->appendChild($asigna);
        $xml->appendChild($xml_cert);

        $xml->save($filenameXML);
    }
    public static function get_codigoqr($numfol, $curp)
    {
        //CODIGO QR
        //include('App\PhpQRcode\phpqrcode.php');
        //$q=QRcodigo::prueba();
        // $codigoqr= QRcodigo::get_raw(strval($resulmater["folio"])); // creates code image and outputs it directly into browser


        //$codigoqr= QRcodigo::get_png($folio); // creates code image and outputs it directly into browser
        //$codigoqr= QRcodigo::get_text(strval($resulmater["folio"])); // creates code image and outputs it directly into browser

        // echo $codigoqr;

        ob_start();
        QRcodigo::get_png("https://sice.cobachsonora.edu.mx/certificados/verificar/" . $curp . "/" . $numfol);
        $result_qr_content_in_png = ob_get_contents();
        ob_end_clean();
        // PHPQRCode change the content-type into image/png... we change it again into html
        $result_qr_content_in_base64 = base64_encode($result_qr_content_in_png);
        //'codigoqr.png'
        // dd($codigoqr);

        return $result_qr_content_in_base64;
    }
    public static function get_folio($alumno_id, $curp, $revisa)
    {
        $numfol = 0;
        $resulmater = [];
        $certificado_id = 0;

        //guarda en la bitácora la emisión del certificado

        $fol = self::find_certificado($alumno_id);

        try {
            if (isset($fol)) {
                $original = "";
                $orig = true;

                $alumno = $fol->nombrealumno;
                $numfol = $fol->folio;
                $certificado_id = $fol->id;
                $img = $fol->fotocertificado;


                if ($orig == false)
                    $original = "DUPLICADO";

                $folio = 'CB-' . $curp . '-' . strval($fol->folio);
                $director = $fol->autoridadeducativa;

                if ($fol->estatus == "T")
                    $estatus = "TOTALMENTE";
                else
                    $estatus = "PARCIALMENTE";

                //dd($fol);

                $original = $fol->original;

                $resulmater = [
                    "estatus" => $estatus,
                    "fecha" => $fol->fecha_certificado,
                    "folio" => $fol->folio,
                    "original" => $original,
                    "plantel_id" => $fol->plantel_id

                ];

                if ($revisa) {
                    $datag = [
                        'certificado_id' => $certificado_id,
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    ];

                    CertificadorevModel::create($datag);
                } else {
                    $datag = [
                        'certificado_id' => $certificado_id,
                        'user_id' => Auth()->user()->id,
                        'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                        'generado' => false,
                    ];
                    CertificadogenModel::create($datag);
                }

            } else
                $resulmater = null;

        } catch (\Exception $e) {
            dd($e);
            $resulmater = null;

        }

        $result = [
            "estatus" => $resulmater['estatus'],
            "fecha" => $resulmater['fecha'],
            "folio" => $folio,
            "original" => $resulmater['original'],
            "numfol" => $numfol,
            "alumno" => $alumno,
            "director" => $director,
            "fotocertificado" => $img,
            "plantel_id" => $resulmater['plantel_id'],
            "certificado_id" => $certificado_id
        ];

        return $result;
    }

    public static function get_foliorevisalistado($grupo_id)
    {
        //reporte listado de alumnos para revisión
        $gruppl = GruposModel::
            select('esc_grupo.nombre as grupo', 'cat_plantel.nombre as plantel')->
            join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
            ->where('esc_grupo.plantel_id', '!=', '34')
            ->where('esc_grupo.id', $grupo_id)->first();

        $grupo = $gruppl->grupo;
        $plantel = $gruppl->plantel;

        ini_set('max_execution_time', 600); // 5 minutes

        $listado = DB::select('call pa_alumnos_certrevisa_lst(?)', array($grupo_id));

        $result = [
            "listado" => $listado,
            "grupo" => $grupo,
            "plantel" => $plantel
        ];

        return $result;
    }
#region Digitalizacion del documento certificado


    /* ESTE ES EL METODO DONDE REALMENTE TERMINA REALIZANDO LA DIGITALIZACION */
    public static function reporteind($alumno_id)
    {
        //doumento certificado
        //busca datos del último periodo escolar
        //$alumno_find = AlumnoModel::find($alumno_id);
        $alumnos = AlumnoModel::where('id', $alumno_id)->get();
        $alumno = $alumnos[0]->apellidos . ' ' . $alumnos[0]->nombre;
        $noexpediente = $alumnos[0]->noexpediente;
        $curp = $alumnos[0]->curp;

        //dd($id_alumno);

        //obtiene calificaciones y promedio
        $calificacionescer = DB::select('call pa_certificado(?)', array($alumno_id));
        $suma = 0;
        $materias = 0;
        $materias_con_rev = 0;

        $valida_alumno = DB::select('select * from view_alumno_certifica where alumno_id = ?', [$alumno_id]);


        $data = DB::select("SELECT cct, concat(titulo,SPACE(1),cat_general.nombre) as directorgeneral, cat_plantel.localidad as ciudad, 
        cat_plantel.nombre as plantel,esc_grupo.plantel_id
        ,efirma_password,efirma_file_certificate,efirma_file_key,numcertificado,sellodigital
        ,cat_general.id as general_id
        ,esc_grupo.nombre as grupo,cat_ciclos_esc.nombre as ciclo
        FROM esc_grupo_alumno
        LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id
        LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id
        left join cat_ciclos_esc on cat_ciclos_esc.id=esc_grupo.ciclo_esc_id
        left join cat_general on/* per_inicio>=fechainicio and directorgeneral=1 and*/ (per_inicio<=fechafinal or fechafinal is null)
        WHERE alumno_id=" . $alumno_id . "
        AND esc_grupo.nombre NOT IN ('ActasExtemporaneas')
        AND esc_grupo.plantel_id != '34'
        ORDER BY per_inicio DESC
        LIMIT 1");

        if (!$data) {

            $data = DB::select("SELECT cct, concat(titulo,SPACE(1),cat_general.nombre) as directorgeneral, cat_plantel.localidad as ciudad, 
            cat_plantel.nombre as plantel,esc_grupo.plantel_id
            ,efirma_password,efirma_file_certificate,efirma_file_key,numcertificado,sellodigital
            ,cat_general.id as general_id
            ,esc_grupo.nombre as grupo,cat_ciclos_esc.nombre as ciclo
            FROM esc_grupo_alumno
            LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id
            LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id
            left join cat_ciclos_esc on cat_ciclos_esc.id=esc_grupo.ciclo_esc_id
            left join cat_general on directorgeneral=1 and (per_inicio<=fechafinal or fechafinal is null)
            WHERE alumno_id=" . $alumno_id . "
            AND esc_grupo.nombre NOT IN ('ActasExtemporaneas')
            AND esc_grupo.plantel_id != '34'
            ORDER BY per_inicio DESC
            LIMIT 1");
        }




        $director = $data[0]->directorgeneral;
        $general_id = $data[0]->general_id;

        $csd = $data[0]->sellodigital;
        $numcsd = $data[0]->numcertificado;

        $grupo = $data[0]->grupo;
        $ciclo = $data[0]->ciclo;

        $materias_con_rev = 0;
        $materias = 0;
        foreach ($calificacionescer as $cal) {
            if (isset($cal->periodo1)) {
                if ($cal->calificacion1 != "REV") {
                    if ($cal->calificacion1 >= 60 || $cal->calificacion1 != "NA") {
                        $materias = $materias + 1;
                    }
                    if ($cal->calificacion1 == "AC" || $cal->calificacion1 == "NA" ) {

                    } else {
                        $suma = $suma + $cal->calificacion1;
                    }

                } else {
                    $materias_con_rev = $materias_con_rev + 1;
                }

            }
            if (isset($cal->periodo2)) {
                if ($cal->calificacion2 != "REV") {
                    if ($cal->calificacion2 >= 60 || $cal->calificacion2 != "NA") {
                        $materias = $materias + 1;
                    }
                    if ($cal->calificacion2 == "AC" || $cal->calificacion2 == "NA") {

                    } else {
                        $suma = $suma + $cal->calificacion2;
                    }
                } else {
                    $materias_con_rev = $materias_con_rev + 1;
                }

            }
            $materias = $cal->count_materia1 + $cal->count_materia2;
        }
        //$materias = $materias - $materias_con_rev;

        $promedio = $suma / ($materias - $materias_con_rev);

        $result = self::get_folio($alumno_id, $curp, false);
        $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        $materias_letra = ucfirst($formatter->format($materias));


        $resulmater = [
            "promedio" => $promedio,
            "materias" => $materias,
            "materias_letra" => $materias_letra,
            "estatus" => $result['estatus'],
            "fecha" => $result['fecha'],
            "original" => $result['original'],
            "materias_con_rev" => $materias_con_rev,
            "fotocertificado" => $result['fotocertificado']
        ];

        $numfol = $result['numfol'];
        $folio = $result['folio'];
        $alumno = $result['alumno'];
        $director = $result['director'];
        $plantel = $data[0]->plantel;
        $plantel_id = $data[0]->plantel_id;
        $certificado_id = $result['certificado_id'];

        $directorio = "certificados";

        if (!is_dir($directorio))
            mkdir($directorio, 0700);
        $directorio = "certificados/" . $ciclo;
        if (!is_dir($directorio))
            mkdir($directorio, 0700);

        $directorio = "certificados/" . $ciclo . "/" . $plantel;
        if (!is_dir($directorio))
            mkdir($directorio, 0700);

        $directorio = "certificados/" . $ciclo . "/" . $plantel . "/" . $grupo . "/";
        if (!is_dir($directorio))
            mkdir($directorio, 0700);

        $cert = CertificadodigitalModel::where('id', $certificado_id)->where('vigente', '1')->first();

        if (!isset($cert)) {
            $cct = $data[0]->cct;
            $ciudad = $data[0]->ciudad;
            if ($result['plantel_id'] != $plantel_id) {

                $dataplantel = DB::select("SELECT  nombre as plantel, cct, localidad
                FROM  cat_plantel
                where id=" . $result['plantel_id']);

                if (count($dataplantel) > 0) {
                    $plantel = $dataplantel[0]->plantel;
                    $plantel_id = $result['plantel_id'];
                    $grupo = 'SIN GRUPO';
                    $cct = $dataplantel[0]->cct;
                    $ciudad = $dataplantel[0]->localidad;
                }
            }

            $datos = [
                "cct" => $cct,
                "directorgeneral" => $director,
                "ciudad" => $ciudad,
                "plantel" => $plantel,
                "plantel_id" => $plantel_id,
                "noexpediente" => $noexpediente,
            ];
            //dd($alumno);

            //CODIGO QR
            $result_qr_content_in_base64 = Certificado::get_codigoqr($numfol, $curp);

            $imagen_logo = ImagenesalumnoModel::where('id', 72245)->where('tipo', 2)->first();
            $logo_foto = ImagenesalumnoModel::where('id', 72541)->first();
            //Cambiar este ID EN Caso de que exista algún cambio en la firma
            $imagen_firma_control_escolar = ImagenesalumnoModel::where('id', 72540)->first();
            //return view('certificados.certificado.reporte', array('alumno_id'=>$alumno_id,'resulmater'=>$resulmater,'calificacionescer'=>$calificacionescer,'csd'=>$csd,'numcsd'=>$numcsd,'codigoqr'=>$result_qr_content_in_base64,'folio'=>$folio,'alumno'=>$alumno,'datos'=>$datos));
            //dd($imagen_firma_control_escolar);
            $pdf = PDF::loadView('certificados.certificado.reporte', array(
                'alumno_id' => $alumno_id,
                'resulmater' => $resulmater,
                'calificacionescer' => $calificacionescer,
                'csd' => $csd,
                'numcsd' => $numcsd,
                'imagen_firma_control_escolar' => $imagen_firma_control_escolar,
                'logo_foto' => $logo_foto,
                'codigoqr' => $result_qr_content_in_base64,
                'folio' => $folio,
                'alumno' => $alumno,
                'datos' => $datos,
                'imagen_logo' => $imagen_logo
            ));

            //generar xml
            self::get_xml($directorio . "cert-" . $alumno . '-' . $noexpediente . '-' . $numfol . ".xml", $calificacionescer, $numfol, $csd, $numcsd, $curp, $alumno, $datos);

            //return $pdf->stream('certificados/cert-'.$alumno.'-'.$noexpediente.'-'.$numfol.'.pdf');
            //guarda pdf en directorio
            $pdf->save($directorio . 'cert-' . $alumno . '-' . $noexpediente . '-' . $numfol . '.pdf');
            $pdf->download('cert-' . $alumno . '-' . $noexpediente . '-' . $numfol . '.pdf');

            //update pdf into database


            self::upload_file($certificado_id, $directorio . 'cert-' . $alumno . '-' . $noexpediente . '-' . $numfol . '.pdf');

            $cert_id = CertificadoModel::find($certificado_id);
            if ($cert_id->digital == null) {
                $certifica_update =
                    [
                        'digital' => now()
                    ];
                $cert_id->update($certifica_update);
            }

        } else {
            //dd($cert);
            //download file from database
            file_put_contents($directorio . 'cert-' . $alumno . '-' . $noexpediente . '-' . $numfol . '.pdf', $cert->certificado_digital);
        }
        //send email
        $alumn = AlumnoModel::where('id', $alumno_id)->first();

        //UNICAMENTE SE GENERA EL PDF NO SE ENVÍA POR CORREO ELECTRÓNICO
        //Envioemail::email($alumn->email,$alumn->apellidos.' '.$alumn->nombre,$alumn->id,$directorio.'cert-'.$alumno.'-'.$noexpediente.'-'.$numfol.'.pdf');

    }

    #endregion
    public static function upload_file($certificado_id, $file)
    {
        try {

            $cert = CertificadodigitalModel::where('id', $certificado_id)->first();

            if (!isset($cert)) {
                $file_pdf = file_get_contents($file);
                $data = [
                    'id' => $certificado_id,
                    'certificado_digital' => $file_pdf,
                    'vigente' => true
                ];
                CertificadodigitalModel::create($data);
            }
        } catch (\Exception $e) {
            // dd($e);

        }
    }

}
