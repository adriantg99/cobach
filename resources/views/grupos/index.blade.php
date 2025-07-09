    @extends('layouts.dashboard-layout')

    @section('title')
        Configuracionde Grupos
    @endsection
    @section('content')

    <section class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Configuración de grupos</li>
            </ol>
        </nav>
    </section>

    @livewire('grupos.grupos')
    @endsection