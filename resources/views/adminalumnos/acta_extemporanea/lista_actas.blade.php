@extends('layouts.dashboard-layout')

@section('title')
    Actas Extemporaneas
@endsection
@section('content')

<section class="py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Actas Extemporaneas/Listado de Actas</li>
        </ol>
    </nav>
</section>

@livewire('adminalumnos.actas-extemporaneas.lista-actas-component', ['plantel_id' => $plantel_id])

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