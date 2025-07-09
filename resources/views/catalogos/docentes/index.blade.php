@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('title')
Docentes
@endsection

@section('content')
<section class="py-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Docentes</li>
          </ol>
        </nav>
</section>

<div class="card">
    <div class="card-header">
        <label class="card-title">Criterios de búsqueda:</label>
    </div>
    <div class="card-body">
        <h5>Filtrar plantel:</h5>
            <div class="col-6 col-sm-6">
              <form method="post" action="{{route('adm.docentes.post')}}">
                @csrf
                <label for="role" class="form-label">Plantel:</label>
                <select class="form-control" name="plantel_id"  onchange="this.form.submit()">
                  <option></option>
                  @foreach($planteles as $plantel)
                    <option value="{{$plantel->id}}" @unlessrole('control_escolar') @unlessrole('control_escolar_'.$plantel->abreviatura) disabled @endunlessrole @endunlessrole>{{$plantel->nombre}}</option>
                  @endforeach
                </select>
              </form>
            </div>
    </div>
</div>

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
</script>
