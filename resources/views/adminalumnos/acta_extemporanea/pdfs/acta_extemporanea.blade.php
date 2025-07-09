<html>
<head>
    <style> 
        @page { margin: 100px 90px; } 
        #header { position: fixed; left: 0px; top: -90px; right: 0px; height: 90px; text-align: center; } 
        #footer { position: fixed; left: 0px; bottom: 0px; right: 0px; height: 10px; } 
        #footer .page:after { content: counter(page); } 
        body {
        font-family: Arial, sans-serif;
        line-height: 1.5;
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
        <table width=100% >
            <tr>
                <td>
                    <img src="../public/images/cobach_vertical.png" width="102" height="50">
                </td>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <h2><strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong></h2>
                    <h2><strong>ACTA ESPECIAL EXTEMPORANEA</strong></h2>
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
        <br>
        <br>
        <br>
        <br>
        <table width=100%>
            <tr>
                <td align="left"><p><span style="font-size:130%";><strong>PLANTEL:</strong> {{$acta->plantel}}</span></p></td>
                <td align="right"><p><span style="font-size:130%";><strong>CICLO ESC:</strong> {{$acta->ciclo_esc->nombre}}</span></p> </td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <br>
        <br>
        @php
            $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
        @endphp
        <p align="justify"><span style="font-size:130%";>EN LA LOCALIDAD DE {{strtoupper($acta->plantel_mod->municipio)}}, SONORA, EL DÍA A LOS {{date('j',strtotime($acta->fecha_creacion))}} DÍAS DEL MES DE {{$meses[date("n",strtotime($acta->fecha_creacion))-1]}} DEL AÑO {{date('Y',strtotime($acta->fecha_creacion))}}, CONFORME A LO PREVISTO EN LOS ART. 29 FRACC. II INCISO B. Y FRACC III, ART. 30 Y 31 DEL REGLAMENTO DE SERVICIOS ESCOLARES Y DE CONVIVENCIA EN ATENCIÓN A APLICACIÓN DE EVALUACIÓN QUE PRESENTO EL INTERESADO,</span></p>
        <br>
        <br>
        <table width=100%>
            <tr>
                <td align="left"><p><span style="font-size:130%";>{{ $acta->nombre_doc() }}</span></p></td>
                <td align="right"><p><span style="font-size:130%";>MAESTRO COMISIONADO</span></p></td>
            </tr>    
        </table>
        <br>
        <br>
        <p><span style="font-size:130%";>SE PROCEDIO A LA EVALUACIÓN EXTRAORDINARIA ESPECIAL, DE LA ASIGNATURA</span></p>
        <br>
        <p align="center"><span style="font-size:130%";><strong>{{ $acta->asignatura->clave }} {{$acta->asignatura->nombre}}</strong></span></p>
        <p align="left"><span style="font-size:130%";>
            @switch($acta->asignatura->periodo)
                @case('1')
                    PRIMERO
                    @break
                    @case('2')
                    SEGUNDO
                    @break
                    @case('3')
                    TERCERO
                    @break
                    @case('4')
                    CUARTO
                    @break
                    @case('5')
                    QUINTO
                    @break
                    @case('6')
                    SEXTO
                    @break
                @default
            @endswitch            
            SEMESTRE, QUE FUE AUTORIZADA AL ALUMNO(A):</span></p>
        <p align="center"><span style="font-size:130%";><strong>{{$acta->alumno->noexpediente}} - {{$acta->alumno->nombre}} {{$acta->alumno->apellidos}} </strong></span></p>
        <p align="left"><span style="font-size:130%";>DEL GRUPO {{$acta->grupo}} {{$acta->turno}}, RESULTANDO DE LA MISMA LA CALIFICACIÓN DE:</span></p>
        @if(is_null($acta->calificacion)==false)
        <p align="center"><span style="font-size:130%";><strong>{{$acta->calificacion}} (@php
                                    $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                                    $result = $formatter->format($acta->calificacion);
                                    echo strtoupper($result); // Esto mostrará "ciento veintitrés"
                                @endphp)</strong></span></p>
        @else
        <p align="center"><span style="font-size:130%";><strong>{{$acta->calif}} ({{$acta->calif=='AC'? 'ACREDITADA':'NO ACREDITADA'}})</strong></span></p>
        @endif
        <p align="left"><span style="font-size:130%";>(MOTIVO: <strong>{{$acta->motivo}}</strong>)<br>
        PARA CONSTANCIA SE FIRMA ESTA ACTA POR TODOS LOS INTERVINIENTES.</span></p>
        <br>
        <br>
        <table width=100%>
            <tr>
                <td align="center"><span style="font-size:130%";>{{$acta->fecha_creacion}}</span></td>
                <td></td>
                <td align="center"><span style="font-size:130%";>{{$acta->fecha_creacion}}</span></td>
            </tr>
            <tr>
                <td align="center"><span style="font-size:130%";>__________________________________</span></td>
                <td></td>
                <td align="center"><span style="font-size:130%";>__________________________________</span></td>
            </tr>
            <tr>
                <td align="center"><span style="font-size:130%";>SOLICITA</span></td>
                <td></td>
                <td align="center"><span style="font-size:130%";>AUTORIZA</span></td>
            </tr>
            <tr>
                <td align="center"><span style="font-size:130%";>{{ $acta->nombre_doc() }}</span></td>
                <td></td>
                <td align="center"><span style="font-size:130%";>{{ $acta->nombre_creador_acta() }}</span></td>
            </tr>
        </table>
    </div>
</body>
</html>