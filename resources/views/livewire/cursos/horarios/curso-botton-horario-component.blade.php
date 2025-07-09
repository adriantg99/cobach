<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="cursos_modal">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        @if($curso_id != null)
        <h5 class="modal-title">Horario del curso: {{$curso->nombre}} del grupo: {{$curso->grupo->nombre}}{{$curso->grupo->turno->abreviatura}}<br>
          Docente: {{$curso->docente->apellido1}} {{$curso->docente->apellido2}} {{$curso->docente->nombre}}
          Horas Semana:
          <div class="input-group input-group-sm">  
            <input type="number" class="form-control" wire:model.defer="horas_semana" />
            <span class="input-group-append">
                    <button type="button"
                            onclick="alert_cambio_horas_semana()" 
                            class="btn btn-info btn-flat">Actualizar Horas Semana</button>
                  </span>
          </div>
        </h5>
        @else
        <h5 class="modal-title">...
        </h5>
        @endif
        <!--
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        -->
      </div>
      <div class="modal-body">
        <div class="list-group">
          <table class="table table-bordered ">
            <tr align="center" class="table-dark">
              <td width="10%"><strong>Hora</strong></td>
              <td width="18%"><strong>Lunes</strong></td>
              <td width="18%"><strong>Martes</strong></td>
              <td width="18%"><strong>Miercoles</strong></td>
              <td width="18%"><strong>Jueves</strong></td>
              <td width="18%"><strong>Viernes</strong></td>
            </tr>
            @if($curso_id != null)
            
            @foreach($horas as $h)
              <tr>
                <td>{{ date("g:iA",strtotime($h->hr_inicio)) }}</td>
{{--LUNES--}}
                @php
                  $curso_horas_lunes = App\Models\Cursos\CursoHoraModel::where('curso_id',$curso_id)
                    ->where('hora_plantel_id',$h->id)
                    ->where('dia_semana','LUNES')
                    ->first();
                @endphp
                @if($curso_horas_lunes!=null)
                  {{--Verde--}}
                  <td align="center" style="background-color:green;">
                  </td>
                @else
                  @php
                    $dato_hr_doc = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno  
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_curso.docente_id = ".$curso->docente_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'LUNES')
)");

                  $dato_hr_gpo = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre AS curso_nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno, esc_grupo.id AS grupo_id 
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_grupo.id = ".$curso->grupo_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'LUNES')
)");

                  @endphp
                  @if($dato_hr_gpo)
                    
                    <td align="center"  style="background-color:DarkKhaki;">
                      <span style="font-size: 60%;"><strong>{{$dato_hr_gpo[0]->curso_nombre}}</strong></span>-<span style="font-size: 60%;">{{$dato_hr_gpo[0]->nombre}}{{$dato_hr_gpo[0]->turno}}</span>
                    </td>

                  @elseif($dato_hr_doc)

                    <td align="center"  style="background-color:yellow;">
                      <span style="font-size: 75%;">{{$dato_hr_doc[0]->abreviatura}} - {{$dato_hr_doc[0]->nombre}}{{$dato_hr_doc[0]->turno}}</span>
                    </td>

                  @else
                    <td align="center" onclick="cambia_color({{$horas_marcadas}},{{$horas_semana}},{{$curso_id}},{{$h->id}},'LUNES',this);">
                    </td>
                  @endif
                @endif

