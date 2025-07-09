<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia {{ $alumnos_curso[0]->curso_nombre . ' ' . $fechas_ciclo->grupo }} @if ($fechas_ciclo->turno_id == '1')
            MATUTINO
        @else
            VESPERTINO
        @endif
    </title>
    <style>
        @page {
            margin-top: 15mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            display: table-header-group;
            /* Dompdf repetirá todo lo que haya en thead */
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            font-size: 10px;
            padding: 4px;
            word-wrap: break-word;
        }

        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-size: 8px;
            min-width: 30px;
            /* Ajusta para que no se empalmen */
            white-space: nowrap;
        }

        /* Evita que se corte una fila a mitad de página */
        tr {
            page-break-inside: avoid;
        }

        .col-expediente {
            width: 8%;
            white-space: nowrap;
            text-align: left;
        }

        .col-nombre {
            width: 30%;
            text-align: left;


        }

        .col-final {
            width: 5%;
        }
    </style>
</head>

<body>

    <table style="margin-top: -40px">
        <!-- ENCABEZADO COMPLETO (repetido en cada página) -->
        <thead>
            <!-- Fila 1: Logo y título -->
            <tr>
                <!-- Usamos colspan para abarcar todas las columnas de la tabla -->
                <th colspan="{{ 2 + 23 }}" style="border: none; text-align: left;">
                    <img src="{{ $logo }}" alt="Logo" style="width: 90px; float: left; margin-right: 10px;">
                    <div style="text-align: center; font-size: 14px;">
                        COLEGIO DE BACHILLERES DEL ESTADO DE SONORA<br>
                        <span style="font-size: 12px;">Lista de Asistencia Preliminar</span>
                    </div>
                </th>
            </tr>
            <!-- Fila 2: Datos generales (plantel, asignatura, etc.) -->
            <tr>
                <th colspan="{{ 4 + 23 }}" style="border: none; text-align: left;">
                    <div style="width: 100%; font-size: 10px;">
                        <div style="float: left; width: 50%;">
                            PLANTEL: {{ $fechas_ciclo->plantel }} <br>
                            ASIGNATURA: {{ $alumnos_curso[0]->curso_nombre }}
                        </div>
                        <div style="float: right; width: 50%; text-align: right;">
                            CICLO ESCOLAR: {{ $fechas_ciclo->ciclo_escolar }} <br>
                            FECHA: {{ now()->format('d/m/Y') }}
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div style="width: 100%; font-size: 10px; margin-top: 5px;">
                        <div style="float: left; width: 50%;">
                            GRUPO: {{ $fechas_ciclo->grupo }} @if ($fechas_ciclo->turno_id == '1')
                                MATUTINO
                            @else
                                VESPERTINO
                            @endif
                        </div>
                        <div style="float: right; width: 50%; text-align: right;">
                            PROFESOR: {{ $fechas_ciclo->docente_apellido_paterno }}
                            {{ $fechas_ciclo->docente_apellido_materno }}
                            {{ $fechas_ciclo->docente_nombre }}
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </th>
            </tr>
            <!-- Fila 3: Cabecera de columnas de la tabla de asistencia -->
            <tr>
                <th class="col-final" style="width: 3%">#</th>
                <th class="col-expediente">Expediente</th>
                <th class="col-nombre">Nombre completo</th>
                @for ($i = 0; $i < 23; $i++)
                    <th class="vertical-text">

                    </th>
                @endfor

                <th class="col-final">CAL</th>
                <th class="col-final">FAL</th>
            </tr>
        </thead>

        <!-- CUERPO DE LA TABLA -->
        <tbody>
            @php
                $contador = 1;
            @endphp
            @foreach ($alumnos_curso as $alumno)
                <tr>
                    <td style="font-size: 9px; text-align:center;">{{ $contador }}</td>
                    <td style="font-size: 9px; text-align:left; ">{{ $alumno->noexpediente }}</td>
                    <td style="font-size: 10px; text-align:left; ">{{ $alumno->apellidos }} {{ $alumno->nombre }}</td>
                    @for ($i = 0; $i < 23; $i++)
                        <td></td>
                    @endfor

                    <td></td>
                    <td></td>
                </tr>
                @php
                    $contador++;
                @endphp
            @endforeach
        </tbody>
    </table>

</body>

</html>
