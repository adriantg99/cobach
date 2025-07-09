@extends('layouts.dashboard-layout') <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
    Ciclos Escolares - Editar
@endsection

@section('content')
    <section class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/catalogos/ciclosesc') }}">Ciclos Escolares</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
    </section>
    <section class="py-4">
        <div class="col-sm-8">
            <a class="btn btn-info btn-sm" href="{{ route('catalogos.ciclosesc.index') }}" title="Regresar">
                <span class="mdi mdi-plus"></span> Regresar
            </a>
        </div>
    </section>

    @can('ciclos_esc-editar')
        {{-- Cargar componente VUE pasando dato del id a editar --}}
        {{-- ciclo_esc_id="{{$ciclo_esc_id}}"/> --}}
        @livewire('catalogos.ciclos.ciclos-component')
    @endcan
@endsection
