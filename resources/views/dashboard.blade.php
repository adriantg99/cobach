@extends('layouts.dashboard-layout') <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('content')
    <h3 class="page-header mb-3">
        Bienvenido. <small></small>
    </h3>
    <br>
    <div class="row">
        @if (Auth()->user()->hasRole('super_admin'))
            @livewire('dashboard.usuarios-conectados-component')
        @endif
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif


    </div>
    @php
        use Carbon\Carbon;

        $fecha = Carbon::now();
        /*
@hasallroles('super_admin')
<button type="button" class="btn btn-secondary">
Descarga de calificaciones del ciclo activo
</button>
@endhasallroles*/
    @endphp
    @hasallroles('control_escolar')
        <div style="align-items: center">
            <a href="/explorador">
                <img src="{{ asset('images/buscar.png') }}" alt="Descripción de la imagen"
                    style="width: 50px; height: auto;">
            </a>
            <p>Explorador de certificados</p>
        </div>
    @endhasallroles

@endsection
