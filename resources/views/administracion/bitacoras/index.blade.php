@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Bitacoras
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bitacoras</li>
          </ol>
        </nav>
</section>
<section class="py-4">
  
</section>
@can('bitacoras-ver')
  @livewire('administracion.bitacoras.table-component')
@endcan
@endsection