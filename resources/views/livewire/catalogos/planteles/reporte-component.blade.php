{{-- ANA MOLINA 07/08/2023 --}}

@extends('layouts.reporte-layout')    <!-- Session Status -->

@section('title')
Planteles
@endsection

@section('reporte')
Listado de Planteles
@endsection
@section('content')
<section class="bdy">
    <div >
    <table  >
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>ABR</th>
            <th>CCT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($planteles as $plantel)
        <tr>
            <td> {{$plantel->id}} </td>
            <td> {{$plantel->nombre}} </td>
            <td> {{$plantel->abreviatura}} </td>
            <td> {{$plantel->cct}} </td>
        </tr>
        @endforeach
    </tbody>
    </table>
    </div>
</section>
@endsection

