{{-- ANA MOLINA 05/15/2024 --}}
@extends('layouts.reportesimple-layout')
<!-- Session Status -->
@section('title')
INGRESOS
@endsection
@section('reporte')
INGRESOS
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
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">PLANTEL</th>
                <th  style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">SEXO</th>
                <th   style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">I</th>
                <th   style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">II</th>
                <th   style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">III</th>
                <th   style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">OTROS</th>
                <th   style="border: 0.5pt solid gray; background-color: lightgray; text-align: center;">TOTAL</th>
            </tr>
            </thead>
        <tbody>
            @php
            $plantel='';
              $cambio_pla=false;
             foreach ($datos as $det)
            {

            if ($plantel!=$det->plantel)
            {
             $cambio_pla=true;

            }

            @endphp
            <tr>
                @php
                if ($cambio_pla)
                {

                @endphp
                <td style="border-top: 0.5pt solid gray;   text-align: center;">
                    {{$det->plantel}} </td>
                <td style="border-top: 0.5pt solid gray;  text-align: center;">{{$det->sexo}}</td>
                <td style="border-top: 0.5pt solid gray;  text-align: center; ">{{$det->s1}}</td>
                <td style="border-top: 0.5pt solid gray;  text-align: center; ">{{$det->s2}}</td>
                <td style="border-top: 0.5pt solid gray;  text-align: center; ">{{$det->s3}}</td>
                <td style="border-top: 0.5pt solid gray;  text-align: center; ">{{$det->s4}}</td>
                <td style="border-top: 0.5pt solid gray;  text-align: center; ">{{$det->tot}}</td>
                @php

                }
                else {
                @endphp
                <td style=" text-align: center;"></td>
                <td style=" text-align: center;">{{$det->sexo}}</td>
                <td style="text-align: center; ">{{$det->s1}}</td>
                <td style="text-align: center; ">{{$det->s2}}</td>
                <td style="text-align: center; ">{{$det->s3}}</td>
                <td style="text-align: center; ">{{$det->s4}}</td>
                <td style="text-align: center; ">{{$det->tot}}</td>
                @php
                }
                @endphp

            </tr>
            @php


            $plantel=$det->plantel;

              $cambio_pla=false;

            }
            @endphp
        </tbody>
    </table>
</section>
@endsection
