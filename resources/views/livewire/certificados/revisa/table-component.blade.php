{{-- ANA MOLINA 02/05/2024 --}}
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
                        <option value="{{ $ciclo->id }}">{{ $ciclo->id }} -- {{ $ciclo->nombre }} -
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
                <select class="form-control" name="id_grupo" wire:model.lazy="id_grupo" id="id_grupo">
                    <option value="" selected>por grupo</option>
                    @foreach ($grupos as $grupo)
                        {{-- @php $turno=''; if ($grupo->turno_id==1) $turno="M"; else $turno="V";
                    @endphp
                    <option value="{{$grupo->id}}">{{$grupo->nombre}} {{$turno}}</option> --}}
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }} -- @if ($grupo->turno_id == '1')
                                Mat
                            @else
                                Ves
                            @endif
                            --
                            {{ $grupo->descripcion }}
                             ---  ID ({{$grupo->id}})
                        </option>
                    @endforeach
                </select>
                {{-- {{$this->id_grupo}} --}}

            </div>
            @if (!empty($getalumnos))

                <div>
                    <label class="form-label">Certificados:</label>

                </div>

                <div class="col-8 col-sm-12">
                    <div class="row g-3 align-items-center">
                        <div class="col-6 col-sm-6">
                            <label>Alumno</label>
                        </div>
                        <div class="col-2 col-sm-2 d-flex justify-content-center align-items-center">
                            <label>Asignaturas AC</label>
                        </div>
                        <div class="col-2 col-sm-1 d-flex justify-content-center align-items-center">
                            <label>Estatus</label>
                        </div>
                        <div class="col-1 col-sm-1 d-flex justify-content-center align-items-center">
                            <label>Duplicado</label>
                        </div>
                        <div class="col-2 col-sm-2">
                            <label>Fecha certificado</label>
                        </div>
                    </div>

                    <div style="height: 500px; overflow: auto;">
                        @foreach ($getalumnos as $alumno)
                            @if (isset($alumno->estatus_cert))
                                <div class="row g-3 align-items-center">
                                    <div class="col-6 col-sm-6">
                                        <label>
                                            <input type="checkbox" name="chkalumno" value="{{ $alumno->id }}"
                                                @if ($alumno->digital == null)
                                                checked="checked"    
                                                @endif
                                                >
                                            {{ $alumno->noexpediente }} {{ $alumno->apellidos }}
                                            {{ $alumno->nombre }}
                                        </label>
                                    </div>
                                    <div class="col-2 col-sm-2 d-flex justify-content-center align-items-center">
                                        <label>{{ $alumno->asignaturas }}</label>
                                    </div>
                                    <div class="col-2 col-sm-1 d-flex justify-content-center align-items-center">
                                        <label>
                                            @if (is_null($alumno->digital))
                                                Sin digitalizar
                                            @else
                                                Digitalizado
                                            @endif
                                        </label>
                                    </div>
                                    <div class="col-1 col-sm-1 d-flex justify-content-center align-items-center">
                                        <input type="checkbox" name="duplicado_{{ $alumno->id }}"
                                            id="duplicado_{{ $alumno->id }}"
                                            @if ($alumno->original == 2) checked @endif
                                            wire:change="duplicado({{ $alumno->id }}, $event.target.value)"
                                            class="custom-checkbox">
                                    </div>
                                    <div class="col-2 col-sm-2">
                                        <input type="date" name="fecha_certificado_{{ $alumno->id }}"
                                            wire:change="actualizarFechaCertificado({{ $alumno->id }}, $event.target.value)"
                                            value="{{ $alumno->fecha_certificado }}" class="form-control">
                                    </div>
                                </div>
                                <hr> <!-- Línea debajo de cada iteración -->
                            @endif
                        @endforeach
                    </div>
                </div>



        </div>
        <div class="col-6 col-sm-6">
            <button class="btn btn-light btn-sm" onclick="selall();">Seleccionar todo</button>
            <button class="btn btn-light btn-sm" onclick="deselall();">Invertir selección</button>
            <button class="btn btn-primary" onclick="revisandoporgrupo();">Revisar Certificado</button>
            <button class="btn btn-info" onclick="imprimir();">Imprimir</button>
        </div>
        <div>
            <label class="form-label">Sin certificado:</label>
        </div>
        <div class="col-8 col-sm-8">
            <div class="row g-3">
                <div class="col-10 col-sm-8">
                    <label>Alumno</label>
                </div>
                <div class="col-10 col-sm-2">
                    <label>Asignaturas AC</label>
                </div>

            </div>
            <div style="height:200px; overflow: auto;">
                @foreach ($getalumnos as $alumno)
                    @if (!isset($alumno->estatus_cert))
                        <div class="row g-3">
                            <div class="col-10 col-sm-8">
                                <label>{{ $alumno->noexpediente }} {{ $alumno->apellidos }}
                                    {{ $alumno->nombre }}</label>
                            </div>
                            <div class="col-10 col-sm-2">
                                <label>
                                    {{ $alumno->asignaturas }}
                                </label>
                            </div>


                        </div>
                    @endif
                @endforeach
            </div>

        </div>

        @endif

    </div>
    </div>

    @section('js_post')
        <script>
            $(document).on('change', '#id_alumno_change', function() {
                cargando(0);

            });

            document.addEventListener('livewire:load', function() {
                Livewire.on('fechaCertificadoActualizada', alumnoId => {
                    Swal.fire({
                        title: 'Fecha Actualizada',
                        text: `La fecha del certificado para el alumno ${alumnoId} ha sido actualizada.`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                });
            });

            document.addEventListener('livewire:load', function() {
                Livewire.on('cambio_duplicidad', (nombreAlumno, duplicidad) => {
                    let mensaje = '';

                    // Validar si duplicidad es 1 o 2 y generar el mensaje adecuado
                    if (duplicidad === 1) {
                        mensaje = `El certificado para el alumno ${nombreAlumno} será emitido como original.`;
                    } else if (duplicidad === 2) {
                        mensaje = `El certificado para el alumno ${nombreAlumno} será emitido como duplicado.`;
                    }

                    // Mostrar el mensaje con SweetAlert
                    Swal.fire({
                        title: 'Duplicidad Actualizada',
                        text: mensaje,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                });
            });

            function cargando(id_alumno) {
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

            }

            function revisandoporgrupo() {
                var alumnos_sel = '';
                $("input[name='chkalumno']").each(function(index, item) {
                    if (item.checked == true) {
                        if (alumnos_sel != "")
                            alumnos_sel = alumnos_sel + ",";
                        alumnos_sel = alumnos_sel + item.value;
                    }
                });

                var grupo = document.getElementById("id_grupo");

                var valuegrupo = grupo.value;


                //codificar
                var encodedgrupo = btoa(valuegrupo);
                console.log(encodedgrupo); // Outputs: "SGVsbG8gV29ybGQh"
                //codificar
                var encodedalumnos_sel = btoa(alumnos_sel);
                console.log(encodedalumnos_sel); // Outputs: "SGVsbG8gV29ybGQh"

                //decodificar atob()
                let url =
                    "{{ route('certificados.certificado.revisagrupo', ['alumnos_sel' => ':alumnos_sel', 'grupo_id' => ':valuegrupo']) }}";
                //url = url.replace(":alumnos_sel", alumnos_sel);
                //url = url.replace(":valuegrupo", valuegrupo);
                url = url.replace(":alumnos_sel", encodedalumnos_sel);
                url = url.replace(":valuegrupo", encodedgrupo);


                Swal.fire({
                    title: 'Generando reporte...',
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

            function imprimir() {
                var grupo = document.getElementById("id_grupo");
                var valuegrupo = grupo.value;
                //codificar
                var encodedgrupo = btoa(valuegrupo);

                let url = "{{ route('certificados.revisa.revisalistado', ['grupo_id' => ':grupo_id']) }}";
                url = url.replace(":grupo_id", encodedgrupo);


                Swal.fire({
                    title: 'Generando reporte...',
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


            function selall() {
                $("input[name='chkalumno']").each(function(index, item) {
                    item.checked = true;
                });
            }

            function deselall() {
                $("input[name='chkalumno']").each(function(index, item) {
                    item.checked = !(item.checked);
                });
            }
        </script>
    @endsection
