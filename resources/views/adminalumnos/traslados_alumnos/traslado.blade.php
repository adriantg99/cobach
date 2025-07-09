@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Datos de Alumno
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ingreso - Alumno</li>
          </ol>
        </nav>
</section>

@can('traslado-alumno')
  {{--@livewire('adminalumnos.traslados.traslados-component')--}}
  @livewire('adminalumnos.traslados.traslados-alumnos-component')
@endcan

@endsection

@section('js_post')

@endsection