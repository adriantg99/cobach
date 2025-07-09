@extends('layouts.dashboard-layout')

@section('title')
    Materias Sueltas Alumno
@endsection
@section('content')

<section class="py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Materias Sueltas</li>
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
        <p class="text-primary m-0 fw-bold">Seleccione el plantel y ciclo escolar donde pertenece el alumno:</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-xl-11 text-nowrap">
                <form method="post" action="{{Route('adminalumnos.cursos_omitidos_post')}}">
                 @csrf
                <table>
                    <tr>
                        <td>
                            <label class="form-label">Plantel:</label>
                        </td>
                        <td>
                            <section class="py-6">
                                <select class="form-select" 
                                    name="plantel_id"
                                    >
                                    <option value="" selected>Seleccionar plantel</option>
                                    @foreach ($planteles as $Pln)
                                        <option value="{{ $Pln->id }}" @unlessrole('control_escolar') @unlessrole('control_escolar_'.$Pln->abreviatura) disabled @endunlessrole @endunlessrole>
                                            {{ $Pln->nombre }}</option>
                                    @endforeach
                                </select>
                            </section>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Ciclo Escolar:</label>
                        </td>
                        <td>
                            <section class="py-6">
                                <select class="form-select select2_ciclo_esc_id" 
                                    name="ciclo_esc_id"
                                    >
                                    <option value="" selected>Seleccionar ciclo escolar</option>
                                    @foreach ($ciclos_esc as $cie)
                                        <option value="{{ $cie->id }}">
                                            {{ $cie->id}} - {{$cie->abreviatura}} {{$cie->nombre}}</option>
                                    @endforeach
                                </select>
                            </section>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button class="btn btn-success" onclick="cargando()" type="submit">Consultar a los alumnos en el ciclo-plantel seleccionado</button></td>
                    </tr>
                </table>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js_post')
<script type="text/javascript">
$(document).ready(function() {
    $('.select2_ciclo_esc_id').select2({
        language: 'es',
        //theme: 'classic',
        
    });
});

$(document).ready(function() {
    $('.select2_alumnos').select2({
        language: 'es',
        //theme: 'classic',
        tags: true,
    });
});

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