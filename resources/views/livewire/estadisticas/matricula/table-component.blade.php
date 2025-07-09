{{-- ANA MOLINA 01/11/2023 --}}

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
                    ciclo_id                    <select class="form-control"
                    name="ciclo_id"
                    id="ciclo_id"
                    wire:model.lazy="ciclo_id"  >
                    <option value="" selected>por ciclo escolar</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{$ciclo->id}}">{{$ciclo->nombre}} - {{$ciclo->per_inicio}} </option>
                    @endforeach
                  </select>
                  <label class="form-label">Fecha: <?php echo $fecha;?> </label>

                  <input class="form-control" wire:model.lazy="fecha" type="date" value=@php  echo date("Y-m-d");  @endphp>

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
        onclick="exportar({{ $ciclo_id }},'{{$fecha}}'); ">Exportar a Excel</button>


    </div>



        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                      <td>CICLO ESCOLAR</td>
                      <td>PLANTEL</td>
                      <td>GRADO</td>
                      <td>SEMESTRE</td>
                      <td>TURNO</td>
                      <td>NO. EXPEDIENTE O MATRICULA</td>
                      <td>ALUMNO</td>
                      <td>APELLIDO PATERNO</td>
                      <td>APELLIDO MATERNO</td>
                      <td>CURP</td>
                      <td>SERIE</td>
                      <td>CAPACITACION</td>
                      <td>GENERO</td>
                      <td>EDAD</td>
                      <td>GRUPO</td>
                      <td>DISCAPACIDAD</td>
                      <td>ETNIA</td>
                      <td>NACIONALIDAD</td>
                      <td>SECUNDARIA DONDE CURSO</td>
                      <td>TIPO DE SECUNDARIA</td>
                      <td>LUGAR DE NACIMIENTO</td>
                      <td>NUEVO INGRESO</td>
                      <td>REPETIDORES</td>
                      <td>ALTA INTERSEMESTRAL</td>
                      <td>ALTA INTERCICLO</td>
                      <td>ES NACIDO EN EL EXTRANJERO</td>
                      <td>TIENE DISCAPACIDAD</td>
                      <td>HABLA LENGUA INDIGENA</td>
                      <td>REGULARIZADO</td>
                      <td>IRREGULAR</td>
                      <td>VIENEN DE OTRO INSTITUCION DE MEDIA SUPERIOR</td>
                      <td>EXTRANJERO UNO DE SUS PADRES ES MEXICANO</td>
                      <td>EXTRANJERO CON ESTUDIOS EN OTRO PAIS</td>
                 </tr>
                </thead>
                @if (!empty($matricula))
                <tbody>
                   @foreach($matricula as $mat)
                    <tr>
                        <td>{{$mat->ciclo}} </td>
                        <td>{{$mat->plantel}} </td>
                        <td> {{$mat->grado}}  </td>
                        <td>{{$mat->periodo}} </td>
                        <td>{{$mat->turno}} </td>
                        <td>{{$mat->noexpediente}} </td>
                        <td>{{$mat->nombre}} </td>
                        <td>{{$mat->apellidopaterno}} </td>
                        <td>{{$mat->apellidomaterno}}</td>
                        <td>{{$mat->curp}} </td>
                        <td>{{$mat->serie}} </td>
                        <td>{{$mat->capacitacion}} </td>
                        <td>{{$mat->genero}} </td>
                        <td>{{$mat->edad}} </td>
                        <td>{{$mat->grupo}} </td>
                        <td>{{$mat->discapacidad}} </td>
                        <td>{{$mat->etnia}} </td>
                        <td>{{$mat->nacionalidad}} </td>
                        <td>{{$mat->secundaria}} </td>
                        <td>{{$mat->tiposec}} </td>
                        <td>{{$mat->lugarnacimiento}} </td>
                        <td>{{$mat->nuevoingreso}} </td>
                        <td>{{$mat->repetidor}} </td>
                        <td>{{$mat->altaintersem}} </td>
                        <td>{{$mat->altaincerciclo}} </td>
                        <td>{{$mat->nacidoext}} </td>
                        <td>{{$mat->tienediscap}} </td>
                        <td>{{$mat->lengua_indigena}} </td>
                        <td>{{$mat->regular}} </td>
                        <td>{{$mat->irregular}} </td>
                        <td>{{$mat->otrainst}} </td>
                        <td>{{$mat->extranjero_padre_mexicano}} </td>
                        <td>{{$mat->extranjero_grado_Ems}} </td>
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

$(document).on('change', '#fecha', function() {
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


    let url="{{route('estadisticas.matricula.excel',['ciclo_id'=>":ciclo_id",'fecha'=>":fecha"])}}";
    url = url.replace(":ciclo_id", ciclo_id);
    url = url.replace(":fecha", fecha);

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
