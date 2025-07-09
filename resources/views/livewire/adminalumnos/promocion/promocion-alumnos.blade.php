<div>
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Promocion de alumnos</p>
        </div>
        <style>
            .container_alumnos {
                display: flex;
                align-items: flex-start;
                /* Alinea los elementos hijos en la parte superior */
            }

            .card_alumnos_izquierda,
            .card_alumnos_derecha {
                width: 50%;
                padding: 10px;
                box-sizing: border-box;
            }

            .card_alumnos_izquierda {
                background-color: #f0f0f0;
            }

            .card_alumnos_derecha {
                background-color: #f0f0f0;
            }

            /* Make sure content starts from the top */
            .card_alumnos_izquierda h3,
            .card_alumnos_derecha h2 {
                margin-top: 0;
            }
        </style>
        @can('promocion-ver')
            <!-- Aquí va tu contenido -->

            <!-- Añade el JS de SweetAlert -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Nose pudieron promocionar a los alumnos',
                        text: '{{ session('error') }}'
                    });
                </script>
            @endif

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Promoción de alumnos con exito',
                        text: '{{ session('success') }}'
                    });
                </script>
            @endif
            @if (session('success_half'))
                <script>
                    // Construir la lista de alumnos no inscritos
                    let alumnosList = '<ul>';
                    @foreach (session('alumnos_no_inscritos') as $alumno)
                        alumnosList += '<li>{{ $alumno['no_expediente'] }} - Ya cuenta con grupo base.</li>'; // Cambio aquí
                    @endforeach
                    alumnosList += '</ul>';

                    // Mostrar el SweetAlert con la lista de alumnos
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        html: '{{ session('success_half') }}' + alumnosList
                    });
                </script>
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-xl-11 text-nowrap">
                        <table>
                            <tr>
                                <td><label class="form-label">Ciclo</label></td>
                                <td style="width: 100%">

                                    <select class="form-select" style="display: inline-block; margin-left:"
                                        name="select_ciclo_" id='select_ciclo_' wire:model="ciclos_escolares"
                                        wire:change="handleCambioSelect">
                                        <option value="" selected>Seleccionar ciclo escolar</option>
                                        @foreach ($Ciclos as $select_ciclos_escolares)
                                            <option value="{{ $select_ciclos_escolares->id }}">
                                                {{ $select_ciclos_escolares->id }}---
                                                {{ $select_ciclos_escolares->nombre }}
                                                {{ $select_ciclos_escolares->abreviatura }}
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
                                        <select class="form-select" name="select_plantel" id="select_plantel"
                                            wire:model="select_plantel" wire:change="handleCambioSelect">
                                            <option value="" selected>Seleccionar plantel</option>
                                            @foreach ($Plantel as $Planteles)
                                                <option value="{{ $Planteles->id }}"
                                                    @unlessrole('control_escolar') @unlessrole('control_escolar_' . $Planteles->abreviatura) disabled @endunlessrole
                                                @endunlessrole>
                                                {{ $Planteles->nombre }}</option>
                                        @endforeach
                                    </select>
                                </section>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label">Grupo</label>
                            </td>
                            <td>
                                <section class="py-3">
                                    <select class="form-select" name="select_grupo_" id="select_grupo_"
                                        wire:model="select_grupo" wire:change="handleCambioSelect"
                                        @if (!$ciclos_escolares || !$select_plantel) disabled @else enable @endif>
                                        <option value="" selected>Seleccionar grupo</option>
                                        @foreach ($grupos as $grupo)
                                            <option value="{{ $grupo->id }}">{{ $grupo->descripcion }} -----------
                                                @if ($grupo->turno_id == '1')
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
        @endcan
        <section class="py-3">

            @if (!empty($message))
                <p>{{ $message }}</p>
            @endif

            <button wire:click="realizarBusqueda" onclick="desactivar()"
                class="btn btn-primary btn-success float-end">Realizar
                Búsqueda</button>
        </section>
    </div>

</div>


