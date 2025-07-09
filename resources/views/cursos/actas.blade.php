@extends('layouts.dashboard-layout')

@section('title')
    Busqueda de actas
@endsection
@section('content')

<section class="py-3">
    <link rel="stylesheet" href="{{ asset('css/promocion.css')}}">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cursos</li>
            <li class="breadcrumb-item active" aria-current="page">Actas</li>
        </ol>
    </nav>
</section>

    @livewire('cursos.actas-especiales-component')

@endsection