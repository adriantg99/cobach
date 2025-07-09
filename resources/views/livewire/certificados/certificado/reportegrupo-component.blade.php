{{-- ANA MOLINA 15/03/2024 --}}
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>SICE-COBACH</title>
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <!--
      <link rel="preconnect" href="https://fonts.googleapis.com">

      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400,600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    -->
    {{-- <link href="{{  env('APP_URL') }}:8000/css/vendor.min.css" rel="stylesheet">
      <link href="{{  env('APP_URL') }}:8000/css/app.min.css" rel="stylesheet"> --}}
    {{-- dd ("{{  env('APP_URL') }}:8000/css/vendor.min.css" ); --}}
    {{-- dd ("{{  env('APP_URL') }}:8000/css/app.min.css" ); --}}
    {{-- <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/app.min.css') }}" rel="stylesheet"> --}}
    <!-- ================== END BASE CSS STYLE ================== -->
    @section('js_post')
        <script></script>
    @endsection

    <style type="text/css">
        body {
            font-family: sans-serif;
            border-block: solid pink;
            margin: 10 10 10 10;
            border: 1px solid gray;
        }

        .bdy {

            margin: 0mm 15mm 0mm 15mm;
            font-size: 9px;
            border-block: solid yellow;

        }

        @page {
            margin: 0 0 0 0;
            background: red;
            border-style: solid;
        }

        header {
            position: fixed;
            left: 15mm;
            top: 15mm;
            right: 15mm;
            /* background-color: #ddd; */
            text-align: center;
            border-block: solid blue;
            font-size: 10px;
        }

        header h1 {
            margin: 1mm 0 0 0;
            border-block: solid green;
        }

        header h2 {
            margin: 1mm 0 1mm 0;
            border-block: gray;
        }

        footer {
            position: fixed;
            left: 15mm;
            bottom: 5mm;
            right: 15mm;
            font-size: 9px;
            border-block: solid red;
        }

        footer .page:after {
            content: counter(page);
        }

        footer table {
            width: 100%;
            border-block: solid coral;
        }

        footer p {
            text-align: right;
        }

        footer .izq {
            text-align: left;
        }

        .salto {
            /* page-break-before: always; */
            page-break-after: always;
            margin: 50mm 0mm 1mm 0mm;

        }

        /* table, th, td {
      border: 1px solid black;
    } */
    </style>

    @livewireStyles
</head>

<body>
    @php
        use App\Models\Adminalumnos\AlumnoModel;
        use App\Models\Adminalumnos\ImagenesalumnoModel;
        use App\Models\Certificados\CertificadoModel;
        use App\Certificado\Certificado;

        use App\EncryptDecrypt\Encrypt_Decrypt;
        use App\PhpCfdi\Efirma;
        use App\PhpQRcode\QRcodigo;

  
        ini_set('max_execution_time', 300); // 5 minutes
        //set_time_limit(300); // 5 minutes

        $datappal = DB::select(
            "SELECT cct,concat(titulo,SPACE(1),cat_general.nombre) as directorgeneral, cat_plantel.localidad as ciudad,cat_plantel.nombre as plantel,esc_grupo.plantel_id
        ,efirma_password,efirma_file_certificate,efirma_file_key,numcertificado,sellodigital
        ,cat_general.id as general_id
        FROM esc_grupo
        LEFT JOIN cat_plantel ON cat_plantel.id=esc_grupo.plantel_id
        left join cat_ciclos_esc on cat_ciclos_esc.id=esc_grupo.ciclo_esc_id
        left join cat_general on per_inicio>=fechainicio and directorgeneral=1 and (per_inicio<=fechafinal or fechafinal is null)
       WHERE esc_grupo.id=" .
                $this->grupo_id .
                "
        ORDER BY per_inicio DESC
        ",
        );
        $plantel = '';
        $plantel_id = '';
        $cct = '';
        $director = '';
        $ciudad = '';
        $general_id = 0;

        if (count($datappal) > 0) {
            $datosppal = $datappal[0];
            $plantel = $datosppal->plantel;
            $plantel_id = $datosppal->plantel_id;
            $cct = $datosppal->cct;
            $director = $datosppal->directorgeneral;
            $ciudad = $datosppal->ciudad;
            $general_id = $datosppal->general_id;
        }
        $array_alumnos = explode(',', $this->alumnos_sel);
        $array_alumnos_unicos = array_unique($array_alumnos);

    @endphp
    @foreach ($array_alumnos_unicos as $each_alumno)
        @php

            $calificaciones_cer = DB::select('call pa_certificado(?)', [$each_alumno]);

            $porciones = explode(',', $this->alumnos_sel);
            $numalum = count($porciones);
            $countalum = 0;

            $alumno_id = 0;
            $bloque = 0;

            //inicializa datos alumno
            $alumno = '';
            $noexpediente = '';
            $renglones = 0;
            $contarreng = 0;
            $promedio = 0;

        @endphp
        <section class="bdy">
            @foreach ($calificaciones_cer as $calif)
                <?php
        $cambiaalumno=false;
        $primerasi=false;

        if($alumno_id!=$calif->alumno_id)
        {
            $max = max($calif->count_materia1, $calif->count_materia2);
            $alumno_id=$calif->alumno_id;
            $cambiaalumno=true;
            $primerasi=true;
            $renglones=$max;
            $contarreng=1;
            $promedio=$calif->suma_total_calificaciones/($calif->count_materia1+$calif->count_materia2);
            $countalum= $countalum+1;

            $alumno="";
            $noexpediente="";
            $data=AlumnoModel::where('id',$alumno_id)->get();

            $alumno=$data[0]->apellidos.' '.$data[0]->nombre;
            $noexpediente=$data[0]->noexpediente;
            $curp  =$data[0]->curp;


            $result=Certificado::get_folio($alumno_id,$noexpediente,true);
            //dd($result);
            $resulmater= [
                "promedio" => $promedio,
                "materias" => $calif->count_materia1+$calif->count_materia2,
                "estatus"=> $result['estatus'],
                "fecha"=> $result['fecha'],
                "original"=> $result['original'],
                "fotocertificado"=>$result['fotocertificado']
            ];
            $numfol=$result['numfol'];
            $folio=$result['folio'];
            $alumno=$result['alumno'];
            $director=$result['director'];

            if($result['plantel_id']!=$plantel_id)
            {
                /*SELECT  nombre as plantel, cct 
                    FROM  cat_plantel
                    where id=" . $result['plantel_id']*/
                $dataplantel=DB::select("SELECT  nombre as plantel, cct 
                FROM  cat_plantel
                where id=".$result['plantel_id']);

                if (count($dataplantel)>0)
                {
                    $plantel=$dataplantel[0]->plantel;
                    $plantel_id=$result['plantel_id'];
                    $cct = $dataplantel[0]->cct;

                }
            }
        }
        else
            $contarreng=$contarreng+1;

        if ($cambiaalumno==true)
        {
            $periodo1=null;
            $periodo2=null;



?>
                <header>
                    <style>
                        tr.border-top-bottom th {
                            border-top: 1pt solid black;
                            border-bottom: 1pt solid black;
                        }

                        tr.border-bottom-dashed td {
                            border-bottom: 1pt dashed gray;
                        }

                        tr.border-bottom-solid td {
                            border-bottom: 1pt solid black;
                        }
                    </style>
                    <table cellspacing="0" cellpadding="0" style="width:100%; ">
                        <tr>
                            <td rowspan="4" width="15%" style="vertical-align:text-top; ">
                                <img src="../public/images/logocobachchico.png" width="100px" alt="Logo"
                                    class="logo">
                            </td>
                            <td width="70%" style="text-align: center; font-size:16px">
                                <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong>
                            </td>
                            <td rowspan="4" style="text-align: center; font-size:14px; border: solid">
                                <strong>EN REVISION</strong>

                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-size:12px">ORGANISMO PUBLICO DESCENTRALIZADO</td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-size:14px">
                                <strong>PLANTEL {{ $plantel }} </strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-size:14px">
                                
                                <strong>{{ $cct }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%"></td>
                            <td colspan="2" style=" font-size:12px " width="90%">LA DIRECCION GENERAL CERTIFICA,
                                QUE SEGUN CONSTANCIAS QUE OBRAN EN EL ARCHIVO DE ESTE PLANTEL, EL ESTUDIANTE:</td>
                        </tr>
                        <br />
                        <tr>
                            <td width="10%"></td>
                            <td colspan="2" style="text-align: center; font-size:12px;" width="90%">
                                <strong>{{ $alumno }}</strong>
                            </td>
                        </tr>


                        <tr>
                            <td width="10%"></td>
                            <td colspan="2" style=" font-size:12px" width="90%">CON EXPEDIENTE:
                                <strong>{{ $noexpediente }}</strong>, CURSO {{ $resulmater['estatus'] }} EL PLAN DE
                                ESTUDIOS DE EDUCACION
                                MEDIA SUPERIOR, OBTENIENDO LAS CALIFICACIONES QUE A CONTINUACION SE ANOTAN:
                            </td>
                        </tr>
                    </table>

                </header>

                {{-- <table  class="table  table-condensed  table-striped text-center" > --}}
                <table style="width:100%; heigth:100%; border-spacing:0; margin: 65mm 0mm 0mm 0mm;  ">
                    <tr>
                        <td style="width:25%; text-align: center; vertical-align:text-top; ">

                            <table>
                                <tr>
                                    <td style="height: 7cm; text-align: center; vertical-align:text-top; ">

                                        @php

                                            $imagen_alumnos_2 = ImagenesalumnoModel::where('alumno_id', $alumno_id)
                                                ->where('tipo', '2')
                                                ->get();

                                        @endphp

                                        @if (count($imagen_alumnos_2))
                                            <div class="imageOne image">
                                                <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_alumnos_2[0]->imagen)) }}"
                                                    height="150px" alt="Logo" class="logo">
                                            </div>
                                        @endif


                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 10cm; text-align: center; vertical-align:text-top; font-size:7px">
                                        <p>Vo.Bo.<br>
                                        JEFE DEL DEPARTAMENTO DE<br>
                                        SERVICIOS ESCOLARES</p>
                                        <br> <br> <br> <br>
                                    </td>
                                </tr>
                            </table>

                        </td>
                        <td style="width:90%; vertical-align:text-top; ">
                            <table style="width:100%; border-spacing:0; ">
                                <tbody>
                                    <?php
                                }
                             ?>
                                    @if (
                                        ($calif->periodo1 != $periodo1 && $calif->periodo1 != null) ||
                                            ($calif->periodo2 != $periodo2 && $calif->periodo2 != null))
                                        @php
                                            $periodo1 = $calif->periodo1;
                                            $periodo2 = $calif->periodo2;

                                            switch ($calif->periodo1) {
                                                case '1':
                                                    $periodo_1 = 'I';
                                                    break;
                                                case '3':
                                                    $periodo_1 = 'III';
                                                    break;
                                                case '5':
                                                    $periodo_1 = 'V';
                                                    break;
                                                default:
                                                    $periodo_1 = 'I';
                                                    break;
                                            }

                                            switch ($calif->periodo2) {
                                                case '2':
                                                    $periodo_2 = 'II';
                                                    break;
                                                case '4':
                                                    $periodo_2 = 'IV';
                                                    break;
                                                case '6':
                                                    $periodo_2 = 'VI';
                                                    break;
                                                default:
                                                    $periodo_2 = 'II';
                                                    break;
                                            }
                                        @endphp
                                        <tr style="width: 100%">
                                            <td style="width:37%;"><strong>{{ $periodo_1 }} SEMESTRE CICLO
                                                    ESCOLAR</strong></td>
                                            <td style="width:12%;"><strong> {{ $calif->ciclo1 }}</strong> </td>
                                            <td style="width:2%;"> </td>
                                            <td style="width:37%;"><strong>{{ $periodo_2 }} SEMESTRE CICLO
                                                    ESCOLAR</strong></td>
                                            <td style="width:12%;"><strong>{{ $calif->ciclo2 }}</strong> </td>
                                        </tr>
                                    @endif
                                    @if ($calif->materia1 == null)
                                        @php
                                            //dd($calif);
                                            $materia1_null = '';
                                        @endphp
                                    @endif
                                    @if ($calif->materia2 == null)
                                        @php
                                            //dd($calif);
                                            $materia2_null = '';
                                        @endphp
                                    @endif

                                    <tr style="width: 100%">
                                        <td style="width:39%;">
                                            @if ($calif->calificacion1 != null)
                                            @if ($calif->calificacion1 != 'REV')
                                                @if ($calif->calificacion1 >= 60 || $calif->calificacion1 != 'NA')
                                                    @if ($calif->calificacion1 == 'AC')
                                                        AC
                                                    @else
                                                        @if ($calif->calificacion1 < 60 || $calif->calificacion1 == 'NA')
                                                            NA
                                                        @else
                                                            {{ number_format($calif->calificacion1) }}
                                                        @endif
                                                    @endif
                                                @else
                                                    NA
                                                @endif
                                            @else
                                                {{ $calif->calificacion1 }}*
                                            @endif
                                        @else
                                            ***
                                        @endif
                                        </td>
                                        <td style="width:2%;"></td>
                                        <td style="width:39%;">
                                            @if ($calif->calificacion2 != null)
                                            @if ($calif->calificacion2 != 'REV')
                                                @if ($calif->calificacion2 >= 60 || $calif->calificacion2 != 'NA')
                                                    @if ($calif->calificacion2 == 'AC')
                                                        AC
                                                    @else
                                                        @if ($calif->calificacion2 < 60 || $calif->calificacion2 == 'NA')
                                                            NA
                                                        @else
                                                            {{ number_format($calif->calificacion2) }}
                                                        @endif
                                                    @endif
                                                @else
                                                    NA
                                                @endif
                                            @else
                                                {{ $calif->calificacion2 }}*
                                            @endif
                                        @else
                                            ***
                                        @endif
                                        </td>
                                    </tr>
                                    <?php
                            if ($renglones==$contarreng)
                            {
                            ?>
                                </tbody>

                            </table>
                            <table style="width:100%; border-top: double">
                                <tr>{{-- 
                                    @if ($)
                                        
                                    @endif --}}
                                    <td style="text-align: right;"><strong>PROMEDIO GENERAL
                                            {{ number_format($resulmater['promedio']) }} </strong></td>
                                </tr>


                            </table>
                        </td>
                    </tr>
                </table>


                @php
                    // Configurar la zona horaria y la configuración regional
                    date_default_timezone_set('America/Hermosillo');
                    setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');

                    // Asegúrate de que $resulmater['fecha'] tenga el formato correcto "dd-mm-yyyy"
                    $fecha_actual = strtotime($resulmater['fecha']);
                    $fec = strtoupper(strftime('%d DE %B DE %Y', $fecha_actual));
                @endphp

                {{--  <footer>
                <p style="margin: 0 0 0 3cm; text-align: left;">SE EXTIENDE EL PRESENTE CERTIFICADO QUE AMPARA {{$resulmater['materias']}} ASIGNATURAS APROBADAS CON LO QUE ACREDITA INTEGRAMENTE SUS ESTUDIOS EN EDUCACION MEDIA SUPERIOR EN {{$ciudad}} EL DIA {{$fec}}</p>
                <br>
                <br>
                <br>
                <p  style="text-align: center; font-size:14px">DIRECTOR GENERAL</p>
                <br>
                <br>
                <br>
                <p  style="text-align: center;  font-size:14px">{{$director}}</p>
                <p style="font-size:8px">ESTE CERTIFICADO ES VALIDO EN LOS ESTADOS UNIDOS MEXICANOS Y NO REQUIERE TRAMITES ADICIONALES DE LEGALIZACION (ART. 60 L.G.E.)</p>
               <br>
              <br>
          </footer>
 --}}
                <footer>
                    <p style="text-align: left;">SE EXTIENDE EL PRESENTE CERTIFICADO QUE AMPARA
                        {{ $resulmater['materias'] }} ((<?php 
                        $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                        echo strtoupper(ucfirst($formatter->format($resulmater['materias'])));
                    ?>))
                        ASIGNATURAS APROBADAS CON LO QUE ACREDITA INTEGRAMENTE SUS
                        ESTUDIOS EN EDUCACION MEDIA SUPERIOR EN {{ strtoupper(str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                            $ciudad
                        )) }}, SONORA, MÉXICO, EL DIA
                        {{ $fec }}
                    </p>
                    <p style="text-align: left; ">Autoridad educativa: {{ $director }} - DIRECTOR GENERAL</p>
                    <p style="text-align: left; ">No. de certificado de autoridad educativa: </p>
                    <p style="text-align: left; ">Sello digital de autoridad educativa: </p>
                    {{-- FIRMA ELECTRÓNICA (fiel) y Certificado Sello Digital (CSD) --}}
                    <p style="text-align: left;"></p>
                    <br>

                    <p style="text-align: left;">Folio {{ $folio }}</p>
                    <br>
                    <p style="text-align: left; font-size:6px">ESTE CERTIFICADO ES VALIDO EN LOS ESTADOS UNIDOS
                        MEXICANOS Y NO REQUIERE TRAMITES ADICIONALES DE LEGALIZACION (ART. 60 L.G.E.)</p>
                    <br>
                </footer>

                <?php if ($countalum!=$numalum) {?>
                <div style="page-break-after:always;"></div>
                <?php } ?>
                <?php
    }
    ?>
            @endforeach
        </section>
    @endforeach
</body>

</html>
