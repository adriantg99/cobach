{{-- ANA MOLINA 10/11/2023 --}}
@php
    use  App\Models\Adminalumnos\ImagenesalumnoModel;
     //fotografía
    //tipo = 1 es la foto de identificación del alumno
    if (isset($this->alumno_id))
        $imagen_find = ImagenesalumnoModel::where('id',$this->alumno_id)->where('tipo',1)->get();
@endphp
@extends('layouts.reporte-layout')    <!-- Session Status -->

@section('title')
Boleta
@endsection
@section('reporte')
<table style="width:100%">
    <tr>
        <td style="text-align: center;">CCT {{$datos->cct}}</td>
    </tr>
    <tr>
        <td style="text-align: center;">Plantel {{$datos->plantel}}</td>
    </tr>
    <tr>
        <td style="text-align: center;">BOLETA DEL ESTUDIANTE</td>
    </tr>
</table>
@endsection
@section('encabezado')

@endsection

@section('encabezadodet')

<style>

    tr.borders  td{
        border: 1pt solid ;
        text-align: center;

    }
    table {
        border-collapse: collapse;
        }

        table tbody.alterna tr:nth-child(even) {
            background: lightgrey;
        }
        table tbody.alterna tr:nth-child(odd) {
            background: white;
        }

</style>
<table  style="width:100%">

    <tr>
    <td >
        <strong>Estudiante:</strong> {{$alumno}}
    </td>
    <td >
        <strong>Mátricula:</strong> {{$datos->noexpediente}}

    </td>
</TR>
<TR>
    <TD>
        <strong>Plan de estudios:</strong> {{$datos->plan}}
    </TD>
    <td >
        <strong>CURP:</strong> {{$datos->curp}}
    </td>
    </tr>
    <TR>
        <TD>
            <strong>Ciclo:</strong> {{$datos->ciclo}}
        </TD>
        <td >
            <strong>Semestre:</strong> {{$datos->periodo}} <strong>Grupo:</strong> {{$datos->grupo}} <strong>Turno:</strong> {{$datos->turno}}
        </td>
        </tr>
        <TR>
            <TD colspan="2">
                <strong>Domicilio:</strong> {{$datos->domi}}
            </TD>

            </tr>
</table>
@endsection




@section('content')
<section  >
        {{-- <table  class="table  table-condensed  table-striped text-center" > --}}
        @php
            $calif1=0;
            $calif2=0;
            $calif3=0;
            $califi=0;
            $cant=count($calificaciones);
        @endphp
            <table style="width:100%; border-spacing:0"  >
        <thead >
            <tr class="borders">
                <td rowspan="2"> <strong>Clave</strong></td>
                <td rowspan="2" style="text-align: left;"> <strong>Asignatura</strong></td>
                <td colspan="3"> <strong>Calificaciones</strong></td>
                <td colspan="3"> <strong>Faltas</strong></td>
                <td colspan="3"> <strong>Final</strong></td>

            </tr>
            <tr class="borders" >
                <td> <strong>P1</strong></td>
                <td> <strong>P2</strong></td>
                <td> <strong>P3</strong></td>
                <td> <strong>P1</strong></td>
                <td> <strong>P2</strong></td>
                <td> <strong>P3</strong></td>
                <td> <strong>ORD</strong></td>
                <td> <strong>REG</strong></td>
                <td> <strong>SEM</strong></td>
              </tr>
        </thead>
        @php $per =0 @endphp
        @if (!empty($calificaciones))
        <tbody  class="alterna">
            @foreach($calificaciones as $calif)
                @php
                    $calif1=$calif1+$calif->calificacion1;
                    $calif2=$calif2+$calif->calificacion2;
                    $calif3=$calif3+$calif->calificacion3;
                    $califi=$califi+$calif->calificacion;
                @endphp

                  <tr  class="borders">

                     <td>{{$calif->clave}} </td>
                    <td style="text-align: left;">{{$calif->materia}} </td>
                      <td>{{$calif->calificacion1}} </td>
                     <td>{{$calif->calificacion2}} </td>
                      <td>{{$calif->calificacion3}} </td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{$calif->calificacion}} </td>
                      <td></td>
                      <td></td>
                 </tr>
          @endforeach
          <tr  class="borders" >
                 <td></td>
                <td style="text-align: left;">Promedio</td>
                  <td>@php if ($cant>0) echo( round($calif1/$cant )) @endphp</td>
                 <td>@php if ($cant>0) echo( round($calif2/$cant )) @endphp</td>
                  <td>@php if ($cant>0) echo( round($calif3/$cant )) @endphp</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>@php if ($cant>0) echo( round($califi/$cant )) @endphp</td>
                  <td></td>
                  <td></td>
            </tr>
        </tbody>
        @endif
    </table>
    <br>
    <span><strong>Extracurriculares:</strong></span>
    <table style="width:100%; border-spacing:0"  >

        @php $per =0 @endphp
        @if (!empty($calificacionesex))
        <tbody  >
            @foreach($calificacionesex as $calif)
                 <tr   class="borders">


                <td>{{$calif->clave}} </td>
                <td style="text-align: left;">{{$calif->materia}} </td>
                <td>{{$calif->calificacion1}} </td>
                <td>{{$calif->calificacion2}} </td>
                <td>{{$calif->calificacion3}} </td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$calif->calificacion}} </td>
                <td></td>
                <td></td>
                </tr>
          @endforeach
        </tbody>
        @endif
    </table>
    <span>**Las extracurriculares no son consideradas para efectos del promedio</span>
</table>
</section>
@endsection

@section('pie')

<table style="width:100%; ">
    <tr>
        <td></td>
        <td style="text-align: center; font-size: 9px; border-top: solid ;"></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; font-size: 9px;">{{$datos->director}}</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; font-size: 9px;">Director del plantel</td>
    </tr>
@endsection
