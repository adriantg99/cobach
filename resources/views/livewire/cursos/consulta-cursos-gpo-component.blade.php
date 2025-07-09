<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}


    <div class="card shadow">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Selección de Grupo</p>
        </div>

        <div class="card-body">
            <div class="row">
                @if ($grupos == null)
                    <div class="form-group pb-3" wire:ignore>
                        <label class="form-label">Plantel:</label>
                        <select class="form-control select-planteles" onchange="this.disabled = true;">
                            <option></option>
                            @if ($planteles)
                                @foreach ($planteles as $plantel_select)
                                    <option value="{{ $plantel_select->id }}"
                                        @unlessrole('control_escolar') @unlessrole('control_escolar_' . $plantel_select->abreviatura) @endunlessrole
                                    @endunlessrole>{{ $plantel_select->id }} - {{ $plantel_select->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group pb-3" wire:ignore>
                    <label class="form-label">Ciclo escolar:</label>
                    <select class="form-control select-ciclos_esc" onchange="this.disabled = true;">>
                        <option></option>
                        @if ($ciclos_esc)
                            @foreach ($ciclos_esc as $ciclo_esc_select)
                                <option value="{{ $ciclo_esc_select->id }}">{{ $ciclo_esc_select->id }} -
                                    {{ $ciclo_esc_select->nombre }} - {{ $ciclo_esc_select->abreviatura }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                <div class="form-group pb-3" wire:ignore>
                    <label class="form-label">Plantel:</label>
                    <input type="text" class="form-control" value="{{ $plantel_id }} - {{ $plantel->nombre }}"
                        disabled>
                </div>
                <div class="form-group pb-3" wire:ignore>
                    <label class="form-label">Ciclo escolar:</label>
                    <input type="text" class="form-control"
                        value="{{ $ciclo_esc->id }} - {{ $ciclo_esc->nombre }} - {{ $ciclo_esc->abreviatura }}"
                        disabled>
                </div>
                <div class="form-group pb-3">
                    <button class="btn btn-primary" wire:click="limpiabusqueda">Limpiar búsqueda</button>
                </div>
            @endif

            {{-- @if ($grupos) --}}
            <div class="form-group pb-3 {{ $grupos != null ? '' : ' d-none' }}" wire:ignore>
                <label class="form-label">Grupos encontrados:
                    {{ $grupos != null ? count($grupos) : '#NA' }}</label>
                <select class="form-control select-grupos">
                    <option></option>
                    @if ($grupos)
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">
                                {{ $grupo->nombre }}{{ $grupo->turno->abreviatura }} - (cursos:
                                {{ $grupo->cursos_count }}) {{-- (alumnos: {{$grupo->alumnos_count}}) --}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            @if ($gpo_id)
                <div class="form-group pb-3">

                    <button class="btn btn-success" wire:click="$toggle('bool_consultar_curso')">Consultar
                        Cursos</button>
                    <button class="btn btn-info" wire:click="$toggle('bool_consultar_alumno')">Consultar
                        Alumno</button>
                    <table>
                        <tr>
                            <td>
                                <form method="post" action="{{ route('imprime_calif_grupo_p1', $gpo_id) }}">
                                    @csrf

                                    <button class="btn btn-warning" type="submit">Imprime Calificaciones
                                        P1</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="{{ route('imprime_calif_grupo_p2', $gpo_id) }}">
                                    @csrf

                                    <button class="btn btn-warning" type="submit">Imprime Calificaciones
                                        P2</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="{{ route('imprime_calif_grupo_p3', $gpo_id) }}">
                                    @csrf

                                    <button class="btn btn-warning" type="submit">Imprime Calificaciones
                                        P3</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="{{ route('imprime_calif_concentrado', $gpo_id) }}">
                                    @csrf

                                    <button class="btn btn-warning" type="submit">Imprime Calificaciones
                                        concentrados</button>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            @endif
            {{-- @endif --}}

        </div>
    </div>
</div>

{{-- CONSULTA DE CURSOS --}}
@if ($bool_consultar_curso)
    @php
        $cursos = $grupo_seleccionado->cursos;
    @endphp
    <div class="card shadow">
        <div class="card-header">
            <p class="text-primary m-0 fw-bold">Cursos: {{ count($cursos) }} - del Grupo:
                {{ $grupo_seleccionado->nombre }}{{ $grupo_seleccionado->turno->abreviatura }}</p>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        {{-- <th>Nombre</th> --}}
                        <th>Cve Asign</th>
                        <th>Asignatura</th>

                    </tr>
                </thead>
                <tbody>
                    @if ($cursos)
                        @foreach ($cursos as $curso)
                            <tr>
                                <td>{{ $curso->id }}</td>
                                <td>{{ $curso->asignatura->clave }}</td>
                                <td>{{ $curso->asignatura->nombre }}</td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endif

@if ($bool_consultar_alumno)

    <div class="card shadow">
        <div class="card-header">
            <p class="text-primary m-0 fw-bold">Alumnos del Grupo: {{ $grupo_seleccionado->nombre }} -
                {{ $grupo_seleccionado->descripcion }}: ({{ Count($alumnos) }})
                @if ($alumnos)
                    <button class="btn btn-light btn-sm" wire:click="exportarExcel()">Exportar a Excel</button>
                    <button class="btn btn-info btn-sm"
                        onclick="window.open('{{ route('adminalumnos.generarboletas', ['alumno_id' => 0, 'ciclo_esc' => $ciclo_esc_id, 'plantel_id' => $plantel_id, 'grupo_id' => $gpo_id]) }}', '_blank')">
                        Imprimir boletas grupales
                    </button>
                    <button class="btn btn-info btn-sm"
                        onclick="window.open('{{ route('adminalumnos.kardex.reporte', ['alumno_id' => 0, 'grupo_id' => $gpo_id]) }}', '_blank')">
                        Imprimir kardex grupales
                    </button>
                    <button class="btn btn-info btn-sm"
                        onclick="window.open('{{ route('adminalumnos.generarConstancias', ['user_id' => 0, 'grupo_id' => $gpo_id]) }}', '_blank')">
                        Constancias grupales
                    </button>
                @endif
            </p>

        </div>
        @livewire('cursos.omitidos.alumno-boton-component')
        <div class="card-body p-0">
            <table class="table table-sm" id="miTabla">
                <thead>
                    <tr>
                        {{-- <th style="width: 10px">#</th> --}}
                        {{-- <th>Nombre</th> --}}
                        <th>Expediente</th>
                        <th>Apellido-Nombre</th>
                            <th>Configuración</th>

                        <th>Cursos</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($alumnos)
                        @foreach ($alumnos as $alumno)
                            <tr>
                                <td>{{ $alumno->noexpediente }}</td>
                                <td>{{ $alumno->apellidos }} {{ $alumno->nombre }}</td>
                                @if ($ciclo_esc->activo == '1')
                                    <td>
                                        @can('promocion-borrar')
                                            @can('grupo-eliminar-alumno')
                                                <button class="btn btn-danger btn-sm" value="{{ $alumno->alumno_id }}"
                                                    onclick="confirmar_borrado('{{ $alumno->alumno_id }}', '{{ $alumno->noexpediente }}', '{{ $gpo_id }}');">Eliminar</button>
                                            @endcan

                                       
                                        @endcan
                                        <button class="btn btn-info btn-sm" value="{{ $alumno->alumno_id }}"
                                            onclick="window.open('{{ route('adminalumnos.generarboletas', ['alumno_id' => $alumno->alumno_id, 'ciclo_esc' => $ciclo_esc_id]) }}', '_blank')">
                                            Imprimir boleta Alumno
                                        </button>
                                    </td>
                                @else
                                    <td>
                                        @livewire('cursos.omitidos.alumno-boton-component')
                                        <button class="btn btn-info btn-sm" value="{{ $alumno->alumno_id }}"
                                            onclick="window.open('{{ route('adminalumnos.generarboletas', ['alumno_id' => $alumno->alumno_id, 'ciclo_esc' => $ciclo_esc_id]) }}', '_blank')">
                                            Imprimir boleta Alumno
                                        </button>
                                    </td>
                                @endif
                                <td align="center">
                                    @php
                                        $alu_m = App\Models\Adminalumnos\AlumnoModel::find($alumno->id);
                                        $csos = $alu_m->cursos_del_grupo($gpo_id, $alumno->id);
                                    @endphp
                                    @if ($csos)
                                        <button
                                            class="{{ count($grupo_seleccionado->cursos) == count($csos) ? 'btn btn-success btn-sm' : 'btn btn-warning btn-sm' }}"
                                            wire:click="$emitTo('cursos.omitidos.alumno-boton-component','muestra-modal','{{ $alumno->id }}',{{ $gpo_id }})">{{ count($csos) }}</button>
                                    @else
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endif


</div>

{{-- SECCION SCRIPTS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}
@section('js_post')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select-planteles').select2();
        $('.select-planteles').on('change', function() {
            @this.set('plantel_id', this.value);
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select-ciclos_esc').select2();
        $('.select-ciclos_esc').on('change', function() {
            @this.set('ciclo_esc_id', this.value);
        });

    });
</script>



<script>
    window.addEventListener('cargar_select2_grupo', event => {
        //alert('Name updated to: ');
        $(document).ready(function() {
            $('.select-grupos').select2();
            $('.select-grupos').on('change', function() {
                @this.set('gpo_id', this.value);
                //@this.@render();
            });

        });
    })
</script>

<script>
    function confirmar_borrado(alumno_id, expediente, gpo_id) {
        Swal.fire({
            title: 'Baja del alumno',
            text: "Confirme que desea eliminar al alumno con el expediente: " + expediente +
                ". Tenga en cuenta que también se ELIMINARÁN las calificaciones del alumno. ESTA ACCIÓN NO PUEDE DESHACERSE.",
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
                // Enviar el motivo junto con el id del alumno y el grupo
                Livewire.emit('borrar_alumno', alumno_id, gpo_id, motivo);
            }
        })
    }
</script>


<script>
    function confirme(clave, expediente, c_id) {
        Swal.fire({
            title: "CONFIRME",
            text: "Confirme que deseea eliminar el curso de la asignatura con clave: [" + clave +
                "], al alumno con expediente: " + expediente +
                ". Si da clik en aceptar se eliminará el curso y todas sus calificaciones asociadas. ESTA ACCIÓN NO SE PODRÁ DESHACER.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ACEPTAR. Eliminar el curso"
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('cursos.omitidos.alumno-boton-component', 'quita_asign', clave, expediente,
                    c_id);
                /*
                Swal.fire({
                  title: "Deleted!",
                  text: "Your file has been deleted.",
                  icon: "success"
                });
                */
            }
        });
    }
</script>
@endsection
