@extends('layouts.dashboard-layout')
@section('title')
    Apertura de captura docentes
@endsection

@section('content')
    <section class="py-3">
        <link rel="stylesheet" href="">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Apertura de captura</li>
            </ol>
        </nav>
    </section>
    @hasallroles('super_admin').

        @livewire('docentes.apertura-component')
    @endhasallroles
@endsection
