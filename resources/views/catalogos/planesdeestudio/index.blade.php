{{-- ANA MOLINA 09/08/2023 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Planes de Estudio
@endsection

@section('content')

<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Planes de Estudio</li>
          </ol>
        </nav>
</section>

@can('plandeestudio-ver')
  @livewire('catalogos.planesdeestudio.table-component')

@endcan

@endsection

