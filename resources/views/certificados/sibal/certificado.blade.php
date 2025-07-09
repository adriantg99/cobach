<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            font-size: 9px;
            margin: 0;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            text-align: center;
        }

        .header {
            font-size: 12px;
        }

        .header {
            margin-top: 45px;
            text-align: center;
        }

        .title {
            margin-top: 20px;
            margin-bottom: -15px;
            line-height: 1.5;
            text-align: left;
            font-size: 12px;
            padding-left: 5%;
            padding-right: 5%;
        }

        .student-info {
            font-size: 10px;
            margin-bottom: 15px;
            line-height: 1.5;
            text-align: center;
        }

        .content-wrapper {
            display: flex;
            align-items: flex-start;
            margin-top: 10px;
        }

        /* Espacio para la imagen del alumno */
        .student-photo {
            width: 120px;
            height: 150px;
            border: 1px solid #000;
            margin-right: 15px;
            text-align: center;
            font-size: 9px;
            line-height: 150px;
            color: #555;
            top: 23%;
            position: absolute;
        }

        .table-container {
            width: 75%;
            position: absolute;
            left: 140px;
            top: 19%;
            font-size: 4px;
            right: 5%;

        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            height: 60%;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 1px;
            font-size: 9px;
            text-align: left;
        }

        .table th {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .th_modulo {
            width: 22%;
            text-align: justify;
        }

        .th_clave {
            width: 13%;
        }

        .th_asig {
            width: 55%;
        }

        .th_calif {
            width: 10%;
            text-align: center
        }

        .signature {
            font-family: 'Arial', sans-serif;
            text-align: center;
            font-size: 12px;
            line-height: 1.5;
            padding-top: 5%;
        }

        .large-text {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5%;

        }

        .abajo {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            text-align: justify;
        }

        footer {
            top: 850px;
            position: absolute;
            padding-left: 5%;
            padding-right: 5%;
        }

        .vobo{
            position: absolute;
            top: 50%;
            left: -5%;
            width: 25%;
        }
        .alineado{
            text-wrap: nowrap;
            font-size: 8px;
        }
        .duplicado{
            text-align: center;
            font-size: 18px;
            font-family: 'Bell MT';
            top: -4%;
            position: absolute;
            left: 30%;
        }

        .promedio{
            font-size: 12px;
            position: absolute;
            top: 58%;
            left: 73%;
        }
        .promedio_numero{
            font-size: 12px;
            position: absolute;
            top: 58%;
            left: 93%;
        }
    </style>
</head>

<body>
    @if($duplicado)
    <div class="fixed-top-text duplicado">
        D <label style="color:white"> . </label> U <label style="color:white"> . </label> P <label
                style="color:white"> . </label> L <label style="color:white"> . </label>
            I <label style="color:white"> . </label> C <label style="color:white"> . </label> A <label
                style="color:white"> . </label> D <label style="color:white"> . </label> O
    </div>
    @endif
    <div class="container">
        <div class="header">
            CLAVE DEL CENTRO DE TRABAJO: 26ECB1024P
        </div>
        <div class="title">
            LA DIRECCIÓN GENERAL CERTIFICA, QUE SEGÚN CONSTANCIAS QUE OBRAN EN EL ARCHIVO DE ESTE PLANTEL,
            @if ($genero == "F")
            LA ALUMNA:    
            @else
            EL ALUMNO:
            @endif
            <br><br>
        </div>
        <span class="large-text" style="text-align: center">{{ $student_name }}</span>

        <div class="student-info"
            style="margin-top: 15px; text-align: left; padding-left: 5%;
            padding-right: 3%;">
            CON EXPEDIENTE {{ $student_id }}, CURSÓ @if ($totalSubjects == 42)
                TOTALMENTE
            @else
                PARCIALMENTE
            @endif EL PLAN DE ESTUDIOS DE EDUCACIÓN MEDIA SUPERIOR EN LÍNEA, <br>
            OBTENIENDO LAS CALIFICACIONES QUE A CONTINUACIÓN SE ANOTAN:
        </div>

        <div class="vobo">
            <p>Vo. Bo.</p>
            <p class="alineacion">JEFE DEL DEPARTAMENTO DE SERVICIOS ESCOLARES</p>

            <br><br><br><br>
            <div class="alineado">
                <p>C.P. COSME DAMIAN CASTILLO DUARTE</p>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="student-photo">
                Foto Alumna
            </div>

            <div class="table-container">
                <table class="table">
                    <tr>
                        <th class="th_modulo">MÓDULO</th>
                        <th class="th_clave">CLAVE</th>
                        <th class="th_asig">ASIGNATURAS</th>
                        <th class="th_calif" style="text-align: center">CALIF</th>
                    </tr>

                    @php $lastModule = ''; @endphp
                    @foreach ($grades as $grade)
                        <tr>
                            {{-- Mostrar el módulo solo la primera vez que aparece --}}
                            @if ($lastModule !== $grade['module'])
                                <td class="modulo"
                                    rowspan="{{ count(array_filter($grades, fn($g) => $g['module'] === $grade['module'])) }}">
                                    {{ $grade['module'] }}
                                </td>
                                @php $lastModule = $grade['module']; @endphp
                            @endif
                            <td>{{ $grade['key'] }}</td>
                            <td>{{ $grade['subject'] }}</td>
                            <td style="text-align: center">{{ $grade['score'] }}</td>
                        </tr>
                    @endforeach
                </table>
                <p class="promedio">Promedio: </p>
                <p class="promedio_numero">{{ $promedio }}</p>
            </div>
        </div>






    </div>

    @php
        date_default_timezone_set('America/Hermosillo');
        setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');

        // Obtener la fecha de hoy y formatearla en español
        $fecha_actual = time();
        $fec = strtoupper(strftime('%d DE %B DE %Y', $fecha_actual));

    @endphp
    <footer>
        <div class="footer abajo">

            SE EXTIENDE EL PRESENTE CERTIFICADO QUE AMPARA {{ $totalSubjects }} <?php   $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                
            ?>

            ({{ strtoupper(str_replace(
                ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                ucfirst($formatter->format($totalSubjects))
            )) }}), ASIGNATURAS APROBADAS
            CON LO
            QUE ACREDITA INTEGRAMENTE SUS ESTUDIOS EN EDUCACIÓN MEDIA SUPERIOR EN HERMOSILLO, SONORA,
            MÉXICO, EL DÍA {{ $fec }}.
            <div class="signature">
                <strong>DIRECTOR GENERAL</strong><br><br> <br><br>
                DR. RODRIGO ARTURO ROSAS BURGOS
            </div>
        </div>
    </footer>
</body>

</html>
