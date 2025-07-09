@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Roles
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perfil</li>
          </ol>
        </nav>
</section>
<section class="py-4">
  <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{$user->adminlte_image()}}"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{$user->email}}</h3>

                <p class="text-muted text-center">Rol:</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Fecha de Registro</b> <a class="float-right">{{$user->created_at}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Última Conección</b> <a class="float-right">{{$user->ultimo_ingreso()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Fecha de Actualización de Perfil</b> <a class="float-right">{{$perfil? $perfil->updated_at:''}}</a>
                  </li>
                </ul>

                {{--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>--}}
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Acerca de:</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card card-primary card-tabs">     
                <div class="card-header p-0 pt-1">
                  <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  
                  <li class="nav-item">
                 {{--   @if($perfil)  --}}
<a class="nav-link active" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="true">1. Datos de contacto</a>
                {{--    @else
<a class="nav-link active" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="true">1. Datos de contacto</a>
                    @endif --}}
                  </li>
                  @if($perfil)
                  <li class="nav-item">
<a class="nav-link" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">2. Planteles y carga</a>    
                  @endif
                    {{--
                    @if($perfil)
                    
<a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">2. Planteles y carga</a>
                    @else
<a class="nav-link" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">2. Planteles y carga</a>
                    @endif
                    --}}
                  </li>
                  
                  </ul>
                </div>
                <div class="card-body">

                  <div class="tab-content" id="custom-tabs-one-tabContent">

                   {{--  @if($perfil)  --}}
<div class="tab-pane fade show active" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                  {{--  @else
<div class="tab-pane fade show active" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                    @endif  --}}
                    
                      <h4>DATOS DE CONTACTO:</h4>
                      {{-- @livewire('perfiles.form-datos-component',['user_id' => Auth()->user()->id]) --}}
                      @livewire('administracion.perfil.perfil',['user_id' => Auth()->user()->id])
                    </div>
                    {{--
                    @if($perfil)
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                      <h4>PLANTELES DONDE LABORA:</h4>
                      @livewire('perfiles.form-planteles-component',['perfil_id' => $perfil->id])
                    </div>
                    @else
                    <div class="tab-pane fade" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                      <h4>PORFAVOR COMPLETE SU PERFIL EN LA PESTAÑA <strong>1. DATOS DE CONTACTO</strong></h4>
                    </div>
                    @endif
                    --}}
                    <div class="tab-pane fade" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                      <h4>PLANTELES DONDE LABORA:</h4>
                      @if($perfil)
                        @livewire('administracion.perfil.form-planteles-component',['user_id' => Auth()->user()->id])
                      @endif
                    </div>

                  </div>
                </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
</section>

@endsection

@section('css_pre') {{-- Para Tabs --}}
    <!-- jQuery antes que bootstrap.js -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>

    <!-- Bootstrap.js despues de jQuery-->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
@endsection