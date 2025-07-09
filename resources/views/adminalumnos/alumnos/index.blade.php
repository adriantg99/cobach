{{-- ANA MOLINA 22/08/2023 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Alumnos
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Alumnos</li>
          </ol>
        </nav>
</section>

@can('grupos-ver')
  @livewire('adminalumnos.alumnos.table-component')
@endcan
@endsection

