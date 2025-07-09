{{-- ANA MOLINA 01/09/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
@section('title')
Tablero
@endsection
@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tablero</li>
          </ol>
        </nav>
</section>

@livewire('estadisticas.tablero.form-component')

@endsection
