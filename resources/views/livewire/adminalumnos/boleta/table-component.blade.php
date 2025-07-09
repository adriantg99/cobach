{{-- ANA MOLINA 10/11/2023 --}}

@php
    use App\Models\Catalogos\CicloEscModel;
    use App\Models\Catalogos\PlantelesModel;
        $ciclos = CicloEscModel::select('id','nombre')->orderBy('per_inicio','desc')
        ->get();

        $planteles = PlantelesModel::select('id','nombre')->orderBy('nombre')
        ->get();

        if (!empty( $this->id_plantel) && !empty( $this->id_ciclo) )
        $grupos = DB::table('esc_grupo')->select('id','nombre','turno_id')->where('plantel_id', $this->id_plantel)->where('ciclo_esc_id', $this->id_ciclo)->orderBy('nombre')
        ->get();
    @endphp

<section class="py-4">
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de selección:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
                <div class="col-6 col-sm-6">
                    <label for="id_ciclo" class="form-label">Ciclo Escolar:</label>
                    <select class="form-control"
                    name="id_ciclo"
                    id="id_ciclo"
                    wire:model.lazy="id_ciclo" >
                    <option value="" selected>por ciclo escolar</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{$ciclo->id}}">{{$ciclo->nombre}} - {{$ciclo->per_inicio}} </option>
                    @endforeach
                  </select>
                  <label for="id_plantel" class="form-label">Plantel:</label>
                    <select class="form-control"
                    name="id_plantel"
                    wire:model.lazy="id_plantel"
                      >
                    <option value="" selected>por plantel</option>
                    @foreach($planteles as $plantel)
                        <option value="{{$plantel->id}}">{{$plantel->nombre}} </option>
                    @endforeach
                  </select>
                  <label class="form-label">Grupo:</label>
                  {{-- <select multiple  class="form-control"
                  name="id_grupo"
                  wire:model.lazy="id_grupo" >
                  <option value="" selected>por grupo</option>
                  @foreach($grupos as $grupo)
                      <option value="{{$grupo->id}}">{{$grupo->nombre}} </option>
                  @endforeach
                /<select> --}}
                @if (!empty($grupos))
                <div style="height:100px; overflow: auto;">
                    @foreach($grupos as $grupo)
                    @php $turno=''; if ($grupo->turno_id==1) $turno="M"; else $turno="V";
                    @endphp
                    <div>
                        <label>
                        <input type="checkbox" name="grupo" value="{{$grupo->id}}">{{$grupo->nombre}} {{$turno}}
                        </label>
                    </div>
                    @endforeach
                </div>
                <div class="col-6 col-sm-6">
                    <button class="btn btn-light btn-sm"  onclick="selall();">Seleccionar todo</button>
                    <button class="btn btn-light btn-sm"  onclick="deselall();">Invertir selección</button>
                     <button class="btn btn-light btn-sm" onclick="generandoporgrupo();">Imprimir Grupo</button>
               </div>

                @endif
                   </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
                <div class="col-6 col-sm-6">

                  <label   class="form-label">Apellidos:</label>
                    <input class="form-control" wire:model.lazy="apellidos">
                  <label   class="form-label">Expediente:</label>
                  <input class="form-control" wire:model.lazy="noexpediente">

                    <button class="btn btn-info"  onclick="cargando();">Buscar</button>
                </div>
        </div>

        <div class="card">
            <div class="card-header">
                <label class="card-title"><strong>Alumnos:</strong> {{$count_alumnos}}</label><br>
                {{-- {{$alumnos->links()}} --}}
            </div>

            <div class="col-6 col-sm-6">
                <label  class="form-label">Alumno:</label>
                <label for="id_alumno_change" class="form-label" >{{$id_alumno_change}}</label>

                <select class="form-control"
                name="id_alumno_change"
                id="id_alumno_change"
                wire:model.lazy="id_alumno_change"
                wire:change="changeEventAlumno($event.target.value)" >
                <option value="0" >por alumno</option>
                @foreach($alumnos as $alumno)
                    <option value="{{$alumno->id}}"  >{{$alumno->noexpediente}} - {{$alumno->apellidos}} {{$alumno->nombre}} </option>
                @endforeach
                </select>

            </div>

            <div class="col-6 col-sm-6">


            {{-- <button class="btn btn-light btn-sm"
            onclick="generando();  window.open('{{route('adminalumnos.boleta.reporte',array($id_alumno_change,$id_ciclo))}}','_blank');">Imprimir</button> --}}
            <button class="btn btn-light btn-sm"
            onclick="generandorep();  ">Imprimir</button>
            </div>

            <span><p>{{$this->flagcalif}}</p></span>

            <div class="card-body table-responsive table-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <td rowspan="2">Clave</td>
                            <td rowspan="2">Asignatura</td>
                            <td colspan="3">Calificaciones</td>
                            <td colspan="3">Faltas</td>
                            <td colspan="3">Final</td>
                          </tr>
                        <tr>
                          <td>P1</td>
                          <td>P2</td>
                          <td>P3</td>
                          <td>P1</td>
                          <td>P2</td>
                          <td>P3</td>
                          <td>ORD</td>
                          <td>REG</td>
                          <td>SEM</td>
                        </tr>
                    </thead>
                    @if (!empty($calificaciones))
                    <tbody>
                       @foreach($calificaciones as $calif)
                        <tr>
                            <td>{{$calif->clave}} </td>
                            <td>{{$calif->materia}} </td>
                          <td>{{$calif->calificacion1}} </td>
                         <td>{{$calif->calificacion2}} </td>
                          <td>{{$calif->calificacion3}} </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>{{$calif->calificacion}} </td>
                          <td></td>
                          <td></td>
                         </tr>
                      @endforeach
                    </tbody>
                    @endif
                </table>
                <span><strong>Extracurriculares:</strong></span>

        <table style="width:100%; border-spacing:0"  >

            @php $per =0 @endphp
            @if (!empty($calificacionesex))
             <tbody  >
                @foreach($calificacionesex as $calif)
                      @if ($calif->ordenaper==1 )
                    <tr  class="border-bottom-solid">

                    @else
                    <tr  class="border-bottom-dashed">

                    @endif

                  <td>{{$calif->materia}} </td>
                  <td>{{$calif->clave}} </td>
                  <td>{{$calif->ciclo1}} </td>
                  <td>{{$calif->clave}} </td>
                            <td>{{$calif->materia}} </td>
                          <td>{{$calif->calificacion1}} </td>
                         <td>{{$calif->calificacion2}} </td>
                          <td>{{$calif->calificacion3}} </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>{{$calif->calificacion}} </td>
                          <td></td>
                          <td></td>


                </tr>
              @endforeach
            </tbody>
            @endif
        </table>
            </div>

        </div>
    </div>

    </div>


