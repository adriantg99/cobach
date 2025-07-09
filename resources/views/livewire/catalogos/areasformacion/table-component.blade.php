{{-- ANA MOLINA 27/06/2023 --}}
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
            <label class="card-title"><strong>Áreas de Formación Registrados:</strong> {{$count_areasformacion}}</label><br>
            {{ $areasformacion->links() }}
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Área de Formación</th>
                         <th>Fecha Hr</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($areasformacion as $areaformacion)
                    <tr>
                        <td>{{$areaformacion->id}}</td>
                        <td>{{$areaformacion->nombre}}</td>
                        <td>{{$areaformacion->updated_at}}</td>
                        <td>
                            @can('areaformacion-editar')
                            <button class="btn btn-info btn-sm"
                                onclick="cargando(); location.href='{{route('catalogos.areasformacion.editar',$areaformacion->id)}}';"
                                >Editar</button>
                            @endcan
                            @can('areaformacion-borrar')
                            <button class="btn btn-warning btn-sm"
                                onclick="confirmar_borrado({{$areaformacion->id}});"
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
    function confirmar_borrado(areaformacion_id)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el área de formación ID:"+areaformacion_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="/catalogos/areasformacion/eliminar/"+areaformacion_id;
          }
        })
    }

    function cargando(areaformacion_id)
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
