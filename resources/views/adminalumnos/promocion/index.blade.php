@extends('layouts.dashboard-layout')
@section('title')
Promocion
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/promocion.css')}}">
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Promocion</li>
          </ol>
        </nav>
</section>
@can('promocion-ver')
  @livewire('adminalumnos.promocion.promocion-alumnos')
@endcan
@endsection
