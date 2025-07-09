{{-- ANA MOLINA 10/07/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Equivalencia y Revalidación - Editar
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/adminalumnos/equivalencia')}}">Equivalencia y Revalidación</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
</section>
<section class="py-4">
	<div class="col-sm-8">
        <a class="btn btn-info btn-sm" href="{{route('adminalumnos.equivalencia.index')}}" title="Regresar">
           	<span class="mdi mdi-plus"></span> Regresar
        </a>
    </div>
</section>
@livewire('adminalumnos.equivalencia.form-component',[
            'equivalencia_id'      =>  $equivalencia_id,
            'tipo'      =>  $tipo,
          ])

@endsection
