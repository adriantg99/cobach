{{-- ANA MOLINA 23/05/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Cambio de ciclos
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cambio de ciclos</li>
          </ol>
        </nav>
</section>

@can('certificadogenera-ver')
  @livewire('certificados.cambio.ciclos-component')
@endcan
@endsection

@section('js_post')

@endsection
