@extends('layouts.dashboard-layout-alumno') <!-- Session Status -->
@section('content')

<section class="py-3" style="margin-top: 5%">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('ingreso_alumno.index') }}">Alumno:
                </a></li>

        </ol>
    </nav>
</section>


    <iframe src="{{ route('adminalumnos.kardex.reporte') }}"width="100%" height="700px" style="border: none;">
    </iframe>
    


@endsection
