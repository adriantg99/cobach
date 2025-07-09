<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ACTA ESPECIAL</title>
    <style>
        .logo {
            position: absolute;
            top: -30px;
            left: 0px;
            width: 125px;
            /* ajusta el ancho según el tamaño de tu logo */
        }

        .titulo {
            position: relative;
            top: -25;
            font-family: Arial, sans-serif;

            text-align: center;
            margin-bottom: 100px;
            font-size: 16px;
        }

        .content {
            font-family: Arial, sans-serif;
            font-size: 14px;
            text-align: justify;
            line-height: 1.5;
            /* Puedes ajustar este valor según tus necesidades */


        }

        .alinea {
            position: absolute;
            top: 70px;
            width: 100%;

            font-family: Arial, sans-serif;
            font-size: 14px;
            text-align: justify;
            line-height: 1.5;
            /* Puedes ajustar este valor según tus necesidades */
        }
    </style>
</head>

<body>
    <strong>
        <div class="titulo">
            COLEGIO DE BACHILLERES DEL ESTADO DE SONORA <br />

            <p>ACTA ESPECIAL CALIFICACIÓN</p>
        </div>
    </strong>

    <img src="{{ $logo }}" alt="Logo" class="logo">
    @foreach ($actas as $acta)
        <div class="alinea">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: left; width: 50%;">
                            <strong>PLANTEL:</strong>
                            {{ $acta->plantel }}
                        </td>
                        <td style="text-align: right">
                            <strong>
                                CICLO ESC:
                            </strong>
                            {{ $acta->ciclo }}
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="content">
            <div class="info">
                <p style="margin-bottom: 5%;">
                    EN LA LOCALIDAD DE {{ mb_strtoupper($acta->localidad) }}, SONORA, EL DÍA
                    {{ mb_strtoupper($fechaTexto) }}, CONFORME
                    A LO PREVISTO EN LOS ART. 19 FRAC. XI, ART. 34,
                    35 Y 36 DEL REGLAMENTO DE SERVICIOS ESCOLARES Y DE CONVIVENCIA EN
                    ATENCION A LA SOLICITUD DE REVISIÓN DE RESULTADOS DE EVALUACION QUE
                    PRESENTO EL INTERESADO,
                </p>
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: left; width: 50%;">
                            {{ $acta->docente }}
                        </td>
                        <td style="text-align: right">
                            TITULAR DE LA ASIGNATURA
                        </td>
                    </tr>
                </table>

                <p>
                    SE PROCEDIO A LA REVISION DE LA

                    @switch($acta->calificacion_tipo)
                        @case('P1')
                            PRIMERA
                        @break

                        @case('P2')
                            SEGUNDA
                        @break

                        @case('P3')
                            TERCERA
                        @break

                        @default
                    @endswitch
                    EVALUACIÓN PARCIAL, DE LA ASIGNATURA
                </p>

                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: center;">
                            <strong>
                                {{ $acta->clave }}
                                {{ $acta->asignatura }}
                            </strong>
                        </td>
                    </tr>
                </table>

                {{ $acta->semestre }} SEMESTRE, QUE FUE AUTORIZADA AL ALUMNO(A):
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: center;">
                            <strong>
                                {{ $acta->noexpediente }}
                                {{ $acta->alumno }}
                            </strong>
                        </td>
                    </tr>
                </table>
                DEL GRUPO {{ $acta->grupo }} @switch($acta->turno_id)
                    @case(1)
                        MATUTINO,
                    @break

                    @case(2)
                        VESPERTINO,
                    @break

                    @default
                @endswitch
                RESULTANDO DE LA MISMA LA CALIFICACIÓN DE:
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: center;">
                            <strong>
                                {{ $acta->nueva_calif }}
                                (@php
                                    $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                                    $result = $formatter->format($acta->nueva_calif);
                                    echo strtoupper($result); // Esto mostrará "ciento veintitrés"
                                @endphp)
                            </strong>
                        </td>
                    </tr>
                </table>
                (MOTIVO:{{ mb_strtoupper($acta->motivo) }})
                <br />
                PARA CONSTANCIA SE FIRMA ESTA ACTA POR TODOS LOS INTERVINIENTES.
            </div>
        </div>

        <div class="footer" style="padding-top: 10%;">

            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; text-align:center;">
                        {{ $acta->created_at }}
                    </td>
                    <td style="text-align:center;">
                        {{ $acta->updated_at }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            __________________________________________
                        </strong>
                    </td>
                    <td>
                        <strong>
                            __________________________________________
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        Solicita
                    </td>
                    <td style="text-align: center">
                        Autoriza
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        {{ mb_strtoupper($acta->docente) }}
                    </td>
                    <td style="text-align: center">
                        {{ mb_strtoupper($acta->name) }}
                    </td>
                </tr>
            </table>
            <div>
                <p>

                </p>


            </div>
        </div>
    @endforeach
</body>

</html>
