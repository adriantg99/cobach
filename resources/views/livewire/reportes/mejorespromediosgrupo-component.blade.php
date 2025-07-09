{{-- ANA MOLINA 03/10/2024 --}}

@extends('layouts.reportegral-layout') <!-- Session Status -->

@section('title')
    Mejores promedios
@endsection
@section('reporte')
    MEJORES PROMEDIOS POR GRUPO/ALUMNO {{$titulo}}
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

        th.grupo  {
        width: 15%
        }
        th.ancho  {
        width: 10%
        }

        th.noexp
        {
            width: 6%
        }
    </style>
    <table style="width:100%">
       <tr>
       <td style=" font-size:12px; ">CICLO ESCOLAR: {{$cicloesc}}</td>
       <td style=" font-size:12px; ">PLANTEL: {{$plantelnom}}</td>
       </tr>
    </table>
    <table cellspacing="0" cellpadding="0" style="width:100%; margin: 2mm 0mm 0mm 0mm; font-size:12px; ">
       <thead>
            <tr>
           <th  class="noexp" style="border: 1pt solid gray; background-color: gray;   text-align: left;" >EXPEDIENTE</th>
           <th style="border: 1pt solid gray; background-color: gray;   text-align: left;" >ALUMNO</th>
           <th class="ancho" style="border: 1pt solid gray; background-color: gray;   text-align: center;" >PROMEDIO</th>
           <th  class="grupo"style="border: 1pt solid gray; background-color: gray;   text-align: center;" >GRUPO</th>
           <th  class="ancho" style="border: 1pt solid gray; background-color: gray;   text-align: center;" >MATERIAS APROBADAS</th>
           <th  class="ancho" style="border: 1pt solid gray; background-color: gray;   text-align: center;" >MATERIAS CURSADAS</th>

       </tr>

   </thead>
   </table>
@endsection
@section('content')
    <section>
           <table style="width:100%; border-spacing:0">
            <thead>
                <tr>
                    <th class="noexp"></th>
                    <th></th>
                    <th class="ancho"></th>
                    <th class="grupo"></th>
                    <th class="ancho"></th>
                    <th class="ancho"></th>

                </tr>
            </thead>
               <tbody>
               @php
                $grupo='';
               foreach ($datos as $det)
               {
                    if ($grupo!=$det->grupo.' '.$det->turno)
                    {
                     @endphp
                   <tr>
                       <td colspan="6" style="border: 1pt solid gray; background-color: gray;">GRUPO: {{$det->grupo}} {{$det->turno}}</td>
                   </tr>
                   @php
                    }


                   @endphp
                   <tr>
                       <td>{{$det->noexpediente}}</td>
                       <td>{{$det->alumno}}</td>

                       <td style=" text-align: center;">{{number_format($det->promedio)}}</td>
                       <td>{{$det->grupo}} {{$det->turno}}</td>
                       <td  style=" text-align: center;">{{$det->aprobadas}}</td>
                       <td style=" text-align: center;">{{$det->cursadas}}</td>
                   @php
                   $grupo=$det->grupo.' '.$det->turno;

               }
               @endphp
                </tbody>
           </table>

   </section>
   @endsection
