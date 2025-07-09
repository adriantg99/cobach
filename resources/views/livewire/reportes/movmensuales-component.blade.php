{{-- ANA MOLINA 03/10/2024 --}}
@extends('layouts.reportegral-layout')
<!-- Session Status -->
@section('title')
Movimientos mensuales
@endsection
@section('reporte')
Movimientos mensuales
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

    tr.border-top-solid td {
        border-top: 1pt solid black;
    }

    tr.border-top-dashed td {
        border-top: 1pt dashed gray;
    }

    th.materia {
        width: 6%
    }

    th.calif {
        width: 6%
    }

    th.inasis {
        width: 6%
    }
</style>
<table style="width:100%">
    <tr>
        <td style=" font-size:12px; ">CICLO ESCOLAR: {{$cicloesc}}</td>
        <td style=" font-size:12px; ">PLANTEL: {{$plantelnom}}</td>
        <td> {{$fecha}}</td>
    </tr>
</table>
@endsection
@section('content')
<section>
    <table style="width:100%; border-spacing:0">
        <thead>
            <tr>
                <th ROWSPAN="3" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">SEM
                </th>
                <th ROWSPAN="3" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TURNO
                </th>
                <th ROWSPAN="3" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">SEXO
                </th>
                <th COLSPAN="20" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">SEMESTRE</th>

                <th rowspan="3" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">APROB
                </th>
                <th rowspan="3" style="border: 0.5pt solid gray; background-color: lightgray;   text-align: center;">REPROB</th>
            </tr>
            <tr>
                <th COLSPAN="4" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">SEPTIEMBRE</th>
                <th COLSPAN="4" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">OCTUBRE</th>
                <th COLSPAN="4" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">NOVIEMBRE</th>
                <th COLSPAN="4" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">DICIEMBRE</th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">INS.
                </th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">EXIS
                </th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL</th>
            </tr>
            <tr>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">INICIO</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">+ ALTA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">- BAJA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">EXIS</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">INICIO</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">+ ALTA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">- BAJA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">EXIS</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">INICIO</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">+ ALTA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">- BAJA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">EXIS</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">INICIO</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">+ ALTA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">- BAJA</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">EXIS</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">ALTAS</th>
                <th style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">BAJAS</th>
            </tr>


        </thead>
        <tbody>
            @php
            $periodo='';
            $turno='';
            $sexo='';
            $cambio=false;
            $cambio_per=false;
            $intper=0;
            $cambio_tur=false;
            $inttur=0;
            $coltrn=1;
            $colsem=1;
            foreach ($datos as $det)
            {

            if ($periodo!=$det->periodosel)
            {
            $intper=$datos->where('periodosel',$det->periodosel)->count();
            $cambio_per=true;

            }
            if($turno!=$det->turno)
            {
            $inttur=$datos->where('periodosel',$det->periodosel)->where('turno',$det->turno)->count();
            $cambio_tur=true;

            }
            if($sexo!=$det->sexo)
            {
            $cambio=true;
            }

            $exis1=$det->ini1+$det->alta1-$det->baja1;
            $exis2=$exis1+$det->alta2-$det->baja2;
            $exis3=$exis2+$det->alta3-$det->baja3;
            $exis4=$exis3+$det->alta4-$det->baja4;
            @endphp
            <tr>
                @php
                if ($cambio_per)
                {
                    $colsem="1";
                @endphp
                <td rowspan={{$intper}} colspan={{$colsem}}
                    style="border: 0.5pt solid gray; background-color:lightgray; text-align: center;">
                    {{$det->periodosel}} </td>
                @php
                }
                if ($cambio_tur  )
                {
                    if($det->ordtrn!=0)
                        $coltrn="2";
                    else $coltrn="1";
                @endphp
                <td rowspan={{$inttur}} colspan={{$coltrn}} style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">{{$det->turno}}
                </td>
                @php
                }
                if ($det->ordtrn==0 )
                {
                @endphp
                <td style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">{{$det->sexo}}
                </td>
                @php
                }
                if ($det->orden!=0)
                {
                @endphp
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->ini1}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->alta1}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->baja1}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray; border-right: 0.5pt solid gray;">{{$exis1}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$exis1}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->alta2}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray;">{{$det->baja2}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray; border-right: 0.5pt solid gray;">{{$exis2}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray;">{{$exis2}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->alta3}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->baja3}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray; border-right: 0.5pt solid gray;">{{$exis3}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$exis3}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->alta4}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->baja4}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray; border-right: 0.5pt solid gray;">{{$exis4}}</td>

                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->ini1+$det->alta1+$det->alta2+$det->alta3+$det->alta4}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$exis4}}</td>
                <td style="text-align: right; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->alta1+$det->alta2+$det->alta3+$det->alta4}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray; border-right: 0.5pt solid gray;">
                    {{$det->baja1+$det->baja2+$det->baja3+$det->baja4}}</td>
                    <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray; border-right: 0.5pt solid gray;">{{$det->aprobado}}</td>
                <td style="text-align: right; background-color: lightgray;  border-bottom: 0.5pt solid gray; border-right: 0.5pt solid gray;">{{$det->reprobado}}</td>
               @php
                }
                else {
                @endphp
                <td style="text-align: right;">{{$det->ini1}}</td>
                <td style="text-align: right;">{{$det->alta1}}</td>
                <td style="text-align: right;">{{$det->baja1}}</td>
                <td style="text-align: right; border-right: 0.5pt solid gray;">{{$exis1}}</td>
                <td style="text-align: right;">{{$exis1}}</td>
                <td style="text-align: right;">{{$det->alta2}}</td>
                <td style="text-align: right;">{{$det->baja2}}</td>
                <td style="text-align: right; border-right: 0.5pt solid gray;">{{$exis2}}</td>
                <td style="text-align: right;">{{$exis2}}</td>
                <td style="text-align: right;">{{$det->alta3}}</td>
                <td style="text-align: right;">{{$det->baja3}}</td>
                <td style="text-align: right; border-right: 0.5pt solid gray;">{{$exis3}}</td>
                <td style="text-align: right;">{{$exis3}}</td>
                <td style="text-align: right;">{{$det->alta4}}</td>
                <td style="text-align: right;">{{$det->baja4}}</td>
                <td style="text-align: right; border-right: 0.5pt solid gray;">{{$exis4}}</td>
                <td style="text-align: right;">{{$det->ini1+$det->alta1+$det->alta2+$det->alta3+$det->alta4}}</td>
                <td style="text-align: right;">{{$exis4}}</td>
                <td style="text-align: right;">{{$det->alta1+$det->alta2+$det->alta3+$det->alta4}}</td>
                <td style="text-align: right; border-right: 0.5pt solid gray;">
                    {{$det->baja1+$det->baja2+$det->baja3+$det->baja4}}</td>
                <td style="text-align: right; border-right: 0.5pt solid gray;">{{$det->aprobado}}</td>
                <td style="text-align: right; border-right: 0.5pt solid gray;">{{$det->reprobado}}</td>
                @php
                }
                @endphp

            </tr>
            @php
            $exis1=0;
            $ini2=0;
            $exis2=0;
            $ini3=0;
            $exis3=0;
            $ini4=0;
            $exis4=0;

            $periodo=$det->periodosel;
            $turno=$det->turno;
            $sexo=$det->sexo;
            $cambio=false;
            $cambio_per=false;
            $cambio_tur=false;

            }
            @endphp
        </tbody>
    </table>
</section>
@endsection
