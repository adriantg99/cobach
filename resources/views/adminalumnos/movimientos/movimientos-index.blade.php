@extends('layouts.dashboard-layout') <!-- Session Status --> {{-- secciones disponibles: title, content, css_pre, js_post --}}

@section('title')
    Alumno
@endsection

@section('content')
    <!-- Breadcrumbs Section -->
    <div class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb fw-bold">
                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-primary">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Traslado</li>
            </ol>
        </nav>
    </div>

    <!-- Main Content Section -->
    @livewire('adminalumnos.movimientos.movimientos-component', ['alumno_id' => $alumno_id])

@endsection