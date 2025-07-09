@extends('layouts.dashboard-layout')

@section('content')
    <section class="py-3 container mx-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reportes</li>
            </ol>
        </nav>
    </section>
    <section class="py-4">

    </section>
    @can('grupos-editar')
        @livewire('reportes.lista-asistencia-component')
    @endcan
@endsection
