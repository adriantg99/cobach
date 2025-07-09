{{-- ANA MOLINA 02/08/2023 --}}
<section class="py-4">
    {{-- Do your work, then step back. --}}
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
                <div class="col-6 col-sm-6">
                    <label for="role" class="form-label">Nombre:</label>
                    <input class="form-control" wire:model.lazy="nombre">
                    <button class="btn btn-info">Buscar</button>
                </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Reglamentos Registrados:</strong> {{$count_reglamentos}}</label><br>
            {{ $reglamentos->links() }}
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Reglamento</th>
                         <th>Fecha Hr</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($reglamentos as $reglamento)
                    <tr>
                        <td>{{$reglamento->id}}</td>
                        <td>{{$reglamento->nombre}}</td>
                        <td>{{$reglamento->updated_at}}</td>
                        <td>
                            @can('reglamento-editar')
                            <button class="btn btn-info btn-sm"
                                onclick="cargando(); location.href='{{route('catalogos.reglamentos.editar',$reglamento->id)}}';"
                                >Editar</button>
                            @endcan
                            @can('reglamento-borrar')
                            <button class="btn btn-warning btn-sm"
                                onclick="confirmar_borrado({{$reglamento->id}});"
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
    function confirmar_borrado(reglamento_id)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el reglamento ID:"+reglamento_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="/catalogos/reglamentos/eliminar/"+reglamento_id;
          }
        })
    }

    function cargando(reglamento_id)
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
