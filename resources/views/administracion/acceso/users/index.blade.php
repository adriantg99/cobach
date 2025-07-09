@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Usuarios
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
          </ol>
        </nav>
</section>
<section class="py-4">
  @can('user-crear')
	  <div class="col-sm-8">
        <a class="btn btn-success btn-sm" href="{{route('user.agregar')}}" title="Nuevo Usuario">
           	<span class="mdi mdi-plus"></span> Agregar
        </a>
    </div>
  @endcan
</section>
@can('user-ver')
  @livewire('administracion.acceso.users.table-component')
@endcan
@endsection