{{--MARTES--}}
                @php
                  $curso_horas_martes = App\Models\Cursos\CursoHoraModel::where('curso_id',$curso_id)
                    ->where('hora_plantel_id',$h->id)
                    ->where('dia_semana','MARTES')
                    ->first();
                @endphp
                @if($curso_horas_martes!=null)
                  {{--Verde--}}
                  <td align="center" style="background-color:green;">
                  </td>
                @else
                  @php
                    $dato_hr_doc = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno  
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_curso.docente_id = ".$curso->docente_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'MARTES')
)");

                  $dato_hr_gpo = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre AS curso_nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno, esc_grupo.id AS grupo_id 
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_grupo.id = ".$curso->grupo_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'MARTES')
)");

                  @endphp
                  @if($dato_hr_gpo)
                    
                    <td align="center"  style="background-color:DarkKhaki;">
                      <span style="font-size: 60%;"><strong>{{$dato_hr_gpo[0]->curso_nombre}}</strong></span>-<span style="font-size: 60%;">{{$dato_hr_gpo[0]->nombre}}{{$dato_hr_gpo[0]->turno}}</span>
                    </td>

                  @elseif($dato_hr_doc)

                    <td align="center"  style="background-color:yellow;">
                      <span style="font-size: 75%;">{{$dato_hr_doc[0]->abreviatura}} - {{$dato_hr_doc[0]->nombre}}{{$dato_hr_doc[0]->turno}}</span>
                    </td>

                  @else
                    <td align="center" onclick="cambia_color({{$horas_marcadas}},{{$horas_semana}},{{$curso_id}},{{$h->id}},'MARTES',this);">
                    </td>
                  @endif
                @endif

{{--MIERCOLES--}}
                @php
                  $curso_horas_miercoles = App\Models\Cursos\CursoHoraModel::where('curso_id',$curso_id)
                    ->where('hora_plantel_id',$h->id)
                    ->where('dia_semana','MIERCOLES')
                    ->first();
                @endphp
                @if($curso_horas_miercoles!=null)
                  {{--Verde--}}
                  <td align="center" style="background-color:green;">
                  </td>
                @else
                  @php
                    $dato_hr_doc = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno  
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_curso.docente_id = ".$curso->docente_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'MIERCOLES')
)");

                  $dato_hr_gpo = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre AS curso_nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno, esc_grupo.id AS grupo_id 
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_grupo.id = ".$curso->grupo_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'MIERCOLES')
)");

                  @endphp
                  @if($dato_hr_gpo)
                    
                    <td align="center"  style="background-color:DarkKhaki;">
                      <span style="font-size: 60%;"><strong>{{$dato_hr_gpo[0]->curso_nombre}}</strong></span>-<span style="font-size: 60%;">{{$dato_hr_gpo[0]->nombre}}{{$dato_hr_gpo[0]->turno}}</span>
                    </td>

                  @elseif($dato_hr_doc)

                    <td align="center"  style="background-color:yellow;">
                      <span style="font-size: 75%;">{{$dato_hr_doc[0]->abreviatura}} - {{$dato_hr_doc[0]->nombre}}{{$dato_hr_doc[0]->turno}}</span>
                    </td>

                  @else
                    <td align="center" onclick="cambia_color({{$horas_marcadas}},{{$horas_semana}},{{$curso_id}},{{$h->id}},'MIERCOLES',this);">
                    </td>
                  @endif
                @endif

{{--JUEVES--}}
                @php
                  $curso_horas_jueves = App\Models\Cursos\CursoHoraModel::where('curso_id',$curso_id)
                    ->where('hora_plantel_id',$h->id)
                    ->where('dia_semana','JUEVES')
                    ->first();
                @endphp
                @if($curso_horas_jueves!=null)
                  {{--Verde--}}
                  <td align="center" style="background-color:green;">
                  </td>
                @else
                  @php
                    $dato_hr_doc = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno  
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_curso.docente_id = ".$curso->docente_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'JUEVES')
)");

                  $dato_hr_gpo = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre AS curso_nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno, esc_grupo.id AS grupo_id 
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_grupo.id = ".$curso->grupo_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'JUEVES')
)");

                  @endphp
                  @if($dato_hr_gpo)
                    
                    <td align="center"  style="background-color:DarkKhaki;">
                      <span style="font-size: 60%;"><strong>{{$dato_hr_gpo[0]->curso_nombre}}</strong></span>-<span style="font-size: 60%;">{{$dato_hr_gpo[0]->nombre}}{{$dato_hr_gpo[0]->turno}}</span>
                    </td>

                  @elseif($dato_hr_doc)

                    <td align="center"  style="background-color:yellow;">
                      <span style="font-size: 75%;">{{$dato_hr_doc[0]->abreviatura}} - {{$dato_hr_doc[0]->nombre}}{{$dato_hr_doc[0]->turno}}</span>
                    </td>

                  @else
                    <td align="center" onclick="cambia_color({{$horas_marcadas}},{{$horas_semana}},{{$curso_id}},{{$h->id}},'JUEVES',this);">
                    </td>
                  @endif
                @endif

