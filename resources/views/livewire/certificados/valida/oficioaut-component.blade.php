{{-- ANA MOLINA 01/07/2024 --}}
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
       body{
           font-family: sans-serif;
           border-block: solid pink;
           margin: 30 30 30 30;
        }

            .bdy{

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
        header h1{
          margin: 1mm 0 0 0;
          border-block: solid green;
        }
        header h2{
          margin: 1mm 0 1mm 0;
          border-block:  gray;
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
          border-block:  solid coral;
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
        use App\Certificado\Oficio;
        ini_set('max_execution_time', 300); // 5 minutes

        @endphp
        <section class="p">
        <header >
            <table cellspacing="0" cellpadding="0" style="width:100%; ">
                <tr>
                    <td   width="20%" style="text-align: center;" >
                        <img  src="../public/images/logo-sec.png"  width="150px"  alt="Logo" class="logo">
                    </td>
                    <td   width="60%" style="  text-align: center; " >
                        <img  src="../public/images/logocobachchico.png"  width="150px"  alt="Logo" class="logo">
                    </td>
                    <td   width="20%" style="text-align: center; " >
                        <img  src="../public/images/logo-sonora.png"  width="150px"  alt="Logo" class="logo">
                    </td>
                </tr>

            </table>

          </header>
                {{-- <table  class="table  table-condensed  table-striped text-center" > --}}
                    <table cellspacing="0" cellpadding="0" style="width:100%; margin: 55mm 0mm 0mm 0mm; ">

                        @php
                            date_default_timezone_set("America/Hermosillo");
                            setlocale(LC_TIME,'es_MX.UTF-8','esp');
                            $fecha_actual =strtotime( "now");
                            $fec=strtoupper(strftime("%B %d de %Y", $fecha_actual));

                        @endphp
                        <tr>
                            <td  style=" font-size:16px; text-align: right; " >Hermosillo, Sonora; {{$fec}}.</td>
                        </tr>
                        <tr>
                            <td   style=" font-size:16px;  font-style: italic; text-align: right; " >{{$datos['oficio']}}</td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;" ><br><br></td>
                        </tr>

                        <tr>
                            <td  style=" font-size:18px;  " ><strong>{{$datos['solicitante']}}</strong></td>
                        </tr>
                        <tr>
                            <td  style=" font-size:18px;  " ><strong>{{$datos['puesto']}} {{$datos['entidad']}}</strong></td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;  " ><strong>Presente.</strong></td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;" ><br></td>
                        </tr>
                        <tr>
                            <td  style=" font-size:16px; text-align:justify  " >En atención a su @php if($datos['numoficio']=='') echo'correo electrónico'; else echo'oficio '.$datos['numoficio']; @endphp
                                @if (count($detalle)>1)
                                , en el cual solicita verificar la autenticidad del certificado de terminación de estudios a nombre de los jóvenes que se enlistan a continuación, le informo que los documentos en cuestión son <strong>auténticos.</string>
                                @else
                                , en el cual solicita verificar la autenticidad del certificado de terminación de estudios a nombre de {{$detalle[0]['el_la']}} joven que se enlista a continuación, le informo que el documento en cuestión es <strong>auténtico.</string>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;" ><br></td>
                        </tr>
                        <tr>

                            <table cellspacing="0" cellpadding="0" style="width:100%; ">
                                <tr>
                                    <th style=" font-size:12px;  border: 1pt solid gray; " width="15%"><strong>EXPEDIENTE</strong></th>
                                    <th style=" font-size:12px;  border: 1pt solid gray; " width="50%"><strong>NOMBRE</strong></th>
                                    <th  style=" font-size:12px;  border: 1pt solid gray; " width="10%"><strong>FOLIO</strong></th>
                                    <th  style=" font-size:12px;  border: 1pt solid gray; " width="25%"><strong>FECHA DE EXPEDICIÓN</strong></th>
                                </tr>

                                @foreach($detalle as $det)
                                @php
                                    $cert=Oficio::oficio_certifica($det['certificado_id']);
                                    $noexp=$cert['noexpediente'];
                                    $feccer=$cert['fecha_certificado']
                                @endphp
                                <tr>
                                    <td style=" font-size:12px;  text-align: center; border: 1pt solid gray; " width="15%">{{$noexp}}</td>
                                    <td style=" font-size:12px;  text-align: center; border: 1pt solid gray; " width="50%">{{$det['nombrealumno']}}</td>
                                    <td  style=" font-size:12px;  text-align: center; border: 1pt solid gray; " width="10%">{{$det['folio']}}</td>
                                    @php
                                    $fecha_actual1=strtotime($feccer);
                                    $fec1=strtoupper(strftime("%d %B %Y", $fecha_actual1));
                                    @endphp
                                    <td  style=" font-size:12px;  text-align: center; border: 1pt solid gray; " width="25%">{{$fec1}}</td>

                                </tr>

                                @endforeach
                            </table>
                           </tr>
                           <tr>
                            <td   style=" font-size:18px;" ><br></td>
                        </tr>
                        <tr>
                            <td  style=" font-size:16px; text-align:justify  " >Lo anterior,
                                en virtud de que
                                @if(count($detalle)>1)
                                los mismos corresponden
                                @else
                                el mismo corresponde
                                @endif
                                 en todos sus datos con los
                                registros electrónicos que se encuentran bajo resguardo del Departamento de
                                Servicios Escolares, adscrito a la Dirección de Planeación de este Colegio de
                                Bachilleres del Estado de Sonora.</td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;" ><br></td>
                        </tr>
                        <tr>
                            <td style=" font-size:16px;  " >Sin otro particular de momento, le envío un cordial saludo.</td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;" ><br></td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;  " ><strong>Atentamente</strong></td>
                        </tr>
                        <tr>
                            <td  style=" font-size:18px;  " ><strong>{{$datos['puestojefeservesc']}}</strong></td>
                        </tr>

                        <tr>
                            <td   style=" font-size:18px;" ><br><br><br><br></td>
                        </tr>
                        <tr>
                            <td  style=" font-size:18px;  " ><strong>{{$datos['jefeservesc']}}</strong></td>
                        </tr>
                        <tr>
                            <td   style=" font-size:18px;" ><br><br></td>
                        </tr>
                        <tr>
                            <td  style=" font-size:8px;  " >C.c.p. @php echo $datos['concopia']; @endphp</td>
                        </tr>
                    </table>
          <footer>
                <p style="font-size:10px; text-align: center;">Blvd. Vildósola Final, Sector Sur y calle Bachilleres, Col. Villa de Seris, C.P. 83280</p>
                <p  style="font-size:10px; text-align: center; ">Teléfono: (662) 259 2900, Hermosillo, Sonora / www.cobachsonora.edu.mx</p>
                <br><br>
            </footer>

        </section>
        <footer>
            <table>
                <tr>
                  <td>
                      <p class="izq">
                        Sistema de Control Escolar
                      </p>
                  </td>
                  <td>Hermosillo, Sonora; {{$fec}}. {{$datos['oficio']}}</td>
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





