<section class="py-4">
    <div class="card">
        <div class="card-header">
            <label class="card-title">
                <strong>Asignaturas Registradas:</strong> {{ $asignaturas->total() }}
            </label>
            <br>
        </div>
        <div class="card-body">
            {{-- Campo de búsqueda global --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <input type="text" class="form-control" placeholder="Buscar por nombre, clave, núcleo o área de formación" wire:model.lazy="search">
                </div>
            </div>

            {{-- Tabla de Asignaturas --}}
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Asignatura</th>
                            <th>Área de Formación</th>
                            <th>Periodo</th>
                            <th>Clave</th>
                            <th>Activa</th>
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($asignaturas as $asignatura)
                            <tr>
                                <td>{{ $asignatura->id }}</td>
                                <td>{{ $asignatura->nombre }}</td>
                                <td>{{ $asignatura->area_formacion }}</td>
                                <td>{{ $asignatura->periodo }}</td>
                                <td>{{ $asignatura->clave }}</td>
                                <td>{{ $asignatura->activa ? 'Sí' : 'No' }}</td>
                                <td>{{ $asignatura->updated_at }}</td>
                                <td>
                                    @can('asignatura-editar')
                                        <button class="btn btn-info btn-sm"
                                            onclick="cargando(); location.href='{{ route('catalogos.asignaturas.editar', $asignatura->id) }}';">
                                            Editar
                                        </button>
                                    @endcan
                                    @can('asignatura-borrar')
                                        <button class="btn btn-warning btn-sm"
                                            onclick="confirmar_borrado({{ $asignatura->id }});">
                                            Eliminar
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Controles de paginación --}}
            <div class="d-flex justify-content-center">
                {{ $asignaturas->links() }}
            </div>
        </div>
    </div>
</section>
