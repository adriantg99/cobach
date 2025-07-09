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
                    <h2><strong>ACTA DE EXAMEN DE PASANTIA</strong></h2>
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
        @php
            $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
        @endphp
        <table width=100%>
            <tr>
                <td align="left">
                    <p><span style="font-size:130%";>{{strtoupper($acta->plantel_mod->municipio)}}, SONORA, A {{date('j',strtotime($acta->fecha_creacion))}} DE {{$meses[date("n",strtotime($acta->fecha_creacion))-1]}} DE {{date('Y',strtotime($acta->fecha_creacion))}}</span>
                    </p>
                    <p><span style="font-size:130%";><strong><br>SECRETARIO ADMINISTRATIVO</strong></span></p>
                    <br>
                    <p><span style="font-size:130%";>P r e s e n t e</span></p>
                </td>
                <td align="right"><p><span style="font-size:130%";></span></p> </td>
            </tr>
        </table>
        <br>
        <br>
        <p align="justify"><span style="font-size:130%";>De acuerdo a las disposiciones emanadas de los articulo 31 Fracc. II inciso C del Reglamento de Servicios Escolares Y Convivencia Escolar, nos permitimos comunicar a usted, que con esta fecha el estudiante:</span></p>
        <br>
        <p align="center"><span style="font-size:130%";><strong>{{$acta->alumno->nombre}} {{$acta->alumno->apellidos}} </strong></span></p>
        <p align="justify"><span style="font-size:130%";>con No. de Expediente <strong>{{$acta->alumno->noexpediente}}</strong> presentó examen de pasantía de la asignatura</span><p>
        <br>
        <p align="center"><span style="font-size:130%";><strong>{{$acta->asignatura->nombre}} </strong></span></p>
        @if(is_null($acta->calificacion)==false)
            <p align="justify"><span style="font-size:130%";>Del {{ $semestre_texto }} semestre del plan de estudios de esta Institución y obtuvo la calificación de <strong>{{$acta->calificacion}} (@php
                                    $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                                    $result = $formatter->format($acta->calificacion);
                                    echo strtoupper($result); // Esto mostrará "ciento veintitrés"
                                @endphp)</strong>, la cual deberá asentarse en el expediente respectivo.</span><p>
        @else
            <p align="justify"><span style="font-size:130%";>Del {{ $semestre_texto }} semestre del plan de estudios de esta Institución y obtuvo la calificación de <strong>{{$acta->calif}} ({{$acta->calif=='AC'? 'ACREDITADA':'NO ACREDITADA'}}), la cual deberá asentarse en el expediente respectivo.</span><p>
        @endif
        <br>
        <br>
        <p align="center"><span style="font-size:130%";><strong> Vo.Bo. </strong></span></p>
        
        <br>
        <table width=100%>
            <tr>
                <td align="center"><span style="font-size:130%";>DIRECTOR DEL PLANTEL {{$acta->plantel}}<br><br><br><br><br></span></td>
                <td></td>
                <td align="center"><span style="font-size:130%";>EL(LA) C. COMISIONADO(A)<br><br><br><br><br></span></td>
            </tr>
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
                <td align="center"><span style="font-size:130%";>{{ $acta->plantel_mod->director }}</span></td>
                <td></td>
                <td align="center"><span style="font-size:130%";>{{ $acta->nombre_doc() }}</span></td>
            </tr>
        </table>
    </div>
</body>
</html>