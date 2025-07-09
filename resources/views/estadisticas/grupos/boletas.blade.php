<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletas</title>
    <style>
        body {
            font-family: 'ArialMT', 'Arial', sans-serif;
            font-size: 12px;
        }

        .align-bottom {
            vertical-align: bottom;
        }


        .nombreDir {
            margin-top: 100px;
            /* ajusta este margen para centrar el contenido */

            text-align: center;
            /* Alinea horizontalmente */

        }

        .fondo_der {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            /* Puedes ajustar el color de fondo según tus necesidades */
            font-family: 'ArialMT', 'Arial', sans-serif;
            font-size: 10px;
        }

        .logo {
            position: absolute;
            top: -30px;
            left: 0px;
            width: 125px;
            /* ajusta el ancho según el tamaño de tu logo */
        }

        .container {
            margin: 0 auto;
            width: 100%;
            /* Puedes ajustar este valor según tus necesidades */
            position: relative;
        }

        header {
            display: flex;
            align-items: center;
        }

        header img {
            width: 100px;
            /* Ajusta el tamaño del logo según sea necesario */
        }

        .boleta {
            width: 100%;
            margin: 50px auto;
        }

        table {
            width: 100%;
        }

        table th {
            height: 2px;
            border-bottom: 3px solid black;

        }

        table th,
        table td {
            text-align: center;
        }

        .linea-divisora {
            border: none;
            height: 2px;
            background-color: black;
            margin-top: 20px;
        }

        .student-photo {
            position: absolute;
            top: 0px;
            right: 0px;
            width: 125px;
            max-width: 125px;
            max-height: 125px;

            min-width: 125px;
            min-height: 125px;

            /* ajusta el ancho según el tamaño de la foto del estudiante */
        }

        .titulo {
            position: relative;
            top: -25;
            font-family: Arial, sans-serif;

            text-align: center;
            margin-bottom: 100px;
            font-size: 16px;
        }

        .datos_plantel {
            position: relative;
            top: -110px;
            text-align: center;

        }


        .alinea {
            position: absolute;
            top: 70px;
            width: 100%;
        }

        .alinea_izq {
            text-align: left;
        }

        .alinea_der {
            text-align: right;
        }
    </style>
</head>

