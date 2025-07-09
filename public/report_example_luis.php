<html>
    <head>
    <style>
    @page { margin: 100px 90px; }
    #header { position: fixed; left: 0px; top: -90px; right: 0px; height: 130px; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: 0px; right: 0px; height: 10px; }
    #footer .page:after { content: counter(page); }
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
                    Departamento de Evaluación y Desarrollo Docente<br>{{$encabezado}}
                </h3>
                </td>
            </tr>

        </table>
    </div>
    <div id="footer">
        {{--<p><span style="font-size:70%">C.c.p Departamento de servicios escolares del plantel.<br>
            C.c.p Departamento de Evaluación y Desarrollo Docente.
            </span></p>--}}
    </div>
    <div id="content">
    <table border="1" cellpadding="0" cellspacing="0" width=100%>
        <tr>
            <td colspan="2"><p>Nombre:</p></td><td colspan="2"><p>Fecha hora:</p></td>
        </tr>
        <tr>
            <td><p>Expediente:</p><td><p>Grupo:</p></td><td><p>Turno:</p></td><td><p>Plantel:</p></td>
        </tr>
    </table>
    <h3 align="center">Examen de {{$examen->asignatura->asignatura}}</h3>

    @if($reactivos)
        @php
            $tren = "";
            $texto_id = 0;
            $texto_cont = 0;
        @endphp
        @foreach($reactivos as $r)
            @php
                $texto = $r->reactivo->temasrelacionado;
                //dd($texto);
            @endphp

            @if(($texto) AND ($texto_id <> $texto->id))
                @php
                    $texto_cont=0;
                @endphp
            @endif

            @if($texto)
                @php
                    $texto_cont++;
                    if($texto_cont==1)
                    {
                        $texto_id = $texto->id;
                    }
                @endphp
            @else
                @php
                    $texto_cont = 0;
                @endphp
            @endif

            @if(($texto) AND ($texto_cont <= 1))
                <table border="1" cellpadding="0" cellspacing="0" width=100%>
                    <tr>
                        <td><strong>Texto relacionado a reactivo(s) {{$r->orden}}.n</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>{!! $texto->texto_relacionado !!}
                        </td>
                    </tr>
                </table>
            @endif

            <table border="1" cellpadding="0" cellspacing="0" width=100%>
                <tr>
                    <td colspan="2">
                        <strong>Reactivo {{$r->orden}}{{$texto_cont>0? '.'.$texto_cont: ''}}</strong>.- {!! $r->reactivo->texto !!}
                    </td>
                </tr>
                <tr>
                    <td><strong>Opcion A:</strong>
                        {!! $r->reactivo->opcion_a !!}
                    </td>
                    <td><strong>Opcion B:</strong>
                        {!! $r->reactivo->opcion_b !!}
                    </td>
                </tr>
                <tr>
                    <td><strong>Opcion C:</strong>
                        {!! $r->reactivo->opcion_c !!}
                    </td>
                    <td><strong>Opcion D:</strong>
                        {!! $r->reactivo->opcion_d !!}
                    </td>
                </tr>
            </table>
            <br>
            @php
                $orden = $r->orden;

                if ($texto_cont>0)
                {
                    $orden = $orden.".".$texto_cont;
                }

                $tren = $tren."Reactivo: ".$orden.": Resp=".$r->reactivo->opcion_correcta." <br>";

            @endphp
        @endforeach

    @endif


    <div style="page-break-after:always;"></div><!-- *************************************************************************************-->
    <br><br>
    <h2>Tren de Respuestas</h2>
        {!!$tren!!}
    </body>
</html>
