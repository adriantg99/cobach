{{-- ANA MOLINA 12/05/2024 --}}
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
            <label class="card-title"><strong>Datos Personales Registrados:</strong> {{$count_datosper}}</label><br>

        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                       <th>Nombre</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Final</th>
                        <th>Es director general?</th>

                    </tr>
                </thead>
                <tbody>
                @foreach($efirmalst  as $index =>    $datoper)
                    <tr>
                        <td>{{$this->efirmalst[$index]['id']}}</td>
                        <td>{{$this->efirmalst[$index]['nombre']}}</td>
                        <td>{{$this->efirmalst[$index]['fechainicio']}}</td>
                        <td>{{$this->efirmalst[$index]['fechafinal']}}</td>
                        <td>
                            <label>
                                <input type="checkbox" name="efirmalst[{{$index}}][directorgeneral]"
                                wire:model="efirmalst.{{$index}}.directorgeneral"
                                wire:change="processMark({{$index}},{{$this->efirmalst[$index]['id']}})"
                                @if ($this->efirmalst[$index]['directorgeneral'])
                                        checked="checked"
                                @endif>
                            </label></td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            @can('datoper-editar')
            <button class="btn btn-primary"
            wire:click="guardar(); "
                >Guardar</button>
            @endcan
        </div>

    </div>
</div>

@section('js_post')
<script>

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
