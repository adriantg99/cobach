<div class="card shadow" id="principal">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card-header py-3">
        <p class="text-primary m-0 fw-bold">Listado de Actas del plantel: {{$plantel->nombre}}</p>
    </div>
        <div class="card-body table-responsive table-sm" wire:ignore>
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Alumno</th>
                        <th>Grupo - Turno</th>
                        <th>Tipo - Acta</th>
                        <td>Asignatura</td>
                        <td>Cal</td>
                        <td>Impresiones</td>
                        <th>Fecha Impresion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($actas_ext as $act)
                    <tr>
                        <td>{{$act->id}}</td>
                        <td>{{$act->alumno->apellidos}} {{$act->alumno->nombre}} <br>{{$act->alumno->noexpediente}}</td>
                        <td>{{$act->grupo}} - {{$act->turno}}</td>
                        <td>{{$act->motivo}}</td>
                        <td>{{$act->asignatura->nombre}}<br>{{$act->asignatura->clave}}</td>
                        <td>{{$act->calificacion}}{{$act->calif}}</td>
                        <td>{{$act->impresiones}}</td>
                        <td>{{$act->fecha_impresion}}</td>
                        <td><a class="btn btn-primary" href="{{ url('adminalumnos/alumnos/'.$act->id.'/acta')  }}" target="_blank">Imprimir</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('js_post')
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