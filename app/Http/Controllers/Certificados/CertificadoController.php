<?php
// ANA MOLINA 06/03/2024
namespace App\Http\Controllers\Certificados;

use App\Http\Controllers\Controller;
use App\Certificado\Certificado;
use App\Certificado\Envioemail;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Certificados\CertificadodigitalModel;
use App\Models\Cursos\CursosModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

// use Illuminate\Pagination\Paginator;
use Dompdf\Dompdf;
use NumberFormatter;
use PDF;

class CertificadoController extends Controller
{
    public function generar()
    {
        return view('certificados.genera.index');
    }

    public function cambio_ciclo()
    {
        return view('certificados.ciclos.cambio_ciclo');
    }

    public function index()
    {
        return view('certificados.certificado.index');
    }

    public function sibal()
    {

        return view('certificados.sibal.index');

    }

    

    public function certificado_sibal($alumno_id)
    {
        //$alumno =  base64_decode($alumno_id);
        $alumno = AlumnoModel::find(base64_decode($alumno_id));
        setlocale(LC_TIME, 'es_ES.UTF-8'); // Configura el idioma a español
        $date = strftime('%d de %B de %Y', strtotime(now())); // Formato de fecha en español
        // Puedes personalizar el formato de la fecha
        $totalSubjects = 0;
        $calificacion = 0;
        $promedio = 0;
        // Ejemplo de datos de calificaciones

        $asignaturas = CursosModel::leftJoin('esc_calificacion', function($join) use($alumno) {
            $join->on('esc_calificacion.curso_id', '=', 'esc_curso.id')
                 ->where('esc_calificacion.alumno_id', '=', $alumno->id);
        })
        ->leftJoin('alu_alumno', 'alu_alumno.id', '=', 'esc_calificacion.alumno_id')
        ->join('asi_asignatura', 'asi_asignatura.id', '=', 'esc_curso.asignatura_id')
        ->leftJoin('esc_grupo', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
        ->select('esc_curso.*', 'esc_calificacion.id as id_calif', 'asi_asignatura.clave', 'esc_calificacion.calificacion', 'esc_grupo.nombre as modulo')
        ->where('esc_curso.curso_tipo', 15)
        ->get();


        //dd($asignaturas);
        foreach ($asignaturas as $asigna) {
            $grades[] = [
                'module' => $asigna->modulo,              // Asegúrate de que esta columna exista en $asignaturas
                'key' => $asigna->clave,                  // La clave de la asignatura
                'subject' => $asigna->nombre,             // El nombre de la asignatura
                'score' => $asigna->calificacion ?? "- -",    // Calificación, usa 0 si es null
            ];
            if ($asigna->calificacion && $asigna->calificacion >= 60) {
                $totalSubjects += 1;
                $calificacion += $asigna->calificacion;

            }

        }


        $promedio = $calificacion / $totalSubjects;

        $pdf = Pdf::loadView('certificados.sibal.certificado', [
            'student_name' => $alumno->nombre . " " . $alumno->apellidos,
            'student_id' => $alumno->noexpediente,
            'date' => $date,
            'totalSubjects' => $totalSubjects,
            'grades' => $grades,
            'duplicado' => $alumno->carta_compromiso,
            'promedio' => $promedio,
            'genero' => $alumno->sexo
        ]);

        return $pdf->stream('certificado.pdf');  // Muestra el PDF en el navegador*/
    }


    public function reporte($id_al)
    {
        //decodificar
        $alumno_id = base64_decode($id_al);
        //codificar base64_encode();

        Certificado::reporteind($alumno_id);

        //decodificar
        // $al =  base64_decode($id_al);
        // $grupo_id= base64_decode($id_grupo);
        // //return view('certificados.certificado.reportegrupo',  array('alumnos_sel'=>$al,'grupo_id'=>$grupo_id));//->render();
        //  $pdf = PDF::loadView('certificados.certificado.reportegrupo', array('alumnos_sel'=>$al,'grupo_id'=>$grupo_id));
        //  return $pdf->stream('certificagrupos.pdf');
    }
    public function reportegrupo($id_al, $id_grupo)
    {
        //decodificar
        $sel_alumnos = base64_decode($id_al);
        //codificar base64_encode();

        $arralumno = explode(",", $sel_alumnos);

        //dd($arralumno);
        foreach ($arralumno as $alumno) {
            //echo $alumno;
            Certificado::reporteind($alumno);
        }

        //decodificar
        $al = base64_decode($id_al);
        $grupo_id = base64_decode($id_grupo);
        //dd("Genere aqui");
        return view('certificados.certificado.reportegrupo', array('alumnos_sel' => $al, 'grupo_id' => $grupo_id));//->render();
        //  $pdf = PDF::loadView('certificados.certificado.reportegrupo', array('alumnos_sel'=>$al,'grupo_id'=>$grupo_id));
        //  return $pdf->stream('certificagrupos.pdf');
    }

    public function revisagrupo($id_al, $id_grupo)
    {
        $pdf = new Dompdf();
        $pdf->setPaper('A4', 'portrait');

        $pdfContent = '';
        $imagen_logo = ImagenesalumnoModel::where('id', 72245)->where('tipo', 2)->first();
        $logo_foto = ImagenesalumnoModel::where('id', 72541)->first();

        //Cambiar este ID EN Caso de que exista algún cambio en la firma
        $imagen_firma_control_escolar = ImagenesalumnoModel::where('id', 72540)->first();
        //decodificar
        $al = base64_decode($id_al);
        $grupo_id = base64_decode($id_grupo);
        $grupo_buscar = GruposModel::find($grupo_id);

        //return view('certificados.certificado.reportegrupo',  array('alumnos_sel'=>$al,'grupo_id'=>$grupo_id));//->render();

        $array_alumnos = explode(',', $al);
        $array_alumnos = array_unique($array_alumnos);


        foreach ($array_alumnos as $alumno_id) {

            $data = DB::select("SELECT cct, concat(titulo,SPACE(1),cat_general.nombre) as directorgeneral, cat_plantel.localidad as ciudad, cat_plantel.nombre as plantel,esc_grupo.plantel_id,
            efirma_password,efirma_file_certificate,efirma_file_key,numcertificado,sellodigital,
            cat_general.id as general_id,
            esc_grupo.nombre as grupo,cat_ciclos_esc.nombre as ciclo
            FROM esc_grupo_alumno
            LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id
            LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id
            left join cat_ciclos_esc on cat_ciclos_esc.id=esc_grupo.ciclo_esc_id
            left join cat_general on /*per_inicio>=fechainicio and directorgeneral=1  and*/ (per_inicio<=fechafinal or fechafinal is null)
            WHERE ALUMNO_ID=" . $alumno_id . "
            AND esc_grupo.plantel_id != 34
            ORDER BY per_inicio DESC
            LIMIT 1");

            $director = $data[0]->directorgeneral;
            $general_id = $data[0]->general_id;

            $csd = $data[0]->sellodigital;
            $numcsd = $data[0]->numcertificado;

            $grupo = $data[0]->grupo;
            $ciclo = $data[0]->ciclo;

            //$alumno_find = AlumnoModel::find($alumno_id);
            $alumnos = AlumnoModel::where('id', $alumno_id)->get();
            $alumno = $alumnos[0]->apellidos . ' ' . $alumnos[0]->nombre;
            $noexpediente = $alumnos[0]->noexpediente;
            $curp = $alumnos[0]->curp;
            
            $calificacionescer = DB::select('call pa_certificado(?)', array($alumno_id));
            
            $suma = 0;
            $materias_con_rev = 0;
            $materias = 0;
            foreach ($calificacionescer as $cal) {
                if (isset($cal->periodo1)) {
                    if ($cal->calificacion1 != "REV") {
                        if ($cal->calificacion1 >= 60 || $cal->calificacion1 != "NA") {
                            $materias = $materias + 1;
                        }
                        if ($cal->calificacion1 == "AC" || $cal->calificacion1 == "NA") {

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


            $result = Certificado::get_folio($alumno_id, $curp, false);
            
            //dd($result);
            //$materias_letra = numeroALetras($materias);

            /* Antes del cambio
            $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
            $materias_letra = ucfirst($formatter->format($materias));
            */
            $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
            $materias_letra = strtoupper(iconv('UTF-8', 'ASCII//TRANSLIT', $formatter->format($materias)));


            $resulmater = [
                "promedio" => $promedio,
                "materias" => $materias,
                "materias_letra" => $materias_letra,
                "estatus" => $result['estatus'],
                "fecha" => $result['fecha'],
                "original" => $result['original'],
                "fotocertificado" => $result['fotocertificado'],
                "materias_con_rev" => $materias_con_rev
            ];


            $numfol = $result['numfol'];
            $folio = $result['folio'];
            $alumno = $result['alumno'];
            $director = $result['director'];
            $plantel = $data[0]->plantel;
            $plantel_id = $data[0]->plantel_id;
            $certificado_id = $result['certificado_id'];
            
            $resultado = DB::select("
            SELECT certs.*
            FROM `sce-cobach-doctos_alumnos`.`certificados` AS certs
            INNER JOIN `sce-cobach`.`esc_certificado`
                ON `sce-cobach`.`esc_certificado`.`folio` = certs.id
            WHERE certs.id = ?
            AND certs.vigente = 1
            AND `sce-cobach`.`esc_certificado`.`digital` IS NULL
            order by `sce-cobach`.`esc_certificado`.`created_at` DESC
            LIMIT 1
        ", [$certificado_id]);
        
        // Convertir el resultado a objeto si es necesario
        $cert = !empty($resultado) ? $resultado[0] : null;
            
            if (!isset($cert)) {
                $ciudad = $data[0]->ciudad;
                $cct = $data[0]->cct;
                if ($result['plantel_id'] != $plantel_id) {
                    $dataplantel = DB::select("SELECT  nombre as plantel, cct, localidad
                    FROM  cat_plantel
                    where id=" . $result['plantel_id']);

                    if (count($dataplantel) > 0) {
                        //dd($dataplantel);
                        $plantel = $dataplantel[0]->plantel;
                        $plantel_id = $result['plantel_id'];
                        $grupo = 'SIN GRUPO';
                        $cct = $dataplantel[0]->cct;
                        $ciudad= $dataplantel[0]->localidad;
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

                //CODIGO QR
                $result_qr_content_in_base64 = "";
                /*return view('certificados.certificado.reporte', array('alumno_id' => $alumno_id, 'resulmater' => $resulmater, 'calificacionescer' => $calificacionescer, 'csd' => $csd, 'numcsd' => $numcsd, 
                'imagen_firma_control_escolar' => $imagen_firma_control_escolar,
                'codigoqr' => $result_qr_content_in_base64, 'folio' => $folio, 'alumno' => $alumno, 'datos' => $datos, 'imagen_logo' => $imagen_logo));*/

                $viewContent = view(
                    'certificados.certificado.reporte',
                    array(
                        'alumno_id' => $alumno_id,
                        'resulmater' => $resulmater,
                        'calificacionescer' => $calificacionescer,
                        'csd' => $csd,
                        'numcsd' => $numcsd,
                        'imagen_firma_control_escolar' => $imagen_firma_control_escolar,
                        'codigoqr' => $result_qr_content_in_base64,
                        'folio' => $folio,
                        'alumno' => $alumno,
                        'datos' => $datos,
                        'imagen_logo' => $imagen_logo,
                        'logo_foto' => $logo_foto,
                    )
                );

                
                $pdfContent .= '<div style="page-break-after: always;">' . $viewContent . '</div>';
            }
            

        }
        $pdf->loadHtml($pdfContent);
        $pdf->render();
        if ($grupo_buscar->turno_id == "1") {
            $turno_nombre = "Mat";
        } else {
            $turno_nombre = "Ves";
        }
        return $pdf->stream('Certificado' . $grupo_buscar->nombre . '_' . $turno_nombre . '_' . $plantel . '.pdf', [
            'Attachment' => false,
        ]);
        /*
        return $pdf->stream('Certificado' . $grupo_buscar->nombre . '_' . $turno_nombre . '_' . $plantel . '.pdf');
        /*
                $pdf = PDF::loadView('certificados.certificado.reporte', array(
                    'alumno_id' => $alumno_id,
                    'resulmater' => $resulmater,
                    'calificacionescer' => $calificacionescer,
                    'csd' => $csd,
                    'numcsd' => $numcsd,
                    'imagen_firma_control_escolar' => $imagen_firma_control_escolar,
                    'codigoqr' => $result_qr_content_in_base64,
                    'folio' => $folio,
                    'alumno' => $alumno,
                    'datos' => $datos,
                    'imagen_logo' => $imagen_logo
                )
                );
                //dd($al, $grupo_id);

                return $pdf->stream('certificarevisa.pdf');
        */


    }
    public function numeroALetras($numero)
    {
        $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        return ucfirst($formatter->format($numero));
    }
    public function revisalistado($id_grupo)
    {
        //decodificar
        $grupo_id = base64_decode($id_grupo);

        $result = Certificado::get_foliorevisalistado($grupo_id);

        //dd($result);
        $listado = $result['listado'];
        $grupo = $result['grupo'];
        $plantel = $result['plantel'];


        //return view('certificados.revisa.revisalistado',array('grupo'=>$grupo,'listado'=>$listado,'plantel'=>$plantel));//->render();
        $pdf = PDF::loadView('certificados.revisa.revisalistado', array('listado' => $listado, 'grupo' => $grupo, 'plantel' => $plantel, 'grupo_id' => $grupo_id));
        return $pdf->stream('certificarevisalst.pdf');
    }

    public function cancelar()
    {
        return view('certificados.cancela.index');
    }
    public function revisar()
    {
        return view('certificados.revisa.index');
    }

    public function visor()
    {
        return view('certificados.visor.index');
    }
    public function validar()
    {
        return view('certificados.valida.index');
    }

    public function enviargrupo($id_al)
    {
        Envioemail::enviargrupo($id_al);
    }
    public function verificar()
    {
        return view('certificados.verifica.verifica');
    }

    public function verificarfolio($curp, $folio)
    {

        return view('certificados.verificafolio.verificafolio', array('curp' => $curp, 'folio' => $folio));

    }

    public function verificar_folio_sin_curp($folio)
    {
        $curp = "";
        return view('certificados.verificafolio.verificafolio', array('curp' => $curp, 'folio' => $folio));
    }


}
