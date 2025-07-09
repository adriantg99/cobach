{{-- ANA MOLINA 27/06/2023 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Áreas de Formación - Editar
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/catalogos/areasformacion')}}">Áreas de Formación</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
</section>
<section class="py-4">
	<div class="col-sm-8">
        <a class="btn btn-info btn-sm" href="{{route('catalogos.areasformacion.index')}}" title="Regresar">
           	<span class="mdi mdi-plus"></span> Regresar
        </a>
    </div>
</section>
@livewire('catalogos.areasformacion.form-component',[
            'areaformacion_id'      =>  $areaformacion_id,
          ])

@endsection
