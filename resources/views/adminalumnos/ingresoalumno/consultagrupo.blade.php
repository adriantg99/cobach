@extends('layouts.dashboard-layout-alumno_no_menu')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}



@section('content')
  <section class="py-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
              <li class="breadcrumb-item active" aria-current="page">Alumno:{{$alumno->noexpediente}}</li>
            </ol>
          </nav>
  </section>

<div class="col-md-9">
<h3><strong>{{$alumno->nombre_alumno}}</strong></h3>
<h4>{{$alumno->noexpediente}}</h4>
<br>
@php
//$plantel = $alumno->plantel();

@endphp

<h3>PLANTEL:<strong>{{$alumno->plantel_nombre}}</strong></h3>
<h3>GRUPO - TURNO:<strong>{{$alumno->grupo_nombre}}</strong></h3>


<br><br>
 


</div>
          <!-- /.col -->

@endsection

@section('js_post')

@endsection

@section('css_pre')
<!-- jQuery antes que bootstrap.js -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

<!-- Bootstrap.js despues de jQuery-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
@endsection