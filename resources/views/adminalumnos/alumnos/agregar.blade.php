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
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/adminalumnos/alumnos')}}">Alumnos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
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

@can('alumno-crear')
  {{-- Cargar componente VUE sin pasar dato del id --}}


  <formalumno plantel_id="{{ $id_plantel }}"/>
@endcan

@endsection
