<section class="py-4">
    {{-- Criterios de búsqueda --}}
    {{-- 
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
            <div class="col-6 col-sm-6">
                <label for="role" class="form-label">Nombre del alumno:</label>
                <input class="form-control" wire:model.lazy="alumno">
                <button class="btn btn-info">Buscar</button>
            </div>
        </div>
    </div>
 --}}
    <div class="card mt-3">
        <div class="card-header">
            <label class="card-title"><strong>Equivalencia y Revalidación Registrados:</strong> {{$count_equivalencia}}</label>
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table table-striped table-bordered" id="equivalenciasTable">
                <thead class="table" style="background-color: rgb(230, 230, 230)">
                    <tr>
                        <th>Id</th>
                        <th>Tipo</th>
                        <th>Plantel</th>
                        <th>Nombre del alumno</th>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Entidad Educativa</th>
                        <th>Autorizado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($equivalencia as $index => $equi)
                    <tr>
                        <td>{{$equi->id}}</td>
                        <td>{{ $equi->tipoer == 'E' ? 'EQUIVALENCIA' : 'REVALIDACION' }}</td>
                        <td>{{ $equi->nombre }}</td>
                        <td>{{$equi->alumno}}</td>
                        <td>{{$equi->folio}}</td>
                        <td>{{$equi->fecha}}</td>
                        <td>{{$equi->institucion}}</td>
                        <td>@if ($equi->fecha_aut)
                            SI
                        @endif</td>
                        <td>
                            @can('equivalencia-editar')
                            <button class="btn btn-info btn-sm"
                                onclick="cargando(); location.href='{{route('adminalumnos.equivalencia.editar',array(base64_encode($equi->id), base64_encode($equi->tipoer)))}}';">
                                Editar
                            </button>
                            @endcan
                            @can('equivalencia-borrar')
                            @if(!isset($equi->fecha_aut))
                            <button class="btn btn-warning btn-sm"
                                onclick="confirmar_borrado('{{$equi->id}}','{{$equi->tipoer}}');">
                                Eliminar
                            </button>
                            @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@section('js_post')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#equivalenciasTable').DataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": true, "targets": "_all" }
            ],
            "paging": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "lengthMenu": [5, 10, 15, 20, 50, 75]
        });
    });

    function confirmar_borrado(equivalencia_id, tipoer) {
        var encodedequi = btoa(equivalencia_id);
        var encodedtipo = btoa(tipoer);
        var msg = tipoer == 'E' ? "equivalencia" : "revalidación";

        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar la " + msg + " ID:" + equivalencia_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href = "/adminalumnos/equivalencia/eliminar/" + encodedequi + "/" + encodedtipo;
          }
        })
    }

    function cargando() {
        let timerInterval;
        Swal.fire({
            title: 'Cargando...',
            html: 'Por favor espere.',
            timer: 10000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                timerInterval = setInterval(() => {
                    const b = Swal.getHtmlContainer().querySelector('b');
                    b && (b.textContent = Swal.getTimerLeft());
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        });
    }
</script>
@endsection
