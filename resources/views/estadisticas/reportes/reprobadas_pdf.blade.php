<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de asignaturas {{ $nombre_pdf }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .content {
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .footer {
            margin-bottom: 1%;
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de asignaturas {{ $nombre_pdf }}</h1>
        <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    <div class="content">
        @php
            use App\Models\Catalogos\AsignaturaModel;
            $contador = 0;
        @endphp

        <table>
            <thead>
                <tr>
                    <th style="width: 2%;">#</th>
                    <th style="width: 8%;">EXP</th>
                    <th style="width: 30%;">Nombre</th>
                    <th style="width: 41%;">Asignatura</th>
                    <th style="width: 19%;">Última calificación obtenida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $alumno)

                    @php
                    $contador +=1;
                        $calificaciones = \DB::select('CALL pa_kardex(?)', [$alumno->id]);
                        if ($calificaciones == null) {
                            continue;
                        }
                        $reprobadas = []; // Array para almacenar asignaturas reprobadas 
                    @endphp
                    @foreach ($calificaciones as $cal)
                        @php
                            $asignatura = AsignaturaModel::find($cal->asignatura_id);
                            if ($asignatura && $asignatura->kardex) {
                                $ultima_calificacion = null;
                                $reprobado = false;

                                // Verificar cada calificación en orden
                                $calificaciones_list = [
                                    ['calif' => $cal->calif, 'calificacion' => $cal->calificacion],
                                    ['calif' => $cal->calif3, 'calificacion' => $cal->calificacion3],
                                    ['calif' => $cal->calif2, 'calificacion' => $cal->calificacion2],
                                    ['calif' => $cal->calif1, 'calificacion' => $cal->calificacion1],
                                ];

                                foreach ($calificaciones_list as $item) {
                                    if (!is_null($item['calificacion'])) {
                                        $ultima_calificacion = $item['calificacion'];
                                        if ($item['calificacion'] < 60 || $item['calif'] == 'NA') {
                                            $reprobado = true;
                                        }
                                    }
                                }

                                // Verificar si hay una calificación aprobatoria posterior
                                $aprobado = false;
                                foreach ($calificaciones_list as $item) {
                                    if (
                                        (!is_null($item['calificacion']) && $item['calificacion'] >= 60) ||
                                        in_array($item['calif'], ['AC', 'REV'])
                                    ) {
                                        $aprobado = true;
                                        break;
                                    }
                                }

                                if ($reprobado && !$aprobado) {
                                    $reprobadas[] = [
                                        'asignatura' => $asignatura->nombre,
                                        'ultima_calificacion' => $ultima_calificacion,
                                    ];
                                }
                            }
                        @endphp
                    @endforeach

                    @if (!empty($reprobadas))
                        <tr>
                            <td>{{ $contador }}</td>
                            <td>{{ $alumno->noexpediente }}</td>
                            <td>{{ $alumno->nombre }} {{ $alumno->apellidos }}  @if ($nombre_grupo != 0)
                               -- {{ $alumno->grupo }}{{ $alumno->turno }}
                            @endif
                                @if ($nombre_plantel != 0)
                                    -- {{ $alumno->plantel }}
                                @endif
                        </td>
                            <td colspan="2">
                                <table>
                                    @foreach ($reprobadas as $asignatura)
                                        <tr>
                                            <td style="width: 70%;">{{ $asignatura['asignatura'] }}</td>
                                            <td style="width: 30%; text-align: center;">{{ $asignatura['ultima_calificacion'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer" style="position: fixed; bottom: 0; width: 100%;">
        <p>Reporte generado automáticamente por el sistema.</p>
    </div>
</body>

</html>
