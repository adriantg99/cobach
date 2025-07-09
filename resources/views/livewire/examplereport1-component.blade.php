{{-- ANA MOLINA 07/08/2023 --}}
<html lang="es">
    <head>
        <meta charset="utf-8" />
{{--
    <link href="{{  env('APP_URL') }}:8000/css/vendor.min.css" rel="stylesheet">
    <link href="{{  env('APP_URL') }}:8000/css/app.min.css" rel="stylesheet"> --}}

    <style type="text/css">
    @page { margin: 0 0 0 0 ;}
    header { position: fixed; left: 25mm; top: 0mm; right: 25mm;  text-align: center;  border-block: solid blue;}
    header h1{
      margin: 0mm 10mm 0mm 10mm;
      border-block: solid green;
    }
    header h2{
        margin: 0mm 20mm 0mm 20mm;
       border-block: solid rgb(147, 81, 223);
    }

    footer { position: fixed; left: 25mm; bottom: 5mm; right: 25mm; border-block: solid red;}
    footer .page:after { content: counter(page); }
    #content { position: fixed; left: 25mm; top: 50mm; right: 25mm; bottom:30mm;  border-block: solid yellow;}
    body {
    font-family: sans-serif;
    font-size: 10px;
    border-block: solid pink;
    margin: 20mm 25mm 20mm 25mm;
    }
    </style>
    {{--
    <style type="text/css">
        .watermark{
            background:url(img/logo_mA.png);
        }
        </style> --}}
    </head>
    <body >
        <header>
            <table>
                <tr>
                    <td>
                        <img src={{ asset('images/logocobachchico.png') }} width="100" height="60" alt="Logo" class="logo">

                    </td>
                    <td>
                        <p>    </p>
                    </td>
                    <td>
                        <h3><strong>DIRECCIÓN ACADÉMICA</strong><br>
                        <strong>Subdirección de Desarrollo Académico</strong><br>
                        Departamento de Evaluación y Desarrollo Docente<br>
                    </h3>
                    </td>
                </tr>

            </table>
            <h1>
                Este es un ejemplo de header1
               </h1>
                <h2>
                Este es un ejemplo de header2
               </h2>



        </header>
        {{-- <div id="header">
            <table>
                <tr>
                    <td>
                        <img src={{ asset('images/logocobachchico.png') }} width="100" height="60" alt="Logo" class="logo">

                    </td>
                    <td>
                        <p>    </p>
                    </td>
                    <td>
                        <h3><strong>DIRECCIÓN ACADÉMICA</strong><br>
                        <strong>Subdirección de Desarrollo Académico</strong><br>
                        Departamento de Evaluación y Desarrollo Docente<br>
                    </h3>
                    </td>
                </tr>

            </table>
        </div> --}}
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
            <div id="footer">
                <p><span>C.c.p Departamento de servicios escolares del plantel.<br>
                    C.c.p Departamento de Evaluación y Desarrollo Docente.
                    </span></p>
            </div>
          </footer>

    <div id="content">
    <table border="1" cellpadding="0" cellspacing="0" width=100% >
        <tr>
            <td colspan="2"><p>Nombre:</p></td><td colspan="2"><p>Fecha hora:</p></td>
        </tr>
        <tr>
            <td><p>Expediente:</p><td><p>Grupo:</p></td><td><p>Turno:</p></td><td><p>Plantel:</p></td>
        </tr>
    </table>
    <h3 align="center">Examen de </h3>


     @for ($i = 0; $i < 100; $i++)



                <table border="1" cellpadding="0" cellspacing="0" width=100%>
                    <tr>
                        <td><strong>Texto relacionado a reactivo(s) .n</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong> aqui {{ $i }}</strong>
                        </td>
                    </tr>
                </table>


            <table border="1" cellpadding="0" cellspacing="0" width=100%>
                <tr>
                    <td colspan="2">
                        <strong>Reactivo  </strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Opcion A:</strong>

                    </td>
                    <td><strong>Opcion B:</strong>

                    </td>
                </tr>
                <tr>
                    <td><strong>Opcion C:</strong>

                    </td>
                    <td><strong>Opcion D:</strong>

                    </td>
                </tr>
            </table>
            <br>
            <
        @endfor



    <div style="page-break-after:always;"></div><!-- *************************************************************************************-->
    <br><br>
    <h2>Tren de Respuestas</h2>
       dd
    </body>
</html>
