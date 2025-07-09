{{-- ANA MOLINA 03/10/2024 --}}

@extends('layouts.reportegral-layout') <!-- Session Status -->

@section('title')
    Materias reprobadas
@endsection
@section('reporte')
    DOCENTES CON ALUMNOS REPROBADOS EN {{$titulo}}
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
        th.calif, th.inasis {
        width: 5%
        }
        th.noexp
        {
            width: 7%
        }
        th.grupo
        {
            width: 10%
        }
        th.turno
        {
            width: 10%
        }
    </style>
    <table style="width:100%">
       <tr>
       <td style=" font-size:12px; ">CICLO ESCOLAR: {{$cicloesc}}</td>
       <td style=" font-size:12px; ">PLANTEL: {{$plantelnom}}</td>
       </tr>
    </table>
    <table cellspacing="0" cellpadding="0" style="width:100%; margin: 2mm 0mm 0mm 0mm; font-size:10px; ">
       <thead>
            <tr>
           <th colspan="4" style="border: 1pt solid gray; background-color: gray;   text-align: left;" >DOCENTE</th>
           <th  class="calif"  rowspan="3" style="border: 1pt solid gray; background-color: gray;">CALIF</th>
           <th  class="inasis"  rowspan="3" style="border: 1pt solid gray; background-color: gray;">INAS</th>
       </tr>
       <tr>
           <th colspan="4" style="border: 1pt solid gray; background-color: gray;   text-align: left;">MATERIA</th>

       </tr>
       <tr>
           <th  style="border: 1pt solid gray; background-color: gray;  text-align: left;">GRUPO</th>
           <th colspan="3" style="border: 1pt solid gray; background-color: gray;   text-align: left;">ALUMNO</th>

       </tr>
   </thead>
   </table>
@endsection

@section('content')
    <section>
           <table style="width:100%; border-spacing:0">
            <thead>
                <tr>
                    <th class="grupo"></th>
                    <th class="turno"></th>
                    <th class="noexp"></th>
                    <th></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                </tr>
            </thead><tbody>
               @php
               $exp='';
               $clave='';
               foreach ($datos as $det)
               {
                   if($exp!=$det->expediente)
                   {
                       $clave='';
                   @endphp
                   <tr>
                       <td colspan="6" style="border: 1pt solid gray; background-color: gray;">{{$det->expediente}} {{$det->docente}}</td>
                   </tr>
                   @php
                   }
                   if($clave!=$det->clave)
                   {
                   @endphp
                   <tr>
                       <td colspan="6" style="border: 1pt solid gray; background-color: lightgray;">{{$det->clave}} {{$det->materia}}</td>
                   </tr>
                   @php
                   }

                   @endphp
                   <tr>
                       <td>{{$det->grupo}} </td>
                       <td>{{$det->turno}}</td>
                       <td>{{$det->noexpediente}}</td>
                       <td>{{$det->alumno}}</td>
                       <td style=" text-align: center;">{{number_format($det->califica)}}</td>
                       <td style=" text-align: center;">{{$det->faltas}}</td>
                   </tr>
                   @php

                   $exp=$det->expediente;
                   $clave=$det->clave;
               }
               @endphp
                </tbody>
           </table>

   </section>
   @endsection
