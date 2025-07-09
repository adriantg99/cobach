@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Planteles - Aulas - Editar
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/catalogos/planteles')}}">Planteles</a></li>
            <li class="breadcrumb-item"><a href="{{url('/catalogos/plantel/'.$aula->plantel_id.'/aulas')}}">Aulas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
</section>
<section class="py-4">
	<div class="col-sm-8">
        <a class="btn btn-info btn-sm" href="{{route('catalogos.plantel.aulas',$aula->plantel_id)}}" title="Regresar">
           	<span class="mdi mdi-plus"></span> Regresar
        </a>
    </div>
</section>

@livewire('catalogos.aulas.form-component',['plantel_id' => $aula->plantel_id,
                                            'aula_id'    => $aula->id
])

@endsection
