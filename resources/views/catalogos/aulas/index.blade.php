@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Plantel - Aulas
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/catalogos/planteles/editar/'.$plantel_id)}}">Plantel</a></li>
            <li class="breadcrumb-item active" aria-current="page">Aulas</li>
          </ol>
        </nav>
</section>
<section class="py-4">
  <div class="row g-3">
    <label>Aulas del plantel:</label>
    <div class="col-12 col-sm-8">
      <label for="nombre" class="form-label">Nombre:</label>
      <input class="form-control" 
        placeholder="Nombre del plantel" 
        name="plantel_nombre" 
        type="text" 
        value="{{$plantel->nombre}}"
        disabled 
        >
       
    </div>

    <div class="col-12 col-sm-4">
      <label for="abreviatura" class="form-label">CCT:</label>
      <input class="form-control" 
        placeholder="Abreviatura" 
        name="plantel_cct" 
        type="text" 
        value="{{$plantel->cct}}"
        disabled 
        >
        
    </div>
  </div>
    <hr>
    <div class="col-sm-8">
        @can('aula-crear')
        <button class="btn btn-success btn-sm"
          onclick="cargando(); window.location='{{route('catalogos.plantel.aulas.agregar',$plantel_id)}}';"
        >Agregar</button>
        @endcan

        <button class="btn btn-info btn-sm"
          onclick="cargando(); window.location='{{route('catalogos.plantel.aulas_excel',$plantel_id)}}';"
        >Exportar a Excel</button>
    </div>

</section>
@can('aula-ver')
<div class="row">
   <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Aulas</h3>
        
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        
        <table class="table table-hover text-nowrap" id="dataTable">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
              <th>Tipo</th>
              <th>Condición</th>
              <th>Activa</th>
              <th>Descripción</th>              
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          @foreach($aulas as $aula)
            <tr>
              <td>{{$aula->id}}</td>
              <td>{{$aula->nombre}}</td>
              <td>{{$aula->tipo_aula->descripcion}}</td>
              <td>{{$aula->condicion_aula->descripcion}}</td>
              <td>{{$aula->aula_activa}}</td>
              <td>
                <div style="
                width:200px;
  white-space: pre-wrap;      /* CSS3 */   
  white-space: -moz-pre-wrap; /* Firefox */    
  white-space: -pre-wrap;     /* Opera <7 */   
  white-space: -o-pre-wrap;   /* Opera 7 */    
  word-wrap: break-word;      /* IE */
                ">{{$aula->descripcion}}</div>
                </td>
              <td>
                @can('aula-editar')
                <button class="btn btn-info btn-sm" 
                    onclick="cargando(); location.href='{{route('catalogos.plantel.aulas.editar',$aula->id)}}';" 
                    >Editar</button>
                @endcan
                @can('aula-borrar')
                <button class="btn btn-warning btn-sm" 
                    onclick="confirmar_borrado({{$aula->id}});"
                    >Eliminar</button>
                @endcan
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div><!--card-body-->
      <div class="card-footer">
        {{$aulas->links()}}
      </div>
    </div>
  </div>
</div>
@endcan
@endsection

<script>
    function cargando(plantel_id)
    {

      let timerInterval
      Swal.fire({
        title: 'Cargando...',
        html: 'Por favor espere.',
        timer: 10000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
          const b = Swal.getHtmlContainer().querySelector('b')
          timerInterval = setInterval(() => {
            b.textContent = Swal.getTimerLeft()
          }, 100)
        },
        willClose: () => {
          clearInterval(timerInterval)
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log('I was closed by the timer')
        }
      })

    }
    function generando()
    {

      let timerInterval
      Swal.fire({
        title: 'Generando reporte...',
        html: 'Por favor espere.',
        timer: 10000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
          const b = Swal.getHtmlContainer().querySelector('b')
          timerInterval = setInterval(() => {
            b.textContent = Swal.getTimerLeft()
          }, 100)
        },
        willClose: () => {
          clearInterval(timerInterval)
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log('I was closed by the timer')
        }
      })

    }

    function confirmar_borrado(aula_id)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el aula ID:"+aula_id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href="/catalogos/plantel/aulas/"+aula_id+"/eliminar";
          }
        })
    }
</script>
