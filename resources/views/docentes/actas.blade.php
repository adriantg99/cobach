@extends('layouts.dashboard-layout')
@section('title')
    Portal docente
@endsection

@section('content')
<section class="py-3">
    <link rel="stylesheet" href="">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Portal docente</li>
        </ol>
    </nav>
</section>
@hasallroles('docente')
<link rel="stylesheet" href="{{ asset('css/docentes.css')}}">
@livewire('docentes.actas-component')
@endhasallroles
@endsection