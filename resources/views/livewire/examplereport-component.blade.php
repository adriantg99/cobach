{{-- ANA MOLINA 07/08/2023 --}}
<html lang="es">
    <head>
    <style>
    @page { margin: 0 0 0 0 ;}
    #header { position: fixed; left: 0px; top: 0px; right: 0px; height: 100px; text-align: center;  border-block: solid blue;}
    #footer { position: fixed; left: 0px; bottom: 50px; right: 0px; height: 50px;  border-block: solid red;}
    #footer .page:after { content: counter(page); }
    #content { position: fixed; left: 0px; top: 110px; right: 0px;  border-block: solid pink;}
    body {
    font-family: sans-serif;
    font-size: 10px;
    }
    </style>
    {{--
    <style type="text/css">
        .watermark{
            background:url(img/logo_mA.png);
        }
        </style> --}}
    </head>
    <body class="watermark">
    <div id="header">
        <table>
            <tr>
                <td>
                    <img src="../public/logocobachchico.png" width="102" height="50">
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
    </div>
    <div id="footer">
        <p><span style="font-size:70%">C.c.p Departamento de servicios escolares del plantel.<br>
            C.c.p Departamento de Evaluación y Desarrollo Docente.
            </span></p>
    </div>
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
