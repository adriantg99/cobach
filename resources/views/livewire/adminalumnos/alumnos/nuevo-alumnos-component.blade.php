<div class="card shadow" id="principal">

    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="card shadow" id="principal" style="margin-bottom: 5%">

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Plantel</label>
                        <select class="form-select" name="plantel_seleccionado" id='plantel_seleccionado'
                            wire:model="plantel_seleccionado">
                            <option value="" selected>Seleccionar plantel</option>
                            @if ($plantel)
                                @foreach ($plantel as $planteles)
                                    <option value="{{ $planteles->id }}"
                                        @unlessrole('control_escolar') @unlessrole('control_escolar_' . $planteles->abreviatura) disabled @endunlessrole
                                    @endunlessrole>
                                    {{ $planteles->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            @if ($administrador == 1 && $plantel_seleccionado && $busca_grupos)
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <button
                        onclick="capacidades_grupos(@foreach ($plantel as $planteles)
                                        @if ($plantel_seleccionado == $planteles->id)
                                            '{{ $planteles->nombre }}' @endif @endforeach)"
                        class="btn btn-secondary ml-2">Capacidades de
                        Grupos</button>
                </div>
            @endif

        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <button wire:click="buscar_alumnos" wire:loading.attr="disabled" wire:loading.class="bg-secondary"
                wire:loading.remove class="btn btn-primary float-end">Buscar</button>
            <span wire:loading wire:target="buscar_alumnos">Buscando</span>
        </div>
    </div>

</div>


@if (!empty($alumnos_nuevos_plantel))
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-primary float-end" wire:click="descargar_listado"
                        wire:loading.attr="disabled" wire:loading.class="bg-secondary text-white"
                        wire:loading.remove>Descargar listado</button>
                    <span wire:loading wire:target="descargar_listado" class="text-secondary float-end m-2 d-none"
                        style="font-size: 0.875rem; line-height: 1.5;" wire:loading.remove.class="d-none">
                        Generando...
                    </span>

                    @if ($administrador == 1)

                        @if (!empty($alumnos_nuevos_plantel) || $ya_asignado)
                            <div class="row">
                                @if ($administrador==1)
                                <p>Número total de alumnos: {{ $alumnos_nuevos_plantel->count() }}. Inscritos: {{  $alumnos_nuevos_plantel->where('id_estatus', 1)->count() }}. 
                                    Turno por plantel: {{ $alumnos_nuevos_plantel->whereIn('turno_especial', ['1', '2'])->count() }}. Turno por administrador: {{ $alumnos_nuevos_plantel->whereIn('turno_especial', ['3', '4'])->count() }}
                                </p>
                                <p>
                                    Turno con coincidencia {{ $alumnos_nuevos_plantel->whereIn('turno_especial', ['5', '6'])->count() }}. Turno con diferencia: {{ $alumnos_nuevos_plantel->whereIn('turno_especial', ['7', '8'])->count() }}
                                </p>
                                <p>Total turnos especiales: {{ $alumnos_nuevos_plantel->whereIn('turno_especial', ['1', '2', '3','4','5','6','7','8','9', '10'])->count() }}.</p>
                                <p>Total turno especial Matutino:{{ $alumnos_nuevos_plantel->whereIn('turno_especial', ['1', '3','5','8'])->count() }}. Total turno especial Vespertino: {{ $alumnos_nuevos_plantel->whereIn('turno_especial', ['2', '4','6','7'])->count() }}. </p>
                                @endif
                                
                                @if (!empty($alumnos_nuevos_plantel) && !$ya_asignado && $fecha)
                                    <div class="col">
                                        <button class="btn btn-primary w-100" wire:click="asignar_alumnos"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="bg-secondary text-white" wire:loading.remove>Generar
                                            relación de alumnos en el plantel</button>

                                        <span wire:loading wire:target="asignar_alumnos"
                                            class="text-secondary float-end m-2 d-none"
                                            style="font-size: 0.875rem; line-height: 1.5;"
                                            wire:loading.remove.class="d-none">
                                            Generando...
                                        </span>
                                    </div>
                                @endif

                                @if ($ya_asignado)
                                    <div class="col">
                                        <button class="btn btn-primary w-100"
                                            wire:click="descargar_excel_nuevos_alumnos" wire:loading.attr="disabled"
                                            wire:loading.class="bg-secondary text-white"
                                            wire:loading.remove>Descargar relación en excel</button>
                                    </div>

                                    <div class="col">
                                        <button class="btn btn-primary w-100" {{-- wire:click="asignacion_final" --}}
                                            onclick="asignar_grupo_alumno()" wire:loading.attr="disabled"
                                            wire:loading.class="bg-secondary text-white" wire:loading.remove>Asignar
                                            relación de alumnos en el plantel</button>

                                        <span wire:loading wire:target="asignacion_final"
                                            class="text-secondary float-end m-2 d-none"
                                            style="font-size: 0.875rem; line-height: 1.5;"
                                            wire:loading.remove.class="d-none">
                                            Generando...
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($errores)
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: "{{ $errores }}",
                                });
                            </script>
                        @endif

                    @endif

                </div>

                <div class="card-body table-responsive table-sm">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>Turno especial</th>
                                <th style="width: 10%">Turno seleccionado</th>
                                <th>NoExpediente</th>
                                <th>Apellidos</th>
                                <th>Nombre</th>

                                @if ($administrador == 1)
                                    <th>Motivo</th>
                                    <th>Documento acredita motivo</th>
                                @else
                                @endif
                            </tr>
                        </thead>
                        {{-- Aqui iria el foreach --}}
                        @foreach ($alumnos_nuevos_plantel as $alumno)
                        
                            <tr @if ($administrador == 1) @if ($alumno->id_estatus != 1)
                            style="background-color: #fff3cd;" @endif
                                @endif >
                                <td>
                                    @if ($administrador == 1)
                                        @if ($alumno->id_estatus != 1)
                                            <select class="form-select" name=""
                                                id="finalizar_insc{{ $alumno->noexpediente }}"
                                                onchange="guardar_estatus('{{ $alumno->noexpediente }}')">
                                                <option value="0" selected>No inscrito</option>
                                                <option value="1">Inscrito</option>
                                            </select>
                                        @else
                                            <select class="form-select" name="turno_admin"
                                                id="turno_admin_{{ $alumno->noexpediente }}"
                                                onchange="guardar_admin('{{ $alumno->noexpediente }}')">
                                                <option value="0"
                                                    @if ($alumno->turno_especial > 1) selected @endif>Sin turno
                                                </option>
                                                <option value="1"
                                                    @if (in_array($alumno->turno_especial, ['1', '3', '5'])) selected @endif>Matutino
                                                </option>
                                                <option value="2"
                                                    @if (in_array($alumno->turno_especial, ['2', '4', '6'])) selected @endif>Vespertino
                                                </option>
                                                @if (in_array($alumno->turno_especial, ['7', '8', '9', '10']))
                                                    <option value="" selected>Discrepancia en turnos</option>
                                                @else
                                                @endif

                                            </select>
                                        @endif
                                    @else
                                        <label class="container">

                                            <input type="checkbox"
                                                @if ($administrador == 1) @if ($alumno->turno_especial != null)
                                                    checked @endif
                                            @else @if (in_array($alumno->turno_especial, ['1', '2', '5', '6', '7', '8', '9', '10'])) checked @endif
                                                @endif
                                            onchange='mostrar_modal("{{ $alumno->id }}", 
                                "{{ $alumno->noexpediente }}", "{{ $alumno->nombre }}", "{{ $alumno->apellidos }}",  "{{ $alumno->turno_especial }}");'
                                            class="select-alumno" data-alumno="{{ json_encode($alumno) }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    @endif

                                </td>
                                {{-- 1 Mat director
                                     2 Ves Director
                                     3 Mat Admin
                                     4 Ves Admin
                                     5 Mat director - admin
                                     6 Ves director - admin
                                     7 Mat director - Ves Admin
                                     8 Ves director - Mat admin 
                                     9 Mat director - Eliminado Admin
                                     10 Ves Director - Eliminado Admin --}}
                                <td>
                                    @if ($administrador == 1)
                                        @switch($alumno->turno_especial)
                                            @case('1')
                                                Matutino - Plantel
                                            @break

                                            @case('2')
                                                Vespertino - Plantel
                                            @break

                                            @case('3')
                                                Matutino - Administrador
                                            @break

                                            @case('4')
                                                Vespertino - Administrador
                                            @break

                                            @case('5')
                                                Matutino - Administrador y plantel
                                            @break

                                            @case('6')
                                                Vespertino - Administrador y plantel
                                            @break

                                            @case('7')
                                                Matutino - Plantel - Vespertino Administrador
                                            @break

                                            @case('8')
                                                Vespertino - Plantel - Matutino Administrador
                                            @break

                                            @case('9')
                                                Matutino - Plantel - Eliminado Administrador
                                            @break

                                            @case('10')
                                                Vespertino - Plantel - Eliminado Administrador
                                            @break

                                            @default
                                        @endswitch
                                    @else
                                        @switch($alumno->turno_especial)
                                            @case('1')
                                                Matutino
                                            @break

                                            @case('2')
                                                Vespertino
                                            @break

                                            @case('5')
                                                Matutino
                                            @break

                                            @case('6')
                                                Vespertino
                                            @break

                                            @case('7')
                                                Matutino
                                            @break

                                            @case('8')
                                                Vespertino
                                            @break

                                            @case('9')
                                                Matutino
                                            @break

                                            @case('10')
                                                Vespertino
                                            @break

                                            @default
                                        @endswitch
                                    @endif
                                </td>
                                <td @if ($administrador == 1) onclick="cambio_plantel('{{ $alumno->noexpediente }}', '{{ $alumno->nombre }}', 
                                    '{{ $alumno->apellidos }}', @foreach ($plantel as $planteles)
                                        @if ($plantel_seleccionado == $planteles->id)
                                            '{{ $planteles->nombre }}' @endif
                                    @endforeach);" @endif>
                                    {{ $alumno->noexpediente }}

                                </td>
                                <td>

                                    {{ $alumno->apellidos }}

                                </td>
                                <td>{{ $alumno->nombre }}</td>
                                @if ($administrador == 1)
                                    <td>
                                        {{ $alumno->observaciones }}
                                    </td>
                                    <td>
                                        {{-- Aqui va para poner el documento de acreditación del turno especial --}}
                                        @if (!is_null($alumno->turno_especial) && in_array($alumno->turno_especial, ['1', '2', '5', '6', '7', '8', '9', '10']))
                                            <a href="{{ route('descargar.evidencia', ['alumno_id' => $alumno->id]) }}"
                                                class="btn btn-primary">Descargar PDF</a>
                                            <br>
                                        @endif

                                        @if (!is_null($alumno->turno_especial) && in_array($alumno->turno_especial, ['3', '4', '5', '6', '7', '8', '9']))
                                            @if (!is_null($grupos_plantel))
                                                <select name="grupo_especial"
                                                    onchange="guardar_grupo('{{ $alumno->noexpediente }}')"
                                                    class="form-select"
                                                    id="grupo_especial_{{ $alumno->noexpediente }}">
                                                    <option value="">Seleccionar Grupo</option>
                                                    @foreach ($grupos_plantel as $grupos_creados)
                                                        @if ($grupos_creados->id == $alumno->grupo_id)
                                                            <option value="{{ $grupos_creados->id }}"
                                                                @if ($grupos_creados->id == $alumno->grupo_id) selected @endif>
                                                                {{ $grupos_creados->nombre }}-- @if ($grupos_creados->turno_id == '1')
                                                                    Matutino
                                                                @else
                                                                    Vespertino
                                                                @endif
                                                                @if ($grupos_creados->descripcion == 'turno_especial')
                                                                    -- Turno_especial
                                                                @else
                                                                @endif
                                                        @endif
                                                        @if (in_array($alumno->turno_especial, ['1', '3', '5', '7']) && $grupos_creados->turno_id == '2')
                                                            @continue
                                                        @endif
                                                        @if (in_array($alumno->turno_especial, ['2', '4', '6', '8']) && $grupos_creados->turno_id == '1')
                                                            @continue
                                                        @endif

                                                        @if ($grupos_creados->descripcion == 'turno_especial')
                                                            @continue
                                                        @endif
                                                        <option value="{{ $grupos_creados->id }}"
                                                            @if ($grupos_creados->id == $alumno->grupo_id) selected @endif>
                                                            {{ $grupos_creados->nombre }}-- @if ($grupos_creados->turno_id == '1')
                                                                Matutino
                                                            @else
                                                                Vespertino
                                                            @endif
                                                            @if ($grupos_creados->descripcion == 'turno_especial')
                                                                -- Turno_especial
                                                            @else
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                    @if (!is_null($alumno->grupo_id))
                                                        <option value="-1">Quitar grupo</option>
                                                    @endif
                                                </select>
                                            @endif

                                        @endif

                                    </td>
                                @else
                                @endif
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

