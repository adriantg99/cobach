{{-- ANA MOLINA 17/11/2023 --}}
@extends('layouts.reporte-layoutmulti')    <!-- Session Status -->
@section('title')
Boleta
@endsection
@section('content')
<section  >
@php
    ini_set('max_execution_time', 300); // 5 minutes
    //set_time_limit(300); // 5 minutes
    $data=DB::select("SELECT cat_plantel.NOMBRE AS plantel,cct,director
    FROM esc_grupo_alumno
    LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.GRUPO_ID
    LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id
    WHERE  FIND_IN_SET( esc_grupo_alumno.grupo_id , ".$this->grupos_sel.") limit 1");
    if (count($data)>0)
    {
        $datos=$data[0];
        $plantel=$datos->plantel;
        $cct=$datos->cct;
        $director=$datos->director;
    }
    $calificaciones_grupo=DB::select('call pa_boleta_grupo(?)',array( $this->grupos_sel));
    use App\Models\Adminalumnos\AlumnoModel;
    use  App\Models\Adminalumnos\ImagenesalumnoModel;

    $alumno_id=0;
    $bloque=0;

    //inicializa datos alumno
    $alumno="";
    $noexpediente="";
    $curp="";
    $plan="";
    $periodo="";
    $grupo="";
    $turno="";
    $domi="";
@endphp
@foreach($calificaciones_grupo as $calificaciones)
<?php
        $cambiaalumno=false;
        $primerasi=false;

        if($alumno_id!=$calificaciones->alumno_id)
        {
             $alumno_id=$calificaciones->alumno_id;

            $cambiaalumno=true;
            $primerasi=true;
            $bloque=$calificaciones->asig;
           //fotografía
           //tipo = 1 es la foto de identificación del alumno
            $imagen_find = ImagenesalumnoModel::where('alumno_id',$alumno_id)->where('tipo',1)->get();


        }
        else {

            if ($bloque!=$calificaciones->asig)
            {
                $bloque=$calificaciones->asig;

            }

        }
        if ($cambiaalumno==true)
        {
            $alumno_find = AlumnoModel::find($alumno_id);
            $alumno="";
            $noexpediente="";
            $curp="";
            $plan="";
            $periodo="";
            $grupo="";
            $turno="";
            $domi="";
            $calif1=0;
            $calif2=0;
            $calif3=0;
            $califi=0;
            $cant=0;

            if (isset($alumno_find))
            {
                $alumno=$alumno_find->apellidos.' '.$alumno_find->nombre;
                $noexpediente=$alumno_find->noexpediente;
                $curp=$alumno_find->curp;
                $domi=$alumno_find->domicilio;

                $dataplan=DB::select("SELECT asi_planestudio.nombre AS plan
                FROM esc_grupo_alumno
                join esc_grupo on esc_Grupo.id=esc_grupo_alumno.grupo_id
                join esc_curso on esc_grupo_alumno.grupo_id=esc_curso.grupo_id
                JOIN asi_planestudio ON asi_planestudio.id=esc_curso.plan_estudio_id
                WHERE  esc_grupo_alumno.alumno_id=".$alumno_id." AND esc_grupo.ciclo_esc_id=".$calificaciones->ciclo_esc_id."  LIMIT 1");
                $plan=$dataplan[0]->plan;


                //busca datos del último periodo escolar
                $data=DB::select("SELECT esc_grupo.nombre AS grupo, case turno_id when 1 then 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno
                ,1 as periodo
                FROM esc_grupo_alumno
                LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.GRUPO_ID
                WHERE ALUMNO_ID=".$alumno_id." and esc_grupo_alumno.GRUPO_ID=".$calificaciones->grupo_id);
                if (count($data)>0)
                {
                $datos=$data[0];
                 $periodo=$datos->periodo;
                $grupo=$datos->grupo;
                $turno=$datos->turno;
                }


            }

        }
if ($cambiaalumno==true)
{
?>
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
<header>
<table style="width:100%">
    <TR>
    <td>
      <img  src="../public/images/logocobachchico.png"  width="100px"  alt="Logo" class="logo">
    </td>
    <td style="text-align: center;">
        <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong><br>
        <table style="width:100%">
            <tr>
                <td style="text-align: center;">CCT {{$cct}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Plantel {{$plantel}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">BOLETA DEL ESTUDIANTE</td>
            </tr>
        </table>
    </td>
    <td style="text-align: right;">

        <?php

        //dd($imagen_find);
            if (isset($imagen_find)) {
                if ($imagen_find->count()>0)
                {
                $row=$imagen_find[0];
                ?>
                <div class="img-container">
                    <div class="imageOne image">
                        <img src="data:image/png;base64,{{ chunk_split(base64_encode($row->imagen)) }}" height="70"  alt="Logo" class="logo">
                    </div>
                </div>
                <?php }
                else { ?>
                <img  src="../public/images/logocobachchico.png"  width="100px"  alt="Logo" class="logo">
                <?php
                }
              }
            else { ?>
            <img  src="../public/images/logocobachchico.png"  width="100px"  alt="Logo" class="logo">
            <?php
            }
?>
      </td>
    </TR>
</table>
<table style="width:100%">
    <tr>

        <td >
            <strong>Estudiante:</strong> {{$alumno}}
        </td>
        <td >
            <strong>Mátricula:</strong> {{$noexpediente}}

        </td>
    </TR>
    <TR>
        <TD>
            <strong>Plan de estudios:</strong> {{$plan}}
        </TD>
        <td >
            <strong>CURP:</strong> {{$curp}}
        </td>
        </tr>
    <TR>
        <TD>
            <strong>Ciclo:</strong> {{$calificaciones->ciclo}}
        </TD>
        <td >
            <strong>Semestre:</strong> {{$periodo}} <strong>Grupo:</strong> {{$grupo}} <strong>Turno:</strong> {{$turno}}
        </td>
        </tr>
        <TR>
            <TD colspan="2">
                <strong>Domicilio:</strong> {{$domi}}
            </TD>

            </tr>

{{--  <tr>
    <td  >
        <img  src="../../../../public/logocobachchico.png"  width="100" height="60" alt="Logo" class="logo">
    </td>
    <td>
        <p>    </p>
    </td>
    <td>
        <h4><strong>Colegio de Bachilleres del Estado de Sonora</strong><br>
        <strong>@yield('reporte')</strong>@yield('encabezado')</h4>
        <p style="font-size: 10px;">@yield('encabezado')</p>
    </td>
</tr> --}}
</table>

</header>

<?php
}
?>


        {{-- <table  class="table  table-condensed  table-striped text-center" > --}}

    @if ($bloque==1)
    <span><strong>Extracurriculares:</strong></span>
    @else
    @php
        $calif1=$calif1+$calificaciones->calificacion1;
        $calif2=$calif2+$calificaciones->calificacion2;
        $calif3=$calif3+$calificaciones->calificacion3;
        $califi=$califi+$calificaciones->calificacion;
        $cant=$cant+1;
        @endphp
    @endif

    @if($primerasi==true)

        <table style="width:100%; border-spacing:0"   >
        <thead>
            <tr class="borders">
                <td rowspan="2">Clave</td>
                <td rowspan="2" style="text-align: left;">Asignatura</td>
                <td colspan="3">Calificaciones</td>
                <td colspan="3">Faltas</td>
                <td colspan="3">Final</td>

            </tr>
            <tr class="borders" >
                <td>P1</td>
                <td>P2</td>
                <td>P3</td>
                <td>P1</td>
                <td>P2</td>
                <td>P3</td>
                <td>ORD</td>
                <td>REG</td>
                <td>SEM</td>
              </tr>
        </thead>

        <tbody  class="alterna" >

    @endif
                <tr  class="borders" >
                      <td>{{$calificaciones->clave}} </td>
                          <td style="text-align: left;">{{$calificaciones->materia}} </td>
                      <td>{{$calificaciones->calificacion1}} </td>
                     <td>{{$calificaciones->calificacion2}} </td>
                      <td>{{$calificaciones->calificacion3}} </td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{$calificaciones->calificacion}} </td>
                      <td></td>
                      <td></td>


            </tr>
    @if ($calificaciones->ordenaper==1 )
    @if ($bloque==0)
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
         @endif
        </tbody>
    </table>
    @endif

<?php
    if ($calificaciones->ordenaper==1 )
    {
    ?>

@section('pie')

<table style="width:100%; ">
    <tr>
        <td></td>
        <td style="text-align: center; font-size: 9px; border-top: solid ;"></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; font-size: 9px;">{{$director}}</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; font-size: 9px;">Director del plantel</td>
    </tr>
@endsection

<div style="page-break-after:always;"></div>
<?php
    }
?>
@endforeach
</section>
@endsection

@section('js_post')
<script>
    //   let timerInterval
    //   Swal.fire({
    //     title: 'Generando reporte...',
    //     html: 'Por favor espere.',
    //     // timer: 10000,
    //     // timerProgressBar: true,
    //     didOpen: () => {
    //       Swal.showLoading()
    //     //   const b = Swal.getHtmlContainer().querySelector('b')
    //     //   timerInterval = setInterval(() => {
    //     //     b.textContent = Swal.getTimerLeft()
    //     //   }, 100)
    //     }
    //     // ,
    //     // willClose: () => {
    //     //   clearInterval(timerInterval)
    //     // }
    //   }).then((result) => {
    //     /* Read more about handling dismissals below */
    //     if (result.dismiss === Swal.DismissReason.timer) {
    //       console.log('I was closed by the timer')
    //     }
    //   })
</script>


@endsection
