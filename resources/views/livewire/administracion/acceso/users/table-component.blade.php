<section class="py-4">
    {{-- Do your work, then step back. --}}

    <div class="card">
        <div class="card-header">
            <label class="card-title">
                <strong>Usuarios Registrados:
                </strong> {{ $count_users }}
            </label>

            <div class="contenedor">

                <div class="buscador">
                    <input class="form-control" type="text" wire:model.debounce.300ms="search" placeholder="Buscar por nombre o correo">
                    
                  
                </div>
                {{ $users->links() }}


                <br>

            </div>

        </div>

        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Rol(es)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if (!empty($user->getRoleNames()))
                                    @foreach ($user->getRoleNames() as $rolNombre)
                                        <span class="badge bg-secondary"
                                            style="font-size:60%">{{ $rolNombre }}</span><br>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if (Auth()->user()->hasPermissionTo('user-editar') or
                                        Auth()->user()->id == 1)
                                    <button class="btn btn-info btn-sm"
                                        onclick="location.href='{{ route('user.editar', $user->id) }}';">Editar</button>
                                @endif
                                @if (Auth()->user()->hasPermissionTo('user-borrar'))
                                    <button class="btn btn-warning btn-sm"
                                        onclick="confirmar_borrado({{ $user->id }});">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    </div>


    @section('js_post')
        <script>
            function confirmar_borrado(user_id) {

                Swal.fire({
                    title: 'CONFIRMAR',
                    text: "Confirme que desea eliminar el usuario ID:" + user_id,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Si, borrarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "usuarios/eliminar/" + user_id;
                    }
                })

            }
        </script>
        

    @endsection
