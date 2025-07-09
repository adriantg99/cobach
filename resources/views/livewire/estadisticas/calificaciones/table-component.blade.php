{{-- ANA MOLINA 17/02/2024 --}}

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

@php echo ( $ciclo_id );@endphp
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Alumnos:</strong> {{$count_alumnos}}</label><br>
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
                      <td>CICLO ESCOLAR</td>
                      <td>ESCUELA</td>
                      <td>MATRICULA</td>
                      <td>ALUMNO</td>
                      <td>APELLIDO PATERNO</td>
                      <td>APELLIDO MATERNO</td>
                      <td>EDAD</td>
                      <td>GENERO</td>
                      <td>CLAVE EMPLEADO PROFESOR</td>
                      <td>CURP PROFESOR</td>
                      <td>PROFESOR</td>
                      <td>PROFESOR APELLIDO PATERNO</td>
                      <td>PROFESOR APELLIDO MATERNO</td>
                      <td>SEMESTRE ALUMNO</td>
                      <td>CLAVE DE ASIGNATURA</td>
                      <td>SEMESTRE DE ASIGNATURA</td>
                      <td>ASIGNATURA</td>
                      <td>CALIFICACION FINAL EN BOLETA</td>
                      <td>APROBADA</td>
                      <td>REPROBADA</td>

                 </tr>
                </thead>
                @if (!empty($calificaciones))
                <tbody>
                   @foreach($calificaciones as $cal)
                    <tr>
                        <td>{{$cal->ciclo}} </td>
                        <td>{{$cal->plantel}} </td>
                        <td> {{$cal->noexpediente}}  </td>
                        <td>{{$cal->nombre}} </td>
                        <td>{{$cal->apellidopaterno}} </td>
                        <td>{{$cal->apellidomaterno}} </td>
                        <td>{{$cal->edad}} </td>
                        <td>{{$cal->genero}} </td>
                        <td>{{$cal->noempleado}} </td>
                        <td>{{$cal->curp}} </td>
                        <td>{{$cal->profesor}} </td>
                        <td>{{$cal->apellidopat}}</td>
                        <td>{{$cal->apellidomat}} </td>
                        <td>{{$cal->grado}} </td>
                        <td>{{$cal->clave}} </td>
                        <td>{{$cal->periodo}} </td>
                        <td>{{$cal->asignatura}} </td>
                        <td>{{$cal->calificacion}} </td>
                        <td>{{$cal->aprobada}} </td>
                        <td>{{$cal->reprobada}} </td>

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
        cargando(0);
});

    function cargando(id_ciclo)
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


    function exportar(ciclo_id,fecha)
    {


    let url="{{route('estadisticas.calificaciones.excel',['ciclo_id'=>":ciclo_id"])}}";
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
