{{-- ANA MOLINA 10/07/2024 --}}
@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Equivalencia y Revalidación
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Equivalencia y Revalidación</li>
          </ol>
        </nav>
</section>
<section class="py-4">
  @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar') || str_contains(Auth()->user()->roles->pluck('name'), 'autorizar_rev') )
  <div class="col-sm-8">
        <button class="btn btn-success btn-sm"
          onclick="cargando(); window.location='{{route('adminalumnos.equivalencia.agregar')}}';"
        >Agregar</button>
    </div>
  @endif
</section>
@can('equivalencia-editar')
@livewire('adminalumnos.equivalencia.table-component')  
@endcan

@endsection

<script>
    function cargando(equivalencia_id)
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
