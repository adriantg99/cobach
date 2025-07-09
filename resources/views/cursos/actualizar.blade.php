    @extends('layouts.dashboard-layout')

    @section('title')
        Configuracion de Cursos
    @endsection
    @section('content')

    <section class="py-3">
        <link rel="stylesheet" href="{{ asset('css/promocion.css')}}">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cursos</li>
                <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
            </ol>
        </nav>
    </section>
    @can('cursos-editar')
        @livewire('cursos.actualiza-cursos-gpo-component')
    @endcan
    @endsection