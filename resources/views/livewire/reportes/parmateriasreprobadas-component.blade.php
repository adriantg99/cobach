{{-- ANA MOLINA 03/10/2024 --}}

@extends('layouts.reportegral-layout') <!-- Session Status -->

@section('title')
    Materias reprobadas
@endsection
@section('reporte')
    {{$titulo}}
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

        th.calif {
        width: 6%
        }
        th.inasis {
        width: 6%
        }

        th.grupo {
        width: 15%
        }
        th.noexp
        {
            width: 7%
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
                <th colspan="3"style="border: 1pt solid gray; background-color: gray; text-align: left;">MATERIA</th>

           <th colspan="2"style="border: 1pt solid gray; background-color: gray; text-align: center;">PARCIAL 1</th>
           <th colspan="2"style="border: 1pt solid gray; background-color: gray;   text-align: center;">PARCIAL 2</th>
           <th colspan="2" style="border: 1pt solid gray; background-color: gray;   text-align: center;">PARCIAL 3</th>
       </tr>
       <tr>
        <th class="grupo"   style="border: 1pt solid gray; background-color: gray; text-align: left;" >GRUPO</th>
         <th colspan="2" style="border: 1pt solid gray; background-color: gray;  text-align: left;">ALUMNO</th>
        <th  class="calif"style="border: 1pt solid gray; background-color: gray;">CALIF</th>
        <th class="inasis" style="border: 1pt solid gray; background-color: gray;">INAS</th>
        <th class="calif" style="border: 1pt solid gray; background-color: gray;">CALIF</th>
        <th class="inasis"style="border: 1pt solid gray; background-color: gray;">INAS</th>
        <th class="calif" style="border: 1pt solid gray; background-color: gray;">CALIF</th>
        <th class="inasis" style="border: 1pt solid gray; background-color: gray;">INAS</th>
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
                    <th class="noexp"></th>
                    <th></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                </tr>
            </thead>
               <tbody>
               @php
               $clave='';
                 foreach ($datos as $det)
               {
                if($clave!=$det->clave)
                   {
                   @endphp
                   <tr>
                        <td colspan="9" style="border: 1pt solid gray; background-color: lightgray;">{{$det->clave}} {{$det->materia}}</td>
                   </tr>
                   @php
                   }

                   @endphp
                   <tr>
                       <td >{{$det->grupo}} {{$det->turno}}</td>
                       <td>{{$det->noexpediente}}</td>
                       <td >{{$det->alumno}}</td>
                       <td style=" text-align: center;">{{number_format($det->p1)}}</td>
                       <td  style=" text-align: center;">{{$det->f1}}</td>
                       <td  style=" text-align: center;">{{number_format($det->p2)}}</td>
                       <td style=" text-align: center;">{{$det->f2}}</td>
                       <td  style=" text-align: center;">{{number_format($det->p3)}}</td>
                       <td  style=" text-align: center;">{{$det->f3}}</td>
                   </tr>
                   @php
                   $clave=$det->clave;
               }
               @endphp
                </tbody>
           </table>
   </section>
   @endsection
