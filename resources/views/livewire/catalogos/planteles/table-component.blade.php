<section class="py-4">
    {{-- Do your work, then step back. --}}
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
                <div class="col-6 col-sm-6">
                    <label for="role" class="form-label">Nombre:</label>
                    <input class="form-control" wire:model.lazy="nombre">
                    <button class="btn btn-info">Buscar</button>
                </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Planteles Registrados:</strong> {{$count_planteles}}</label><br>
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Plantel</th>
                        <th>ABR</th>
                        <th>CCT</th>
                        <th>Fecha Hr</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($planteles as $plantel)
                    <tr>
                        <td>{{$plantel->id}}</td>
                        <td>{{$plantel->nombre}}</td>
                        <td>{{$plantel->abreviatura}}</td>
                        <td>{{$plantel->cct}}</td>
                        <td>{{$plantel->updated_at}}</td>
                        <td>
                            {{-- LS Acceso a Modulo Aulas 2023-10-05 --}}
                            <button class="btn btn-info btn-sm" 
                                onclick="cargando(); location.href='{{route('catalogos.plantel.aulas',$plantel->id)}}';" 
                                >Aulas</button>
                            <button class="btn btn-success btn-sm" 
                                onclick="cargando(); location.href='{{route('catalogos.plantel.horas',$plantel->id)}}';" 
                                >Horas</button>
                            @can('plantel-editar')
                            <button class="btn btn-info btn-sm" 
                                onclick="cargando(); location.href='{{route('catalogos.planteles.editar',$plantel->id)}}';" 
                                >Editar</button>
                            @endcan
                            @can('plantel-borrar')
                            <button class="btn btn-warning btn-sm" 
                                onclick="confirmar_borrado({{$plantel->id}});"
                                >Eliminar</button>
                            @endcan
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



    function confirmar_borrado(plantel_id)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el plantel ID:"+plantel_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="/catalogos/planteles/eliminar/"+plantel_id;
          }
        })
    }

    function cargando(plantel_id)
    {

      let timerInterval
      Swal.fire({
        title: 'Cargando...',
        html: 'Por favor espere.',
        timer: 10000,
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

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            'paging': true,                    
            'language':{
                            "decimal": "",
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                            "infoPostFix": "",
                            "thousands": ",",
                            "lengthMenu": "Mostrar _MENU_ Entradas",
                            "loadingRecords": "Cargando...",
                            "processing": "Procesando...",
                            "search": "Buscar:",
                            "zeroRecords": "Sin resultados encontrados",
                            "paginate": {
                                "first": "Primero",
                                "last": "Ultimo",
                                "next": "Siguiente",
                                "previous": "Anterior"
                            }
                        },
                
        });
    });
</script>
@endsection
