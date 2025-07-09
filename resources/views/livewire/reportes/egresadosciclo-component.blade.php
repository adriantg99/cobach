{{-- ANA MOLINA 05/15/2024 --}}
@extends('layouts.reportesimple-layout')
<!-- Session Status -->
@section('title')
Egresados
@endsection
@section('reporte')
Egresados
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
                <th COLSPAN="6" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;"> ALUMNOS QUE ACREDITARON EL TOTAL DE ASIGNATURAS DE SU PLAN DE ESTUDIOS
                </th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL DE EGRESADOS NO INSCRITOS EN EL CICLO
                </th>
                <th ROWSPAN="2" style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;"> TOTAL DE EGRESADOS
                </th>
            </tr>
            <tr>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">CON EXP DIFERENTE AL DEL PLANTEL</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">CON EXP DIFERENTE AL DE LA GEN</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">CON EXP SUPERIOR AL NUM DE ALUM DE 1º INGRESO DE LA GEN.</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">CON EXP DEL PLANTEL EGRESADO EN OTROS PLANTELES</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL DE EGRESADO PARA EFICIENCIA TERMINAL</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL DE EGRESADO DEL CICLO AL 31 DE AGOSTO</th>
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

                if ($cambio_pla)
                {
                    @endphp
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->exp_dif_plantel}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->exp_dif_gen}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->exp_sup}}</td>
                <td style="text-align: center; background-color: gray; border-bottom: 0.5pt solid gray;">{{$det->exp_pla_otros}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->eficiencia}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->egreso}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->noins}}</td>
                <td style="text-align: center; background-color: gray;  border-bottom: 0.5pt solid gray;">{{$det->egreso+$det->noins}}</td>
               @php
                }
                    else
                if ($cambio_tur)
                {
                @endphp
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->exp_dif_plantel}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->exp_dif_gen}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->exp_sup}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->exp_pla_otros}}</td>
                  <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->eficiencia}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->egreso}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->noins}}</td>
                <td style="text-align: center; background-color: lightgray; border-bottom: 0.5pt solid gray;">{{$det->egreso+$det->noins}}</td>
               @php
                }
                else {
                @endphp
                <td style="text-align: center; ">{{$det->exp_dif_plantel}}</td>
                <td style="text-align: center; ">{{$det->exp_dif_gen}}</td>
                <td style="text-align: center; ">{{$det->exp_sup}}</td>
                <td style="text-align: center; ">{{$det->exp_pla_otros}}</td>
                <td style="text-align: center; ">{{$det->eficiencia}}</td>
                <td style="text-align: center; ">{{$det->egreso}}</td>
                <td style="text-align: center; ">{{$det->noins}}</td>
                <td style="text-align: center; ">{{$det->egreso+$det->noins}}</td>
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
