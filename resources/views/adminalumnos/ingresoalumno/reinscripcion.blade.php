@extends('layouts.dashboard-layout-alumno')

@php
    use App\Models\Adminalumnos\AlumnoModel;
    use App\Models\Finanzas\FichasModel;
@endphp

@section('content')

    <link href="{{ asset('css/alumno.css') }}" rel="stylesheet">

    <section class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('ingreso_alumno.index') }}">Alumno</a></li>
            </ol>
        </nav>
    </section>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h3><strong>{{ $alumno->nombre }} {{ $alumno->apellidos }}</strong></h3>
                <h4>{{ $alumno->noexpediente }}<br>{{ $alumno->correo_institucional }}</h4>
                <h5>
                    TELÉFONO: {{ $alumno->telefono }}<br>
                    CELULAR: {{ is_numeric($alumno->celular) ? $alumno->celular : 'SIN CELULAR CARGADO' }}
                </h5>
                <h5>CURP: {{ $alumno->curp }}</h5>
                <hr />
            </div>

            <div class="col-12 mb-4">
                <h5>TUTOR: {{ $alumno->tutor_nombre }} {{ $alumno->tutor_apellido1 }} {{ $alumno->tutor_apellido2 }}</h5>
                <h5>TELÉFONO TUTOR: {{ $alumno->tutor_telefono }}</h5>
                @if ($alumno->familiar_nombre)
                    <h5>FAMILIAR: {{ $alumno->familiar_nombre }} {{ $alumno->familiar_apellido1 }} {{ $alumno->familiar_apellido2 }}</h5>
                    <h5>TELÉFONO FAMILIAR: {{ $alumno->familiar_celular }}</h5>
                @else
                    <h5>SIN DATOS DE FAMILIAR CARGADOS</h5>
                @endif
            </div>

            <div class="col-12 mb-4">
                @if ($grupo)
               
                    <h4>Último ciclo inscrito:{{ $grupo->nombre }} - 
                         {{ $grupo->descripcion }} {{ $grupo->turno_id == 1 ? 'Matutino' : 'Vespertino' }}
                    </h4>
                @endif
            </div>

            <div class="col-12">
                <a class="btn btn-info" href="{{ url('alumno/boleta') }}">Ver Boleta</a>
            </div>
        </div>
            {{--
    @if ($reinscribe == 0)
        <body onload="no_inscribe()" class="">
            <h3>Calificaciones del ciclo escolar pasado</h3>

            <div class="card">
                <table>
                    <thead>
                        <th>
                            Nombre de la asignatura
                        </th>
                        <th>
                            Calificación de la asignatura
                        </th>
                        <th>

                        </th>
                    </thead>
                    @foreach ($materias as $materias)
                        @if ($materias->calificacion < 60)
                            @php
                                $condicion = true;
                            @endphp
                        @else
                            @php
                                $condicion = true;
                            @endphp
                        @endif
                        <tr>
                            <td style="background-color: {{ $condicion ?: 'green' }}">
                                {{ $materias->nombre }}
                            </td>
                            <td style="background-color: {{ $condicion ?: 'green' }}">{{ $materias->calificacion }}</td>
                            <td>{{ $materias->calif }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <button class="btn btn-primary btn-lg" onclick="location.href='{{ url('/') }}';">Volver</button>
        </body>
    @else
        <body>
            <p style="margin-top:0.25pt; text-align:justify; line-height:25.6pt; font-family:Calibri; font-size:21pt">
                Semestre cursado
            </p>
            <div class="card-container-alumno">
                <div class="card-alumno">
                    <!-- Contenido de la primera tarjeta -->
                    <h2>Semestre cursado</h2>
                    <table class="responsive">


                        @foreach ($materias as $materias)
                            @if ($materias->calificacion < 60)
                                @php
                                    $condicion = false;
                                @endphp
                            @else
                                @php
                                    $condicion = false;
                                @endphp
                            @endif
                            <tr>
                                <td class="card-td" style="background-color: {{ $condicion ?: 'green' }}">
                                    {{ $materias->nombre }}
                                </td>
                                <td class="card-td" style="background-color: {{ $condicion ?: 'green' }}">
                                    {{ $materias->calificacion }}
                                </td>
                                
                            </tr>
                        @endforeach
                        
                    </table>
                </div>

                <div class="card-alumno">
                    <!-- Contenido de la segunda tarjeta -->
                    <h2>Semestre a cursar</h2>
                    <table class="responsive">
                        @if (!empty($materias_a_cursar))
                            
                        @endif
                    </table>
                </div>
            </div>
--}}
@if ($tieneficha)



@if ($semestre == '2') {{-- and (date('j') >= 6 or date('j') <= 6))  --}}
    @php
        $user = Auth()->user();
        $alumno = AlumnoModel::where('correo_institucional', $user->email)->first();

        $fichas = FichasModel::where('matricula', $alumno->noexpediente)->orderBy('total', 'DESC')->get();
        if (count($fichas) > 0) {
            foreach ($fichas as $ficha) {
                $ficha->generada = date('Y-m-d H:i:s');
                $ficha->update();
            }
        }
    @endphp
    @if ((date('j') == 9 or date('j') >= 10) && $pasar_ventanilla == true)
        {{-- <------- Fechas para 2do sem   --}}
        <div class="col-md-9">
            <form method="post" action="{{ route('imprime_ref') }}">
                @csrf

                <br><br>
                <button class="btn btn-primary btn-lg" type="submit">Para completar tu reinscripción imprime tu
                    Papeleta de depósito.</button>
                <p>
                    @if ($ficha->generada)
                        Su ficha de deposito fue generada con exito el {{ $ficha->generada }}
                    @endif
                </p>
            </form>
        </div>
    @else
        @if ($pasar_ventanilla == false)
            <div class="col-md-9">
                <br><br>
                <h3>No se encontró el Certificado de Secundaria en el sistema. Por favor, acude a ventanilla con tu
                    certificado para que podamos ayudarte a obtener tu ficha.</h3>
            </div>
        @else
            <div class="col-md-9">
                <br><br>
                <h3>Su papeleta de deposito estará disponible a partir del 9 de junio.</h3>
            </div>
        @endif
    @endif
