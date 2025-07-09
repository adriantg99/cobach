{{-- ANA MOLINA 10/08/2023 --}}
@php
use App\Models\Catalogos\PlantelesModel;
use App\Models\Catalogos\PlandeEstudioModel;
$planteles = PlantelesModel::select('id','nombre')->orderBy('nombre')
        ->get();
    @endphp

<section class="py-4">
    @can('plandeestudio-crear')

      @if ($id_plantel_change!="")
          <div class="col-sm-8">
              <button class="btn btn-success btn-sm"
              onclick="cargando(); window.location='{{route('catalogos.planesdeestudio.agregar')}}';"
              >Agregar</button>
          </div>
      @endif
    @endcan

    <button class="btn btn-light btn-sm"
    onclick="generando(); window.location='{{route('catalogos.planesdeestudio.reporte')}}';"
  >Reporte</button>
    {{-- Do your work, then step back. --}}


    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
                <div class="col-6 col-sm-6">
                    <label for="id_plantel" class="form-label">Plantel:</label>
                    <select class="form-control"
                    name="id_plantel"
                    wire:model.lazy="id_plantel"
                    wire:change="changeEvent($event.target.value)">
                    <option value="" selected>por plantel</option>
                    @foreach($planteles as $plantel)
                        <option value="{{$plantel->id}}">{{$plantel->id}} - {{$plantel->nombre}}</option>
                    @endforeach
                  </select>
                  <label for="role" class="form-label">Nombre:</label>
                    <input class="form-control" wire:model.lazy="nombre">
                    <button class="btn btn-info">Buscar</button>
                </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Planes de Estudio Registrados:</strong> {{$count_planes}}</label><br>
            {{ $planes->links() }}
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Plan de Estudio</th>
                        <th>Plantel</th>
                        <th>Activa</th>
                        <th>Fecha Hr</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($planes as $plan)
                    <tr>
                        <td>{{$plan->id}}</td>
                        <td>{{$plan->nombre}}</td>

                        {{-- <td>@php $plantel= PlantelesModel::find($plan->id_plantel )->nombre @endphp
                        {{$plantel}}</td> --}}
                        <td>{{$plan->plantel}}</td>
                        <td>@if ($plan->activo==1)
                            SI
                            @endif</td>
                        <td>{{$plan->updated_at}}</td>
                        <td>
                            @can('plandeestudio-editar')
                            <button class="btn btn-info btn-sm"
                                onclick="cargando(); location.href='{{route('catalogos.planesdeestudio.editar',$plan->id)}}';"
                                >Editar</button>
                            @endcan
                            @can('plandeestudio-borrar')
                            <button class="btn btn-warning btn-sm"
                                onclick="confirmar_borrado({{$plan->id}});"
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
    function confirmar_borrado(plan_id)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el plan de estudio ID:"+plan_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="/catalogos/planesdeestudio/eliminar/"+plan_id;
          }
        })
    }

    function cargando(plan_id)
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
    function generando()
    {

      let timerInterval
      Swal.fire({
        title: 'Generando reporte...',
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
