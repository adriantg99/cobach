@extends('layouts.dashboard-layout')

@section('title')
    Replicación de calificaciones
@endsection
@section('content')

<section class="py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Replicacion</li>
        </ol>
    </nav>
</section>
@hasallroles('super_admin')
@livewire('cursos.replicacion-component')  
@endhasallroles  
@endsection