@elseif($semestre == '4')
    @php
        $user = Auth()->user();
        $alumno = AlumnoModel::where('correo_institucional', $user->email)->first();

        $fichas = FichasModel::where('matricula', $alumno->noexpediente)->orderBy('total', 'DESC')->get();
        if (count($fichas) > 0) {
            foreach ($fichas as $ficha) {
                $ficha->generada = date('Y-m-d H:i:s');
                $ficha->update();
            }
        }
    @endphp
    @if (date('j') == 11 or date('j') >= 12)
        {{-- <------- Fechas para 4to sem   --}}
        <div class="col-md-9">
            <form method="post" action="{{ route('imprime_ref') }}">
                @csrf

                <br><br>
                <button class="btn btn-primary btn-lg" type="submit">Para completar tu reinscripción imprime tu
                    Papeleta de depósito.</button>

                <p>
                    @if ($ficha->generada)
                        Su ficha de deposito fue generada con exito el {{ $ficha->generada }}
                    @endif
                </p>
            </form>
        </div>
    @else
        <div class="col-md-9">
                <br><br>
                <h3>Su papeleta de deposito estará disponible a partir del 11 de junio.</h3>
            </div>
    @endif
@else
    <div class="col-md-9">

        <form action="{{ route('aceptar') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <iframe src="{{ asset('guia/Carta_compromiso.pdf') }}" width="100%" height="600px"></iframe>
            @if (is_null($alumno->carta_compromiso))
                <button type="submit" class="btn btn-primary" id="aceptar" onclick="mostrar();">Aceptar y
                    descargar</button>
            @else
                <button type="submit" class="btn btn-primary" id="aceptar"
                    onclick="mostrar();">Descargar</button>
            @endif



        </form>
        @if (!is_null($alumno->carta_compromiso))
            <form method="post" action="{{ route('imprime_ref') }}">
                @csrf

                <br><br>
                <button class="btn btn-primary btn-lg" type="submit">Para completar tu reinscripción imprime tu
                    Papeleta de depósito.</button>
                <p>
                    @if ($ficha->generada && $ficha->generada > '2024-07-28 00:00:00')
                        Su ficha de deposito fue generada con exito el {{ $ficha->generada }}
                    @endif
                </p>
            </form>
        @else
            <h2>El estudiante ya se encuentra inscrito y con documentos cargados correctamente</h2>
            <br><br>
            <div hidden id="mostrar">
                <form method="post" action="{{ route('imprime_ref') }}">
                    @csrf

                    <br><br>
                    <button class="btn btn-primary btn-lg" type="submit">Para completar tu reinscripción
                        imprime tu Papeleta de depósito.</button>
                    <p>
                        @if ($ficha->generada && $ficha->generada > '2024-07-28 00:00:00')
                            Su ficha de deposito fue generada con exito el {{ $ficha->generada }}
                        @endif
                    </p>
                </form>
            </div>
        @endif



    </div>
@endif
@else
{{-- 
<div class="col-md-9">
    <h2>El estudiante aun no cuenta con ficha de deposito:</h2>
    <br><br>
    <h3>Favor de pasar verificar más tarde.</h3>
</div>
 --}}
@endif

    </div>
    

    @section('js_post')
        <script>
            function no_inscribe() {
                Swal.fire({
                    icon: "error",
                    title: "El alumno no puede reinscribirse",
                    text: "Favor de pasar con control escolar de tu plantel lo antes posible para validar su situación",
                });
            }

            function mostrar() {
                var div = document.getElementById('mostrar');
                var boton = document.getElementById('aceptar');
                boton.setAttribute('hidden', true);
                div.removeAttribute('hidden');
            }
        </script>
    @endsection
@endsection
