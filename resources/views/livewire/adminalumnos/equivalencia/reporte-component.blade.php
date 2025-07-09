{{-- ANA MOLINA 22/07/2024 --}}
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
            margin: 65mm 10 60mm 10;

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

        .containerbackground::after {
            content: "VISTA PREVIA";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(300deg);
            font-size: 150px;
            color: rgba(198, 175, 175, 0.2);
            z-index: -1;
            white-space: nowrap;
        }

        /* table, th, td {
      border: 1px solid black;
    } */

        #contenedor {
            text-align: left;
            width: 100%;
            margin: auto;
            margin: 0mm 0mm 0mm 0mm;
        }

        #lateral {
            width: 40%;
            float: left;
        }

        #principal {
            width: 60%;
            float: right;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .container {
            position: relative;
            z-index: 2;

        }
    </style>
    @livewireStyles

</head>


<body>

    <div class="container">

    </div>


    <header>
        @if ($result['autorizo'] == 'SIN AUTORIZAR')
            <div class="containerbackground"></div>
        @endif


        <table cellspacing="0" cellpadding="0" style="width:100%; ">
            <tr>
                <td width="15%" style="vertical-align:text-top; ">
                    <img src="../public/images/logocobachchico.png" width="100px" alt="Logo" class="logo">
                </td>
                <td style="text-align: center; font-size:16px; ">
                    <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong>
                </td>
                <td width="20%" style="text-align: center; font-size:16px; ">
                    <strong>Folio {{ $result['folio'] }}</strong>
                </td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
            <tr>

                <td colspan="3" style="text-align: center; font-size:16px">
                    <strong>{{ $result['titulo'] }}</strong>
                </td>

            </tr>

        </table>
        <br>
        <br>
        @php
            // date_default_timezone_set("America/Mexico_City");
            date_default_timezone_set('America/Hermosillo');
            setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');
            //{{ $resulmater['fechacertifica'] }}
            //$fecha_actual =strtotime( "now");
            $fecha_actual = strtotime($result['fecha']);
            $fec = strtoupper(strftime('%d DE %B DE %Y', $fecha_actual));
            // $fec=  $fecha_actual['mday'] .' DE ' .strtoupper($fecha_actual['month'] ).' DE '. $fecha_actual['year'];
        @endphp
        <table cellspacing="0" cellpadding="0" style="width:100%; font-size:14px">
            <tr>
                <td>ALUMNO: </td>
                <td><strong>{{ $result['alumno'] }}</strong></td>
                <td>EXPEDIENTE: <strong>{{ $result['noexpediente'] }}</strong></td>
            </tr>
            <tr>
                <td>PLANTEL: </td>
                <td>
                    <strong>{{ $result['plantel'] }}</strong>
                </td>
                <td>FECHA:{{ $fec }}</td>
            </tr>
            <tr>
                <td colspan="3" style=" border-bottom: 5px double;"><br></td>
            </tr>
        </table>
    </header>

    <section class="bdy">

        {{-- <table  class="table  table-condensed  table-striped text-center" > --}}


        <div id="contenedor" class="clearfix" style=" border-bottom: 5px double;">

            <div id="lateral">
                <table style="width:100%;   font-size:12px">
                    <tr>
                        <td style="font-size:14px; text-align: center;"><strong>PROCEDENCIA</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">{{ $result['procedencia'] }}</td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td>{{ $result['semestres'] }}</td>
                    </tr>
                </table>
            </div>

            <div id="principal" style=" border-left: solid;">
                @if (!empty($calificaciones))
                    <table style="width:100%;   font-size:12px">
                        <thead style=" border-bottom: solid; border-spacing:0;">
                            <tr>
                                <th>Asignaturas</th>
                                <th>Calif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $per='';@endphp
                            @foreach ($calificaciones as $calif)
                                @if ($per != $calif->periodo)
                                    <tr>
                                        <td><br></td>
                                    </tr>
                                @endif
                                @php $per=$calif->periodo;@endphp
                                <tr>
                                    <td>{{ $calif->nombre }}</td>

                                    <td style="width:20%;">
                                        @if ($tipo == 'E')
                                            {{ $calif->calificacion }}{{ $calif->calif }}
                                        @else
                                            REV
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
        @if ($result['autorizo'] != 'SIN AUTORIZAR')
            <footer>

                <p style="text-align: right;  font-size:14px">{{ strtoupper($result['ciudad']) }}, SONORA A
                    {{ $fec }}.</p>
                <br><br>
                <table style="width:100%;   font-size:14px; text-align: center;">
                    <tr>
                        <td style="width:50%">Elaboró electrónicamente</td>
                        <td style="width:50%">Autorizó electrónicamente</td>
                    </tr>
                    <tr>
                        <td><br>{{ $result['fecha'] }}<br></td>
                        <td><br>
                            {{ $result['fecha_aut'] }}<br></td>
                    </tr>
                    <tr>
                        <td style=" border-bottom:  solid;">{{ $result['elaboro'] }}</td>
                        <td style=" border-bottom:  solid;">Mtro. Jaime Ballesteros Arvizu</td>
                    </tr>
                    <tr>
                        <td>Presidente de la Comision Dictaminadora</td>
                        <td>Departamento de Incorporación y Validación de Estudios</td>
                    </tr>
                    <tr>
                        <td colspan="2" style=" border-bottom: 5px double;"><br></td>
                    </tr>
                    <tr>
                        <td><br><br></td>
                    </tr>
                </table>

            </footer>
        @else
        @endif

    </section>
    <footer>
        <table>
            <tr>
                <td>
                    <p class="izq">
                        Sistema de Control Escolar
                    </p>
                </td>
                <td>
                    <p class="page">
                        Página
                    </p>
                </td>
            </tr>
        </table>
    </footer>

</body>

</html>
