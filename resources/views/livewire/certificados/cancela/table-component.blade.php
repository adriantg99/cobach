{{-- ANA MOLINA 04/06/2024 --}}
<section class="py-4">
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de selección:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
            <div class="col-6 col-sm-6">
                <label for="id_ciclo" class="form-label">Ciclo Escolar:</label>
                <select class="form-control" name="id_ciclo" id="id_ciclo" wire:model.lazy="id_ciclo">
                    <option value="" selected>por ciclo escolar</option>
                    @foreach($ciclos as $ciclo)
                    <option value="{{$ciclo->id}}">{{$ciclo->id}}--{{$ciclo->nombre}} - {{$ciclo->per_inicio}}</option>
                    @endforeach
                </select>
                <label for="id_plantel" class="form-label">Plantel:</label>
                <select class="form-control" name="id_plantel" wire:model.lazy="id_plantel">
                    <option value="" selected>por plantel</option>
                    @foreach($planteles as $plantel)
                    <option value="{{$plantel->id}}">{{$plantel->nombre}} </option>
                    @endforeach
                </select>
                <label for="id_grupo" class="form-label">Grupo:</label>
                <select class="form-control" name="id_grupo" wire:model.lazy="id_grupo" id="id_grupo"
                wire:change="changeEvent($event.target.value)">
                    <option value="" selected>por grupo</option>
                    @foreach($grupos as $grupo)
                    {{-- @php $turno=''; if ($grupo->turno_id==1) $turno="M"; else $turno="V";
                    @endphp
                    <option value="{{$grupo->id}}">{{$grupo->nombre}} {{$turno}}</option> --}}
                    <option value="{{$grupo->id}}">{{$grupo->nombre}} ---- @if ($grupo->turno_id==1) M @else V                        
                    @endif ---- ID ({{ $grupo->id }}) </option>
                    @endforeach
                </select>
                {{-- {{$id_grupo}} --}}

            </div>
            @if (!empty($getalumnos))

            <div>
                <label class="form-label">Certificados:</label>

            </div>

            <div class="col-8 col-sm-8">
                <div class="row g-3">
                    <div class="col-12 col-sm-8">
                        <label>Alumno</label>
                    </div>

                </div>
                <div  style="height:200px; overflow: auto;" >
                    @foreach($getalumnos as $alumno)
                        @if (isset($alumno->estatus_cert))
                        <div class="row g-3">
                            <div class="col-12 col-sm-8">
                            <label>
                                <input type="checkbox" name="chkalumno" value="{{$alumno->id}}"  >
                                {{$alumno->noexpediente}} {{$alumno->apellidos}} {{$alumno->nombre}}
                            </label>
                            </div>

                     </div>
                        @endif
                    @endforeach
                </div>

             </div>
             <div class="row g-3">
             <div class="col-12 col-sm-10">
                <label   class="form-label">Motivo de cancelación:</label>
                <input class="form-control "
                  placeholder="Motivo de cancelación"
                  name="motivo"  id="motivo"
                  type="text">
              </div>
            </div>
            <div class="col-6 col-sm-6">
                <button class="btn btn-light btn-sm" onclick="selall();">Seleccionar todo</button>
                <button class="btn btn-light btn-sm" onclick="deselall();">Invertir selección</button>
                <button class="btn btn-primary" onclick="grupo();">Cancelar Certificado</button>

            </div>

            @endif

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
            {{-- {{$alumnos->links()}} --}}
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
            <div class="col-6 col-sm-6">
                <label class="form-label">Apellidos:</label>
                <input class="form-control" wire:model.lazy="apellidos">
                <label class="form-label">Expediente:</label>
                <input class="form-control" wire:model.lazy="noexpediente">

                <button class="btn btn-info" onclick="cargando();">Buscar</button>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <label class="card-title"><strong>Alumnos:</strong> {{$count_alumnos}}</label><br>
            </div>

            <div class="col-6 col-sm-6">
                <label class="form-label">Alumno:</label>
                <select class="form-control" name="id_alumno_change" id="id_alumno_change"
                    wire:model.lazy="id_alumno_change" wire:change="changeEventAlumno($event.target.value)">
                    <option value="0">por alumno</option>
                    @foreach($alumnos as $alumno)
                    <option value="{{$alumno->id}}">{{$alumno->noexpediente}} - {{$alumno->apellidos}}
                        {{$alumno->nombre}} </option>
                    @endforeach
                </select>
                <label class="form-label">Estatus: {{$estatus}}</label>
            </div>

            <div class="col-6 col-sm-6">
                {{-- <button class="btn btn-light btn-sm"
                    onclick="generando(); window.open('{{route('adminalumnos.certificado.reporte',$id_alumno_change)}}','_blank');">Imprimir</button>
                --}}
                @if ($id_alumno_change!=0)
                <div class="row g-3">
                    <div class="col-12 col-sm-10">
                       <label   class="form-label">Motivo de cancelación:</label>
                       <input class="form-control "
                         placeholder="Motivo de cancelación"
                         name="motivoid"  id="motivoid"
                         type="text">
                     </div>
                   </div>
                <button class="btn btn-primary" type="button" onclick="alumno(); ">Cancelar Certificado</button>
                @endif
            </div>
        </div>
    </div>
    @section('js_post')

      <script>
 $(document).on('change', '#id_alumno_change', function() {
                cargando(0);

        });

    window.addEventListener('finish_can', event => {
        Swal.close();
        Swal.fire({
        title: 'Cancelación de certificados',
        icon: "success",
        html: 'Proceso realizado correctamente.',
        showConfirmButton: false,
        timer: 10000,
        didOpen: () => {
          Swal.showLoading();

           }
        });
    })
    function cargando(id_alumno)
    {
        $("input[name='chkalumno']").each(function(index, item) {
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
        Livewire.emit('canbuscar');
    }
    function grupo()
    {
        var observa = document.getElementById("motivo");
        var valueobserva = observa.value;

        var alumnos_sel='';
        $("input[name='chkalumno']").each(function(index, item) {
            if(item.checked == true)
            {
                if (alumnos_sel!="")
                    alumnos_sel=alumnos_sel+",";
                alumnos_sel=alumnos_sel+item.value;
            }
        });

        //codificar
        var encodedalumnos_sel = btoa(alumnos_sel);
        console.log(encodedalumnos_sel); // Outputs: "SGVsbG8gV29ybGQh"

        //decodificar atob()

        Swal.fire({
            title: 'Esta seguro que desea cancelar certificados?',
            showDenyButton: true,
            confirmButtonText: 'SI',
            denyButtonText: 'NO',
            icon: "question",
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: 'Cancelando certificado...',
                html: 'Por favor espere.',
                showConfirmButton: false,
                didOpen: () => {
                Swal.showLoading();
                Livewire.emit('cangrupo',encodedalumnos_sel,valueobserva);
                }
            });
            } else if (result.isDenied) {
                Swal.fire({
                    title: 'Proceso de cancelación abortado',
                    icon: "info",
                    showConfirmButton: false,
                    timer: 10000,
                    didOpen: () => {
                    Swal.showLoading();

                    }
                });
            }
            });

        // Livewire.emit('cangrupo',encodedalumnos_sel,valueobserva);
        // Swal.showLoading();

     }

    function alumno()
    {
        var observa = document.getElementById("motivoid");
        var valueobserva = observa.value;

        var alumno = document.getElementById("id_alumno_change");
        var valuealumno = alumno.value;

         //codificar
         var encodedalumno = btoa(valuealumno);
        console.log(encodedalumno); // Outputs: "SGVsbG8gV29ybGQh"

        Swal.fire({
            title: 'Esta seguro que desea cancelar certificados?',
            showDenyButton: true,
            confirmButtonText: 'SI',
            denyButtonText: 'NO',
            icon: "question",
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: 'Cancelando certificado...',
                html: 'Por favor espere.',
                showConfirmButton: false,
                didOpen: () => {
                Swal.showLoading();
                Livewire.emit('canalumno',encodedalumno,valueobserva);

                }
            });

            } else if (result.isDenied) {
                Swal.fire({
                    title: 'Proceso de cancelación abortado',
                    icon: "info",
                    showConfirmButton: false,
                    timer: 10000,
                    didOpen: () => {
                    Swal.showLoading();

                    }
                });
            }
            });

     }

    function selall()
    {
      $("input[name='chkalumno']").each(function(index, item) {
        item.checked = true;
    });
    }

    function deselall()
    {
        $("input[name='chkalumno']").each(function(index, item) {
        item.checked =!( item.checked) ;
    });
    }



    </script>

    @endsection
