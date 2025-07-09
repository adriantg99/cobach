{{-- ANA MOLINA 19/09/2024 --}}

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
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">{{ $ciclo->id }} - {{ $ciclo->nombre }} -
                            {{ $ciclo->per_inicio }} </option>
                    @endforeach
                </select>
                <label for="id_plantel" class="form-label">Plantel:</label>
                <select class="form-control" name="id_plantel" wire:model.lazy="id_plantel">
                    <option value="" selected>por plantel</option>
                    @foreach ($planteles as $plantel)
                        <option value="{{ $plantel->id }}">{{ $plantel->nombre }} </option>
                    @endforeach
                </select>
                <label for="id_grupo" class="form-label">Grupo:</label>
                <select class="form-control" name="id_grupo" wire:model.lazy="id_grupo" id="id_grupo"
                    wire:change="changeEvent($event.target.value)">
                    <option value="" selected>por grupo</option>
                    @foreach ($grupos as $grupo)
                        @php
                        $turno = '';
                            if ($grupo->turno_id == 1) {
                                $turno = 'M';
                            } else {
                                $turno = 'V';
                            }
                        @endphp
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }} {{ $turno }}</option>
                        <!-- <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option> -->
                    @endforeach
                </select>

                {{-- {{$id_grupo}} --}}
                <label class="form-label">Alumnos:</label>
                {{-- <select multiple class="form-control" name="id_grupo" wire:model.lazy="id_grupo">
                    <option value="" selected>por grupo</option>
                    @foreach ($grupos as $grupo)
                    <option value="{{$grupo->id}}">{{$grupo->nombre}} </option>
                    @endforeach
                    </select> --}}

                @if (isset($getalumnos))
                    <select class="form-control" name="alumnos_sel" id="alumnos_sel" wire:model.lazy="alumnos_sel">
                        <option value="0">Por grupo</option>
                        @foreach ($getalumnos as $alumno)
                            <option value="{{ $alumno->id }}">{{ $alumno->noexpediente }} -
                                {{ $alumno->apellidos }}
                                {{ $alumno->nombre }} </option>
                        @endforeach
                    </select>
                    <div class="col-6 col-sm-6">
                        <button class="btn btn-primary" type="button" onclick="grupo();">Credencial</button>


                    </div>
                @endif

            </div>


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
                <label class="card-title"><strong>Alumnos:</strong> {{ $count_alumnos }}</label><br>
            </div>

            <div class="col-6 col-sm-6">
                <label class="form-label">Alumno:</label>
                <select class="form-control" name="id_alumno_change" id="id_alumno_change"
                    wire:model.lazy="id_alumno_change">
                    <option value="0">por alumno</option>
                    @foreach ($alumnos as $alumno)
                        <option value="{{ $alumno->id }}">{{ $alumno->noexpediente }} - {{ $alumno->apellidos }}
                            {{ $alumno->nombre }} </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-sm-6">
                {{-- <button class="btn btn-light btn-sm"
                    onclick="generando(); window.open('{{route('adminalumnos.certificado.reporte',$id_alumno_change)}}','_blank');">Imprimir</button>
                --}}
                @if ($id_alumno_change != 0)
                    <button class="btn btn-primary" type="button" onclick="alumno();">Credencial</button>
                @endif

            </div>



        </div>
    </div>
    @section('js_post')
        <script>
            $(document).on('change', '#id_alumno_change', function() {
                cargando(0);

            });




            function cargando(id_alumno) {

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
                Livewire.emit('genbuscar');
            }

            function grupo() {
                var alumno = document.getElementById("alumnos_sel").value;
                var grupo = document.getElementById("id_grupo").value;

                // Si hay un alumno seleccionado, el grupo se pone como 0
                if (alumno != 0) {
                    grupo = 0;
                }

                var encodedalumno = btoa(alumno);

                // Construir la URL
                let url = "{{ route('alumno.credencial.individual', ['alumno' => ':alumno', 'grupo_id' => ':grupo']) }}";
                url = url.replace(':alumno', encodedalumno).replace(':grupo', grupo);

                Swal.fire({
                    title: 'Generando credencial digital...',
                    html: 'Por favor espere.',
                    showConfirmButton: false
                });
                Swal.showLoading();

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        window.open(url, "_blank");
                        Swal.close();
                    }
                });
            }



            function alumno() {

                var alumno = document.getElementById("id_alumno_change");
                var valuealumno = alumno.value;
                //codificar
                var encodedalumno = btoa(valuealumno);
                console.log(encodedalumno); // Outputs: "SGVsbG8gV29ybGQh"


                //alert(url);

                let url = "{{ route('alumno.credencial.individual', ['alumno' => ':alumno']) }}";
                //url = url.replace(":alumnos_sel", alumnos_sel);
                //url = url.replace(":valuegrupo", valuegrupo);
                url = url.replace(":alumno", encodedalumno);

                //alert(url);

                Swal.fire({
                    title: 'Generando credencial digital...',
                    html: 'Por favor espere.',

                    showConfirmButton: false
                });
                Swal.showLoading();


                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        window.open(url, "_blank");
                        Swal.close(); // this is what actually allows the close() to work
                        //console.log(result);
                    },
                });



            }
        </script>
    @endsection
