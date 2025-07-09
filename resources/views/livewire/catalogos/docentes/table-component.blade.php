<div class="card">
  <div class="card-header">
      <label class="card-title">Docentes del plantel: {{$plantel->nombre}}</label>
  </div>
  <div class="card-body">
      <table class="table table-sm">
          <thead>
              <tr>
                  <th style="width: 10px">Correo</th>
                  <th>Nombres</th>
                  <th>Apellidos</th>
                  <th>Editar</th>
              </tr>
          </thead>
          <tbody>
              @foreach($docentes as $docente)
                  <tr>
                      <td>{{ $docente->email }}</td>
                      <td>{{ $docente->nombre }}</td>
                      <td>{{ $docente->apellido1 }} {{ $docente->apellido2 }}</td>
                      <td>
                        <button class="btn btn-info"
                        wire:click="$emit('abrirModal', '{{ $docente->email }}')">
                    Editar
                </button>
                      </td>
                  </tr>
              @endforeach
          </tbody>
      </table>
  </div>
  <div class='row'>
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="col-12 col-sm-8">
     <label for="nombre" class="form-label">Agregar Docente: (ingrese el correo institucional del docente)</label>
     <input class="form-control" wire:model="nuevo_email">
     <button class="btn btn-danger" wire:click="agregar">Agregar</button><button class="btn btn-success" onclick="window.location='{{route('adm.docentes')}}'">Regresar</button>
    </div>
  </div>
</div>

@livewire('catalogos.docentes.modal-docentes')

<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Escuchar el evento de "guardar-exito"
      window.addEventListener('guardar-exito', event => {
          livewire.emit('actualizar_docentes');
      });

      // Escuchar el evento de "cerrar-modal"
      window.addEventListener('cerrar-modal', event => {          
          livewire.emit('actualizar_docentes');
      });
  });
</script>

{{-- 
<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Escuchar el evento para abrir el modal
      window.addEventListener('mostrar-modal', () => {
          const modal = new bootstrap.Modal(document.getElementById('modalDocente'));
          modal.show();
          
      });

      // Escuchar el evento de cierre del modal
      window.addEventListener('cerrar-modal', () => {
          const modal = new bootstrap.Modal(document.getElementById('modalDocente'));
          modal.hide();
      });

      // Escuchar el evento 'modal-cerrado' para recibir un valor al cerrar
      window.addEventListener('modal-cerrado', event => {
          //console.log(event.detail.mensaje); // Imprime el valor recibido (en este caso el mensaje)
          livewire.emit('actualizar_docentes');
        console.log("esta parte");
          data-bs-dismiss="modal";

          // Aquí puedes hacer algo más con el valor que pasas, como actualizar el frontend
      });
  });
</script>
 --}}