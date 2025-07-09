 {{-- ANA MOLINA 16/10/2023 --}}
 @php
     use App\Models\Adminalumnos\ImagenesalumnoModel;
     //fotografía
     //tipo = 1 es la foto de identificación del alumno
     if (isset($this->alumno_id)) {
         $imagen_find = ImagenesalumnoModel::where('alumno_id', $this->alumno_id)
             ->where('tipo', 1)
             ->get();
     }
 @endphp
 @extends('layouts.reporte-hist_academic-layout') <!-- Session Status -->

 @section('title')
 HISTORIAL ACADEMICO
 @endsection
 @section('reporte')
     HISTORIAL ACADEMICO
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
     </style>
     <table style="width:100%">
         <tr>
            @php
                $plantel = App\Models\Catalogos\PlantelesModel::find($datos->plantel_id);
            @endphp
             <td>
                 <strong>PLANTEL:</strong> {{ $datos->plantel }}            
             </td>

             <td>
                 <strong>TEL:</strong> {{ $plantel->telefono }}
            </td> 
            <td><strong>CCT:</strong> {{ $plantel->cct }}</td>


         </tr>
         <tr>
             <td colspan="2">
                 <strong>DOMICILIO:</strong> {{ $plantel->plan_domicilio }}
             </td>
             
         </TR>
         <tr>
             <td>
                 <strong>ALUMNO:</strong> {{ $alumno }}
             </td>
             <td>
                 <strong>GRUPO:</strong> {{ $datos->grupo }}
             </td>
         </TR>
         <TR>
             <TD>
                <strong>CURP:</strong> {{ $alumno_find->curp }}
             </TD>
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
     </table>
 @endsection




 @section('content')
     <section>
         {{-- <table  class="table  table-condensed  table-striped text-center" > --}}
         <table style="width:100%; border-spacing:0">
             <thead>
                 <tr class="border-top-bottom">
                     <th>MATERIA</th>
                     <th width="10%">MAT</th>
                     <th width="5%">C</th>
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
                         $ordenasiper_anterior = 100;
                         $cont = 0;
                     @endphp
                     @foreach ($calificacioneska as $calif)
                         @php
                             $asignatura = App\Models\Catalogos\AsignaturaModel::find($calif->asignatura_id);
                         @endphp
                         @if ($asignatura->kardex)
                             @if ($calif->ordenasiper > $ordenasiper_anterior)
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

                         <td align="left">{{ $calif->materia }}{{ $asignatura->afecta_promedio ? '' : '*' }} </td>
                         <td align="center">{{ $calif->clave }} </td>
                         <td align="center">{{ $calif->creditos }} </td>
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
                         $ordenasiper_anterior = $calif->ordenasiper;
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
                 <td width="40%">
                     <strong>MATERIAS APROBADAS: </strong>{{ $aprobados }} <br>
                     <strong>MATERIAS REPROBADAS: </strong>{{ $reprobados }}<br>
                     <strong>PROMEDIO: </strong>{{ round($promedio) }}
                 </td>
                 <td width="30%">
                     <strong>CREDITOS: </strong>{{ $creditos }} <br><br>
                 </td>
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
                {{-- 
                 <td width="40%">
                     <table width="100%">
                         <tr>
                             <td><span style="font-size: 60%;">
                                     TIPO 1: ORDINARIO<br>
                                     TIPO 2: REGULARIZACIÓN<br>
                                     TIPO 3: PASANTIA<br>
                                     TIPO 4: ACTA ESPECIAL<br>
                                     TIPO 5: EQUIVALENCIA<br>
                                 </span>
                             </td>
                             <td>

                             <td> <span style="font-size: 60%;">

                                     TIPO 6: EN LINEA<br>
                                     TIPO 7: REVALIDACIÓN<br>
                                     TIPO 8: ACTA EXTEMPORANEA<br>
                                     TIPO 9: RECURSAMIENTO<br>
                                     TIPO 10: ESTRATEGIAS DE RECUPERACIÓN ACADEMICA<br>
                                 </span></td>

                 </td>
                  --}}
             </tr>
         </table>

         </td>
         </tr>
         </table>
         <hr>
         <p align="right"><strong>* Las extracurriculares no son consideradas para efectos del promedio.</strong></p>
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
     </section>
 @endsection