{{--LUIS SPINDOLA JUN2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Alumnos - Impresión Reconocimientos Capacitación
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Alumnos</li>
          </ol>
        </nav>
</section>

@can('reconocimiento_capacitacion')
  @livewire('adminalumnos.reconocimiento-capacitacion.select-grupo-component')
@endcan
@endsection