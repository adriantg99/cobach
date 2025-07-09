@extends('layouts.dashboard-layout')    <!-- Session Status -->
@section('title')
Sibal - Certificados
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">SIBAL</li>
          </ol>
        </nav>
</section>
<section class="py-4">
	<div class="col-sm-8">
      
    </div>
</section>
{{-- @livewire('certificados.valida.form-component') --}}

@livewire('certificados.sibal.genera')


@endsection