<div id="overlay" wire:ignore></div>

<div id="popup2" wire:ignore>
    <button id="cerrar" onclick="cerrarDivEmergente()">X</button>

    <h2 style="text-align: center;">Alumno para turno especial</h2>
    <div>
        <!-- Aquí se mostrarán los detalles del alumno -->
        <table style="width: 100%;" class="table_modal">
            <tr>
                <td style="width: 33%; text-align: center;">Datos alumno</td>
                <td style="width: 33%; text-align: center;">Seleccionar turno</td>
                @if ($administrador == 1)
                @else
                    <td style="width: 33%; text-align: center;">Motivo</td>
                @endif
            </tr>
            <tr>
                <td style="padding-right: 2%">
                    <table style="border: 1px; width: 100%">
                        <tr>
                            <td>
                                Expediente

                            </td>
                            <td style="text-align: center">
                                Nombre
                            </td>
                        </tr>
                        <tr class="">
                            <td>
                                <span id="nombre_materia"></span>

                            </td>
                            <td style="text-align: right;">
                                <span id="clave_materia"></span>
                                <input type="text" hidden wire:model="alumno_seleccionado"
                                    name="alumno_seleccionado" id="alumno_id">

                                <input type="text" hidden id ="id_tipo">
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <select class="form-select" name="turno_id" id="turno_id"
                        style="display: inline-block; margin-left: 10px">
                        <option value="0" selected>Seleccione turno</option>
                        <option value="1">Matutino</option>
                        <option value="2">Vespertino</option>
                    </select>
                </td>
                <td>
                    <select class="form-select" name="motivo_id" id="motivo_id"
                        style="display: inline-block; margin-left: 10px" onchange="mostrarCampoMotivo(this)">

                        <option value="0" selected>Seleccione motivo</option>
                        <option value="Atleta de alto rendimiento">Atleta de alto rendimiento</option>
                        <option value="Discapacidad">Discapacidad</option>
                        <option value="Hermano">Hermano en el plantel</option>
                        <option value="Ubicacion">Ubicación Foranea</option>
                        <option value="otro">Otro</option>

                    </select>
                </td>
            </tr>
            <tr style="padding-top: 5px">
                <td colspan="3">
                    <input type="text" class="form-control hidden" id="campo_otro_motivo"
                        placeholder="Escriba el motivo">
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <label for="file">Documento para acreditar el turno especial</label>
                    <input type="file" id="file" class="form-control" wire:model="file"
                        accept=".pdf, .jpg, .png, .jpeg">
                    <span id="file-error" class="text-danger"></span>
                    <!-- Asegúrate de que este elemento exista -->
                </td>


            </tr>
        </table>
        <div id="resultado_busqueda"></div>
        <div class="mt-3">
            <button id="boton_guardar" onclick="guardar()" class="btn btn-primary float-end">Guardar</button>
        </div>
    </div>
