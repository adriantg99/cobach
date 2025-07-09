@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Grupos - Agregar
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/grupos/grupos')}}">Grupos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
          </ol>
        </nav>
</section>
<section class="py-4">
	<div class="col-sm-8">
        <a class="btn btn-info btn-sm" href="{{route('Grupos.crear.index')}}" title="Regresar">
           	<span class="mdi mdi-plus"></span> Regresar
        </a>
    </div>

</section>
@livewire('grupos.create')


@endsection

