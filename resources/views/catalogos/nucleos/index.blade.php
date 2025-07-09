@extends('layouts.dashboard-layout') <!-- Session Status --> {{-- secciones disponibles: title, content, css_pre, js_post --}}

@section('title')
    Núcleos
@endsection

@section('content')

    <!-- Breadcrumbs Section -->
    <div class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb fw-bold">
                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-primary">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Núcleos</li>
            </ol>
        </nav>
    </div>

    <!-- Main section -->
    @can('nucleo-ver')
        @livewire('catalogos.nucleos.table-component')
    @endcan

@endsection