@section('js_post')
<script>


  $(document).on('change', '#id_alumno_change', function() {
        cargando(0);


});


    function cargando(id_alumno)
    {

    $("input[name='grupo']").each(function(index, item) {
        item.checked = false;
        });
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

    function generandogrupo()
    {
    var grupos_sel='';
    $("input[name='grupo']").each(function(index, item) {
        if(item.checked == true)
        {
            if (grupos_sel!="")
                grupos_sel=grupos_sel+",";
            grupos_sel=grupos_sel+item.value;
        }
    });

    let url="{{route('adminalumnos.boleta.reportegrupo',['grupos_sel'=>":grupos_sel"])}}";
    url = url.replace(":grupos_sel", grupos_sel);

    $.ajax({
        url: url,
        success: function(data) {
            window.open(url, "_blank");
        },
        error: function(error) {
            //debugger;
            alert("Error:" + error);
            //alert(url);
        }
    });
    //let timerInterval
      Swal.fire({
        title: 'Generando reporte...',
        html: 'Por favor espere.',

      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log('I was closed by the timer')
        }
      })
      Swal.showLoading();

    }
     function generandoporgrupo()
    {
        var grupos_sel='';
    $("input[name='grupo']").each(function(index, item) {
        if(item.checked == true)
        {
            if (grupos_sel!="")
                grupos_sel=grupos_sel+",";
            grupos_sel=grupos_sel+item.value;
        }
    });

    let url="{{route('adminalumnos.boleta.reportegrupo',['grupos_sel'=>":grupos_sel"])}}";
    url = url.replace(":grupos_sel", grupos_sel);

    let swalAlert =Swal; // cache your swal

    swalAlert.fire({
      title: 'Generando reporte...',
       html: 'Por favor espere.',

        showConfirmButton: false
    });
    Swal.showLoading();


    $.ajax({
  url: url,
  type: "GET",
  success: function (result) {
    window.open(url, "_blank");
    swalAlert.close(); // this is what actually allows the close() to work
    //console.log(result);
  },
});

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
    function generandorep()
    {
           let url="{{route('adminalumnos.boleta.reporte',array(":id_alumno_change",":id_ciclo"))}}";

           var ciclo = document.getElementById("id_ciclo");
            var valueciclo = ciclo.value;
            var alumno = document.getElementById("id_alumno_change");
            var valuealumno = alumno.value;

            url = url.replace(":id_alumno_change",valuealumno);
            url = url.replace(":id_ciclo", valueciclo);

            let swalAlert =Swal; // cache your swal

            swalAlert.fire({
            title: 'Generando reporte...',
            html: 'Por favor espere.',

                showConfirmButton: false
            });
            Swal.showLoading();


            $.ajax({
        url: url,
        type: "GET",
        success: function (result) {
            window.open(url, "_blank");
            swalAlert.close(); // this is what actually allows the close() to work
            //console.log(result);
        },
        });
    }
    function selall()
    {
      $("input[name='grupo']").each(function(index, item) {
        item.checked = true;
    });
    }

    function deselall()
    {
        $("input[name='grupo']").each(function(index, item) {
        item.checked =!( item.checked) ;
    });
    }



</script>

@endsection
