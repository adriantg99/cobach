<div>
    {{-- Do your work, then step back. --}}
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Captura de calificaciones</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-xl-11 text-nowrap">
                    <table>
                        <tr>
                            <td><label class="form-label">Ciclo</label></td>
                            <td style="width: 100%">
                                <select class="form-select" style="display: inline-block; margin-left: 10px"
                                    name="select_ciclo_" id='select_ciclo_' wire:model="ciclos_escolares"
                                    @if ($ciclos_escolares != null) disabled @endif>
                                    <option value="" selected>Seleccionar ciclo escolar</option>
                                    @foreach ($Ciclos as $ciclos_escolares)
                                        <option value="{{ $ciclos_escolares->id }}">{{ $ciclos_escolares->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label">Plantel</label>
                            </td>
                            <td>
                                <section class="py-3">
                                    <select class="form-select" style="display: inline-block; margin-left: 10px"
                                        name="select_plantel" id="select_plantel" wire:model="select_plantel"
                                        @if ($select_plantel != null) disabled @endif>
                                        <option value="" selected>Seleccionar plantel</option>
                                        @foreach ($Plantel as $plantel)
                                            <option value="{{ $plantel->id }}">{{ $plantel->nombre }}</option>
                                        @endforeach
                                    </select>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">Curso</label>
                            </td>
                            <td>
                                <section class="py-3">
                                    <select class="form-select" name="select_curso" id="select_curso"
                                        style="display: inline-block; margin-left: 10px"
                                        wire:change="handleCambioSelect" wire:model="select_curso">
                                        <option value="" selected>Seleccionar curso</option>
                                        @foreach ($Curso as $cursos)
                                            <option value="{{ $cursos->id }}">{{ $cursos->nombre }} -----
                                                {{ $cursos->descripcion_grupo }} ----- @if ($cursos->turno_id == '1')
                                                    Matutino
                                                @else
                                                    Vespertino
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">Parcial</label>
                            </td>
                            <td>
                                <section class="py-3">
                                    <select class="form-select" name="select_parcial" id="select_parcial"
                                        style="display: inline-block; margin-left: 10px"
                                        wire:change="handleCambioSelect" wire:model="select_parcial">
                                        <option value="" selected>Seleccionar parcial</option>
                                        @foreach ($parciales as $parcial)
                                            <option value="{{ $parcial->politica_variable_id }}">
                                                {{ $parcial->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </section>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <section class="py-3">
                @if ($ciclos_escolares && $select_plantel && $select_curso && $select_parcial && $activa_grupo)
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <form method="post"
                                action="/docentes/curso/{{ $select_curso }}/politica_variable/{{ $select_parcial }}">
                                @csrf
                                <button class="btn btn-warning w-100" type="submit">Imprimir Lista de
                                    calificaciones</button>
                            </form>
                        </div>
                        <div class="col-12 col-md-4">
                            <form method="POST"
                                action="/docentes/lista_asistencia/{{ $select_curso }}/{{ $select_parcial }}">
                                @csrf
                                <button class="btn btn-success w-100" type="submit">Imprimir Lista de
                                    asistencia</button>
                            </form>
                        </div>
                    </div>
                @endif

                @if (!$activa_grupo && $ciclos_escolares && $select_plantel && $select_curso && $select_parcial)
                    <p class="text-danger mt-3">Favor de buscar el grupo nuevamente</p>
                @endif

                <div class="text-end mt-3">
                    <button wire:click="realizarBusqueda" id="boton_presionado" class="btn btn-primary">Buscar</button>
                </div>
            </section>
        </div>

    </div>
    {{-- BOTON PARA PRUEBAS DURANTE DESARROLLO
    <div class="mb-2">
        <button wire:click="ver_calificaciones();">
            <span class="badge bg-info">Registros:
                {{ is_array($alumnoCalificaciones) ? count($alumnoCalificaciones) : 0 }}</span>
        </button>
    </div>
     --}}
    @if ($activa_grupo)

        <div class="card shadow" wire:ignore>
            <div class="card-body">
                <div class="row">
                    @if ($parcial_activo)
                        <section class="py-3">
                            <button wire:click="guardarDatos()" class="btn btn-primary float-end">Guardar</button>
                        </section>
                    @endif

                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="myTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 120px;">N° Expediente</th>
                                        <th style="min-width: 200px;">Nombre</th>
                                        @if ($es_numerico && $parcial_nombre != 'F')
                                            <th style="min-width: 130px;">Inasistencias</th>
                                        @endif
                                        <th style="min-width: 130px;">Calificación</th>
                                        @if ($actas_activas)
                                            <th style="min-width: 150px;">Acta especial</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alumnos_en_curso as $alumno)
                                        <tr>
                                            <td>{{ $alumno->noexpediente }}</td>
                                            <td>{{ $alumno->apellidos }} {{ $alumno->nombre }}</td>

                                            @if ($es_numerico && $parcial_nombre != 'F')
                                                <td>
                                                    <input @if (!$parcial_activo || $alumno->calificacion_r_encontrada) readonly @endif
                                                        type="number"
                                                        class="form-control form-control-sm fw-bold text-center p-1"
                                                        style="font-size: 1.25rem; max-width: 80px; min-width: 60px; height: 2.2em; display: inline-block;"
                                                        id="F{{ $alumno->id }}"
                                                        wire:model.lazy="alumnoFaltas.{{ $alumno->id }}"
                                                        max="100" oninput="formatInput_faltas(this);"
                                                        maxlength="3">
                                                </td>
                                            @endif

                                            <td>
                                                @if (!$es_numerico)
                                                    <select class="form-control form-control-sm"
                                                        wire:model.lazy="alumnoCalificaciones.{{ $alumno->id }}"
                                                        id="{{ $alumno->id }}">
                                                        <option value="">Seleccione</option>
                                                        <option value="AC"
                                                            @if ($alumno->calif == 'AC') selected @endif>AC
                                                        </option>
                                                        <option value="NA"
                                                            @if ($alumno->calif == 'NA') selected @endif>NA
                                                        </option>
                                                    </select>
                                                @else
                                                    <input @if (!$parcial_activo || $alumno->calificacion_r_encontrada) readonly @endif
                                                        type="number"
                                                        class="form-control form-control-sm fw-bold text-center p-1"
                                                        style="font-size: 1.25rem; max-width: 90px; min-width: 60px; height: 2.2em; display: inline-block;"
                                                        wire:model.lazy="alumnoCalificaciones.{{ $alumno->id }}"
                                                        max="100" oninput="formatInput(this);" maxlength="3"
                                                        id="{{ $alumno->id }}">
                                                @endif
                                            </td>

                                            @if ($actas_activas && $alumno->calificacion_r_encontrada == 0)
                                                
                                                <td id="td_acta{{ $alumno->id }}">
                                                    @if ($alumno->id_acta == null && $alumnoCalificaciones[$alumno->id] == null)
                                                        <button class="btn btn-primary btn-sm"
                                                            id="actas{{ $alumno->id }}"
                                                            onclick="mostrarActas('{{ $alumno->noexpediente }}', '{{ $alumno->apellidos }} {{ $alumno->nombre }}', '{{ $alumno->id }}', '{{ $alumno->id_calif }}')">
                                                            Generar acta
                                                        </button>
                                                    @else
                                                    @if ($alumnoCalificaciones[$alumno->id] == null)
                                                            @switch($alumno->estado)
                                                            @case('2')
                                                            @case('3')
                                                                <button class="btn btn-primary btn-sm"
                                                                    id="actas{{ $alumno->id }}"
                                                                    onclick="mostrarActas('{{ $alumno->noexpediente }}', '{{ $alumno->apellidos }} {{ $alumno->nombre }}', '{{ $alumno->id }}', '{{ $alumno->id_calif }}')">
                                                                    Generar acta
                                                                </button>
                                                            @break

                                                            @default
                                                                <span class="text-muted">En revisión</span>
                                                        @endswitch
                                                    @endif
                                                        
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- /.table-responsive -->
                    </div> <!-- /.col-12 -->
                </div> <!-- /.row -->
            </div> <!-- /.card-body -->
        </div> <!-- /.card -->


        @if ($parcial_activo)
            <section class="py-3">
                <button wire:click="guardarDatos()" class="btn btn-primary float-end">Guardar</button>
            </section>
            {{-- <button wire:click="actualizarCalificaciones">Ver calificaciones</button> --}}
        @endif

        @if ($modal)
            @livewire('docentes.actas-component')
        @endif

        <div id="overlay"></div>

        <div id="popup" wire:ignore>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generacion de actas</h5>
                        {{-- 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" onclick="cerrarDivEmergente()">&times;</span>
                        </button> --}}
                    </div>
                    <div id="datos_alumno" class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <table class="tabla_modal">
                            <tr>
                                <td>
                                    Corrección de faltas
                                </td>
                                <td>
                                    Correción de calificación
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="number" class="form-control form-control-lg"
                                        oninput="formatInput_faltas(this);" maxlength="3" id="faltas.acta" />
                                </td>
                                <input type="text" readonly hidden id="id_alumno" />
                                <input type="text" readonly hidden id="id_calificacion">
                                <td>
                                    <input type="number" class="form-control form-control-lg"
                                        oninput="formatInput(this);" maxlength="3" id="calificacion.acta" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label for="exampleFormControlTextarea1">Motivo del acta especial</label>
                                    <textarea class="form-control" id="motivos" rows="3"></textarea>
                                </td>
                            </tr>
                        </table>


                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSolicitarActa" class="btn btn-primary"
                            onclick="guardar()">Solicitar
                            acta
                            especial</button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarDivEmergente()"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="detallesAlumno">

        </div>
</div>



@endif
<script>
    let advertenciaMostrada = false; // Solo mostramos la alerta una vez

    document.addEventListener('DOMContentLoaded', function() {
        let botonPresionado = false;

        let miBoton = document.getElementById('boton_presionado');

        if (miBoton) {
            miBoton.addEventListener('click', function() {
                botonPresionado = true;
            });
        }

        setInterval(function() {
            if (botonPresionado) {
                Livewire.emit('guardarDatosPorTiempo');
            } else {
                console.log("Sin selección");
            }
        }, 60000); // Llama cada 60 segundos

        verificarConexion(); // Verifica la conexión al cargar
        setInterval(verificarConexion, 30000); // Revisa cada 30 segundos
    });

    function verificarConexion() {
        if (advertenciaMostrada) return; // Evita mostrar la alerta más de una vez

        if (navigator.connection) {
            let tipoConexion = navigator.connection.effectiveType;
            let velocidad = navigator.connection.downlink;

            if (tipoConexion === '2g' || velocidad < 5) { // 1 Mbps es un límite razonable para detectar problemas
                mostrarAdvertencia();
                return;
            }
        }
        medirLatencia();
    }

    function medirLatencia() {
        let inicio = performance.now();

        fetch('/favicon.ico', {
                method: 'HEAD',
                cache: 'no-store'
            })
            .then(() => {
                let tiempo = performance.now() - inicio;
                if (tiempo > 1500) { // Si el tiempo de respuesta es mayor a 1.5s, hay lentitud
                    mostrarAdvertencia();
                }
            })
            .catch(() => {
                mostrarAdvertencia(); // Si hay un error, probablemente no hay internet
            });
    }

    function mostrarAdvertencia() {
        if (advertenciaMostrada) return; // Evita mostrar la alerta más de una vez
        advertenciaMostrada = true;

        Swal.fire({
            icon: 'warning',
            title: '⚠️ Conexión inestable',
            html: 'Tu conexión a internet es lenta o inestable. <br><br>' +
                '<strong>Sugerencias para mejorar:</strong><br>' +
                '🔹 Acércate al módem o router.<br>' +
                '🔹 Cambia a una red WiFi más estable.<br>' +
                '🔹 Usa una conexión por cable si es posible.<br>' +
                '🔹 Cierra aplicaciones que usen internet en segundo plano.',
            confirmButtonText: 'Entendido'
        });
    }

    function mostrarActas(noexpediente, nombre, id_alumno, id_calif) {
        document.getElementById("btnSolicitarActa").style.display = "block"; // o "inline" dependiendo de tu diseño

        document.getElementById("overlay").style.display = "block";
        document.getElementById("popup").style.display = "block";

        var calificacion = document.getElementById(id_alumno).value;
        var faltas = document.getElementById("F" + id_alumno).value;
        document.getElementById("calificacion.acta").value = calificacion;
        document.getElementById("faltas.acta").value = faltas;
        document.getElementById("id_alumno").value = id_alumno;
        document.getElementById("id_calificacion").value = id_calif;
        var contenidoHtml = 'Alumno: ' + nombre + '<br>';
        contenidoHtml += 'Expediente:' + noexpediente + '<br>';
        $('#datos_alumno').html(contenidoHtml);
    }

    function guardar() {
        document.getElementById("btnSolicitarActa").style.display = "none";

        var nueva_calif = document.getElementById("calificacion.acta").value;
        var nuevas_faltas = document.getElementById("faltas.acta").value;
        if (nuevas_faltas === '') {
            nuevas_faltas = '0';
        }
        var motivo = document.getElementById("motivos").value;
        var alumno_id = document.getElementById("id_alumno").value;
        var calificacion_id = document.getElementById("id_calificacion").value;
        var boton_alumno = document.getElementById("actas" + alumno_id);
        if (motivo.trim() === "") {
            Swal.fire({
                position: 'top-end',
                icon: 'danger',
                title: 'Favor de capturar un motivo',
                showConfirmButton: false,
                timer: 10000
            });
        } else {
            respuesta = @this.emit('solicitudActa', alumno_id, nueva_calif, nuevas_faltas, motivo, calificacion_id)

            Livewire.on('solicitudActaCompleta', respuesta => {
                if (respuesta == 1) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Acta guardada correctamente',
                        showConfirmButton: false,
                        timer: 10000
                    });

                    boton_alumno.disabled = true;
                    boton_alumno.style.display = 'none';
                    document.getElementById("td_acta" + alumno_id).innerHTML = "En revisión";
                    cerrarDivEmergente();
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'danger',
                        title: 'No se pudo guardar el acta, favor de intentar más tarde.',
                        showConfirmButton: false,
                        timer: 10000
                    });
                }
            });
        }

    }

    function cerrarDivEmergente() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("popup").style.display = "none";

        document.getElementById("motivos").value = "";
    }

    function formatInput(input) {
        // Elimina cualquier caracter que no sea un número
        input.value = input.value.replace(/\D/g, '');

        // Limita la longitud del valor a 3 dígitos
        if (input.value.length > 3) {
            input.value = input.value.slice(0, 3);
        }
        if (parseInt(input.value) > 100) {
            input.value = '100';
        }
    }

    function formatInput_faltas(input) {
        // Elimina cualquier caracter que no sea un número
        input.value = input.value.replace(/\D/g, '');

        // Limita la longitud del valor a 3 dígitos
        if (input.value.length > 2) {
            input.value = input.value.slice(0, 2);
        }
        if (parseInt(input.value) > 30) {
            input.value = '30';
            alert("No se pueden capturar más inasistencias que días habiles en el parcial.");
        }
    }

    document.addEventListener('livewire:load', function() {
        Livewire.on('calificacionesGuardadas', function(data) {
            console.log('Calificaciones guardadas por tiempo', data);
        });

        Livewire.on('guardadas_correctamente', function() {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Calificaciones guardadas correctamente',
                showConfirmButton: false,
                timer: 10000
            });
        });

        Livewire.on('actualizarEstadoBoton', function(nuevoEstado) {
            botonPresionado = nuevoEstado;
        });

        Livewire.on('solicitudActaCompleta', function(respuesta) {
            if (respuesta == 1) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Acta guardada correctamente',
                    showConfirmButton: false,
                    timer: 10000
                });
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'No se pudo guardar el acta, intenta más tarde.',
                    showConfirmButton: false,
                    timer: 10000
                });
            }
        });
    });
</script>



</div>
