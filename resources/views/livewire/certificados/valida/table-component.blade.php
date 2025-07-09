{{-- ANA MOLINA 17/07/2024 --}}
<section class="py-4">
    {{-- Do your work, then step back. --}}
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
                <div class="col-6 col-sm-6">
                    <label for="role" class="form-label">Oficio:</label>
                    <input class="form-control" wire:model.lazy="oficio">
                    <button class="btn btn-info">Buscar</button>
                </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Oficio Registrados:</strong> {{$count_oficios}}</label><br>
            {{ $oficios->links() }}
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Oficio</th>
                         <th>Fecha</th>
                         <th>Entidad Solicitante</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($oficios as $oficio)
                    <tr>
                        <td>{{$oficio->id}}</td>
                        <td>{{$oficio->oficio}}</td>
                        <td>{{$oficio->fecha_solicitud}}</td>
                        <td>{{$oficio->entidad}}</td>
                        <td>
                            @can('oficio-editar')
                            <button class="btn btn-info btn-sm"
                                onclick="cargando(); location.href='{{route('certificados.valida.editar',$oficio->id)}}';"
                                >Editar</button>
                            @endcan
                            @can('oficio-borrar')
                            <button class="btn btn-warning btn-sm"
                                onclick="confirmar_borrado({{$oficio->id}});"
                                >Eliminar</button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

@section('js_post')
<script>
    function confirmar_borrado(oficio_id)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el oficio ID:"+oficio_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="/certificados/valida/eliminar/"+oficio_id;
          }
        })
    }

    function cargando(oficio_id)
    {

      let timerInterval
      Swal.fire({
        title: 'Cargando...',
        html: 'Por favor espere.',
        timer: 10000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
          const b = Swal.getHtmlContainer().querySelector('b')
          timerInterval = setInterval(() => {
            b.textContent = Swal.getTimerLeft()
          }, 100)
        },
        willClose: () => {
          clearInterval(timerInterval)
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log('I was closed by the timer')
        }
      })

    }
</script>
@endsection
