<html>
<head>
    <meta charset="utf-8">
    
    <style>

        @font-face {
            font-family: 'Elegance';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: "http://eclecticgeek.com/dompdf/fonts/Elegance.ttf";
          }
          body {
            font-family: Elegance, sans-serif;
          }
            /** 

            body{
    
            }
                        * Establecer los márgenes del PDF en 0
            * por lo que la imagen de fondo cubrirá toda la página.
            **/
            @page {
                margin: 0cm 0cm;
            }

            /**
            * Define los márgenes reales del contenido de tu PDF
            * Aquí arreglarás los márgenes del encabezado y pie de página
            * De tu imagen de fondo.
            **/
            body {
                margin-top:    3.5cm;
                margin-bottom: 1cm;
                margin-left:   1cm;
                margin-right:  4cm;
            }

            /** 
            * Defina el ancho, alto, márgenes y posición de la marca de agua.
            **/
            #watermark {
                position: fixed;
                bottom:   0px;
                left:     0px;
                /** El ancho y la altura pueden cambiar
                    según las dimensiones de su membrete
                
                width:    30.9cm;
                height:   21.6cm;
                **/
                width:    21.0cm;
                height:   30.5ccm;
                
                /** Tu marca de agua debe estar detrás de cada contenido **/
                z-index:  -1000;
            }
    </style>
</head>
<body>
    <div id="watermark">
            <img src="images/plantilla_diploma_cpt.jpg" height="100%" width="100%" />
    </div>
<main>
    <center>
    @foreach($alumnos as $key => $value)
        @if($value)
            @php
                $alumn = App\Models\Adminalumnos\AlumnoModel::find($key);
            @endphp
            <table width= "100%">
            <tr>
                <td width="110px"></td>
                <td align="center">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>                            
                    <br>
                    <br>
                    <p align="center"><span style="font-size: 140%"><strong>{{$alumn->nombre}} {{$alumn->apellidos}}</strong></font></span></p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>                            
                    <br>
                    <br>
                    @php
                    switch ($alumn->capacitacion()) 
                    {
                        case 'DES_MICR':
                            $capacitacion =  "DESARROLLO MICROEMPRESARIAL";
                            break;
                        case 'CONTABIL':
                            $capacitacion =  "CONTABILIDAD";
                            break;
                        case 'INFORMAT':
                            $capacitacion =  "INFORMÁTICA";
                            break;
                        case 'ING_P_RE':
                            $capacitacion =  "INGLÉS PARA RELACIONES LABORALES";
                            break;
                        case 'COMUNICA':
                            $capacitacion =  "COMUNICACIÓN";
                            break;
                        case 'SER_TURI':
                            $capacitacion =  "SERVICIOS TURÍSTICOS";
                            break;
                        case 'TEC_D_CO':
                            $capacitacion =  "TÉCNICAS DE CONSTRUCCIÓN";
                            break;
                        case 'GASTRONO':
                            $capacitacion =  "GASTRONOMÍA Y NUTRICIÓN";
                            break;
                    }
                    @endphp
                    <p align="center"><span style="font-size: 125%"><strong>{{ $capacitacion }}</strong></font></span></p>
                </td>
            </tr>
            </table>
            <div style="page-break-after:always;"></div><!-- *************************************************************************************-->
        @endif
    @endforeach
    
    </center>
</main>
</body>
</html>