{{-- ANA MOLINA 07/03/2024 --}}
{{-- ANA MOLINA modificación 11/04/2024 --}}
{{--  El correcto --}}
@php
    //fotografía
    //tipo = 1 es la foto de identificación del alumno
    $periodo1 = null;
    $periodo2 = null;

@endphp
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>SCE-COBACH</title>
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
    <style type="text/css">
        body {
            font-family: sans-serif;
            border-block: solid pink;
            margin: 10 10 10 10;
            /*border: 1px solid gray;*/
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
            bottom: 10mm;
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

        .texto_rev {
            left: 15mm;
            font-size: 8px;
            position: fixed;
            right: 15mm;

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
        use App\Models\Adminalumnos\ImagenesalumnoModel;
        use App\Models\Adminalumnos\AlumnoModel;
        use App\Models\Adminalumnos\RevalidacionModel;
        $alumno_ciclo = AlumnoModel::find($alumno_id);
        
    @endphp
    <section class="bdy">
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

                .fixed-top-text {
                    position: fixed;
                    top: -20;
                    /* Ajusta la posición vertical para estar visible en la parte superior */
                    left: 0;
                    width: 100%;
                    /* Asegura que el contenedor ocupe todo el ancho de la ventana */
                    /*background-color: white;
                    /* O el color de fondo que prefieras */
                    text-align: center;
                    padding: 20px 0;
                    /* Aumenta el padding vertical para el tamaño del texto */
                    font-family: 'Bell MT', serif;
                    /* Define la fuente */
                    font-size: 28px;
                    /* Ajusta el tamaño de la fuente */
                    font-weight: bold;
                    /* Hace el texto en negritas */
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                    /* Sombra de texto */
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    /* Agrega una sombra ligera para mejorar el contraste */
                    z-index: 1000;
                    /* Asegúrate de que el texto esté por encima de otros elementos */
                    white-space: nowrap;
                    /* Asegura que el texto no se envuelva en varias líneas */
                }
            </style>
            @if ($resulmater['original'] == 2)
                <div class="fixed-top-text">
                    <p>D <label style="color:white"> . </label> U <label style="color:white"> . </label> P <label
                            style="color:white"> . </label> L <label style="color:white"> . </label>
                        I <label style="color:white"> . </label> C <label style="color:white"> . </label> A <label
                            style="color:white"> . </label> D <label style="color:white"> . </label> O</p>
                </div>
            @else
                <!-- Puedes agregar contenido alternativo aquí si es necesario -->
            @endif

            <table cellspacing="0" cellpadding="0" style="width:100%; ">
                <tr style="padding-bottom: 5px;">
                    <td rowspan="4" width="15%" style="vertical-align:text-top;">
                        <!--<img src="../public/images/logocobachchico.png" width="100px" alt="Logo" class="logo">-->

                        <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_logo->imagen)) }}"
                            width="100px" alt="Logo" class="logo">
                    </td>
                    <td width="70%" style="text-align: center; font-size:16px; ">
                        <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong>
                    </td>
                    <td rowspan="4"
                        @if ($codigoqr == '') style="text-align: center; font-size:14px; border: solid;"
                    @else
                        style="text-align: center; font-size:14px;" @endif>

                        @if ($codigoqr == '')
                            <strong>EN REVISION</strong>
                        @else
                            <img src="data:image/png;base64,.{{ $codigoqr }}." alt="qrcode" height="100"
                                style="position: absolute; top: 0px; left: 580px;  border: solid;">
                        @endif

                        {{-- <table >
                            <tr>
                                <td  style="text-align: center; border-bottom: solid ; border-top: solid">
                                <strong>CERTIFICADO</strong>
                                </td>
                            </tr>
                            <tr><td style="text-align: center; "> --}}
                        {{-- <br>
                                <strong>{{$resulmater['folio']}}</strong> --}}
                        {{-- <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{$resulmater['folio']}}&choe=UTF-8"> --}}
                        {{-- ESTE ES EL BUENO --}}
                        {{-- <img src="data:image/png;base64,.{{$codigoqr}}." alt="qrcode" > --}}
                        {{-- <img src="data:image/png;base64,.{{$codigoqr}}." alt="qrcode" > --}}
                        {{-- <div style="
                                        width:150px;
                                        height: 150px;
                                        background: url(data:image/png;base64,.{{$codigoqr}}.) no-repeat;">
                                </div> --}}
                        {{-- <div style="text-align: center; width:60; height: 50; background: url(data:image/png;base64,.{{$codigoqr}}.) no-repeat;"> </div> --}}
                        {{-- </td>
                            </tr>
                        </table> --}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size:12px; ">ORGANISMO PUBLICO DESCENTRALIZADO</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size:14px; ">
                        <strong>PLANTEL {{ $datos['plantel'] }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size:14px; padding-bottom: 10px;">
                        <strong>{{ $datos['cct'] }}</strong>
                    </td>
                </tr>
                <br />
                <br />
                <br />
                <tr style="padding-top: 20px">
                    <td width="10%"></td>
                    <td colspan="2" style=" font-size:12px " width="90%">LA DIRECCION GENERAL CERTIFICA, QUE SEGUN
                        CONSTANCIAS QUE OBRAN EN EL ARCHIVO DE ESTE PLANTEL, @if(substr($alumno_ciclo->curp, 10,1) == 'H') EL @elseif (substr($alumno_ciclo->curp, 10,1) == 'M') LA 
                        @endif  ESTUDIANTE:</td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td colspan="2"
                        style="text-align: center; font-size:12px;  padding-bottom: 10px; padding-top: 10px;"
                        width="90%">
                        <strong>{{ $alumno }}</strong>
                    </td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td colspan="2" style=" font-size:12px" width="90%">CON EXPEDIENTE:
                        <strong>{{ $datos['noexpediente'] }}</strong>, CURSO {{ $resulmater['estatus'] }} EL PLAN DE
                        ESTUDIOS DE EDUCACION MEDIA SUPERIOR, OBTENIENDO LAS CALIFICACIONES QUE A CONTINUACION SE
                        ANOTAN:
                    </td>
                </tr>
            </table>
            {{--  <tr>
                    <td  >
                        <img  src="../../../../public/logocobachchico.png"  width="100" height="60" alt="Logo" class="logo">
                    </td>
                    <td>
                        <p>    </p>
                    </td>
                    <td>
                        <h4><strong>Colegio de Bachilleres del Estado de Sonora</strong><br>
                        <strong>@yield('reporte')</strong>@yield('encabezado')</h4>
                        <p style="font-size: 10px;">@yield('encabezado')</p>
                    </td>
                </tr> --}}
        </header>
        {{-- <table  class="table  table-condensed  table-striped text-center" > --}}
        <br><br><br>
        <table style="width:100%; border-spacing:0; margin: 65mm 0mm 0mm 0mm;  ">
            <tr>
                <td style="width:25%;  text-align: center; vertical-align:text-top;  ">
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

                                    <div class="imageOne image">
                                        <img src="data:image/png;base64,{{ chunk_split(base64_encode($logo_foto->imagen)) }}"
                                            style="position: absolute; top: 435px; left: 125px; width: 60px; height: 30px;">
                                    </div>
                                @endif



                            </td>
                        </tr>

                        <tr>
                            <table width="100%" @if ($codigoqr == '') style="visibility: hidden;" @endif>


                                <tr
                                    style=" width: 150px; height: 100px; text-align: center; vertical-align: top; font-size: 7px;">
                                    <td style="">
                                        <p style="background: rgba(255, 255, 255, 0.7); padding: 5px;">
                                            CERTIFICA<br>
                                            DIRECTOR GENERAL<br>
                                            <br>
                                        </p>
                                    </td>
                                </tr>
                                <br>

                                <br>
                                <tr>
                                    <td>
                                        <p
                                            style="border-top: 1px solid black; width: 150px; height: 100px; text-align: center; vertical-align: top; font-size: 7px;">
                                            DR. RODRIGO ARTURO ROSAS BURGOS</p>

                                        <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_firma_control_escolar->imagen)) }}"
                                            style="position: absolute; top: 594px; left: 78px; width: 150px; height: 70px;">

                                    </td>
                                </tr>
                            </table>

                        </tr>

                    </table>
                </td>
                <td style="width:90%;  vertical-align:text-top;">
                    @if (!empty($calificacionescer))
                        <table style="width:100%; border-spacing:0;">
                            <tbody>
                                @foreach ($calificacionescer as $calif)
                                    @if (
                                        ($calif->periodo1 != $periodo1 && $calif->periodo1 != null) ||
                                            ($calif->periodo2 != $periodo2 && $calif->periodo2 != null))
                                        @php
                                            $total_materias = $calif->count_materia1 + $calif->count_materia2;
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
                                            if ($calif->periodo2 == null) {
                                                $calif->periodo2 = $calif->periodo1 + 1;
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
                                        <tr style="width: 100%;">
                                            <td style="width:37%;"><strong>{{ $periodo_1 }} SEMESTRE CICLO
                                                    ESCOLAR</strong></td>
                                            <td style="width:12%; word-wrap: break-word; white-space: nowrap;"><strong>
                                                    @if ($calif->materia1 != null)
                                                        {{ $alumno_ciclo->buscar_ciclo($calif->alumno_id, $calif->periodo1, $calif->materia1) }}
                                                    @else
                                                    @endif {{--  {{ $calif->ciclo1 }} --}}
                                                </strong> </td>

                                            <td style="width:2%;"> </td>
                                            <td style="width:37%;"><strong>{{ $periodo_2 }} SEMESTRE CICLO
                                                    ESCOLAR</strong></td>
                                            <td style="width:12%; word-wrap: break-word; white-space: nowrap;"><strong>
                                                    @if ($calif->materia2 != null)
                                                        {{ $alumno_ciclo->buscar_ciclo($calif->alumno_id, $calif->periodo2, $calif->materia2) }}
                                                    @else
                                                    @endif {{-- {{ $calif->ciclo2 }} --}}
                                                </strong> </td>
                                        </tr>
                                    @endif
                                    <tr style="width:100%;">
                                        <td style="width:39%;">
                                            @if ($calif->materia1 == null)
                                                ******************************************************
                                            @else
                                                {{ $calif->materia1 }}
                                            @endif
                                        </td>
                                        <td style="text-align: right; width:10%;">
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
                                        <td style="width:2%;"> </td>
                                        <td style="width:39%;">
                                            @if ($calif->materia2 == null)
                                                ******************************************************
                                            @else
                                                {{ $calif->materia2 }}
                                            @endif
                                        </td>
                                        <td style="text-align: right; width:10%;">
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
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <table style="width:100%; border-top: double">
                        <tr>
                            @if ($resulmater['estatus'] === 'PARCIALMENTE')
                            @else
                                <td style="text-align: right;"><strong>PROMEDIO GENERAL
                                        {{ number_format($resulmater['promedio']) }}</strong></td>
                            @endif

                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @php
            $revalidacion_texto = RevalidacionModel::where('alumno_id', $alumno_id)->first();

        @endphp
        @if ($revalidacion_texto)
            @php
                date_default_timezone_set('America/Hermosillo');
                setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');

                $fecha_rev = strtotime($revalidacion_texto->fecha);

                $fecha_rev_texto = strtoupper(strftime('%d DE %B DE %Y', $fecha_rev));
            @endphp
            <div class="texto_rev">
                <p style="text-align: left;">*REVALIDACION OTORGADA POR
                    {{ mb_strtoupper($revalidacion_texto->emitidopor, 'UTF-8') }} No. DE FOLIO
                    {{ $revalidacion_texto->folio }} DEL {{ $fecha_rev_texto }}
                </p>
            </div>
        @else
        @endif
        {{-- 
        @if ($resulmater['materias_con_rev'])
            @if ($resulmater['materias_con_rev'] != 0)
                
                @endphp
                
            @endif
        @else
        @endif
         --}}
        @php
            date_default_timezone_set('America/Hermosillo');
            setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');
            $fecha_actual = strtotime($resulmater['fecha']);

            $fec = strtoupper(strftime('%d DE %B DE %Y', $fecha_actual));
        @endphp
        <footer>




            <p style="text-align: left;">SE EXTIENDE EL PRESENTE CERTIFICADO QUE AMPARA {{ $resulmater['materias'] }}
                <?php
                $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                
                ?>

                ({{ strtoupper(
                    str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        ucfirst($formatter->format($resulmater['materias'])),
                    ),
                ) }})
                ASIGNATURAS APROBADAS CON LO QUE ACREDITA
                @if ($resulmater['estatus'] === 'PARCIALMENTE')
                    {{ $resulmater['estatus'] }}
                @else
                    INTEGRAMENTE
                @endif
                SUS ESTUDIOS EN EDUCACION MEDIA SUPERIOR EN {{ mb_strtoupper($datos['ciudad'], 'UTF-8') }}
                , SONORA, MÉXICO, EL DIA
                {{ $fec }}.
            </p>
            <p style="text-align: left; ">Autoridad educativa:


                {{ $datos['directorgeneral'] }}
                - DIRECTOR GENERAL</p>
            <p style="text-align: left; ">No. de certificado de autoridad educativa: @if ($codigoqr == '')
                @else
                    {{ $numcsd }}
                @endif
            </p>
            <p style="text-align: left; ">Sello digital de autoridad educativa: </p>
            {{-- FIRMA ELECTRÓNICA (fiel) y Certificado Sello Digital (CSD) --}}
            <p style="text-align: left;">
                @if ($codigoqr == '')
                @else
                    {{ $csd }}
                @endif
            </p>
            <br>
            <p style="text-align: left;">Folio {{ $folio }}</p>
            <p style="text-align: center; font-size:6px">ESTE CERTIFICADO ES VALIDO EN LOS ESTADOS UNIDOS MEXICANOS Y NO
                REQUIERE TRAMITES ADICIONALES DE LEGALIZACION, ART. 141 L.G.E. </p>
            <br>
        </footer>
    </section>
</body>

</html>
