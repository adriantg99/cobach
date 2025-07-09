{{-- ANA MOLINA 16/10/2023 --}}

@php
    use App\Models\Catalogos\CicloEscModel;

    use App\Models\Catalogos\PlantelesModel;
    $ciclos = CicloEscModel::select('id', 'nombre')->orderBy('per_inicio', 'desc')->get();

    $planteles = PlantelesModel::select('id', 'nombre')->orderBy('nombre')->get();
@endphp

<section class="py-4">

    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
            <div class="col-6 col-sm-6">
                <label for="role" class="form-label">Ciclo Escolar:</label>
                <select class="form-control" name="id_ciclo" wire:model.lazy="id_ciclo">
                    <option value="" selected>por ciclo escolar</option>
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">{{ $ciclo->nombre }} - {{ $ciclo->per_inicio }} </option>
                    @endforeach
                </select>

                <label for="role" class="form-label">Plantel:</label>
                <select class="form-control" name="id_plantel" wire:model.lazy="id_plantel">
                    <option value="" selected>por plantel</option>
                    @foreach ($planteles as $plantel)
                        <option value="{{ $plantel->id }}">{{ $plantel->nombre }} </option>
                    @endforeach
                </select>

                <label for="role" class="form-label">Apellidos:</label>
                <input class="form-control" wire:model.lazy="apellidos">
                <label for="role" class="form-label">Expediente:</label>
                <input class="form-control" wire:model.lazy="noexpediente">

                <button class="btn btn-info" onclick="cargando();">Buscar</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Alumnos:</strong> {{ $count_alumnos }}</label><br>
            {{-- {{$alumnos->links()}} --}}
        </div>

        <div class="col-6 col-sm-6">
            <label for="role" class="form-label">Alumno:</label>
            <label for="role" class="form-label">{{ $id_alumno_change }}</label>

            <select class="form-control" name="id_alumno_change" id="id_alumno_change"
                wire:model.lazy="id_alumno_change" wire:change="changeEvent($event.target.value)">
                <option value="">por alumno</option>
                @foreach ($alumnos as $alumno)
                    <option value="{{ $alumno->id }}">{{ $alumno->noexpediente }} - {{ $alumno->apellidos }}
                        {{ $alumno->nombre }} </option>
                @endforeach
            </select>

        </div>

        <div class="col-6 col-sm-6">
            {{-- <button class="btn btn-light btn-sm"
        onclick="generando(); window.open('{{route('adminalumnos.kardex.reporte',$id_alumno_change)}}','_blank');">Imprimir</button> --}}
            <button class="btn btn-light btn-sm" onclick="generandorep(); ">Imprimir</button>
        </div>
        @if (!empty($calificaciones))
            {{ $id_alumno_change }}
        @endif
        @hasallroles('control_escolar')
            <div class="card-body table-responsive table-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Periodo</td>
                            <td>Materias</td>
                            <td>Clave</td>
                            <td>Ciclo</td>
                            <td>Calificación</td>
                            <td>AC/NA</td>
                            <td>Tipo</td>
                            <td>Ciclo</td>
                            <td>Calificación</td>
                            <td>AC/NA</td>
                            <td>Ciclo</td>
                            <td>Calificación</td>
                            <td>AC/NA</td>
                            <td>Tipo</td>
                            <td>Ciclo</td>
                            <td>Calificación</td>
                            <td>AC/NA</td>
                            <td>Tipo</td>
                        </tr>
                    </thead>
                    @if (!empty($calificaciones))
                        <tbody>
                            @foreach ($calificaciones as $calif)
                                @if (is_object($calif))
                                    @php
                                        $asignatura = App\Models\Catalogos\AsignaturaModel::find($calif->asignatura_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $calif->periodo }}</td>
                                        <td
                                            onclick="mostrarDetalles('{{ $calif->materia }}', '{{ $calif->clave }}', '{{ $calif->esc_curso_id }}', '{{ $calif->tipo1 }}');">
                                            {{ $calif->materia }}<br>
                                            <span style="font-size:90%">({{ $calif->asignatura_id }})
                                                (K:{{ $asignatura->kardex }})
                                                (P:{{ $asignatura->afecta_promedio }})</span>
                                        </td>

                                        <td>{{ $calif->clave }} </td>
                                        <td>{{ $calif->ciclo1 }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id1 }}', '{{ $calif->materia }}', '{{ $calif->ciclo1 }}', '{{ $calif->calificacion1 }}', '{{ $calif->tipo1 }}')">
                                            {{ $calif->calificacion1 }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id1 }}', '{{ $calif->materia }}', '{{ $calif->ciclo1 }}', '{{ $calif->calif1 }}', '{{ $calif->tipo1 }}')">
                                            {{ $calif->calif1 }} </td>
                                        <td>{{ $calif->tipo1 }} </td>

                                        <td>{{ $calif->ciclo2 }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id2 }}', '{{ $calif->materia }}', '{{ $calif->ciclo2 }}', '{{ $calif->calificacion2 }}', '{{ $calif->tipo2 }}')">
                                            {{ $calif->calificacion2 }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id2 }}', '{{ $calif->materia }}', '{{ $calif->ciclo2 }}', '{{ $calif->calif2 }}', '{{ $calif->tipo2 }}')">
                                            {{ $calif->calif2 }} </td>
                                        <td>{{ $calif->tipo2 }} </td>

                                        <td>{{ $calif->ciclo3 }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id3 }}', '{{ $calif->materia }}', '{{ $calif->ciclo3 }}', '{{ $calif->calificacion3 }}', '{{ $calif->tipo3 }}')">
                                            {{ $calif->calificacion3 }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id3 }}', '{{ $calif->materia }}', '{{ $calif->ciclo3 }}', '{{ $calif->calif2 }}', '{{ $calif->tipo3 }}')">
                                            {{ $calif->calif3 }} </td>
                                        <td>{{ $calif->tipo3 }} </td>

                                        <td>{{ $calif->ciclo }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id }}', '{{ $calif->materia }}', '{{ $calif->ciclo }}', '{{ $calif->calificacion }}', '{{ $calif->tipo }}')">
                                            {{ $calif->calificacion }} </td>
                                        <td
                                            onclick="mostrar_detalles_calif('{{ $calif->calificacion_id }}', '{{ $calif->materia }}', '{{ $calif->ciclo }}', '{{ $calif->calif }}', '{{ $calif->tipo }}')">
                                            {{ $calif->calif }} </td>
                                        <td>{{ $calif->tipo }} </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    @endif
                </table>
                <h3>
                    PROMEDIO = <strong>{{ $promedio }}</strong><br>
                    APROBADAS = <strong>{{ $aprobados }}</strong><br>
                    REPROBADAS = <strong>{{ $reprobados }}</strong><br>
                </h3>
            </div>
        @endhasallroles

    </div>
    <div id="overlay" wire:ignore></div>

    <div id="popup2" wire:ignore>
        <button id="cerrar" onclick="cerrarDivEmergente()">X</button>

        <h2 style="text-align: center;">Cambio de clave de materia</h2>
        <div>
            <!-- Aquí se mostrarán los detalles del alumno -->
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;text-align: center;">
                        Asignatura actual
                    </td>
                    <td style="width: 50%; text-align:center">
                        Asignatura a cambiar
                    </td>
                </tr>
                <tr>
                    <td style="padding-right: 2%">
                        <table style="border: 1px; width: 100%">
                            <tr>
                                <td>
                                    Nombre
                                </td>
                                <td style="text-align:right">
                                    Clave
                                </td>
                            </tr>
                            <tr class="distancia">
                                <td>
                                    <span id="nombre_materia"></span>

                                </td>
                                <td style="text-align: right;">
                                    <span id="clave_materia"></span>
                                    <input type="text" hidden id ="id_materia">
                                    <input type="text" hidden id ="id_tipo">
                                </td>
                            </tr>
                        </table>



                    </td>
                    <td>


                        <select class="form-select select2" name="clave_asignatura" id="clave_asignatura"
                            style="display: inline-block; margin-left: 10px">
                            <option value="0" selected>Seleccionar Asignatura</option>
                            @foreach ($asignaturas_encontradas as $asignatura)
                                <option style="text-align: center;" value="{{ $asignatura->id }}">
                                    {{ $asignatura->id }} -
                                    {{ $asignatura->nombre }}
                                    {{ $asignatura->clave }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>
            <div id="resultado_busqueda">

            </div>
            <div class="mt-3">
                <button id="boton_guardar" class="btn btn-primary" onclick="guardar()" style="float:right;">Guardar</button>
            </div>
        </div>
    </div>

    <div id="popup_calif" wire:ignore>
        <button id="cerrar" onclick="cerrarDivEmergente_calif()">X</button>

        <h2 style="text-align: center;">Cambio de calificacion de materia</h2>
        <div>
            <!-- Aquí se mostrarán los detalles del alumno -->
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;text-align: center;">
                        Asignatura seleccionada
                    </td>
                    <td style="width: 30%; text-align:center">
                        Nueva calificación
                    </td>
                    <td style="width: 30%; text-align:center">
                        Tipo calificación
                    </td>
                </tr>
                <tr>
                    <td style="padding-right: 2%">
                        <table style="border: 1px; width: 100%">
                            <tr>
                                <td>
                                    Nombre asignatura
                                </td>
                                <td style="text-align:right">
                                    Ciclo
                                </td>
                            </tr>
                            <tr class="distancia">
                                <td>
                                    <span id="asignatura"></span>
                                </td>
                                <td style="text-align: right;">
                                    <span id="ciclo_seleccionado"></span>
                                    <input type="text" hidden id ="calificacion_id">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>

                        <input type="number" class="form-control form-control-lg" hidden
                            oninput="formatInput(this);" maxlength="3" id="calificacion_puesta" />


                        <select class="form-control form-control-lg" name="calif_alfabetica" id="calif_alfabetica"
                            hidden>
                            <option value="0" selected>Seleccione una opción</option>
                            <option value="AC">AC</option>
                            <option value="NA">NA</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control form-control-lg" name="tipo_calificacion" id="tipo_calificacion">
                            <option value="0" selected>Seleccione un tipo de calificación</option>
                            <option value="1">1.- ORDINARIO</option>
                            <option value="2">2.- REGULARIZACIÓN</option>
                            <option value="3">3.- PASANTIA</option>
                            <option value="4">4.- ACTA ESPECIAL</option>
                            <option value="5">5.- EQUIVALENCIA</option>
                            <option value="6">6.- EN LINEA</option>
                            <option value="7">7.- REVALIDACIÓN</option>
                            <option value="8">8.- ACTA EXTEMPORANEA</option>
                            <option value="9">9.- RECURSAMIENTO</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div id="resultado_busqueda">

            </div>
            @hasrole(['super_admin', 'control_escolar'])
            <div class="mt-3">
                <button id="boton_guardar" class="btn btn-primary" onclick="eliminar_calificacion()"
                    style="float:right;">Eliminar calificación</button>
            </div>
            @endhasrole

            <div class="mt-3">
                <button id="boton_guardar" class="btn btn-secondary" onclick="guardar_calificacion()"
                    style="float:right;">Guardar</button>
            </div>
           
        </div>
    </div>


    </div>
    @section('js_post')
        <script>
            function formatInput(input) {
                const value = input.value.toUpperCase();
                const allowedValues = ["AC", "NA", "REV"];

                // Elimina cualquier caracter que no sea un número o una letra válida
                if (!/^\d+$/.test(value) && !allowedValues.includes(value)) {
                    input.value = input.value.slice(0, -1);
                    return;
                }

                // Limita la longitud del valor a 3 dígitos para números y 3 caracteres para letras
                if (value.length > 3) {
                    input.value = value.slice(0, 3);
                }

                // Si el valor es un número, limitarlo a 100
                if (/^\d+$/.test(value) && parseInt(value) > 100) {
                    input.value = '100';
                }
            }


            function guardar(clave) {
                var id_curso_anterior = document.getElementById("id_materia").value;
                var id_nueva_asignatura = document.getElementById("clave_asignatura").value;
                var tipo_id = document.getElementById("id_tipo").value;
                if (id_nueva_asignatura != 0) {
                    respuesta = @this.emit('guardar_nuevoCurso', id_curso_anterior, id_nueva_asignatura, tipo_id);
                    //alert('Name updated to: ');

                    if (respuesta = 1) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Curso guardado correctamente',
                            showConfirmButton: false,
                            timer: 10000
                        });
                        cerrarDivEmergente();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error', // Cambié 'danger' a 'error'
                            title: 'Existe un error al intentar guardar la nueva clave.',
                            showConfirmButton: false,
                            timer: 10000
                        });
                    }

                } else {
                    //alert('Name updated to: ');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'danger',
                        title: 'Favor de seleccionar una asignatura',
                        showConfirmButton: false,
                        timer: 10000
                    });

                }
            }

            function guardar_calificacion() {
                var calificacion_id = document.getElementById("calificacion_id").value;
                var calificacion_numerica = document.getElementById("calificacion_puesta").value;
                var calificacion_alfabetica = document.getElementById("calif_alfabetica").value;

                var tipo_calificacion = document.getElementById("tipo_calificacion").value;

                @this.call('cambiar_calificacion', calificacion_id, calificacion_alfabetica, calificacion_numerica,
                    tipo_calificacion).then(
                    response => {
                        if (response == 1) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Calificación actualizada correctamente',
                                showConfirmButton: false,
                                timer: 10000
                            });
                            cerrarDivEmergente_calif();
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error', // Cambié 'danger' a 'error'
                                title: 'Hubo un error, intenta nuevamente más tarde.',
                                showConfirmButton: false,
                                timer: 10000
                            });
                        }
                    });
            }

            function eliminar_calificacion() {
                var asignatura = document.getElementById("asignatura").innerHTML;
                var calificacion_id = document.getElementById("calificacion_id").value;
                var ciclo = document.getElementById("ciclo_seleccionado").innerHTML;

                Swal.fire({
                    title: 'Borrado de calificación',
                    text: "Confirme que desea eliminar la calificación de " +asignatura +
                    ". Tenga en cuenta que esta acción no puede deshacerse",
                    icon: 'warning',
                    input: 'text', // Añade un campo de texto para el motivo
                    inputPlaceholder: 'Ingrese el motivo de la eliminación',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, borrarlo',
                    preConfirm: (motivo) => {
                        if (!motivo) {
                            Swal.showValidationMessage('Debe ingresar un motivo')
                        }
                        return motivo;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const motivo = result.value;
                        @this.call('eliminar_calificacion', calificacion_id, motivo).then(
                            response => {
                                if (response == 1) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Calificación eliminada correctamente',
                                        showConfirmButton: false,
                                        timer: 10000
                                    });
                                    cerrarDivEmergente_calif();
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error', // Cambié 'danger' a 'error'
                                        title: 'Hubo un error, intenta nuevamente más tarde.',
                                        showConfirmButton: false,
                                        timer: 10000
                                    });
                                }
                            });
                    }
                })
            }

            function mostrarDetalles(materia, clave, esc_curso_id, tipo_id) {
                // Mostrar detalles en el div emergente
                document.getElementById("overlay").style.display = "block";
                document.getElementById("popup2").style.display = "block";
                document.getElementById("nombre_materia").innerHTML = materia;
                document.getElementById("clave_materia").innerHTML = clave;
                document.getElementById("id_materia").value = esc_curso_id;
                document.getElementById("id_tipo").value = tipo_id;
            }

            function cerrarDivEmergente() {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("popup2").style.display = "none";

                document.getElementById("nombre_materia").innerHTML = "";
                $('#clave_asignatura').val(null).trigger('change');

            }

            function cerrarDivEmergente_calif() {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("popup_calif").style.display = "none";


                document.getElementById("calificacion_puesta").value = 0;
                document.getElementById("calif_alfabetica").value = "0";
                document.getElementById("tipo_calificacion").value = "0";


                document.getElementById("calif_alfabetica").setAttribute('hidden', 'true');
                document.getElementById("calificacion_puesta").setAttribute('hidden', 'true');

            }

            function obtenerCursos(curso_id) {
                /*
                $.ajax({
                  url: '/cambio_clave/'+ curso_id,
                  type: 'GET',
                  success: function(data) {
                    var contenidoHTML = '<ul>';
                      contenidoHTML += 

                  }
                  
                });*/
            }

            function mostrar_detalles_calif(calificacion_id, asignatura, ciclo_seleccionado, calificacion_actual, tipo_id) {
                if (isNaN(calificacion_actual)) {
                    console.log("La calificación actual es texto");
                    document.getElementById("calif_alfabetica").value = calificacion_actual;
                    document.getElementById("calif_alfabetica").removeAttribute("hidden");
                } else {
                    // calificacion_actual es un número
                    document.getElementById("calificacion_puesta").value = calificacion_actual;
                    document.getElementById("calificacion_puesta").removeAttribute("hidden");
                }


                document.getElementById("tipo_calificacion").value = tipo_id;
                document.getElementById("calificacion_id").value = calificacion_id;
                document.getElementById("overlay").style.display = "block";
                document.getElementById("popup_calif").style.display = "block";
                document.getElementById("asignatura").innerHTML = asignatura;
                document.getElementById("ciclo_seleccionado").innerHTML = ciclo_seleccionado;
            }
        </script>

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
                })

            }

            function generandorep() {

                let url = "{{ route('adminalumnos.kardex.reporte', [':id_alumno_change']) }}";

                var alumno = document.getElementById("id_alumno_change");
                var valuealumno = alumno.value;

                url = url.replace(":id_alumno_change", valuealumno);

                let swalAlert = Swal; // cache your swal

                swalAlert.fire({
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
                        swalAlert.close(); // this is what actually allows the close() to work
                        //console.log(result);
                    },
                });
            }
        </script>

        <script></script>



        <script>
            window.addEventListener('carga_sweet_guardar', event => {
                //alert('Name updated to: ');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Clave de asignatura cambiada correctamente',
                    showConfirmButton: false,
                    timer: 10000
                })
            });
        </script>
    @endsection
