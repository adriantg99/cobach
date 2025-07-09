@extends('layouts.dashboard-layout') <!-- Session Status -->
@section('content')
    <section class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Reportes</a></li>
            </ol>
        </nav>
    </section>
    <section class="py-4">



    </section>
    @if (str_contains(Auth()->user()->roles->pluck('name'), 'credenciales'))
    @livewire('reportes.planteles-fotos-component-credenciales')
    @endif

    @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
    @can('credenciales')
        @livewire('reportes.planteles-fotos-component')
    @endcan
    @endif
    


@endsection
