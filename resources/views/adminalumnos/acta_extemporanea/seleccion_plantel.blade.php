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
        <p class="text-primary m-0 fw-bold">Seleccione el plantel:</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-xl-11 text-nowrap">
                <form method="post" action="{{route('adminalumnos.actas_ext.busqueda_plante_post')}}">
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
                        <td></td>
                        <td><button class="btn btn-info" type="submit">Buscar</button></td>
                    </tr>                
                </table>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js_post')




@endsection