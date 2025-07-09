@extends('layouts.dashboard-layout') <!-- Session Status -->
@section('title')
    Boleta
@endsection

@section('content')
    <section class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Boleta</li>
            </ol>
        </nav>
    </section>
    <div class="card">
    <div class="card-body table-responsive table-sm">
        
    
    @if ($calificaciones)
    <p><strong>Expediente</strong> {{ $expediente }}</p>
        <table class="table" >
            <th>
                Nombre Asignatura
            </th>
            <th>
                Calificacion tipo
            </th>
            <th>
                Calificacion
            </th>
            <th>
                Acreditada
            </th>
            <th>
                Ciclo
            </th>

            @foreach ($calificaciones as $calificacion_alumno)
                <tr>
                    <td>
                        {{ $calificacion_alumno->nombre }}
                    </td>
                    <td>
                        {{ $calificacion_alumno->calificacion_tipo }}
                    </td>
                    <td>
                        {{ $calificacion_alumno->calificacion }}
                    </td>
                    <td>
                        {{ $calificacion_alumno->calif }}
                    </td>
                    <td>
                        {{ $calificacion_alumno->ciclo }}
                    </td>
                    <td>
                        {{ $calificacion_alumno->descripcion }}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
</div>
@endsection