{{--VIERNES--}}
                @php
                  $curso_horas_viernes = App\Models\Cursos\CursoHoraModel::where('curso_id',$curso_id)
                    ->where('hora_plantel_id',$h->id)
                    ->where('dia_semana','VIERNES')
                    ->first();
                @endphp
                @if($curso_horas_viernes!=null)
                  {{--Verde--}}
                  <td align="center" style="background-color:green;">
                  </td>
                @else
                  @php
                    $dato_hr_doc = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno  
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_curso.docente_id = ".$curso->docente_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'VIERNES')
)");

                  $dato_hr_gpo = Illuminate\Support\Facades\DB::select("
SELECT esc_grupo.ciclo_esc_id, esc_curso.nombre AS curso_nombre, esc_curso_hora.dia_semana, esc_grupo.nombre, esc_curso.docente_id, cat_hora_plantel.hr_inicio, cat_hora_plantel.hr_fin, esc_grupo.plantel_id, cat_plantel.abreviatura, cat_turno.abreviatura AS turno, esc_grupo.id AS grupo_id 
FROM esc_curso 
INNER JOIN esc_grupo ON esc_curso.grupo_id = esc_grupo.id 
INNER JOIN cat_turno ON esc_grupo.turno_id = cat_turno.id 
INNER JOIN esc_curso_hora ON esc_curso_hora.curso_id = esc_curso.id 
INNER JOIN cat_hora_plantel ON esc_curso_hora.hora_plantel_id = cat_hora_plantel.id 
INNER JOIN cat_plantel ON esc_grupo.plantel_id = cat_plantel.id 
WHERE((esc_grupo.ciclo_esc_id =".$curso->grupo->ciclo_esc_id." ) 
  AND (esc_grupo.id = ".$curso->grupo_id." ) 
  AND (cat_hora_plantel.hr_inicio >= '". $h->hr_inicio ."') 
  AND (cat_hora_plantel.hr_fin <= '".$h->hr_fin."') 
  AND (esc_curso_hora.dia_semana = 'VIERNES')
)");

                  @endphp
                  @if($dato_hr_gpo)
                    
                    <td align="center"  style="background-color:DarkKhaki;">
                      <span style="font-size: 60%;"><strong>{{$dato_hr_gpo[0]->curso_nombre}}</strong></span>-<span style="font-size: 60%;">{{$dato_hr_gpo[0]->nombre}}{{$dato_hr_gpo[0]->turno}}</span>
                    </td>

                  @elseif($dato_hr_doc)

                    <td align="center"  style="background-color:yellow;">
                      <span style="font-size: 75%;">{{$dato_hr_doc[0]->abreviatura}} - {{$dato_hr_doc[0]->nombre}}{{$dato_hr_doc[0]->turno}}</span>
                    </td>

                  @else
                    <td align="center" onclick="cambia_color({{$horas_marcadas}},{{$horas_semana}},{{$curso_id}},{{$h->id}},'VIERNES',this);">
                    </td>
                  @endif
                @endif
                         
              </tr>
            @endforeach
            @endif

            
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        @can('horario-borrar')
          <button type="button" class="btn btn-warning btn-sm" wire:click="limpiar" >Limpiar</button>
        @endcan
        @can('horario-crear')
          <button type="button" class="btn btn-success btn-sm" onclick="guardar_script();" id="guardar_script" disabled>Guardar</button>
        @endcan
        <button type="button" class="btn btn-secondary btn-sm" wire:click="cerrar_modal" >Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
window.addEventListener('show-modal', event => {
    //alert('Name updated to: ' + event.detail.alumno_id);
    $('#cursos_modal').modal('show');
})

window.addEventListener('hide-modal', event => {
    //alert('Name updated to: ' + event.detail.alumno_id);
    $('#cursos_modal').modal('hide');
})


</script>

{{-- SECCION SCRIPTS --}}


