{{-- ANA MOLINA 31/05/2023 --}}

@extends('layouts.reporte-layout') <!-- Session Status -->

@section('title')
    Certificados
@endsection
@section('reporte')
    Listado de alumnos
@endsection
@section('encabezado')
@endsection

@section('encabezadodet')
    <style>
        tr.border-top-bottom th {
            border-top: 1pt solid black;
            border-bottom: 1pt solid black;
        }

        tr.border-bottom-dashed td {
            border-bottom: 1pt dashed gray;

        }

        tr.border-bottom-solid td {
            border-bottom: 1pt solid black;

        }

        .font-large {
            font-family: Arial, sans-serif;
            font-size: 12px;
            /* Cambia este valor al tamaño de letra que desees */
        }
    </style>
    <table style="width:100%">
        <tr>
            <td>
                <strong>PLANTEL:</strong>{{ $plantel }}
            </td>
            <td>
                <strong>GRUPO:</strong>{{ $grupo_datos->nombre }}--{{ $grupo_datos->turno->nombre }}
            </td>
            <td>
                <strong>FECHA:</strong>
                @php
                    // date_default_timezone_set("America/Mexico_City");
                    date_default_timezone_set('America/Hermosillo');
                    setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');
                    //$fec=strftime("%d DE %B DE %Y")
                    $fecha_actual = strtotime(date('Y-m-d H:i:s'));
                    $fec = strtoupper(strftime('%d DE %B DE %Y', $fecha_actual));
                    $alumnos = 0;

                    function identificarDuplicados($listado)
                    {
                        $contadores = [];
                        foreach ($listado as $info) {
                            if (!isset($contadores[$info->noexpediente])) {
                                $contadores[$info->noexpediente] = 0;
                            }
                            $contadores[$info->noexpediente]++;
                        }
                        return array_filter($contadores, function ($count) {
                            return $count > 1;
                        });
                    }

                    // Obtener los duplicados
                    $duplicados = identificarDuplicados($listado);
                @endphp
                {{ $fec }}
            </td>
        </tr>

    </table>
@endsection
@section('content')
    <section>
        {{-- <table  class="table  table-condensed  table-striped text-center" > --}}
        <table style="width:100%; border-spacing:0" class="font-large">
            <thead>
                <tr class="border-top-bottom">
                    <th>#</th>
                    <th width="10%">No. EXPEDIENTE</th>
                    <th width="30%">ALUMNO</th>
                    <th width="5%">FOTO</th>
                    <th width="35%">
                        <table width="100%" border="1">
                            <tr>
                                <td colspan="7" style="text-align: center">TOTAL ASIGNATURAS APROBADAS</td>

                            </tr>
                            <tr>
                                <td>SEM 1</td>
                                <td>SEM 2</td>
                                <td>SEM 3</td>
                                <td>SEM 4</td>
                                <td>SEM 5</td>
                                <td>SEM 6</td>
                                <td>TOTAL</td>
                            </tr>
                            <tr>

                            </tr>
                        </table>
                    </th>
                    <th width="10%">Estatus</th>
                    @if (empty($info->plantel))
                    @else
                        <th width="10%">Plantel</th>
                    @endif


                </tr>
            </thead>

            @if (!empty($listado))
                <tbody>

                    @foreach ($listado as $info)
                    <?php
                    // Determinar si la fila actual es un duplicado
                    $esDuplicado = isset($duplicados[$info->noexpediente]);
                    $alumnos +=1;
                    ?>
                    <tr style="{{ $esDuplicado ? 'background-color: yellow;' : '' }}">
                        <td style="text-align: center">{{ $alumnos }}-</td>
                        <td>{{ $info->noexpediente }}</td>
                        <td>{{ $info->apellidos }} {{ $info->nombre }}</td>
                        <td style="text-align: center; vertical-align:text-top;">
                            <?php $img = $info->foto; ?>
                            <div class="img-container">
                                <div class="imageOne image">
                                    @if ($img)
                                        Si
                                    @else
                                        No
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php
                            $certificado = DB::select('call pa_certificado(?)', [$info->id]);
                            $periodo1 = 0;
                            $periodo2 = 0;
                            $periodo3 = 0;
                            $periodo4 = 0;
                            $periodo5 = 0;
                            $periodo6 = 0;
                
                            foreach ($certificado as $auxiliar) {
                                if ($auxiliar->periodo1 == '1') {
                                    $periodo1 += 1;
                                }
                                if ($auxiliar->periodo2 == '2') {
                                    $periodo2 += 1;
                                }
                                if ($auxiliar->periodo1 == '3') {
                                    $periodo3 += 1;
                                }
                                if ($auxiliar->periodo2 == '4') {
                                    $periodo4 += 1;
                                }
                                if ($auxiliar->periodo1 == '5') {
                                    $periodo5 += 1;
                                }
                                if ($auxiliar->periodo2 == '6') {
                                    $periodo6 += 1;
                                }
                            }
                            ?>
                            <table width="100%" style="text-align: center">
                                <tr>
                                    <td>{{ $periodo1 }}</td>
                                    <td>{{ $periodo2 }}</td>
                                    <td>{{ $periodo3 }}</td>
                                    <td>{{ $periodo4 }}</td>
                                    <td>{{ $periodo5 }}</td>
                                    <td>{{ $periodo6 }}</td>
                                    <td>{{ $info->asignaturas }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="text-align: center">
                            @foreach ($alumnos_estatus as $valida_status)
                                @if ($valida_status->id == $info->id)
                                    <?php
                                    $fase = '';
                                    $read = '';
                                    if ($valida_status->estatus_cert != null) {
                                        if ($valida_status->digital == null) {
                                            $fase = 'Generado';
                                        } else {
                                            $fase = 'Digital';
                                        }
                                        $read = 'readonly';
                                    }
                                    ?>
                                @endif
                            @endforeach
                            {{ $fase }}
                        </td>
                        @if ($plantel != $info->plantel)
                            <td>
                                {{ $info->plantel }}
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            @endif
        </table>

    </section>
@endsection
