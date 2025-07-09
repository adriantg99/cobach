{{-- ANA MOLINA 19/09/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
@section('title')
Credencial del alumno
@endsection
@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Credencial del alumno</li>
          </ol>
        </nav>
</section>

@livewire('estadisticas.credenciales.form-component')

@endsection
