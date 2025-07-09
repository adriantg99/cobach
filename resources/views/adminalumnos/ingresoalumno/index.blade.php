@extends('layouts.dashboard-layout-alumno')    <!-- Session Status -->
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
<h3><strong>{{$alumno->nombre}} {{$alumno->apellidos}}</strong></h3>
<h4>{{$alumno->noexpediente}}<br>{{$alumno->correo_institucional}}</h4>
<br>
<button hidden="hidden" class="btn btn-primary btn-lg" onclick="location.href='{{route('ingreso_alumno.inform_personal')}}';">CONFIRMA TU INFORMACIÓN PERSONAL</button>


<br><br>
@if(isset($reinscripcion))
<button class="btn btn-primary btn-lg" onclick="location.href='{{route('ingreso_alumno.iniciar_reinscripcion')}}';">INICIAR REINSCRIPCIÓN</button>
@endif


</div>
          <!-- /.col -->

@endsection

@section('js_post')
<script>
  function select_entidad_id_ajax(entidad_id){
    var parametros = {
      "entidad_id" : entidad_id,
    };
    console.log('Ajax entidad:' +entidad_id);

    select_municipio_id_ajax(0,0);

    $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
        });
        $.ajax({
                data:  parametros,
                url:   '{{ Route('ingreso_alumno.carga_municipio') }}',
                type:  'post',
                beforeSend: function () {
                        $("#carga_municipio").html('<div class="form-group col-lg-12 col-12" id="carga_municipio"><label class="form-label">Municipio:*</label><select class="form-control" id="municipio_id" name="municipio_id" > <option value="00">...</option></select></div>');                        
                },
                success:  function (response) {
                        $("#carga_municipio").html(response);                       
                }
        });

  };

  function select_municipio_id_ajax(municipio_id, entidad_id){
     var parametros = {
      "municipio_id" : municipio_id,
      "entidad_id" : entidad_id,
    };
    console.log('Ajax municipio:' +municipio_id+ ' entidad: '+entidad_id);

    $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
        });
        $.ajax({
                data:  parametros,
                url:   '{{ Route('ingreso_alumno.carga_localidad') }}',
                type:  'post',
                beforeSend: function () {
                        $("#carga_localidad").html('<div class="form-group col-lg-12 col-12" id="carga_localidad"><label class="form-label">Localidad:*</label><select class="form-control" id="localidad_id" name="localidad_id" > <option value="00">...</option></select></div>');                        
                },
                success:  function (response) {
                        $("#carga_localidad").html(response);                       
                }
        });


  };
</script>
@endsection

@section('css_pre')
<!-- jQuery antes que bootstrap.js -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

<!-- Bootstrap.js despues de jQuery-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
@endsection