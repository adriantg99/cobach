{{-- LUIS SPINDOLA 17MZO2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Alumnos - Nuevos alumnos
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="">Nuevos alumnos</a></li>
            
          </ol>
        </nav>
</section>
{{--
<section class="py-4">
	<div class="col-sm-8">
        <a class="btn btn-info btn-sm" href="{{route('adminalumnos.alumnos.index')}}" title="Regresar">
           	<span class="mdi mdi-plus"></span> Regresar
        </a>
    </div> 
</section>
--}}
@can('nuevos_alumnos')
@livewire('adminalumnos.alumnos.importar-alumnos-component')
  
@endcan
  {{-- Cargar componente VUE pasando dato del id a editar --}}
  {{--@livewire('adminalumnos.alumnos.editar') --}}


@endsection
