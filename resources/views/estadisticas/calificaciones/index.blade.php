{{-- ANA MOLINA 19/02/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Estadisticas Calificaciones Escolar
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Estadísticas Calificaciones Escolar</li>
          </ol>
        </nav>
</section>

@can('estadisticas-ver')
  @livewire('estadisticas.calificaciones.table-component')
@endcan
@endsection
