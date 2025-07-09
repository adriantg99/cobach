@extends('layouts.dashboard-layout')

@section('title')
    Actas Extemporaneas
@endsection
@section('content')

<section class="py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Actas Extemporaneas</li>
        </ol>
    </nav>
</section>
@if ($errors->any())
    <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card shadow" id="principal">
    <div class="card-header py-3">
        <p class="text-primary m-0 fw-bold">Alumno Seleccionado:</p>
    </div>
    <div class="card-body">
        <div class="row">
            @livewire('adminalumnos.actas-extemporaneas.alumno-acta-component',['alumno_id' => $alumno_id])
        </div>
    </div>
</div>

@endsection

@section('js_post')
<script>
function cargando()
{
  let timerInterval
  Swal.fire({
    title: 'Cargando...',
    html: 'Por favor espere.',
    timer: 100000,
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


@endsection