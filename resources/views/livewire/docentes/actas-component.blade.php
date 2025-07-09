<div>
    {{-- Be like water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Buscar Actas</p>
        </div>
        <div class="card-body">
            <div class="row" wire:ignore>
                <div class="col-md-6 col-xl-11 text-nowrap">
                    <table>
                        <tr>
                            <td>
                                <label class="form-label">Plantel</label>
                            </td>
                            <td style="width: 100%;">
                                <section class="py-3">
                                    <select class="form-select" name="select_plantel" id="select_plantel"
                                        wire:model="select_plantel" wire:change="borrar_contador()">

                                        <option value="" selected>Seleccionar plantel</option>
                                        @foreach ($plantel as $Planteles)
                                            <option value="{{ $Planteles->id }}">{{ $Planteles->nombre }}</option>
                                        @endforeach

                                    </select>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td><label class="form-label">Curso</label></td>
                            <td>
                                <section class="py-3">
                                    <select class="form-select" name="select_curso" id="select_curso"
                                        wire:model="select_curso" wire:change="borrar_contador()">
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
                    </table>
                </div>
            </div>
            <section class="py-3">
                <button wire:click="realizarBusqueda" class="btn btn-primary btn-success float-end">Realizar
                    Búsqueda</button>
            </section>
        </div>
    </div>
    @if (!empty($actas) && count($actas) > 0)
        <div class="card shadow card-body table-responsive table-sm" id="datos">

            <table style="width: 100%;  height: 50px; " wire:ignore>
                <thead>
                    <th>
                        Expediente
                    </th>
                    <th>
                        Nombre
                    </th>
                    <th>
                        Parcial
                    </th>
                    <th>
                        Nueva calificación
                    </th>
                    <th>
                        Nueva Faltas
                    </th>
                    <th>
                        Estatus
                    </th>
                    <th>
                        Acciones
                    </th>
                    <th>
                        Observaciones
                    </th>
                </thead>
                @foreach ($actas as $acta)
                    <tr style="height: 50px;             border-bottom: 1px solid black;
                    ">
                        <td>
                            {{ $acta->noexpediente }}
                        </td>
                        <td>
                            {{ $acta->alumno }}
                        </td>
                        <td>
                            {{ $acta->calificacion_tipo }}
                        </td>
                        <td>
                            {{ $acta->calificacion }}
                        </td>
                        <td>
                            {{ $acta->faltas }}
                        </td>
                        <td id="td_acta{{ $acta->id }}">
                            @if ($acta->estado == '3')
                            Rechazada
                        @elseif ($acta->estado == '2')
                        Aceptada
                        @else ($acta->estado == '1')
                        En revisión
                        @endif
                        </td>
                     
                        <td>
                            @if ($acta->estado == '3')
                                <div>
                                    <button class="btn btn-primary" id="acta{{ $acta->id }}"
                                        onclick="mostrarActas('{{ $acta->id }}')">Modificar
                                        acta</button>
                                </div>
                                {{ $acta->observaciones }}
                            @elseif($acta->estado == '2')
                                <button class="btn btn-primary"
                                    onclick="cargando(); window.open('{{ route('cursos.generar_acta', $acta->id) }}', '_blank')">
                                    Generar documento
                                </button>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div id="overlay" wire:ignore></div>

        <div id="popup" wire:ignore>

            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generacion de actas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" onclick="cerrarDivEmergente()">&times;</span>
                        </button>
                    </div>
                    <div id="datos_alumno" class="modal-body">
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
                                        oninput="formatInput(this);" maxlength="3" id="faltas.acta" />
                                </td>
                                <input type="text" readonly id="acta_id_hidden" />
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
                            onclick="guardar()">Modificar acta
                            especial</button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarDivEmergente()"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        window.addEventListener('funcion_listener', function(event) {
            sin_actas();
        });

        function sin_actas() {
            Swal.fire({
                icon: 'warning',
                title: 'No hay actas generadas en este grupo',
                showConfirmButton: false,
                timer: 10000
            });
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

        function guardar() {

            document.getElementById("btnSolicitarActa").style.display = "none";

            var nueva_calif = document.getElementById("calificacion.acta").value;
            var nuevas_faltas = document.getElementById("faltas.acta").value;
            var acta_id = document.getElementById("acta_id_hidden").value;

            if (nuevas_faltas === '') {
                nuevas_faltas = '0';
            }

            var motivo = document.getElementById("motivos").value;
            var boton_alumno = document.getElementById("acta" + acta_id);

            if (motivo.trim() === "") {
                Swal.fire({
                    position: 'top-end',
                    icon: 'danger',
                    title: 'Favor de capturar un motivo',
                    showConfirmButton: false,
                    timer: 10000
                });
            } else {
                valida_respuesta = @this.emit('modificar_acta', acta_id, nueva_calif, nuevas_faltas, motivo)
                Livewire.on('solicitudActaCompleta', valida_respuesta => {
                    if (valida_respuesta == "1") {
                        document.getElementById("acta" + acta_id).style.display = "none";
                        document.getElementById("td_acta" + acta_id).innerHTML = "En revisión";
                        cerrarDivEmergente();
                    } else {
                        console.log("No funciono");
                    }
                });
            }





        }



        function cargando() {

            let timerInterval
            Swal.fire({
                title: 'Cargando Documento',
                html: 'Por favor espere.',
                timer: 1000,
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

        function mostrarActas(id_acta) {
            document.getElementById("btnSolicitarActa").style.display = "block"; // o "inline" dependiendo de tu diseño

            document.getElementById("overlay").style.display = "block";
            document.getElementById("popup").style.display = "block";


            respuesta = @this.emit('buscar_acta', id_acta)

            Livewire.on('acta_encontrada', respuesta => {
                //console.log(respuesta);
                //console.log(respuesta);
                if (respuesta) {
                    document.getElementById("calificacion.acta").value = respuesta.nueva_calif;
                    document.getElementById("faltas.acta").value = respuesta.nueva_falta;
                    document.getElementById("motivos").value = respuesta.motivo;
                    document.getElementById("acta_id_hidden").value = respuesta.id;
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'danger',
                        title: 'Favor de intentarlo nuevamente más tarde.',
                        showConfirmButton: false,
                        timer: 10000
                    });
                }

            });

            //$('#datos_alumno').html(contenidoHtml);
        }

        function cerrarDivEmergente() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("popup").style.display = "none";

            document.getElementById("motivos").value = "";
        }
    </script>
</div>
