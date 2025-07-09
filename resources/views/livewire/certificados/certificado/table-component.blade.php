{{-- ANA MOLINA 06/03/2024  --}}
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
                        <option value="{{ $ciclo->id }}">{{ $ciclo->id }} --- {{ $ciclo->nombre }} -
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
                        {{-- @php $turno=''; if ($grupo->turno_id==1) $turno="M"; else $turno="V";
                    @endphp
                    <option value="{{$grupo->id}}">{{$grupo->nombre}} {{$turno}}</option> --}}
                        <option value="{{ $grupo->id }}">
                            {{ $grupo->nombre }}---{{ $grupo->turno->abreviatura }}---{{ $grupo->descripcion }}  ---  ID ({{$grupo->id}}) </option>
                    @endforeach
                </select>
                {{-- {{$this->id_grupo}} --}}
                <label class="form-label">Alumnos:</label>
                {{-- <select multiple class="form-control" name="id_grupo" wire:model.lazy="id_grupo">
                    <option value="" selected>por grupo</option>
                    @foreach ($grupos as $grupo)
                    <option value="{{$grupo->id}}">{{$grupo->nombre}} </option>
                    @endforeach
                    /<select> --}}
            </div>
            <div class="col-8 col-sm-8">
                @if (!empty($getalumnos))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Alumno</th>
                                    <th>Adeudos</th>
                                    <th>Asignaturas AC</th>
                                    <th>Estatus</th>
                                    <th>Enviado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $contador = 1;
                                    $totalMarcados = 0;
                                @endphp
                                @foreach ($getalumnos as $alumno)
                                    <tr>
                                        <td>{{ $contador }}</td>
                                        <td>
                                            @php
                                                $fase = '';
                                                $read = '';
                                                $contador += 1;
                                                if ($alumno->digital == null) {
                                                    $fase = 'Generado';
                                                } else {
                                                    $fase = 'Digital';
                                                }
                                                $read = 'readonly';
                                                if ($alumno->estatus_cert == 'T' && $alumno->deudas_finanzas == 0) {
                                                    $totalMarcados += 1;
                                                }
                                            @endphp
                                            <input type="checkbox" name="chkalumno" {{ $read }}
                                                value="{{ $alumno->id }}"
                                                @if ($alumno->estatus_cert == 'T' && $alumno->deudas_finanzas == 0) checked="checked" @endif
                                                onclick="confirmAdeudo(this, {{ $alumno->deudas_finanzas }})">
                                            {{ $alumno->noexpediente }} {{ $alumno->apellidos }} {{ $alumno->nombre }}
                                        </td>
                                        <td>{{ $alumno->deudas_finanzas_desc }}</td>
                                        <td>{{ $alumno->asignaturas }}</td>
                                        <td>{{ $fase }}</td>
                                        <td>
                                            @if ($alumno->email)
                                                Enviado
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">Total de alumnos marcados: {{ $totalMarcados }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-6 col-sm-6">
                        <button class="btn btn-light btn-sm" onclick="selall();">Seleccionar todo</button>
                        <button class="btn btn-light btn-sm" onclick="deselall();">Invertir selección</button>
                        <button class="btn btn-primary" type="button" onclick="generandoporgrupo();">Certificado
                            Digital</button>
                        <button class="btn btn-info" type="button" onclick="enviarporgrupo();">Enviar por
                            correo</button>
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
                    wire:model.lazy="id_alumno_change" wire:change="changeEventAlumno($event.target.value)">
                    <option value="">por alumno</option>
                    @foreach ($alumnos as $alumno)
                        <option value="{{ $alumno->id }}">{{ $alumno->noexpediente }} - {{ $alumno->apellidos }}
                            {{ $alumno->nombre }} </option>
                    @endforeach
                </select>
                <label class="form-label">Estatus: {{ $estatus }}</label>

            </div>

            <div class="col-6 col-sm-6">
                {{-- <button class="btn btn-light btn-sm"
                    onclick="generando(); window.open('{{route('adminalumnos.certificado.reporte',$id_alumno_change)}}','_blank');">Imprimir</button>
                --}}
                @if ($id_alumno_change != 0)
                    <button class="btn btn-primary" type="button" onclick="generandorep(); ">Certificado</button>
                @endif
            </div>

            <div class="card-body table-responsive table-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Orden</td>
                            <td>Periodo</td>
                            <td>Ciclo</td>
                            <td>Clave</td>
                            <td>Materia</td>
                            <td>Calificacion</td>

                        </tr>
                    </thead>
                    @if (!empty($calificaciones))
                        <tbody>
                            @foreach ($calificaciones as $calif)
                                <tr>
                                    <td>{{ $calif->orden }}</td>
                                    <td>{{ $calif->periodo }}</td>
                                    <td>{{ $calif->ciclo }} </td>
                                    <td>{{ $calif->clave }} </td>
                                    <td>{{ $calif->materia }} </td>
                                    <td>{{ $calif->calificacion }} </td>

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
            $(document).on('change', '#id_alumno_change', function() {
                cargando(0);

            });

            function confirmAdeudo(checkbox, adeudo) {
                if (adeudo != 0 && checkbox.checked) {
                    Swal.fire({
                        title: 'Adeudo Pendiente',
                        text: "El alumno tiene un adeudo. ¿Desea continuar?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, continuar'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            checkbox.checked = false;
                        }
                    });
                }
            }

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
                Livewire.emit('cerbuscar');
            }

            function generandogrupo() {
                var alumnos_sel = '';
                $("input[name='alumno']").each(function(index, item) {
                    if (item.checked == true) {
                        if (alumnos_sel != "")
                            alumnos_sel = alumnos_sel + ",";
                        alumnos_sel = alumnos_sel + item.value;
                    }
                });
                var lstgrupo = document.getElementById("id_grupo");
                var grupo = lstgrupo.value;

                /* let url="{{ route('certificados.certificado.reportegrupo', ['alumnos_sel' => ':alumnos_sel', 'grupo_id' => ':grupo']) }}";
                url = url.replace(":alumnos_sel", alumnos_sel);
                url = url.replace(":grupo", grupo);

                $.ajax({
                    url: url,
                    success: function(data) {
                        window.open(url, "_blank");
                    },
                    error: function(error) {
                        //debugger;
                        alert("Error:" + error);
                        //alert(url);
                    } */

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

            function generandoporgrupo() {

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
                //console.log(encodedgrupo); // Outputs: "SGVsbG8gV29ybGQh"
                //codificar
                var encodedalumnos_sel = btoa(alumnos_sel);
                //console.log(encodedalumnos_sel); // Outputs: "SGVsbG8gV29ybGQh"

                //decodificar atob()
                let url =
                    "{{ route('certificados.certificado.reportegrupo', ['alumnos_sel' => ':alumnos_sel', 'grupo_id' => ':valuegrupo']) }}";
                //url = url.replace(":alumnos_sel", alumnos_sel);
                //url = url.replace(":valuegrupo", valuegrupo);
                url = url.replace(":alumnos_sel", encodedalumnos_sel);
                url = url.replace(":valuegrupo", encodedgrupo);

                //alert(url);

                Swal.fire({
                    title: 'Generando certificado digital...',
                    html: 'Por favor espere.',

                    showConfirmButton: false
                });
                Swal.showLoading();


                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        //window.open(url, "_blank");
                        Swal.close(); // this is what actually allows the close() to work
                        //console.log(result);
                        Swal.fire({
                            title: 'Generación y envío de certificados digitales',
                            icon: "success",
                            html: 'Proceso realizado correctamente.',
                            showConfirmButton: false,
                            timer: 10000,
                            didOpen: () => {
                                Swal.showLoading();

                            }
                        });
                        Livewire.emit('refresh');
                    },
                });
            }

            function enviarporgrupo() {
                adeudos_exist = false;
                var alumnos_sel = '';
                $("input[name='chkalumno']").each(function(index, item) {

                    if (item.checked == true) {
                        if (alumnos_sel != "")
                            alumnos_sel = alumnos_sel + ",";
                        alumnos_sel = alumnos_sel + item.value;
                        if (item.closest('tr').querySelector('td:nth-child(2)').textContent != '') {
                            adeudos_exist = true;
                        }
                    }
                    if (item.checked == true) {
                        if (alumnos_sel != "")
                            alumnos_sel = alumnos_sel + ",";
                        alumnos_sel = alumnos_sel + item.value;
                    }
                });
                if (adeudos_exist) {
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Hay alumnos con adeudos!',
                        text: '¿Desea enviar los correos de todas formas?',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, enviar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var encodedalumnos_sel = btoa(alumnos_sel);

                            let url =
                                "{{ route('certificados.email.enviargrupo', ['alumnos_sel' => ':alumnos_sel']) }}";
                            url = url.replace(":alumnos_sel", encodedalumnos_sel);

                            Swal.fire({
                                title: 'Enviando certificado digital...',
                                html: 'Por favor espere.',

                                showConfirmButton: false
                            });
                            Swal.showLoading();


                            $.ajax({
                                url: url,
                                type: "GET",
                                success: function(result) {
                                    //window.open(url, "_blank");
                                    Swal.close(); // this is what actually allows the close() to work
                                    if (result.success) {
                                        Swal.fire({
                                            title: 'Envíos de certificados digitales',
                                            icon: "success",
                                            html: 'Proceso realizado correctamente.',
                                            showConfirmButton: false,
                                            timer: 1000,
                                            didOpen: () => {
                                                Swal.showLoading();
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error en el envío',
                                            icon: "error",
                                            html: result.message,
                                            showConfirmButton: true,
                                        });
                                    }
                                    Livewire.emit('refresh');

                                },
                                error: function(xhr, status, error) {
                                    Swal.close();
                                    Swal.fire({
                                        title: 'Error en el envío',
                                        icon: "error",
                                        html: 'Hubo un problema en el envío de correos. Por favor, intente de nuevo.',
                                        showConfirmButton: true,
                                    });
                                }
                            });
                            //Livewire.emit('envgrupo', alumnos_sel);
                        }
                    });
                } else {
                    var encodedalumnos_sel = btoa(alumnos_sel);

                    let url = "{{ route('certificados.email.enviargrupo', ['alumnos_sel' => ':alumnos_sel']) }}";
                    url = url.replace(":alumnos_sel", encodedalumnos_sel);

                    Swal.fire({
                        title: 'Enviando certificado digital...',
                        html: 'Por favor espere.',

                        showConfirmButton: false
                    });
                    Swal.showLoading();


                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function(result) {
                            //window.open(url, "_blank");
                            Swal.close(); // this is what actually allows the close() to work
                            //console.log(result);
                            Swal.fire({
                                title: 'Envíos de certificados digitales',
                                icon: "success",
                                html: 'Proceso realizado correctamente.',
                                showConfirmButton: false,
                                timer: 10000,
                                didOpen: () => {
                                    Swal.showLoading();

                                }
                            });
                            Livewire.emit('refresh');
                        },
                    });
                    //Livewire.emit('envgrupo', alumnos_sel);
                }

                //codificar
                //console.log(encodedalumnos_sel); // Outputs: "SGVsbG8gV29ybGQh"

                //decodificar atob()
                //url = url.replace(":alumnos_sel", alumnos_sel);
                //url = url.replace(":valuegrupo", valuegrupo);
                //alert(url);


            }

            function generando() {

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
                });

            }

            function generandorep() {

                let url = "{{ route('certificados.certificado.reporte', [':id_alumno_change']) }}";

                var alumno = document.getElementById("id_alumno_change");
                var valuealumno = alumno.value;
                //codificar
                var encodedalumno = btoa(valuealumno);
                //console.log(encodedalumno); // Outputs: "SGVsbG8gV29ybGQh"


                //url = url.replace(":id_alumno_change",valuealumno);
                url = url.replace(":id_alumno_change", encodedalumno);
                //alert(url);

                Swal.fire({
                    title: 'Generando certificado digital...',
                    html: 'Por favor espere.',

                    showConfirmButton: false
                });
                Swal.showLoading();


                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        //window.open(url, "_blank");
                        Swal.close(); // this is what actually allows the close() to work
                        //console.log(result);
                        Swal.fire({
                            title: 'Generación de certificados digitales',
                            icon: "success",
                            html: 'Proceso realizado correctamente.',
                            showConfirmButton: false,
                            timer: 10000,
                            didOpen: () => {
                                Swal.showLoading();

                            }
                        });
                        Livewire.emit('cerbuscar');
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