@if (!empty($alumnos_en_grupo))
    <div wire:ignore>
        <div class="container_alumnos">
            <div class="card_alumnos_izquierda"> <!-- Card Izquierda -->
                <h3>Alumnos inscritos en el ciclo anterior</h3>
                <button onclick="seleccionarTodos()" class="btn btn-secondary float-end">Seleccionar Todos</button>
                <br>
                <table style="width: 100%">
                    <th></th>
                    <th>Expediente</th>
                    <th>Nombre</th>
                    @foreach ($alumnos_en_grupo as $alumnos)
                        @if ($alumnos->reprobado == 0)
                            @continue;
                        @endif
                        <?php
                        $reinscripcion = DB::table('fin_fichas')->where('matricula', $alumnos->noexpediente)->first();
                        if ($reinscripcion && $reinscripcion->generada != null) {
                        ?>
                        <tr style="background-color: #317f43;" class="fila_verde">
                            <td>
                                <input type="checkbox" class="checkbox_promocion"
                                    name="alumno_{{ $alumnos->noexpediente }}" value="{{ $alumnos->id_alumno }}"
                                    id="{{ $alumnos->noexpediente }}" onchange="moverAlumno(this)"
                                    wire:click="seleccionarAlumno('{{ $alumnos->id_alumno }}', '{{ $alumnos->noexpediente }}')">
                            </td>
                            <td>{{ $alumnos->noexpediente }}</td>
                            <td colspan="4"
                                onclick="mostrarDetalles(); obtenerCalificacionAlumno('{{ $alumnos->id_alumno }}', '{{ $alumnos->ciclo_esc_id }}', '{{ $alumnos->noexpediente }}', '{{ $alumnos->nombre }}', '{{ $alumnos->apellidos }}')">
                                {{ $alumnos->apellidos }} {{ $alumnos->nombre }}
                            </td>
                        </tr>
                        <?php
                        continue;
                        } elseif ($reinscripcion && $reinscripcion->generada == null && !empty($reinscripcion->id)) {
                        ?>
                        <tr style="background-color: #e5be01;" class="fila_amarilla">
                            <td>
                                <input type="checkbox" name="alumno_{{ $alumnos->noexpediente }}"
                                    value="{{ $alumnos->id_alumno }}" id="{{ $alumnos->noexpediente }}"
                                    onchange="moverAlumno(this)"
                                    wire:click="seleccionarAlumno('{{ $alumnos->id_alumno }}', '{{ $alumnos->noexpediente }}')">
                            </td>
                            <td>{{ $alumnos->noexpediente }}</td>
                            <td
                                onclick="mostrarDetalles(); obtenerCalificacionAlumno('{{ $alumnos->id_alumno }}', '{{ $alumnos->ciclo_esc_id }}', '{{ $alumnos->noexpediente }}', '{{ $alumnos->nombre }}', '{{ $alumnos->apellidos }}')">
                                {{ $alumnos->apellidos }} {{ $alumnos->nombre }}
                            </td>
                        </tr>
                        <?php
                        } else {
                        ?>
                        <tr style="background-color:  #e5be01;" class="fila_roja">
                            <td>
                                <input type="checkbox" name="alumno_{{ $alumnos->noexpediente }}"
                                    value="{{ $alumnos->id_alumno }}" id="{{ $alumnos->noexpediente }}"
                                    onchange="moverAlumno(this)"
                                    wire:click="seleccionarAlumno('{{ $alumnos->id_alumno }}', '{{ $alumnos->noexpediente }}')">
                            </td>
                            <td>{{ $alumnos->noexpediente }}</td>
                            <td
                                onclick="mostrarDetalles(); obtenerCalificacionAlumno('{{ $alumnos->id_alumno }}', '{{ $alumnos->ciclo_esc_id }}', '{{ $alumnos->noexpediente }}', '{{ $alumnos->nombre }}', '{{ $alumnos->apellidos }}')">
                                {{ $alumnos->apellidos }} {{ $alumnos->nombre }}
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    @endforeach
                </table>
            </div>
            <div class="card_alumnos_derecha" wire:ignore> <!-- Card Derecha -->
                <h2 class="top">Lista de alumnos del grupo a promover</h2>
                Grupo a promover
                <select name="grupo_seleccionado" class="form-select" id="grupo_seleccionado"
                    wire:model="grupo_seleccionado">
                    <option value="" selected></option>
                    @foreach ($grupos_pasar as $grupo_nuevo)
                        <option value="{{ $grupo_nuevo->id }}">{{ $grupo_nuevo->nombre }}
                            @if ($grupo_nuevo->turno_id == 1)
                                Matutino
                            @else
                                Vespertino
                            @endif
                            {{ $grupo_nuevo->descripcion }}
                        </option>
                    @endforeach
                </select>
                <table style="width: 100%">
                    <th></th>
                    <th>Expediente</th>
                    <th>Nombre completo</th>
                </table>
            </div>
        </div>


    </div>
    <div class="row g-3 mt-3">
        <div class="col-sm-8">
            <button class="btn btn-primary" onclick="cargando()" wire:click="guardar_alumnos_grupos"
                @if (empty($grupo_seleccionado)) disabled @endif>
                Guardar</button>
        </div>
    </div>

    <div class="container_alumnos" wire:ignore>
        <div class="card_alumnos"> <!-- Card Abajo -->
            <h2>Alumnos que tienen más de 3 reprobadas del ciclo escolar pasado</h2>
            <p>Estos alumnos no pueden ser cargados al nuevo grupo</p>
            <table>
                <tr>
                    <th>Número de expediente</th>
                    <th>Nombre</th>
                </tr>
                @foreach ($alumnos_en_grupo as $alumno)
                    @if ($alumno->reprobado == 1)
                        @continue
                    @endif
                    <tr>
                        <td>
                            {{ $alumno->noexpediente }}
                        </td>
                        <td>
                            {{ $alumno->nombre }} {{ $alumno->apellidos }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>


    <div id="overlay"></div>

    <div id="popup" wire:ignore>
        <span id="cerrar" onclick="cerrarDivEmergente()">X</span>
        <h2>¡Calificaciones del alumno con el expediente !</h2>
        <div id="detallesAlumno">
            <!-- Aquí se mostrarán los detalles del alumno -->
            <table>
                <tr>
                    <th>
                        Alumno
                    </th>
                    <th>
                        Asignatura
                    </th>
                    <th>
                        Calificación
                    </th>
                </tr>

                @if (!empty($calificaciones_alumnos))
                    @foreach ($calificaciones_alumnos as $calificaciones_alumnos)
                        <tr>
                            <td>

                            </td>
                            <td>
                                {{ $calificaciones_alumnos->nombre }}
                            </td>
                            <td>
                                {{ $calificaciones_alumnos->calificacion }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
@endif

<!--<input class="form-control" placeholder="0" name="cantidad_grupos_extra" type="text" id="cantidad_grupos_extra" wire:model="cantidad_grupos_extra">-->
<script>
    function seleccionarTodos() {
        var filasVerdes = document.querySelectorAll('.card_alumnos_izquierda .fila_verde');

        // Recorrer todas las filas y mover cada alumno a la derecha
        filasVerdes.forEach(function(fila) {
            var checkbox = fila.querySelector('input[type="checkbox"]');
            if (checkbox && !checkbox.checked) {
                checkbox.checked = true; // Marcar el checkbox
                // console.log(checkbox.value);
                @this.emit('seleccionarAlumno', checkbox.value, checkbox.name);
                moverAlumno(checkbox); // Llamar a la función moverAlumno
            }
        });
    }
    /*    
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = true;
        // También puedes llamar a la función moverAlumno o cualquier otra lógica que necesites aquí
    });
   
}*/
    function moverAlumno(checkbox) {
        // Obtener la fila del alumno que contiene el checkbox
        var row = checkbox.closest('tr');

        // Obtener el div card izquierdo y derecho
        var cardIzquierda = document.querySelector('.card_alumnos_izquierda');
        var cardDerecha = document.querySelector('.card_alumnos_derecha');

        // Verificar si el checkbox está marcado o desmarcado
        if (checkbox.checked) {
            // Mover la fila al div card derecho
            cardDerecha.querySelector('table').appendChild(row.cloneNode(true));
            // Eliminar la fila del div card izquierdo
            row.remove();
        } else {
            // Mover la fila al div card izquierdo
            // console.log(checkbox.value + "Eliminado");
            @this.emit('quitar_del_array', checkbox.value);
            cardIzquierda.querySelector('table').appendChild(row.cloneNode(true));
            // Eliminar la fila del div card derecho
            row.remove();
        }
    }
</script>
</div>
<script>
    function mostrarDetalles() {
        console.log("entre aqui");
        // Mostrar detalles en el div emergente
        document.getElementById("overlay").style.display = "block";
        document.getElementById("popup").style.display = "block";

    }
</script>

<script>
    function obtenerCalificacionAlumno(id_alumno, ciclo_esc_id, expediente, nombre, apellido) {
        $.ajax({
            url: '/calificacion_alumno/' + id_alumno + '/' + ciclo_esc_id,
            type: 'GET',
            success: function(data) {
                var contenidoHTML = '<ul>';
                contenidoHTML += '<h2>¡Calificaciones del alumno con el expediente ' + expediente +
                    '!</h2>';
                contenidoHTML += '<h3>' + nombre + ' ' + apellido + '</h3>'
                contenidoHTML += '<span id="cerrar" onclick="cerrarDivEmergente()">X</span>';


                for (var i = 0; i < data.length; i++) {
                    contenidoHTML += '<li>' + data[i].nombre + ': ' + data[i].calificacion + '</li>';
                }
                contenidoHTML += '</ul>';

                $('#popup').html(contenidoHTML);
            },
            error: function(error) {
                console.error('Error en la solicitud AJAX', error);
            }
        });
    }
</script>

<script>
    function confirmar_borrado(grupo_id) {
        Swal.fire({
            title: 'CONFIRMAR',
            text: "Confirme que desea eliminar el Grupo con el ID:" + grupo_id,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = "/grupos/eliminar/" + grupo_id;
            }
        })
    }

    function cargando() {

        let timerInterval
        Swal.fire({
            title: 'Cargando...',
            html: 'Por favor espere.',
            timer: 50000,
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
</script>
<script>
    function cerrarDivEmergente() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("popup").style.display = "none";
    }


    window.addEventListener('carga_sweet_borrar', event => {
        //alert('Name updated to: ');
        Swal.fire({
            position: 'top-end',
            icon: 'danger',
            title: 'Grupo eliminado correctamente',
            showConfirmButton: false,
            timer: 10000
        })

    });

    function desactivar() {
        document.getElementById('select_ciclo_').enabled = false;
    }
</script>



</div>
