@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Plantel - Horas
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{url('/catalogos/planteles/editar/'.$plantel->id)}}">Plantel</a></li>
            <li class="breadcrumb-item active" aria-current="page">Horas</li>
          </ol>
        </nav>
</section>
<section class="py-4">
  <div class="row g-3">
    <label>Horas del plantel:</label>
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
        <button class="btn btn-success btn-sm" disabled
          onclick="cargando(); ;"
        >Agregar</button>
        @endcan

        <button class="btn btn-info btn-sm" disabled 
          onclick="cargando(); ';"
        >Exportar a Excel</button>
        
    </div>

</section>
@can('aula-ver')
<div class="row">
   <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Horas</h3>
        
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        
        <table class="table table-hover text-nowrap" id="dataTable">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Turno</th>
              <th>Hr Inicio</th>
              <th>Hr Fin</th>
              <th>Fecha actualización</th>  
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          
          @foreach($horas as $hr)
            <tr>
              <td>{{ $hr->id }}</td>
              <td>{{ $hr->turno->nombre }}</td>
              <td>{{ $hr->hr_inicio }}</td>
              <td>{{ $hr->hr_fin }}</td>
              <td>{{ $hr->updated_at }}</td>
              <td>
              
                
                <button class="btn btn-info btn-sm" disabled
                    onclick="cargando(); ;" 
                    >Editar</button>
                
                <button class="btn btn-warning btn-sm" disabled
                    onclick=";"
                    >Eliminar</button>
                
              </td>
            </tr>
          @endforeach
          
          </tbody>
        </table>
      </div><!--card-body-->
      <div class="card-footer">
        
        {{ $horas->links() }}
        
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