<div class="">

    <strong>
        <div class="titulo">
            COLEGIO DE BACHILLERES DEL ESTADO DE SONORA
        </div>

    </strong>
    @php
        use App\Models\Adminalumnos\ImagenesalumnoModel;
        $imagen_alumnos_2 = ImagenesalumnoModel::where('alumno_id', $datos_alumno->id)
            ->where('tipo', '1')
            ->get();

    @endphp

    <img src="{{ $logo }}" alt="Logo" class="logo">


    @if (count($imagen_alumnos_2))
        <div class="imageOne image">
            <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_alumnos_2[0]->imagen)) }}"
                alt="Foto del Estudiante" class="student-photo">
        </div>
    @endif
    <div>
        <div class="datos_plantel">
            <strong> CICLO ESCOLAR: {{ $datos_grupo_plantel_alumno->Ciclo }} </strong><br>
            <strong>PLANTEL {{ $datos_grupo_plantel_alumno->plantel }}</strong>
        </div>
        <div class="alinea">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: left; width: 75%;">
                            <strong>ESTUDIANTE:</strong>
                            {{ $datos_alumno->apellidos }} {{ $datos_alumno->nombre }}
                        </td>
                    </tr>
                </table>
            </div>

            <table>
                <tr>
                    <td style="text-align: left">
                        <strong>CURP:</strong>{{ $datos_alumno->curp }}
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="text-align: left; width:30%">
                        <strong>CCT:</strong> {{ $datos_grupo_plantel_alumno->cct }}
                    </td>
                    <td style="text-align: left;">
                        <strong>Grupo</strong> {{ $datos_grupo_plantel_alumno->grupo }} @if ($datos_grupo_plantel_alumno->turno_id == '1')
                            Matutino
                        @else
                            Vespertino
                        @endif
                    </td>
                </tr>
            </table>
            <table style="width:100%">
                <tr>
                    <td style="text-align:left; width: 70%;">
                        <strong>DOMICILIO: </strong> {{ $datos_grupo_plantel_alumno->domicilio }}
                    </td>
                    <td style="text-align:right; width: 30%;  vertical-align: top;">
                        <strong>Matrícula:</strong>
                        {{ $datos_alumno->noexpediente }}
                    </td>
                </tr>
            </table>

        </div>

    </div>
    <br>
    <br>

    <hr>
    <table>
        <thead>
            <tr>

                <td style="width: 30%; text-align: left">
                    <strong> MATERIAS</strong>
                </td>
                <td colspan="4">
                    <strong>EVALUACIONES</strong>
                </td>

            </tr>
            <tr>
                <th style="width: 40%;"></th>
                <th style="width: 15%;">
                    <table>
                        <tr>
                            <td colspan="2">Parcial 1</td>
                        </tr>
                        <tr>
                            <td>Cal.</td>
                            <td>Fal.</td>
                        </tr>
                    </table>
                </th>
                <th style="width: 15%;">
                    <table>
                        <tr>
                            <td colspan="2">Parcial 2</td>
                        </tr>
                        <tr>
                            <td>Cal.</td>
                            <td>Fal.</td>
                        </tr>
                    </table>
                </th>
                <th style="width: 15%;">
                    <table>
                        <tr>
                            <td colspan="2">Parcial 3</td>
                        </tr>
                        <tr>
                            <td>Cal.</td>
                            <td>Fal.</td>
                        </tr>
                    </table>
                </th>
                <th style="width: 15%;">
                    <table>

                        <tr>
                            <td colspan="3" style="color: white;">.</td>
                        </tr>
                        <tr>
                            <td class="align-bottom">ORD</td>
                            <td>REG</td>
                            <td>SEM</td>
                        </tr>
                    </table>
                </th>
            </tr>
        </thead>
        @php
            $promedio1 = 0;
            $promedio2 = 0;
            $promedio3 = 0;
            $contador = 0;
            $extracurriculares = [];
            $promedio_total = 0;
            $curriculum;
            $faltasP1 = 0;
            $faltasP2 = 0;
            $faltasP3 = 0;
            $calificacion_semestre = 0;

        @endphp
        <tbody>
            @foreach ($calificaciones as $calificacion)
                @php
                    $no_ingreso = 1;

                    if ($calificacion->nombre_curso == 'CURRICULUM AMPLIADO') {
                        $extracurriculares[] = [
                            $calificacion->nombre_curso,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            $calificacion->calificacion_final,
                        ];
                        continue;
                    }
                    if ($calificacion->afecta_promedio != 1) {
                        $extracurriculares[] = [
                            $calificacion->nombre_curso,
                            $calificacion->parcial1,
                            $calificacion->faltas_parcial1,
                            $calificacion->parcial2,
                            $calificacion->faltas_parcial2,
                            $calificacion->parcial3,
                            $calificacion->faltas_parcial3,
                            $calificacion->calificacion_final,
                        ];
                        continue;
                    }
                    if ($calificacion->nombre_curso === strtoupper($calificacion->nombre_curso)) {
                        if ($calificacion->parcial1 != '') {
                            $promedio1 = $promedio1 + $calificacion->parcial1;
                            $contador += 1;
                            $promedio2 = $promedio2 + $calificacion->parcial2;
                            $promedio3 = $promedio3 + $calificacion->parcial3;
                        }
                    } else {
                        $extracurriculares[] = [
                            $calificacion->nombre_curso,
                            $calificacion->parcial1,
                            $calificacion->faltas_parcial1,
                            $calificacion->parcial2,
                            $calificacion->faltas_parcial2,
                            $calificacion->parcial3,
                            $calificacion->faltas_parcial3,
                            $calificacion->calificacion_final,
                        ];
                        continue;
                    }

                @endphp
                <tr>
                    <td style="text-align: left; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                        {{ $calificacion->nombre_curso }}</td>
                    <td>
                        <table>
                            <tr>
                                <td
                                    style="text-align: center; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px; ">
                                    {{ $calificacion->parcial1 }}
                                </td>
                                <td
                                    style="text-align: center; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    {{ $calificacion->faltas_parcial1 }}</td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table>
                            <tr>
                                <td
                                    style="text-align: center; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    {{ $calificacion->parcial2 }}</td>
                                <td
                                    style="text-align: center; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    {{ $calificacion->faltas_parcial2 }}</td>
                            </tr>
                        </table>

                    </td>
                    <td>
                        <table>
                            <tr>
                                <td
                                    style="text-align: center; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    {{ $calificacion->parcial3 }}
                                </td>
                                <td
                                    style="text-align: center; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    {{ $calificacion->faltas_parcial3 }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                            <tr>
                                <td
                                    style="text-align: right; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    @if (
                                        $calificacion->calificacion_final != null &&
                                            ($calificacion->calificacion_final != 'AC' && $calificacion->calificacion_final != 'NA'))
                                        {{ round($calificacion->calificacion_final) }}
                                    @else
                                        {{ $calificacion->calificacion_final }}
                                    @endif
                                </td>
                                <td
                                    style="text-align: right; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    @if ($calificacion->calificacion_r != null)
                                        {{ round($calificacion->calificacion_r) }}
                                    @endif

                                </td>

                                <td
                                    style="text-align: right; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                    @if (
                                        $calificacion->calificacion_final != null &&
                                            ($calificacion->calificacion_final != 'AC' && $calificacion->calificacion_final != 'NA'))
                                        @if ($calificacion->calificacion_r != null)
                                            {{ round($calificacion->calificacion_r) }}
                                            @php
                                                $calificacion_semestre += round($calificacion->calificacion_r);
                                            @endphp
                                        @else
                                            {{ round($calificacion->calificacion_final) }}
                                            @php
                                                $calificacion_semestre += round($calificacion->calificacion_final);
                                            @endphp
                                        @endif
                                    @else
                                        {{ $calificacion->calificacion_final }}
                                    @endif
                                    @php

                                        //echo ceil($calificacion->parcial1 + $calificacion->parcial2 + $calificacion->parcial3 / 3);
                                        if ($calificacion->parcial3 != null) {
                                            $no_ingreso = 0;
                                        } else {
                                            $no_ingreso = 1;
                                        }
                                        $faltasP1 += $calificacion->faltas_parcial1;
                                        $faltasP2 += $calificacion->faltas_parcial2;
                                        $faltasP3 += $calificacion->faltas_parcial3;
                                        $promedio_total += round(
                                            ($calificacion->parcial1 +
                                                $calificacion->parcial2 +
                                                $calificacion->parcial3) /
                                                3,
                                        );
                                    @endphp
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
            <!-- Agregar más filas para otras materias -->
        </tbody>

    </table>
    <hr class="linea-divisora">
    <table style="width: 100%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
        <tr>
            <td style="width: 40%; text-align: left;">
                <strong>PROMEDIOS</strong>
            </td>
            <td style="width: 15%; text-align: center;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            @php
                                if ($promedio1 != 0) {
                                    echo round($promedio1 / $contador);
                                }
                            @endphp
                        </td>
                        <td style="width: 50%; color: white;">
                            .
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 15%; text-align: center;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            @php
                                if ($promedio2 != 0) {
                                    echo round($promedio2 / $contador);
                                }
                            @endphp
                        </td>
                        <td style="width: 50%; color: white;">
                            .
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 15%; text-align: center;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            @php
                                if ($promedio3 != 0) {
                                    echo round($promedio3 / $contador);
                                }
                            @endphp
                        </td>
                        <td style="width: 50%; color: white;">
                            .
                        </td>
                    </tr>
                </table>

            </td>


            <td style="width: 15%; text-align: center;">
                <table style="width: 100%">
                    <tr>
                        <td
                            style="text-align: right; width:33%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">

                            @if ($contador != 0 && $promedio3 != 0)
                                {{ round($promedio_total / $contador) }}
                            @endif


                        </td>
                        <td style=" color: white;">
                            .
                        </td>
                        <td
                            style="text-align: right; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">

                            @if ($calificacion_semestre != 0 && $contador != 0)
                                {{ round($calificacion_semestre / $contador) }}
                            @endif

                        </td>

                    </tr>
                </table>


            </td>

        </tr>
        <tr>
            <td style="text-align: left">
                <strong>
                    INASISTENCIAS:
                </strong>
            </td>
            <td style="width: 15%; text-align: center;">
                <table style="width: 100%;">
                    <tr>
                        <td style=" color: white;">
                            .
                        </td>
                        <td>
                            @php
                            if($faltasP1 != 0){
                                echo $faltasP1;
                            }
                                
                            @endphp
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center;">
                <table>
                    <tr>
                        <td style=" color: white;">
                            .
                        </td>
                        <td>
                            @php
                            if($faltasP2 != 0){
                                echo $faltasP2;
                            }
                                

                            @endphp
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center;">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%; color:white">
                            .
                        </td>
                        <td style="width: 50%">
                            @php
                                if ($faltasP3 != 0) {
                                    echo $faltasP3;
                                }
                            @endphp
                        </td>
                    </tr>
                </table>
            </td>


            <td style=" text-align: left; color:white">
                .
            </td>

        </tr>
    </table>
    <hr class="linea-divisora">
    <strong>EXTRACURRICULARES **</strong>
    <hr class="linea-divisora">
    <table style="width: 100%;  font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">

        @foreach ($extracurriculares as $materias)
            <tr>
                <td style="text-align: left; width:40%;">
                    {{ $materias[0] }}
                </td>
                <td style="width: 15%;">
                    <table>
                        <tr>
                            <td style="text-align: right; width: 5%;">{{ $materias[1] }}
                            </td>
                            <td style="text-align: right; width: 5%; ">{{ $materias[2] }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 15%;">
                    <table>
                        <tr>
                            <td style="text-align: right; width: 5%;">
                                {{ $materias[3] }}
                            </td>
                            <td style="text-align: right; width: 5%;">
                                {{ $materias[4] }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 15%;">
                    <table>
                        <tr>
                            <td style="text-align: right; width: 50%;">{{ $materias[5] }}</td>
                            <td style="text-align: right; width: 50%;">{{ $materias[6] }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 15%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: right; width: 33%;">
                                @if ($materias[0] == 'CURRICULUM AMPLIADO ')
                                    @if ($materias[7] >= 60)
                                        AC
                                    @else
                                        NA
                                    @endif
                                @else
                                    @if (is_numeric($materias[7]))
                                        {{ round($materias[7]) }}
                                    @else
                                        {{ $materias[7] }}
                                    @endif
                                @endif
                            </td>
                            <td style="text-align: right; color: white;">
                                <strong style="">.</strong>
                            </td>
                            <td style="text-align: right; color: white;">
                                <strong style="">.</strong>
                            </td>
                            <td></td>


                        </tr>
                    </table>
                </td>

            </tr>
        @endforeach
    </table>

</div>
<div class="footer">

    <div class="nombreDir">

        <p>
            <strong>
                ______________________________________________
                <br>
                {{ $datos_grupo_plantel_alumno->director }}
            </strong>
        </p>
        <strong>Director del plantel</strong>



    </div>

    <div class="fondo_der">
        <table style="width: 100%">
            <tr>
                <td style="width: 50%; text-align:left;">
                    @php
                        echo strtoupper($datos_grupo_plantel_alumno->localidad . ', SONORA ' . $fechaTexto);
                    @endphp
                </td>
                <td style="text-align: right; width: 50%">
                    **Las extracurriculares no son consideradas para efectos del promedio.
                </td>
            </tr>
        </table>

    </div>
</div>
</body>

</html>
