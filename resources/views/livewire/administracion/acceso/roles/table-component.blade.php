<section class="py-4">
    {{-- Do your work, then step back. --}}
    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Roles Registrados:</strong> {{$count_roles}}</label><br>
            {{ $roles->links() }}
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($roles as $rol)
                    <tr>
                        <td>{{$rol->id}}</td>
                        <td>{{$rol->name}}</td>
                        <td>
                            @if(Auth()->user()->hasPermissionTo('rol-editar'))
                            <button class="btn btn-info btn-sm" 
                                onclick="location.href='{{route('rol.editar',$rol->id)}}';" 
                                >Editar</button>
                            @endif
                            @if(Auth()->user()->hasPermissionTo('rol-borrar'))
                            <button class="btn btn-warning btn-sm" 
                                onclick="confirmar_borrado({{$rol->id}});"
                                >Eliminar</button>
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
    function confirmar_borrado(rol_id)
    {
        
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el rol ID:"+rol_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="roles/eliminar/"+rol_id;
          }
        })

    }
</script>
@endsection