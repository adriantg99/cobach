{{-- ANA MOLINA 02/05/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}

@section('title')
Revisar certificado
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Revisar Certificado</li>
          </ol>
        </nav>
</section>
<style>
  .custom-checkbox {
    width: 25px;
    height: 25px;
    cursor: pointer;
}

.custom-checkbox:checked {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@can('certificadorevisa-ver')
  @livewire('certificados.revisa.table-component')
@endcan
@endsection
