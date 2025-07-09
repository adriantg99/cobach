<section class="py-4">
    <div class="card">
        <!-- Paginación -->
        {{ $bitacoras->links() }}
    </div>

    <!-- Filtro por usuario -->
    <div class="form-group">
        <label for="buscar_usuario">Buscar movimientos por usuario:</label>
        <select class="form-control" wire:model="buscar_usuario" id="buscar_usuario">
            <option value="">Seleccione un usuario</option>
            @foreach ($users as $usuario)
                <option value="{{ $usuario->id }}">
                    {{ $usuario->name }} --- {{ $usuario->email }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tabla de bitácoras -->
    <div class="card-body table-responsive table-sm">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Usuario / IP</th>
                    <th>Path:Method</th>
                    <th>Controller - Component / Function</th>
                    <th>Descripción</th>
                    <th>Fecha Hr</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bitacoras as $bitacora)
                    <tr>
                        <td>{{ $bitacora->id }}</td>
                        <td>{{ $bitacora->user->name ?? 'Desconocido' }} / {{ $bitacora->ip }}</td>
                        <td style="width: 25%">{{ $bitacora->path }}:{{ $bitacora->method }}</td>
                        <td>{{ $bitacora->controller }}{{ $bitacora->component }}<br>{{ $bitacora->function }}</td>
                        <td>{{ $bitacora->description }}</td>
                        <td>{{ $bitacora->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay movimientos para este usuario.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
