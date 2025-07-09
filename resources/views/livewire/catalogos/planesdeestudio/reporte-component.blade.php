{{-- ANA MOLINA 11/08/2023 --}}

@extends('layouts.reporte-layout')    <!-- Session Status -->

@section('title')
Planes de Estudio
@endsection

@section('reporte')
Listado de Planes de Estudio
@endsection
@section('content')
<section class="bdy">
    <div >
    <table  >
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Plantel</th>

        </tr>
    </thead>
    <tbody>
        @foreach($planes as $plan)
        <tr>
            <td> {{$plan->id}} </td>
            <td> {{$plan->nombre}} </td>
            <td> {{$plan->plantel}}</td>

        </tr>
        @endforeach
    </tbody>
    </table>
    </div>
</section>
@endsection

