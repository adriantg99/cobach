{{-- ANA MOLINA 16/07/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Oficios
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Oficios</li>
          </ol>
        </nav>
</section>
<section class="py-4">
    @can('oficio-crear')
      <div class="col-sm-8">
          <button class="btn btn-success btn-sm"
            onclick="cargando(); window.location='{{route('certificados.valida.agregar')}}';"
          >Agregar</button>
      </div>
    @endcan
  </section>
  @can('oficio-ver')
      @livewire('certificados.valida.table-component')
  @endcan
  @endsection

  <script>
      function cargando(oficio_id)
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


