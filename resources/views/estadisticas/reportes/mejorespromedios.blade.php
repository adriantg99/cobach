@extends('layouts.dashboard-layout')    <!-- Session Status -->
@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="#">Reportes</a></li>
          </ol>
        </nav>
</section>
<section class="py-4">





</section>
@livewire('reportes.mejorespromedios-component')
@endsection
