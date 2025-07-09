<div>
    <div class="card shadow text-monospace font-monospace mb-3">

        <!-- Encabezado de la tarjeta -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title text-nowrap">Núcleos ({{ $nucleos_totales }})</h4>
            
        </div>

        <!-- Cuerpo de la tarjeta -->
        <div class="card-body">

            <!-- Tarjeta de Grupos: Muestra una tabla con los grupos históricos del alumno, incluyendo el ciclo, plantel, grupo, turno y número de cursos -->
            <div class="card table-responsive mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <label for="nombre_filtrado" class="form-label">Filtro({{ $count_nucleos }}):</label>
                    <input id="nombre_filtrado" name="nombre_filtrado" class="form-control me-2" wire:model.lazy="nombre_filtrado" type="text" placeholder="Filtrar por nombre">
                    <button id="boton_buscar" class="btn btn-info btn-sm">Buscar</button>
                </div>
                <table class="table-hover text-nowrap table-sm table-bordered table-striped m-0 table p-0 text-center align-middle">
                    <thead class="thead-light" style="background-color: #D3D3D3;">
                        <tr>
                            <th>Id</th>
                            <th>Núcleo</th>
                            <th>Área de formación</th>
                            <th>Consecutivo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $nucleos as $nucleo )
                            <tr>
                                <th>{{ $nucleo->id }}</th>
                                <td>{{ $nucleo->nombre }}</td>
                                <td>{{ $nucleo->areaformacion_id }} - {{ $nucleo->areaformacion ? $nucleo->areaformacion->nombre : '' }}</td>
                                <td>{{ $nucleo->clave_consecutivo }}</td>
                                <td>
                                    @can('nucleo-editar')
                                        <button id="editar" class="btn btn-warning btn-sm" onclick="Livewire.emitTo('catalogos.nucleos.form-component', 'eventoCargarFormulario', {{ $nucleo->id }})">
                                            Editar
                                        </button>
                                    @endcan
                                    @can('nucleo-borrar')
                                        <button class="btn btn-danger btn-sm" onclick="confirmar_borrado( {{ $nucleo->id }} );">
                                            Eliminar
                                        </button>
                                    @endcan
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

            <!-- Paginación -->
            <div>{{ $nucleos->links() }}</div>

            <!-- Botones -->
            @can('nucleo-crear')
                <button id="agregar" class="btn btn-success btn-sm" onclick="Livewire.emitTo('catalogos.nucleos.form-component', 'eventoCargarFormulario', null)">
                    Agregar
                </button>
            @endcan
        </div>
    </div>

    

    @section('js_post')

        <script>
            function confirmar_borrado( nucleo_id ) {
                Swal.fire({
                title: 'CONFIRMAR',
                text: "Confirme que desea eliminar el núcleo ID:" + nucleo_id,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, borrarlo'
                }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eventoEliminarNucleo', nucleo_id);
                }
                })
            }
        </script>

        <script>
            window.addEventListener('livewire:load', event => {
                console.log('Todos los elementos del DOM han sido cargados');
                document.getElementById('nombre_filtrado').focus();
            });
        </script>

        <script>
            window.addEventListener('eventoFocusNucleoNombre', event => {
                console.log('eventoFocusNucleoNombre');
                document.getElementById('nucleo_nombre').focus();
            });
        </script>

    @endsection

    <livewire:catalogos.nucleos.form-component key="{{ $user_id }}-{{ now() }}" :user_id="$user_id" />

</div>


