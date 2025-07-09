<html> 
    <head> 
    <style> 
    @page { margin: 100px 90px; } 
    #header { position: fixed; left: 0px; top: -60px; right: 0px; height: 130px; text-align: center; } 
    #footer { position: fixed; left: 0px; bottom: 0px; right: 0px; height: 10px; } 
    #footer .page:after { content: counter(page); } 
    body {
    font-family: sans-serif;
    font-size: 9px;
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
        <table width="100%">
            <tr>
                <td align="left">
                    <img src="../public/images/logo_sonora.png" height="50">
                </td>
                <td>
                    <p>    </p>
                </td>
                <td width="50%" align="center">
                    <h2><strong>FICHA DE DEPOSITO</strong></h2>
                </td>
                <td>
                    <img src="../public/images/logocobachchico.png" height="50">
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
    @php
        $cont = 0;
    @endphp
    @foreach($fichas as $ficha)
        
        @if($cont>0)
            <div style="page-break-after:always;"></div><!-- *************************************************************************************-->
        @endif

        @php
            $cont++;
        @endphp
        <p align="right">{{date('d/m/Y')}}</p>
        <h2><strong>FOLIO: </strong> {{$ficha->folio}}<br>
            <strong>MATRICULA: </strong> {{$ficha->matricula}}<br>
            <strong>NOMBRE: </strong> {{$ficha->nombre_alumno}}<br>
        </h2>
        <br><br>
        <table border="1" cellpadding="0" cellspacing="0" width=100%>
            <tr>
                <td align="center" width="80%"><h2><strong>Descripción</strong></h2></td>
                <td align="center"><h2><strong>Importe</strong></h2></td>
            </tr>
        </table>
        @php
        $fichas_detalles = $ficha->fichas_detalles();
        @endphp
        <table width=100%>
            @foreach($fichas_detalles as $detalle)
            <tr>
                <td width="80%"><h3><strong>{{$detalle->descripcion}}</strong></h3></td>
                <td align="right"><h3><strong>{{number_format($detalle->importe,2)}}</strong></h3></td>
            </tr>
            @endforeach
        </table>
        
        <table border="1" cellpadding="0" cellspacing="0" width=100%> 
            <tr>
                <td width="80%" align="right"><h2><strong>TOTAL:</strong></h2></td>
                <td align="right"><h2><strong>{{ number_format($ficha->total,2) }}</strong></h2></td>
            </tr>
        </table><br>
        <h2 align="center">Para uso Mediante Transferencia:</h2>
        <p><strong>DEPÓSITO MEDIANTE TRANSFERENCIA ELECTRÓNICA SPEI DESDE CUENTAS DE CUALQUIER BANCO (BBVA, BANAMEX, SANTANDER, BANORTE, ETC):</strong></p>
        <table border="1" cellpadding="0" cellspacing="0" width=60% align="center"> 
            <tr>
                <td>CLABE INTERBANCARIA:</td><td align="center"><strong>021180550300002937</strong></td>
            </tr>
            <tr>
                <td>BANCO:</td><td align="center"><strong>HSBC</strong></td>
            </tr>
            <tr>
                <td>NOMBRE DEL BENEFICIARIO:</td><td align="center"><strong>COLEGIO DE BACHILLERES DE SONORA</strong></td>
            </tr>
            <tr>
                <td>CONCEPTO DE PAGO:</td><td align="center"><strong>{{substr($ficha->cadena_hsbc_bienestar,4)}}</strong></td>
            </tr>
        </table>
        <p><strong>DEPÓSITO MEDIANTE TRANSFERENCIA ELECTRÓNICA DESDE CUENTAS DE HSBC:</strong></p>
        <table border="1" cellpadding="0" cellspacing="0" width=60% align="center"> 
            <tr>
                <td>TIPO DE PAGO:</td><td align="center"><strong>Pago de Servicios</strong></td>
            </tr>
            <tr>
                <td>CONTRATO RAP:</td><td align="center"><strong>293</strong></td>
            </tr>
            <tr>
                <td>REFERENCIA DE PAGO:</td><td align="center"><strong>{{substr($ficha->cadena_hsbc_bienestar,4)}}</strong></td>
            </tr>
        </table>
        <ul>
            <li><strong>NOTA 1:</strong> ES OBLIGATORIO COLOCAR EL CONCEPTO O REFERENCIA DE PAGO INDICADO Y APLICARLO ANTES DE LA VIGENCIA ESTABLECIDA, <strong>DE LO CONTRARIO TU TRANSFERENCIA SERÁ DEVUELTA DE MANERA AUTOMÁTICA</strong>.</li>
            <li><strong>NOTA 2:</strong> CONSERVAR EL COMPROBANTE PARA CUALQUIER ACLARACIÓN.</li>
        </ul>
        <br>
        <h2 align="center">Para uso exclusivo en banco:</h2>
        <table width=100%>
            <tr style="height: 50px;">
                <td width=45%><img src="../public/images/bancos/hsbc.png" height="25">
                <img src="../public/images/bancos/bienestar.png" height="25"></td>
                <td colspan="2">
                    <table border="1" cellpadding="0" cellspacing="0" width=100%>
                    <tr><td>
                    <h1><span style="font-size: 70%"><strong>{{$ficha->cadena_hsbc_bienestar}}</strong></span></h1>
                    </td></tr>
                    </table
                </td>
            </tr>
        </table>
        <br>
        <table width=100%>
            <tr style="height: 50px;">
                <td width=45%><img src="../public/images/bancos/banamex.png" height="30"></td>
                <td colspan="2">
                    <table border="1" cellpadding="0" cellspacing="0" width=100%>
                    <tr><td>
                    <h1><span style="font-size: 70%"><strong>{{$ficha->cadena_banamex}}</strong></span></h1>
                    </td></tr>
                    </table
                </td>
            </tr>
        </table>
        <br>
        <table width=100%>
            <tr style="height: 50px;">
                <td width=45%><img src="../public/images/bancos/bancomer.png" height="35"></td>
                <td colspan="2">
                    <table border="1" cellpadding="0" cellspacing="0" width=100%>
                    <tr>
                        <td width=15%><strong>Convenio</strong></td>
                        <td><strong>Referencia</strong></td>
                    </tr>
                    <tr>
                        <td>
                        <h1><span style="font-size: 70%"><strong>{{substr($ficha->cadena_bbva,0,6)}}</strong></span></h1>
                        </td>
                        <td>
                            <h1><span style="font-size: 70%"><strong>{{substr($ficha->cadena_bbva,7)}}</strong></span></h1>
                        </td>
                    </tr>
                    </table
                </td>
            </tr>
        </table>
        <br>
        <table width=100%>
            <tr style="height: 50px;">
                <td width=45%><img src="../public/images/bancos/banco_azteca.png" height="35"></td>
                <td colspan="2">
                    <table border="1" cellpadding="0" cellspacing="0" width=100%>
                    <tr>
                        <td width=35%><strong>Convenio</strong></td>
                        <td><strong>Referencia</strong></td>
                    </tr>
                    <tr>
                        <td>
                        <h1><span style="font-size: 60%"><strong>{{substr($ficha->cadena_banco_azteca,0,14)}}</strong></span></h1>
                        </td>
                        <td>
                            <h1><span style="font-size: 70%"><strong>{{substr($ficha->cadena_banco_azteca,15)}}</strong></span></h1>
                        </td>
                    </tr>
                    </table
                </td>
            </tr>
        </table>
        <br>


        @php
            $dias_semana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
            $mes = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        @endphp
        <p><br><strong>Esta ficha tiene vigencia hasta el día {{$dias_semana[date('N', strtotime($ficha->fecha_caducidad))-1]}}, {{ date('j',strtotime($ficha->fecha_caducidad))}} de {{$mes[date('n',strtotime($ficha->fecha_caducidad))-1]}} de {{ date('Y',strtotime($ficha->fecha_caducidad))}}.</strong></p>
    @endforeach
    
    </body> 
</html>