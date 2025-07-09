{{-- ANA MOLINA 07/12/2023 --}}

@php
    use App\Models\Catalogos\CicloEscModel;

        $ciclos = CicloEscModel::select('id','nombre')->orderBy('per_inicio','desc')
        ->get();

    @endphp
<section class="py-4">
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
                <div class="col-6 col-sm-6">
                    <label for="ciclo_id" class="form-label">Ciclo Escolar:</label>
                    <select class="form-control"
                    name="ciclo_id"
                    id="ciclo_id"
                    wire:model.lazy="ciclo_id"  >
                    <option value="" selected>por ciclo escolar</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{$ciclo->id}}">{{$ciclo->nombre}} - {{$ciclo->per_inicio}} </option>
                    @endforeach
                  </select>

                </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Grupos:</strong> {{$count_grupos}}</label><br>
            {{-- {{$alumnos->links()}} --}}
        </div>

        <div class="col-6 col-sm-6">

        <button class="btn btn-light btn-sm"
        onclick="exportar({{ $ciclo_id }}); ">Exportar a Excel</button>

    </div>



        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <td>CICLO</td>
                        <td>PLANTEL</td>
                        <td>TURNO</td>
                        <td>SERIE</td>
                        <td>CAPACITACION</td>
                        <td>GRADO</td>
                        <td>SEMESTRE</td>
                        <td>GRUPO</td>
                        <td>GRUPO DE FORMACION</td>
                        <td>GRUPO SERIE</td>
                        <td>GRUPO CAPACITACION</td>
                    </tr>
                </thead>
                @if (!empty($grupos))
                <tbody>
                   @foreach($grupos as $gr)
                    <tr>
                        <td>{{$gr->ciclo}} </td>
                        <td>{{$gr->plantel}} </td>
                        <td> {{$gr->turno}} </td>
                        <td> {{$gr->serie}} </td>
                        <td>{{$gr->capacitacion}} </td>
                        <td> {{$gr->grado}} </td>
                        <td> {{$gr->semestre}} </td>
                        <td>{{$gr->grupo}} </td>
                        <td>{{$gr->forma}}</td>
                      <td> {{$gr->ser}}</td>
                      <td>{{$gr->cap}}</td>
                 </tr>
                  @endforeach
                </tbody>
                @endif
            </table>
        </div>

    </div>
</div>
@section('js_post')
<script>


$(document).on('change', '#ciclo_id', function() {
        cargando();
});
    function cargando()
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
    function exportar(ciclo_id)
    {
    let url="{{route('estadisticas.grupos.excel',['ciclo_id'=>":ciclo_id"])}}";
    url = url.replace(":ciclo_id", ciclo_id);


    Swal.fire({
      title: 'Exportando a Excel...',
       html: 'Por favor espere.',

        showConfirmButton: false
    });
    Swal.showLoading();


    $.ajax({
  url: url,
  type: "GET",
  success: function (result) {
    window.open(url);
    Swal.close(); // this is what actually allows the close() to work
    //console.log(result);
  },
});

    }




</script>

@endsection
