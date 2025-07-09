<div>
    <button class="btn btn-success btn-sm" wire:click="$emitTo('cursos.omitidos.alumno-boton-component','muestra-modal','{{$alu->id}}',{{$gru}})">
        {{ $csos ? count( $csos ) : 0 }}
    </button>
</div>
