{{-- ANA MOLINA 03/10/2024 --}}

@extends('layouts.reportegral-layout') <!-- Session Status -->

@section('title')
    Materias reprobadas
@endsection
@section('reporte')
    MATERIAS REPROBADAS POR ALUMNO EN {{$titulo}}
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
        th.materia
        {
            width: 6%
        }
        th.exp
        {
            width: 4%
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
           <th colspan="2" style="border: 1pt solid gray; background-color: gray;   text-align: left;" >GRUPO</th>
           <th  class="calif" rowspan="3" style="border: 1pt solid gray; background-color: gray;">CALIF</th>
           <th class="inasis"  rowspan="3" style="border: 1pt solid gray; background-color: gray;">INAS</th>
       </tr>
       <tr>
         <th colspan="2" style="border: 1pt solid gray; background-color: gray;   text-align: left;" >ALUMNO</th>

    </tr>
       <tr>
           <th    style="border: 1pt solid gray; background-color: gray;  text-align: left;">MATERIA</th>
           <th   style="border: 1pt solid gray; background-color: gray;   text-align: left;">DOCENTE</th>

       </tr>
   </thead>
   </table>
@endsection
@section('content')
    <section>
           <table style="width:100%; border-spacing:0">
            <thead>
                <tr>
                    <th class="materia"></th>
                    <th></th>
                    <th class="exp"></th>
                    <th></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                </tr>
            </thead>
               <tbody>
               @php
               $noexp='';
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

                   if($noexp!=$det->noexpediente)
                   {
                   @endphp
                   <tr>
                       <td colspan="6" style="border: 1pt solid gray; background-color: lightgray;">{{$det->noexpediente}} {{$det->alumno}}</td>
                   </tr>
                   @php
                   }

                   @endphp
                   <tr>
                       <td>{{$det->clave}}</td>
                       <td>{{$det->materia}}</td>
                       <td>{{$det->expediente}}</td>
                       <td>{{$det->docente}}</td>
                       <td style=" text-align: center;">{{number_format($det->califica)}}</td>
                       <td style=" text-align: center;">{{$det->faltas}}</td>
                   </tr>
                   @php
                   $grupo=$det->grupo.' '.$det->turno;
                   $noexp=$det->noexpediente;
               }
               @endphp
                </tbody>
           </table>

   </section>
   @endsection
