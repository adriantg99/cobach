@extends('layouts.dashboard-layout-alumno') <!-- Session Status -->
@section('content')

    <style>

    </style>

<section class="py-3" style="margin-top: 5%">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('ingreso_alumno.index') }}">Alumno:
                </a></li>

        </ol>
    </nav>
</section>

    @if (!empty($calificaciones))
        <div class="table-responsive">
            <div style="text-align: center">
                <h3>Ciclo escolar: {{ $ciclo_esc->nombre }}</h3>
            </div>
            
            <table class="table align-middle">

            </table>
            <table class="table align-middle">
                <thead>
                    <tr>
                        <td style="width: 30%; text-align: center">
                            <strong> MATERIAS</strong>
                        </td>
                        <td colspan="4" style="text-align: center;">
                            <strong>EVALUACIONES</strong>
                        </td>

                    </tr>
                    <tr>
                        <th style="width: 40%;"></th>
                        <th style="width: 15%;">
                            <table style="width: 100%">
                                <tr>
                                    <td style="text-align: center;" colspan="2">Parcial 1</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">Cal.</td>
                                    <td style="text-align: center;">Fal.</td>
                                </tr>
                            </table>
                        </th>
                        <th style="width: 15%;">
                            <table style="width: 100%">
                                <tr>
                                    <td style="text-align: center;" colspan="2">Parcial 2</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">Cal.</td>
                                    <td style="text-align: center;">Fal.</td>
                                </tr>
                            </table>
                        </th>
                        <th style="width: 15%;">
                            <table style="width: 100%">
                                <tr>
                                    <td style="text-align: center;" colspan="2">Parcial 3</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">Cal.</td>
                                    <td style="text-align: center;">Fal.</td>
                                </tr>
                            </table>
                        </th>
                        <th style="width: 15%;">
                            <table style="width: 100%">
                                <tr>
                                    <td style="text-align: center;" colspan="2">Final</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; color: white;">.</td>
                                    <td style="text-align: center; color: white;">.</td>
                                </tr>
                            </table>
                        </th>
                        <th>
                            <table>
                                <tr>
                                    <td style="text-align: center;" colspan="2">Regularización</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; color: white;">.</td>
                                    <td style="text-align: center; color: white;">.</td>
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

                @endphp
                <tbody>

                    @foreach ($calificaciones as $calificacion)
                        @php

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
                            if (
                                (($calificacion->area_formacion > 4 && $calificacion->area_formacion < 11) || $calificacion->area_formacion > 13) || $calificacion->nombre_curso == 'PRACTICAS PREPROFESIONALES') {
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
                                            style="text-align: center; width:5%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                                            @if (
                                                $calificacion->calificacion_final != null &&
                                                    ($calificacion->calificacion_final != 'AC' && $calificacion->calificacion_final != 'NA'))
                                                {{ round($calificacion->calificacion_final) }}
                                            @else
                                                {{ $calificacion->calificacion_final }}
                                            @endif
                                        </td>



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
                                            $promedio_total += ceil(
                                                ($calificacion->parcial1 +
                                                    $calificacion->parcial2 +
                                                    $calificacion->parcial3) /
                                                    3,
                                            );
                                        @endphp
                                    </tr>
                                </table>
                            </td>
                            <td>
                                @if ($calificacion->calificacion_r != null)
                                    {{ round($calificacion->calificacion_r) }}
                                @endif

                            </td>

                        </tr>
                    @endforeach
                    <!-- Agregar más filas para otras materias -->
                </tbody>

            </table>
            <hr class="linea-divisora">
            <table style="width: 100%; font-family: 'ArialMT', 'Arial', sans-serif; font-size: 12px;">
                <tr>
                    <td style="width: 35%; text-align: left;">
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
                    <td style="width: 15%; text-align: left;">
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
        
        
                    <td style="width: 15%; text-align: left;">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 33%">
                                    @if ($no_ingreso != 1)
                                        {{ round($promedio_total / $contador) }}
                                    @endif
                                </td>
                                <td style=" color: white;">
                                    .
                                </td>
                                <td style=" color: white;">
                                    .
                                </td>
        
                            </tr>
                        </table>
        
        
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <table style="width: 100%">
                            <tr>
                                <td style=" color: white;">
                                    @if ($no_ingreso != 1)
                                        {{ round($promedio_total / $contador) }}
                                    @endif
                                </td>
                                <td style=" color: white;">
                                    .
                                </td>
                                <td style=" color: white;">
                                    .
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
                                       // echo $faltasP1;
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
                                        //echo $faltasP2;
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
                        <td style="text-align: left; width:35%;">
                            {{ $materias[0] }}
                        </td>
                        <td style="width: 15%;">
                            <table>
                                <tr>
                                    <td style="text-align: left; width: 5%;">{{ $materias[1] }}
                                    </td>
                                    <td style="text-align: left; width: 5%; ">{{ $materias[2] }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table>
                                <tr>
                                    <td style="text-align: left; width: 5%;">
                                        {{ $materias[3] }}
                                    </td>
                                    <td style="text-align: left; width: 5%;">
                                        {{ $materias[4] }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table>
                                <tr>
                                    <td style="text-align: left; width: 50%;">{{ $materias[5] }}</td>
                                    <td style="text-align: left; width: 50%;">{{ $materias[6] }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="text-align: left; width: 33%;">
                                        @if ($materias[0] == 'CURRICULUM AMPLIADO ')
                                            @if ($materias[7] >= 60)
                                                AC
                                            @else
                                                NA
                                            @endif
                                        @else
                                            {{ $materias[7] }}
                                        @endif
                                    </td>
                                    <td style="text-align: left; color: white;">
                                        <strong style="">.</strong>
                                    </td>
                                    <td style="text-align: left; color: white;">
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
    @endif



@endsection
