 {{-- ANA MOLINA 16/10/2023 --}}
 @php
     use App\Models\Adminalumnos\ImagenesalumnoModel;
     //fotografía
     //tipo = 1 es la foto de identificación del alumno
     if (isset($alumno_id)) {
         $imagen_find = ImagenesalumnoModel::where('alumno_id', $alumno_id)
             ->where('tipo', 1)
             ->get();
     }
 @endphp
 @extends('layouts.reporte-layout') <!-- Session Status -->

 @section('title')
     Kardex
 @endsection
 @section('reporte')
     Expediente del estudiante
 @endsection
 @section('encabezado')
 @if ($vista_alumno)
 <style>
     body {
         position: relative;
         z-index: 0;
     }

     body::before {
         content: "REVISIÓN EXCLUSIVA PARA USO ESTUDIANTIL";
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%) rotate(-50deg);
         font-size: 3rem;
         /* Ajusta el tamaño del texto */
         color: rgba(0, 0, 0, 0.3);
         /* Transparencia */
         white-space: nowrap;
         z-index: -1;
         pointer-events: none;
     }
 </style>
@endif
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
     </style>


     <table style="width:100%">
         <tr>
             <td>
                 <strong>PLANTEL:</strong> {{ $datos->plantel }}
             </td>
             <td>
                 <strong>FECHA:</strong>
                 @php
                     // date_default_timezone_set("America/Mexico_City");
                     //date_default_timezone_set("America/Hermosillo");
                     //setlocale(LC_TIME,'es_MX.UTF-8');
                     //$fecha_actual = getdate();
                     //$fec=strftime("%d DE %B DE %Y")

                     //$fec=  $fecha_actual['mday'] .' DE ' .strtoupper($fecha_actual['month'] ).' DE '. $fecha_actual['year'];

                     $meses = [
                         'enero',
                         'febrero',
                         'marzo',
                         'abril',
                         'mayo',
                         'junio',
                         'julio',
                         'agosto',
                         'septiembre',
                         'octubre',
                         'noviembre',
                         'diciembre',
                     ];
                 @endphp
                 {{ date('j') }} de {{ $meses[date('n') - 1] }} de {{ date('Y') }}
             </td>

         </tr>
         <tr>
             <td>
                 <strong>ESTUDIANTE:</strong> {{ $alumno }}
             </td>
             <td>
                 <strong>GRUPO:</strong> {{ $datos->grupo }}
             </td>
         </TR>
         <TR>
             <TD>
             </TD>
             <td>
                 <strong>TURNO:</strong> {{ $datos->turno }}
             </td>
         </tr>
     </table>
 @endsection




 @section('content')

     <section>
         {{-- <table  class="table  table-condensed  table-striped text-center" > --}}
         <table style="width:100%; border-spacing:0">
             <thead>
                 <tr class="border-top-bottom">
                     <th>MATERIAS</th>
                     <th width="10%">MAT</th>
                     <th width="7%">CICLO</th>
                     <th width="7%">CALIF</th>
                     <th width="5%">TIPO</th>
                     <th width="7%">CICLO</th>
                     <th width="7%">CALIF</th>
                     <th width="5%">TIPO</th>
                     <th width="7%">CICLO</th>
                     <th width="7%">CALIF</th>
                     <th width="5%">TIPO</th>
                     {{-- <th>CICLO</th>
              <th>CALIF</th>
              <th>TIPO</th> --}}
                 </tr>
             </thead>
             @php $per =0 @endphp
             @if (!empty($calificacioneska))
                 <tbody>
                     @php
                         $semestre_anterior = 100;
                         $cont = 0;
                     @endphp
                     @foreach ($calificacioneska as $calif)
                         @php
                             $asignatura = App\Models\Catalogos\AsignaturaModel::find($calif->asignatura_id);
                         @endphp
                         @if ($asignatura->kardex)
                             @if ($calif->periodo > $semestre_anterior)
                                 {{--
                    <tr  class="border-bottom-solid">
                    --}}
                                 <tr class="border-top-solid">
                                 @else
                                     {{--
                    <tr  class="border-bottom-dashed">
                    --}}
                                     @if ($cont != 0)
                                 <tr class="border-top-dashed">
                             @endif
                         @endif
                        <td @if ((substr($calif->clave, 1, 2) === "15" || substr($asignatura->clave, 1, 2) === "16") && stripos($asignatura->nombre, "CURRICULUM AMPLIADO") === false && stripos($asignatura->nombre, "CURRICULUM") === false) style="padding-left: 2em;"
                                                 @else style="padding-left: 0;" @endif > {{ $calif->materia }}{{ $asignatura->afecta_promedio ? '' : '*' }} </td>
                         <td align="center">{{ $calif->clave }} </td>
                         <td align="center">{{ $calif->ciclo1 }} </td>
                         <td align="center">
                             @if ($calif->calificacion1 != null)
                                 {{ number_format($calif->calificacion1) }}
                             @else
                                 @if (is_numeric($calif->calificacion1))
                                     0
                                 @else
                                     {{ $calif->calif1 }}
                                 @endif
                             @endif
                         </td>
                         <td align="center">
                             {{ $calif->tipo1 != null ? $calif->tipo1 : '' }}
                         </td>
                         <td align="center">{{ $calif->ciclo2 }} </td>
                         <td align="center">
                             @if ($calif->calificacion2 != null)
                                 {{ number_format($calif->calificacion2) }}
                             @else
                                 @if (is_numeric($calif->calificacion2))
                                     0
                                 @else
                                     {{ $calif->calif2 }}
                                 @endif
                             @endif
                         </td>
                         <td align="center">
                             {{ $calif->tipo2 != null ? $calif->tipo2 : '' }}
                         </td>
                         <td align="center">{{ $calif->ciclo3 }} </td>
                         <td align="center">
                             @if ($calif->calificacion3 != null)
                                 {{ number_format($calif->calificacion3) }}
                             @else
                                 @if (is_numeric($calif->calificacion3))
                                     0
                                 @else
                                     {{ $calif->calif3 }}
                                 @endif
                             @endif
                         </td>
                         <td align="center">
                             {{ $calif->tipo3 != null ? $calif->tipo3 : '' }}
                         </td>
                         {{-- <td>{{$calif->ciclo}} </td>
              <td>
                @if ($calif->calificacion != 0)
                {{$calif->calificacion}}
                @else
                {{$calif->calif}}
                @endif
             </td>
              <td>  </td> --}}
                     @endif
                     </tr>
                     @php
                         $semestre_anterior = $calif->periodo;
                         $cont++;
                     @endphp
             @endforeach
             <tr class="border-top-solid">
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             </tbody>
             @endif
         </table>
         <table style="width:100%">
             <tr>
                 <td width="70%">
                     <strong>MATERIAS APROBADAS: </strong>{{ $aprobados }} <br>
                     <strong>MATERIAS REPROBADAS: </strong>{{ $reprobados }}<br>
                     <strong>PROMEDIO: </strong>{{ round($promedio) }}
                 </td>
                 {{-- 
                 <td><span style="font-size: 70%;">
                    TIPO 1: ORDINARIO<br>
                    TIPO 2: REGULARIZACIÓN<br>
                    TIPO 3: PASANTIA<br>
                    TIPO 4: ACTA ESPECIAL<br>
                    TIPO 5: EQUIVALENCIA<br>
                    TIPO 6: EN LINEA<br>
                    TIPO 7: REVALIDACIÓN<br>
                    TIPO 8: ACTA EXTEMPORANEA<br>
                    TIPO 9: RECURSAMIENTO<br>
                    </span>
                </td>
                 --}}
                 <td width="40%">
                     <table width="100%">
                         <tr>
                             <td><span style="font-size: 65%;">
                                     TIPO 1: ORDINARIO<br>
                                     TIPO 2: REGULARIZACIÓN<br>
                                     TIPO 3: PASANTIA<br>
                                     TIPO 4: ACTA ESPECIAL<br>
                                     TIPO 5: EQUIVALENCIA<br>
                                 </span>
                             </td>
                             <td>

                             <td> <span style="font-size: 65%;">

                                     TIPO 6: EN LINEA<br>
                                     TIPO 7: REVALIDACIÓN<br>
                                     TIPO 8: ACTA EXTEMPORANEA<br>
                                     TIPO 9: RECURSAMIENTO<br>
                                     TIPO 10: ESTRATEGIAS DE RECUPERACIÓN ACADEMICA<br>
                                 </span></td>

                 </td>

             </tr>
         </table>

         </td>
         </tr>
         </table>
         <hr>
         <p align="right"><strong>* Las extracurriculares no son consideradas para efectos del promedio.</strong></p>
         @if (!$vista_alumno)
         <br>
         <br>
         <br>
         <br>
         <br>
         <br>
             <table style="width:100%; ">
                 <tr>
                     <td></td>
                     <td style="text-align: center; font-size: 9px; border-top: solid ;"></td>
                     <td></td>
                 </tr>
                 <tr>
                     @php
                         $plantel = App\Models\Catalogos\PlantelesModel::find($datos->plantel_id); //

                     @endphp
                     <td colspan="3" style="text-align: center; font-size: 9px;">{{ $plantel->director }}</td>
                 </tr>
                 <tr>
                     <td colspan="3" style="text-align: center; font-size: 9px;">Director del plantel</td>
                 </tr>
             </table>
         @endif

     </section>
 @endsection
