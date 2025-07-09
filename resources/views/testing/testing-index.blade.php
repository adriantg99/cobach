@extends('layouts.dashboard-layout') <!-- Session Status --> {{-- secciones disponibles: title, css_pre, js_post --}}
@section('title')
	Testing
@endsection
@section('content')
	<section class="py-3">
			<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/')}}">Inicio</a></li>
				<li class="breadcrumb-item active" aria-current="page">Testing</li>
			</ol>
			</nav>
	</section>
	<section class="py-4">
		<div class="col-sm-8">
			@can('plantel-ver')
				<label for="email">Correo:</label>
				<input type="text" id="email" name="email"/>
				<button class="btn btn-success btn-sm" onclick="test(document.getElementById('email').value);">Test</button>
			@endcan
            @can('promocion-ver')
                @livewire('testing.testing-component')
            @endcan
		</div>
	</section>
@endsection
@section('js_post')
	<script>
		function test($email) {
			console.log($email);
			alert($email);
		}
	</script>
@endsection