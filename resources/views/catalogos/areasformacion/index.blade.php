{{-- ANA MOLINA 27/06/2023 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Áreas de Formación
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Áreas de Formación</li>
          </ol>
        </nav>
</section>
<section class="py-4">
  @can('areaformacion-crear')
    <div class="col-sm-8">
        <button class="btn btn-success btn-sm"
          onclick="cargando(); window.location='{{route('catalogos.areasformacion.agregar')}}';"
        >Agregar</button>
    </div>
  @endcan
</section>
@can('areaformacion-ver')
    @livewire('catalogos.areasformacion.table-component')
@endcan
@endsection

<script>
    function cargando(areaformacion_id)
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
</script>
