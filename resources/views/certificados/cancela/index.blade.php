{{-- ANA MOLINA 04/06/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Cancelar certificado
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cancelar Certificado</li>
          </ol>
        </nav>
</section>

@can('certificadocancela-ver')
  @livewire('certificados.cancela.table-component')
@endcan
@endsection
