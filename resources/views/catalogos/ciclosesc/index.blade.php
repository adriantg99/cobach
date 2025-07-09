@extends('layouts.dashboard-layout') <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
    Ciclos Escolares
@endsection

@section('css_pre')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
    <section class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ciclos Escolares</li>
            </ol>
        </nav>
    </section>

    <section class="py-4">
        @can('ciclos_esc-crear')
            <div class="col-sm-8">
                <button class="btn btn-success btn-sm"
                    onclick="cargando(); window.location='{{ route('catalogos.ciclosesc.agregar') }}';">Agregar</button>
            </div>
        @endcan
    </section>

    <section class="py-4">
        <div class="card">
            
            <div class="card-body table-responsive table-sm">
                <table class="table table-hover text-nowrap" width="100%" id="dataTable">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Abreviatura</td>
                            <td>Nombre</td>
                            <td>Inicio Periodo</td>
                            <td>Final Periodo</td>
                            <td>Activo</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ciclos_esc as $ciclo_esc)
                            <tr>
                                <td>{{ $ciclo_esc->id }}</td>
                                <td>{{ $ciclo_esc->abreviatura }}</td>
                                <td>{{ $ciclo_esc->nombre }}</td>
                                <td>{{ substr($ciclo_esc->per_inicio, 0, 10) }}</td>
                                <td>{{ substr($ciclo_esc->per_final, 0, 10) }}</td>
                                <td>@if($ciclo_esc->activo) 1 @endif</td>
                                <td>
                                    @can('ciclos_esc-editar')
                                        <button class="btn btn-info btn-sm"
                                            onclick="cargando(); location.href='{{ route('catalogos.ciclosesc.editar', $ciclo_esc->id) }}';">Editar</button>
                                    @endcan
                                    @can('ciclos_esc-borrar')
                                        <button class="btn btn-warning btn-sm"
                                            onclick="confirmar_borrado({{ $ciclo_esc->id }});">Eliminar</button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

<script>


    function confirmar_borrado(ciclo_esc_id) {
        Swal.fire({
            title: 'CONFIRMAR',
            text: "Confirme que desea eliminar el ciclo escolar ID:" + ciclo_esc_id,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = "/catalogos/ciclosesc/eliminar/" + ciclo_esc_id;
            }
        })
    }

    function cargando(plantel_id) {

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



@section('js_post')
   <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                'paging': true,   
                'order': [[3, 'desc']], // Ordena por la columna 4 (per_inicio) descendente                 
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