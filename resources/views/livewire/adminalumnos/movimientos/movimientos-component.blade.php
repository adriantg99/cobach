<div class="card shadow text-monospace font-monospace">

    <!-- Encabezado de la tarjeta -->
    <div class="card-header">
        <h4 class="card-title text-nowrap">Cambio de plantel, grupo o turno</h4>
    </div>

    <!-- Cuerpo de la tarjeta -->
    <div class="card-body">

        <!-- Tarjeta de Alumno: Muestra información detallada del alumno, incluyendo su foto, nombre, expediente, correo, teléfono, ciclo escolar, plantel, grupo y turno -->
        <div class="card table-responsive mb-3">
            <div class="card-header">
                <h4 class="card-title">Alumno</h4>
            </div>
            <table class="table-hover text-nowrap table-sm table-bordered table-striped m-0 table p-0 text-center align-middle">
                <tbody>
                    <tr>
                        <td rowspan="8">
                            <div class="img-container m-3">
                                <img alt="Fotografía" class="logo rounded" height="270" src="data:image/png;base64,{{ $alumno_imagen['imagen'] }}">
                            </div>
                        </td>
                        <th class="text-end">Nombre:</th>
                        <td>{{ $alumno['nombre'] }} {{ $alumno['apellidos'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Expediente:</th>
                        <td>{{ $alumno['noexpediente'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Correo:</th>
                        <td>{{ $alumno['correo_institucional'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Teléfono:</th>
                        <td>
                            @if (empty($alumno['telefono']))
                                {{ $alumno['celular'] }}
                            @else
                                {{ $alumno['telefono'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end">Ciclo escolar:</th>
                        <td>{{ $ciclo_ultimo['nombre'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Plantel:</th>
                        <td>{{ $plantel_origen['nombre'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Grupo:</th>
                        <td>{{ current($grupos_historicos)['nombre'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Turno:</th>
                        <td>{{ current($grupos_historicos)['turno']['nombre'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tarjeta de Grupos: Muestra una tabla con los grupos históricos del alumno, incluyendo el ciclo, plantel, grupo, turno y número de cursos -->
        <div class="card table-responsive mb-3">
            <div class="card-header">
                <h4 class="card-title">Grupos</h4>
            </div>
            <table class="table-hover text-nowrap table-sm table-bordered table-striped m-0 table p-0 text-center align-middle">
                <thead class="thead-light" style="background-color: #D3D3D3;">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Ciclo</th>
                        <th scope="col">Periodo</th>
                        <th scope="col">Plantel</th>
                        <th scope="col">Grupo</th>
                        <th scope="col">Turno</th>
                        <th scope="col">Cursos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $grupos_historicos as $grupo_historico )
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>({{ $grupo_historico['ciclo']['id'] }}) {{ $grupo_historico['ciclo']['nombre'] }} {{ $grupo_historico['ciclo']['activo'] == 1 ? 'Activo' : '' }}</td>
                            <td>{{ $grupo_historico['ciclo']['per_inicio'] }} - {{ $grupo_historico['ciclo']['per_final'] }}</td>
                            <td>({{ $grupo_historico['plantel']['id'] }}) {{ $grupo_historico['plantel']['abreviatura'] }} - {{ $grupo_historico['plantel']['nombre'] }}</td>
                            <th>({{ $grupo_historico['id'] }}) {{ $grupo_historico['nombre'] }}</th>
                            <td>{{ $grupo_historico['turno']['id'] == 1 ? 'M' : 'V' }}</td>
                            <td>{{ $grupo_historico['cursos_count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tarjeta de Cursos: Muestra una tabla con los cursos del alumno, incluyendo la asignatura, grupos de origen, calificaciones y grupos de destino -->
        <div class="card table-responsive {{ !$alumno_inscrito ? 'd-none' : '' }}">
            <div class="card-header">
                <h4 class="card-title">Cursos</h4>
            </div>
            <table class="table-hover text-nowrap table-sm table-bordered table-striped m-0 table p-0 text-center align-middle">
                <thead class="thead-light" style="background-color: #D3D3D3;">
                    <tr class="align-middle">
                        <th scope="col">No.</th>
                        <th scope="col">Asignatura</th>
                        <th scope="col">Curso</th>
                        <th scope="col" class="align-top">Grupos Origen
                            <div class="m-1 rounded bg-white p-1">({{ $plantel_origen['id'] }}) [{{ $plantel_origen['abreviatura'] }}] {{ $plantel_origen['nombre'] }}</div>
                            @foreach ( $grupos_origen as $grupo_origen )
                                <div class="m-1 rounded bg-white p-1">({{ $grupo_origen['id'] }}) {{ $grupo_origen['nombre'] }} {{ $grupo_origen['turno_id'] == 1 ? 'M' : 'V' }} {{ $grupo_origen['gpo_base'] == 1 ? 'BA' : 'NB' }}</div>
                            @endforeach
                        </th>
                        <th scope="col">P1</th>
                        <th scope="col">P2</th>
                        <th scope="col">P3</th>
                        <th scope="col">F</th>
                        <th scope="col">⇨</th>
                        <th scope="col" class="align-top">Grupos Destino
                            <select wire:model.live="plantel_destino_selected" class="form-control">
                                @foreach ( $planteles_destino as $plantel_destino )
                                    <option value="{{ $plantel_destino['id'] }}">({{ $plantel_destino['id'] }}) [{{ $plantel_destino['abreviatura'] }}] {{ $plantel_destino['nombre'] }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="grupos_destino_selected" class="form-control" multiple>
                                @foreach ( $grupos_destino as $grupo_destino )
                                    <option value="{{ $grupo_destino['id'] }}">({{ $grupo_destino['id'] }}) {{ $grupo_destino['nombre'] }} {{ $grupo_destino['turno_id'] == 1 ? 'M' : 'V' }} {{ $grupo_destino['gpo_base'] == 1 ? 'BA' : 'NB' }}</option>
                                @endforeach
                            </select>
                        </th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($cursos_origen as $curso_origen)
                        <tr class="align-middle">
                            <th>{{ $loop->iteration }}</th>
                            <th>{{ $curso_origen['asignatura']['clave'] }}</th>
                            <th>{{ $curso_origen['id'] }}</th>
                            <td class="text-wrap text-start">{{ $curso_origen['asignatura']['nombre'] }}</td>
                            <td>{{ $curso_origen['calificaciones']['P1']['calificacion'] ?? '' }}</td>
                            <td>{{ $curso_origen['calificaciones']['P2']['calificacion'] ?? '' }}</td>
                            <td>{{ $curso_origen['calificaciones']['P3']['calificacion'] ?? '' }}</td>
                            <td>{{ $curso_origen['calificaciones']['FINAL']['calificacion'] ?? '' }}</td>
                            <td clas="bg-dark text-white">⇨</td>
                            <td>
                                <select id="select_{{ $curso_origen[ 'id' ] }}" wire:model.live="cursos_destino_selected.{{ $curso_origen[ 'id' ] }}" class="form-control">
                                    <option selected value=null></option>
                                    @foreach ( $cursos_destino as $curso_destino )
                                        <option {{ in_array($curso_destino['id'], $cursos_destino_selected) ? 'disabled class=d-none' : '' }} value="{{ $curso_destino['id'] }}">&#123;G:{{ $curso_destino['grupo_id'] }}&#125; [A:{{ $curso_destino['asignatura']['clave'] }}] (C:{{ $curso_destino['id'] }}) {{ $curso_destino['asignatura']['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pie de página de la tarjeta: Muestra una sección de alertas y un botón para trasladar al alumno -->
    <div class="card-footer d-flex justify-content-between align-items-center">

        <!-- Sección de alertas -->
        <div>
            @if (session()->has('message'))
                <div class="alert alert-{{ session('alert-type') }}">{{ session('message') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- Botones -->
        <button class="btn btn-primary btn-sm {{ !$alumno_inscrito ? 'disabled bg-secondary text-white' : '' }} m-2" wire:click="trasladar" wire:loading.attr="disabled" wire:loading.class="bg-secondary text-white" wire:loading.remove>Trasladar</button>
        <span wire:target="trasladar" wire:loading wire:loading.remove.class="d-none" class="btn btn-secondary btn-sm m-2 d-none" >Trasladando...</span>

    </div>

    {{-- Código de depuración --}}

    {{-- Dump --}}{{-- <div>@dump( 'bytes: ' . strlen( serialize( $cursos_destino ) ) , 'array size: ' . sizeof( $cursos_destino ), 'cursos_destino: ', $cursos_destino )</div> --}}
    {{-- Console --}}{{-- <script>console.log('$grupos_destino: ', @json($this->grupos_destino) );</script> --}}

    {{-- Console --}}
    {{-- <script>
        window.addEventListener('logToConsole', event => {
            console.log(event.detail.message, event.detail.data);
        });
    </script> --}}

    {{--@if ($errors->any())
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

</div>
