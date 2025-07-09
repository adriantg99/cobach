{{-- ANA MOLINA 09/11/2023 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Boleta
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Boleta</li>
          </ol>
        </nav>
</section>

@can('boleta-ver')
  @livewire('adminalumnos.boleta.table-component')
@endcan
@endsection
