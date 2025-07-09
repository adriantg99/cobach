{{-- ANA MOLINA 06/03/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Certificado Digital
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Certificado Digital</li>
          </ol>
        </nav>
</section>

@can('certificado-ver')
  @livewire('certificados.certificado.table-component')
@endcan
@endsection
