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
       </tr>
    </table>
    <table cellspacing="0" cellpadding="0" style="width:100%; margin: 2mm 0mm 0mm 0mm; font-size:10px; ">
       <thead>
        <tr>
            <th   colspan="2" style="border: 1pt solid gray; background-color: gray; text-align: left;">GRUPO</th>
             <th colspan="2" rowspan="2" style="border: 1pt solid gray; background-color: gray; text-align: center;">PARCIAL 1</th>
          <th colspan="2" rowspan="2" style="border: 1pt solid gray; background-color: gray;   text-align: center;">PARCIAL 2</th>
          <th colspan="2" rowspan="2"  style="border: 1pt solid gray; background-color: gray;   text-align: center;">PARCIAL 3</th>
      </tr>
      <tr>
        <th  colspan="2" style="border: 1pt solid gray; background-color: gray; text-align: left;">ALUMNO</th>
     </tr>


       <tr>
        <th class="materia"  style="border: 1pt solid gray; background-color: gray; text-align: left;">CLAVE</th>
        <th  style="border: 1pt solid gray; background-color: gray; text-align: left;">MATERIA</th>
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
                    <th class="materia"></th>
                    <th ></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                    <th class="calif"></th>
                    <th class="inasis"></th>
                </tr>
            </thead>
               <tbody>
               <?php
                $noexp='';
               $grupo='';
                 foreach ($datos as $det)
               {
                    if ($grupo!=$det->grupo.' '.$det->turno)
                        {
                        ?>
                    <tr>
                        <td colspan="8" style="border: 1pt solid gray; background-color: gray;">GRUPO: {{$det->grupo}} {{$det->turno}}</td>
                    </tr>
                    <?php
                        }

                    if($noexp!=$det->noexpediente)
                    {
                    ?>
                    <tr>
                        <td colspan="8" style="border: 1pt solid gray; background-color: lightgray;">{{$det->noexpediente}} {{$det->alumno}}</td>
                    </tr>
                    <?php
                    }

                    ?>
                   <tr>
                       <td >{{$det->clave}}</td>
                       <td >{{$det->materia}}</td>
                       <td  style=" text-align: center;">{{number_format($det->p1)}}</td>
                       <td  style=" text-align: center;">{{$det->f1}}</td>
                       <td  style=" text-align: center;">{{number_format($det->p2)}}</td>
                       <td  style=" text-align: center;">{{$det->f2}}</td>
                       <td  style=" text-align: center;">{{number_format($det->p3)}}</td>
                       <td  style=" text-align: center;">{{$det->f3}}</td>
                   </tr>
                   <?php
                   $grupo=$det->grupo.' '.$det->turno;
                   $noexp=$det->noexpediente;
               }
               ?>
                </tbody>
           </table>
   </section>
   @endsection
