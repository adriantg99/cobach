<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="cursos_modal">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        @if($alumno)
        <h5 class="modal-title">Cursos del alumno: ({{$alumno->noexpediente}}) - {{$alumno->apellidos}} {{$alumno->apellidos}}</h5>
        @endif
        <!--
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        -->
      </div>
      <div class="modal-body">
        <div class="list-group">
          @if($alumno)
          @php
            $cursos = $alumno->cursos_del_grupo($grupo_id,$alumno->id);
          @endphp
          <ol>
            @foreach($cursos as $c)
              @php
                $calif = null;
                if($c->grupo->ciclo->activo <> 1)
                {
                  $calificacion = App\Models\Escolares\CalificacionesModel::where('alumno_id',$alumno->id)
                    ->where('curso_id',$c->id)->where('calificacion_tipo','R')->first();
                  if(is_null($calificacion))
                  {
                    $calificacion = App\Models\Escolares\CalificacionesModel::where('alumno_id',$alumno->id)
                    ->where('curso_id',$c->id)->where('calificacion_tipo','Final')->first();
                    $calif = 'Final: '.$calificacion->calificacion.$calificacion->calif;
                  }
                  else
                  {
                    $calif = 'R: '.$calificacion->calificacion.$calificacion->calif;
                  }
                }

                @endphp

              <li>{{$c->asignatura->clave}} - <strong>{{$c->nombre}} - @can('cursos-omitidos-alumno')
                {!!$calif? $calif:'<button class="btn btn-danger btn-sm" onclick="confirme('.$c->asignatura->clave.','.$alumno->noexpediente.', '.$c->id.');">Quitar curso</button>'!!} @endcan {{$calif}} </strong> </li>
            @endforeach
          </ol>
          @endif
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        <button type="button" class="btn btn-secondary" wire:click="cerrar_modal" >Cerrar</button>
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

window.addEventListener('notificar_curso_omitido', event => {
    //alert('Name updated to: ' + event.detail.alumno_id);
    Swal.fire({
      title: "Deleted!",
      text: "Curso eliminado!.",
      icon: "success"
    });
})

</script>