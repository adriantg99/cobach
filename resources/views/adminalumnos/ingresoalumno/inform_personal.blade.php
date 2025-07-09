{{-- {{ $alumno->noexpediente }} --}}

@extends('layouts.dashboard-layout-alumno') <!-- Session Status -->
@section('content')
    @livewire('alumnos.inresoalumno.informacion-personal-component', ['alumno' => $alumno, 'estados' => $estados])
@endsection
