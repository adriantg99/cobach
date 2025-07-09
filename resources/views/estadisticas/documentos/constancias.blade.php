<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Estudios</title>
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <style>
        /* Estilos CSS para la constancia de estudios */
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            /* Asegura que el contenedor sea relativo */
        }

        .header {
            text-align: right;
            /* Alinea el texto a la derecha */
            margin-bottom: 20px;
            position: relative;
        }

        .title {
            font-size: 20px;

            margin-bottom: 10px;
            position: absolute;
            /* Asegura que el título sea absoluto */
            top: 50px;
            /* Ajusta la posición vertical */
            right: 30px;
            /* Ajusta la posición horizontal para alinear con el logo */
        }

        .info {
            font-family: Calibri;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 0px;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 140px;
            /* ajusta el ancho según el tamaño de tu logo */
        }

        .student-photo {
            position: absolute;
            top: 125px;
            left: 30px;
            width: 125px;
            max-width: 125px;
            max-height: 125px;


            min-width: 125px;
            min-height: 125px;


            /* ajusta el ancho según el tamaño de la foto del estudiante */
        }

        .content {
            margin-top: 300px;
            /* ajusta este margen para centrar el contenido */
            text-align: justify;
            /* Alinea el texto justificado */

        }

        .atentamente {
            margin-top: 200px;
            /* ajusta este margen para centrar el contenido */
            text-align: center;
            /* Alinea horizontalmente */
        }

        .nombreDir {
            margin-top: 50px;
            /* ajusta este margen para centrar el contenido */

            text-align: center;
            /* Alinea horizontalmente */

        }

        .subject {
            position: absolute;
            top: 150px;
            /* ajusta la posición vertical */
            right: 20px;
            /* ajusta la posición horizontal */
        }
    </style>
</head>

<body>
    @php
        use App\Models\Adminalumnos\ImagenesalumnoModel;

    @endphp
    @foreach ($datos as $dato)
        <div class="container">
            <div class="header">
                <img src="{{ $logo }}" alt="Logo" class="logo">

                <div class="title">{{ $dato->plantel }}</div>
                @php
                    if ($promedio == 1) {
                        $results = DB::select('CALL pa_kardex_totales(?)', [$dato->id_alumno]);
                    }
                    $imagen_alumnos_2 = ImagenesalumnoModel::where('alumno_id', $dato->id_alumno)
                        ->where('tipo', 1)
                        ->get();
                    switch ($dato->periodo) {
                        case '1':
                            $semestre_texto = 'PRIMER';
                            break;
                        case '2':
                            $semestre_texto = 'SEGUNDO';
                            break;
                        case '3':
                            $semestre_texto = 'TERCER';

                            break;
                        case '4':
                            $semestre_texto = 'CUARTO';
                            break;
                        case '5':
                            $semestre_texto = 'QUINTO';
                            break;
                        case '6':
                            $semestre_texto = 'SEXTO';
                            break;
                        default:
                            break;
                    }

                    if ($dato->turno_id == '1') {
                        $turno_nombre = 'MATUTINO';
                    } else {
                        $turno_nombre = 'VESPERTINO';
                    }
                @endphp
                @if ($dato->qr)
                <div class="qr" style="position: absolute; top: 180px; right: 10px;">
                    <img src="data:image/png;base64,{{ $dato->qr }}" alt="Código QR">
                </div>    
                @endif
                
                @if (count($imagen_alumnos_2))
                    <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_alumnos_2[0]->imagen)) }}"
                        alt="Foto del Estudiante" class="student-photo">
                @endif


                <div class="subject">Asunto: Constancia de Estudios</div>

            </div>
            <div class="content">
                <strong>
                    <p style="margin-bottom: 5%;" style="font-family: Calibri; font-size: 16px;">A QUIEN CORRESPONDA:
                    </p>
                </strong>
                <div class="info">
                    <p style="margin-bottom: 5%;">La Dirección del plantel <!---->{{ $dato->plantel }} con clave de
                        incorporación {{ $dato->cct }},
                        hace constar que: <strong> {{ $dato->nombre }} {{ $dato->apellidos }}</strong>
                        con número de matricula <strong>{{ $dato->noexpediente }}</strong>, con CURP:
                        {{ $dato->curp }}
                        inscrito en {{ $semestre_texto }} semestre de Bachillerato General perteneciente
                        al grupo {{ $dato->grupo_nombre }} {{ $turno_nombre }}. Correspondiente al periodo escolar del
                        6
                        de Enero de 2025
                        - 15 de Junio de 2025, con un periodo vacacional del 16 Junio al 7 de Agosto de 2025.
                        @if ($promedio == 1)
                            Con un promedio general de {{ $results[0]->promedio }}.
                        @endif
                    </p>



                    <p>Por lo que a petición del interesado y para los fines legales a que haya lugar, se extiende la
                        presente
                        en la localidad de {{ $dato->localidad }}, Sonora {{ $fechaTexto }}</p>
                </div>





                <!-- Puedes agregar más información aquí según tus necesidades -->
            </div>

        </div>
        <div class="footer">
            <div class="atentamente">ATENTAMENTE</div>

            <div class="nombreDir">

                <p>
                    <strong>
                        ______________________________________________
                        <br>
                        {{ $dato->director }}
                    </strong>
                </p>
                Director del plantel



            </div>

        </div>
        @if ($datos->count() > 1)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

</body>

</html>
