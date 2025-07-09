@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Docentes
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('adm.docentes')}}">Docentes</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$plantel->nombre}}</li>
          </ol>
        </nav>
</section>

@livewire('catalogos.docentes.table-component', ['plantel_id'  => $plantel->id])

@endsection