</div>

@if ($administrador == 1)
    <div id="cambio" wire:ignore>
        <button id="cerrar" onclick="cerrarDivEmergente()">X</button>

        <div>
            <h2 style="text-align: center;">Alumno para cambio de plantel</h2>

            <h2 style="text-align: center;">Plantel actual <span id="plantel_nombre"></span>
            </h2>


            <table style="width: 100%;" class="table_modal">
                <tr>
                    <td>
                        NoExpediente
                    </td>
                    <td>
                        Nombre
                    </td>
                    <td>
                        Apellidos
                    </td>
                    <td>
                        Nuevo plantel
                    </td>
                </tr>
                <tr>
                    <td><span id="no_expediente"></span></td>
                    <td><span id="nombre_alumno"></span></td>
                    <td> <span id="apellidos"></span></td>
                    <td>
                        <select class="form-select" name="plantel_nuevo" id="plantel_nuevo">
                            @if ($plantel)
                                @foreach ($plantel as $planteles)
                                    <option value="{{ $planteles->id }}"
                                        @unlessrole('control_escolar') @unlessrole('control_escolar_' . $planteles->abreviatura) disabled @endunlessrole
                                    @endunlessrole>
                                    {{ $planteles->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </td>
            </tr>
        </table>
        <div class="mt-3">
            <button id="boton_guardar" onclick="guardar_cambio_plantel()"
                class="btn btn-primary float-end">Guardar cambio de plantel</button>
        </div>
    </div>
@endif


</div>

@if ($administrador == 1)
<div id="capacidad_grupos">
<button id="cerrar" onclick="cerrarDivEmergente()">X</button>

<div>
    <h2 style="text-align: center;">Capacidades de los grupos</h2>

    <h2 style="text-align: center;">Plantel actual <span id="plantel_nombre_capacidad"></span>
    </h2>


    <table style="width: 100%;" class="table_modal">
        <tr>
            <thead>
                <th>
                    Grupo
                </th>
                <th>
                    Matutino
                </th>
                <th>
                    Vespertino
                </th>
            </thead>
        </tr>
        @if ($grupos_plantel)
        @php
            $gruposAgrupados = [];
    
            foreach ($grupos_plantel as $grupo) {
                $gruposAgrupados[$grupo->nombre][$grupo->turno_id] = $grupo;
            }
        @endphp
        <input type="text" id="grupos_ids" hidden name="grupos_ids">
    
        @foreach ($gruposAgrupados as $nombreGrupo => $turnos)
            <tr>
                <td>{{ $nombreGrupo }}</td>
                <td>
                    @if (isset($turnos[1])) <!-- Matutino -->
                        <input type="number" class="form-control"
                            oninput="formatInput(this); updateGrupoIds({{ $turnos[1]->id }});"
                            value="{{ $turnos[1]->capacidad }}" id="{{ $turnos[1]->id }}"
                            min="0" max="60" step="1" class="input-small"
                            maxlength="100" {{-- wire:change="cambio_capacidad({{ $turnos[1]->id }}, $event.target.value)" --}}>
                        <span>Cursos del grupo: {{ $turnos[1]->total_coincidencias }}</span>
                    @endif
                </td>
                <td>
                    @if (isset($turnos[2])) <!-- Vespertino -->
                        <input type="number" class="form-control"
                            oninput="formatInput(this); updateGrupoIds({{ $turnos[2]->id }});"
                            value="{{ $turnos[2]->capacidad }}" id="{{ $turnos[2]->id }}"
                            min="0" max="60" step="1" class="input-small"
                            maxlength="100" {{-- wire:change="cambio_capacidad({{ $turnos[2]->id }}, $event.target.value)" --}}>
                        <span>Cursos del grupo: {{ $turnos[2]->total_coincidencias }}</span>
                    @endif
                </td>
            </tr>
        @endforeach
    @endif

    </table>
    <div class="mt-3">
        <button id="boton_guardar" onclick="guardar_cambios_capacidades()"
            class="btn btn-primary float-end">Guardar capacidades del plantel</button>
    </div>
</div>
@endif

</div>



</div>
@section('js_post')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('fileSaved', function() {
            Swal.fire({
                title: 'Datos guardados correctamente',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });

            cerrarDivEmergente();
        });
    });



    function guardar_cambios_capacidades() {

        let grupos_id = document.getElementById("grupos_ids").value;

        // Divide la cadena en un array usando la coma como separador
        let arrayDeIds = grupos_id.split(',');

        // Convierte cada elemento del array a un número entero
        let arrayDeIdsNumerico = arrayDeIds.map(id => parseInt(id, 10));

        let array_id_capacidad;
        // Itera sobre el array de números e imprime cada número
        arrayDeIdsNumerico.forEach(function(numero) {
            Livewire.emit('cambio_capacidad', numero, document.getElementById(numero).value);
        });

        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'El cambio de plantel se guardó correctamente.',
        });
        Livewire.emit('emitir_busqueda');

        cerrarDivEmergente();

    }


    document.addEventListener('livewire:load', function() {
        Livewire.on('mostrar-error', event => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: event.mensaje,
            });
        });


        Livewire.on('sin-error', event => {
            Swal.fire({
                icon: 'success',
                title: 'Todo Bien',
                text: event.mensaje,
            });
        });
    });

    function guardar_admin(noexpediente) {
        var select_turno = document.getElementById("turno_admin_" + noexpediente).value;
        Livewire.emit('nuevos_alumnos_admin', noexpediente, select_turno);
    }

    function guardar_grupo(noexpediente) {
        var grupo_seleccionado = document.getElementById("grupo_especial_" + noexpediente).value;
        if (grupo_seleccionado == "-1") {
            Swal.fire({
                title: "Se eliminara al alumno del grupo especial",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, continuar"
            }).then((result) => {
                if (result.isConfirmed) {
                    //Aqui cambiar para cuando este la ficha
                    Livewire.emit('guardar_grupo_especial', noexpediente, grupo_seleccionado);

                    //window.location.href = '{{ route('ingreso_alumno.index') }}';
                }
            });
        } else {
            Livewire.emit('guardar_grupo_especial', noexpediente, grupo_seleccionado);
        }
    }

    function capacidades_grupos(plantel) {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("capacidad_grupos").style.display = "block"

        document.getElementById("plantel_nombre_capacidad").innerHTML = plantel;

    }

    function mostrar_modal(alumno_id, noexpediente, nombre, apellidos, turno_especial) {

        document.getElementById("overlay").style.display = "block";
        document.getElementById("popup2").style.display = "block"
        //document.getElementById("boton_guardar").style.display = "block";
        document.getElementById("nombre_materia").innerHTML = noexpediente;
        document.getElementById("clave_materia").innerHTML = nombre + " " + apellidos;
        document.getElementById("alumno_id").value = alumno_id;

        if (turno_especial != null) {
            //document.getElementById("boton_guardar").style.display = "none";
            document.getElementById("boton_actualizar").style.display = "block";
        }
    }

    function cambio_plantel(expediente, nombre, apellidos, plantel) {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("cambio").style.display = "block"

        document.getElementById("no_expediente").innerHTML = expediente;
        document.getElementById("nombre_alumno").innerHTML = nombre;
        document.getElementById("apellidos").innerHTML = apellidos;
        document.getElementById("plantel_nombre").innerHTML = plantel;

    }

    function updateGrupoIds(grupoId) {
        let gruposIds = document.getElementById('grupos_ids').value;
        let gruposArray = gruposIds ? gruposIds.split(',') : [];

        if (!gruposArray.includes(grupoId.toString())) {
            gruposArray.push(grupoId);
        }

        document.getElementById('grupos_ids').value = gruposArray.join(',');
    }

    function guardar_cambio_plantel() {
        var noexpediente = document.getElementById("no_expediente").innerHTML;
        var plantel_id = document.getElementById("plantel_nuevo").value;

        Livewire.emit('guardar_cambio_plantel', noexpediente, plantel_id);

        // Escuchar el evento emitido desde Livewire con el resultado
        Livewire.on('resultadoGuardarCambioPlantel', (resultado) => {
            if (resultado === 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'El cambio de plantel se guardó correctamente.',
                });
                cerrarDivEmergente();

            } else if (resultado === 2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ya cuenta con turno especial en el plantel.',
                });
                cerrarDivEmergente();

            } else if (resultado === 3) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'YA ESTA EN UN GRUPO, QUITARLO DEL GRUPO ANTES DE HACER CUALQUIER CAMBIO',
                });
                cerrarDivEmergente();

            }
        });

    }

    function guardar_estatus(noexpediente) {
        const selectElement = document.getElementById('finalizar_insc' + noexpediente);
        const selectedValue = selectElement.value;
        if (selectedValue == "1") {
            Livewire.emit('guardar_estatus', noexpediente);
        }

    }

    function asignar_grupo_alumno() {

        Swal.fire({
            title: 'Asignación de grupo',
            text: "Al presionar aceptar los alumnos serán asignados como aparece en el excel. ¿Esta seguro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, asignar',
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('asignacion_final').then(
                    response => {
                        if (response == 1) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Alumnos asignados correctamente',
                                showConfirmButton: false,
                                timer: 10000
                            });
                            cerrarDivEmergente_calif();
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error', // Cambié 'danger' a 'error'
                                title: 'Hubo un error con las asignaciones.',
                                showConfirmButton: false,
                                timer: 10000
                            });
                        }
                    });
            }
        })
    }

    function guardar() {
        var no_expediente = document.getElementById("nombre_materia").innerHTML;


        var alumno_id = document.getElementById("alumno_id").value;
        var select_turno = document.getElementById("turno_id").value;
        var select_motivo = document.getElementById("motivo_id").value;
        var motivo_enviado = "";
        var validador = 0;
        if (select_turno == "0") {

        } else {
            if (select_motivo === 'otro') {
                motivo_enviado = document.getElementById('campo_otro_motivo').value;
            } else {
                motivo_enviado = select_motivo;
            }

            var fileInput = document.getElementById('file');
            var file = fileInput.files[0];
            var fileError = document.getElementById('file-error');

            if (!file) {
                fileError.textContent = 'Por favor, seleccione un archivo para cargar.';
                return;
            } else {
                fileError.textContent = '';
                validador += 1;
            }

            if (motivo_enviado == "") {

                fileError.textContent = 'Por favor, ingrese un motivo.';
                return;
            } else {
                fileError.textContent = '';
                validador += 1;
            }

            if (select_turno == "") {

                fileError.textContent = 'Por favor, ingrese un turno.';
                return;

            } else {
                fileError.textContent = '';
                validador += 1;
            }

            if (validador == 3) {
                Livewire.emit('saveFile', no_expediente, select_turno, motivo_enviado);
            } else {
                fileError.textContent = 'Por favor, llenar todos los campos.';
            }
            // Enviar los datos a Livewire

        }

    }


    function cerrarDivEmergente() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("popup2").style.display = "none";
        document.getElementById("cambio").style.display = "none";
        document.getElementById("capacidad_grupos").style.display = "none";

        document.getElementById("file").value = "";
        document.getElementById("nombre_materia").innerHTML = "";
        document.getElementById("motivo_id").value = "0";
        document.getElementById("turno_id").value = "0";

        document.getElementById("no_expediente").value = "";
        document.getElementById("nombre_alumno").value = "";
        document.getElementById("apellidos").value = "";
        document.getElementById("plantel_nombre").value = "";
        document.getElementById("plantel_nuevo").value = "0";
        $('#clave_asignatura').val(null).trigger('change');

    }

    function mostrarCampoMotivo(select) {
        var campoMotivo = document.getElementById('campo_otro_motivo');
        if (select.value === 'otro') {
            campoMotivo.classList.remove('hidden');
            campoMotivo.classList.add('visible');
        } else {
            campoMotivo.classList.remove('visible');
            campoMotivo.classList.add('hidden');
        }
    }


    function formatInput(input) {
        // Elimina cualquier caracter que no sea un número
        input.value = input.value.replace(/\D/g, '');

        // Limita la longitud del valor a 3 dígitos
        if (input.value.length > 2) {
            input.value = input.value.slice(0, 2);
        }
        if (parseInt(input.value) > 60) {
            input.value = '60';
        }
    }
</script>
@endsection
