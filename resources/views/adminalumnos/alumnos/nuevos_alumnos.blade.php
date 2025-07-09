{{-- ANA MOLINA 22/08/2023 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Alumnos - Agregar
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/nuevos_alumnos_2_1.css')}}">
<link rel="stylesheet" href="{{ asset('css/promocion.css')}}">
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/adminalumnos/alumnos')}}">Alumnos</a></li>
          </ol>
        </nav>
</section>
<section class="py-4">
	<div class="col-sm-8">
        <a class="btn btn-info btn-sm" href="{{route('adminalumnos.alumnos.index')}}" title="Regresar">
           	<span class="mdi mdi-plus"></span> Regresar
        </a>
    </div>
</section>

{{-- @can('nuevos_alumnos') --}}

@livewire('adminalumnos.alumnos.nuevo-alumnos-component')

{{-- @endcan  --}}


@endsection
