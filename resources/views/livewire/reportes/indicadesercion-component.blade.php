{{-- ANA MOLINA 12/10/2024 --}}
@extends('layouts.reportegral-layout')
<!-- Session Status -->
@section('title')
Indice de deserción
@endsection
@section('reporte')
Indice de deserción
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
        <td> {{$fecha}}</td>
    </tr>
</table>
@endsection
@section('content')
<section>
    <table style="width:100%; border-spacing:0">
        <thead>
            <tr>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">PLANTEL
                </th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">INSCRIPCION INICIAL
                </th>
                <th COLSPAN="4" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">ALTAS AGOSTO-JUNIO
                </th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">INSCRIPCION TOTAL
                </th>
                <th COLSPAN="4" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">BAJAS AGOSTO-JUNIO
                </th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">EXISTENCIA
                </th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">DESERCIÓN
                </th>
            </tr>
            <tr>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">ALTAS AGOSTO-DICIEMBRE</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">ALTAS INTER SEMESTRALES</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">ALTAS ENERO-JUNIO</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL ALTAS DEL CICLO</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">BAJAS AGOSTO-DICIEMBRE</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">BAJAS INTER SEMESTRALES</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">BAJAS ENERO-JUNIO</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL BAJAS DEL CICLO</th>

            </tr>
            </thead>
        <tbody>
            @php
            $plantel='';
            $turno='';
             $cambio_pla=false;
             $cambio_tur=false;
            foreach ($datos as $det)
            {

            if ($plantel!=$det->plantel)
            {
             $cambio_pla=true;

            }
            if($turno!=$det->turno)
            {
             $cambio_tur=true;

            }

            @endphp
            <tr>
                @php
                if ($cambio_pla)
                {

                @endphp
                <td
                    style="border: 0.5pt solid gray; background-color:gray; text-align: center;">
                    {{$det->plantel}} </td>
                @php
                }
                else
                if ($cambio_tur  )
                {

                @endphp
                <td   style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">{{$det->turno}}
                </td>
                @php
                }
                else
                {
                @endphp
                <td style=" text-align: center;">{{$det->sexo}}
                </td>
                @php
                }
                $div=$det->ini+$det->altanon+$det->intera+$det->altapar;
                    if ($div>0 && $det->bajanon+$det->interb+$det->bajapar>0)
                    {
                        $porc=($det->bajanon+$det->interb+$det->bajapar)/$div;
                        $porc = number_format($porc * 100, 2, ",", ".")." %";
                    }
                    else $porc='';
                if ($cambio_pla)
                {
                    @endphp
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->ini}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->altanon}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->intera}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->altapar}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->altanon+$det->intera+$det->altapar}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->ini+$det->altanon+$det->intera+$det->altapar}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->bajanon}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->interb}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->bajapar}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->bajanon+$det->interb+$det->bajapar}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->ini+$det->altanon+$det->intera+$det->altapar-($det->bajanon+$det->interb+$det->bajapar)}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$porc}}</td>

               @php
                }
                    else
                if ($cambio_tur)
                {
                @endphp
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->ini}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->altanon}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->intera}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->altapar}}</td>
                <td style="text-align: center; background-color: lightgray;  border-bottom: 0.5pt solid gray;">{{$det->altanon+$det->intera+$det->altapar}}</td>
                <td style="text-align: center; background-color: lightgray;  border-bottom: 0.5pt solid gray;">{{$det->ini+$det->altanon+$det->intera+$det->altapar}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->bajanon}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->interb}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->bajapar}}</td>
                <td style="text-align: center; background-color: lightgray;  border-bottom: 0.5pt solid gray;">{{$det->bajanon+$det->interb+$det->bajapar}}</td>
                <td style="text-align: center; background-color: lightgray;  border-bottom: 0.5pt solid gray;">{{$det->ini+$det->altanon+$det->intera+$det->altapar-($det->bajanon+$det->interb+$det->bajapar)}}</td>
                <td style="text-align: center; background-color: lightgray;  border-bottom: 0.5pt solid gray;">{{$porc}}</td>

               @php
                }
                else {
                @endphp
                <td style="text-align: center; ">{{$det->ini}}</td>
                <td style="text-align: center; ">{{$det->altanon}}</td>
                <td style="text-align: center; ">{{$det->intera}}</td>
                <td style="text-align: center; ">{{$det->altapar}}</td>
                <td style="text-align: center; ">{{$det->altanon+$det->intera+$det->altapar}}</td>
                <td style="text-align: center; ">{{$det->ini+$det->altanon+$det->intera+$det->altapar}}</td>
                <td style="text-align: center; ">{{$det->bajanon}}</td>
                <td style="text-align: center; ">{{$det->interb}}</td>
                <td style="text-align: center; ">{{$det->bajapar}}</td>
                <td style="text-align: center; ">{{$det->bajanon+$det->interb+$det->bajapar}}</td>
                <td style="text-align: center; ">{{$det->ini+$det->altanon+$det->intera+$det->altapar-($det->bajanon+$det->interb+$det->bajapar)}}</td>
                <td style="text-align: center; ">{{$porc}}</td>

                @php
                }
                @endphp

            </tr>
            @php


            $plantel=$det->plantel;
            $turno=$det->turno;
              $cambio_pla=false;
            $cambio_tur=false;

            }
            @endphp
        </tbody>
    </table>
</section>
@endsection
