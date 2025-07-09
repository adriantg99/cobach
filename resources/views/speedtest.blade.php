@extends('layouts.dashboard-layout')    <!-- Session Status -->
{{--
secciones disponibles:
title
css_pre
js_post

--}}
@section('content')
<h3 class="page-header mb-3">
	Valores Speedtest. <small></small>
</h3>

<p>Velocidad de descarga: {{ $download_speed }} Mbps</p>
<p>Velocidad de carga: {{ $upload_speed }} Mbps</p>
<p>Latencia: {{ $latency }} ms</p>
@endsection
