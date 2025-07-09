{{-- ANA MOLINA 29/06/2023 --}}
@php
use App\Models\Catalogos\Politica_variabletipoModel;
use App\Models\Catalogos\AreaFormacionModel;
@endphp
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
            <label class="card-title"><strong>Políticas Registrados:</strong> {{$count_politicas}}</label><br>
            {{ $politicas->links() }}
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Politica</th>
                        <th>Area de Formación</th>
                        <th>Tipo de variable</th>
                        <th>Fecha Hr</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($politicas as $politica)
                    <tr>
                        <td>{{$politica->id}}</td>
                        <td>{{$politica->nombre}}</td>
                        <td>@php $areaformacion= AreaFormacionModel::find($politica->id_areaformacion )->nombre @endphp
                        {{$areaformacion}}</td>
                        <td>@php $variable= Politica_variabletipoModel::find($politica->id_variabletipo)->nombre @endphp
                            {{$variable}}</td>
                        <td>{{$politica->updated_at}}</td>
                        <td>
                            @can('politica-editar')
                            <button class="btn btn-info btn-sm"
                                onclick="cargando(); location.href='{{route('catalogos.politicas.editar',$politica->id)}}';"
                                >Editar</button>
                            @endcan
                            @can('politica-borrar')
                            <button class="btn btn-warning btn-sm"
                                onclick="confirmar_borrado({{$politica->id}});"
                                >Eliminar</button>
                            @endcan
                            @can('politica-editar')
                            <button class="btn btn-light btn-sm"
                            onclick="cargando(); location.href='{{route('catalogos.politicas.formula',$politica->id)}}';"
                            >Fórmula</button>
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
    function confirmar_borrado(politica_id)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar la política ID:"+politica_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="/catalogos/politicas/eliminar/"+politica_id;
          }
        })
    }

    function cargando(politica_id)
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
