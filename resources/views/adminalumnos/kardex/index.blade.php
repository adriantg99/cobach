{{-- ANA MOLINA 16/10/2023 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Kardex
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/promocion.css')}}">

<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kardex</li>
          </ol>
        </nav>
</section>

@hasallroles('control_escolar')
@can('kardex-imprimir')
  @livewire('adminalumnos.kardex.table-component')
@endcan
@endhasallroles
@endsection
