@extends('layouts.dashboard-layout') <!-- Session Status --> {{-- secciones disponibles: title, css_pre, content, js_post --}}

@section('title')
    Datos de Alumno
@endsection

@section('content')
    <div class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Alumno</li>
            </ol>

        </nav>
{{-- 
        @if (
            $alumno->id_estatus != 1 &&
                $alumno->cicloesc_id == 248 &&
                ($alumno->created_at >= '2024-08-01' ||
                    in_array($alumno->plantel_id, ['3', '12', '13', '15', '16', '17', '20', '21', '25', '26', '27'])))
            <button class="btn btn-primary float-end"
                onclick="cargando(); location.href='{{ route('ingreso_alumno.inform_personal', $alumno->id) }}';">Inscribir
                alumno

            </button>
        @endif
 --}}
    </div>

    <div class="container mt-3">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="d-flex">
                    @php
                        $imagen_find = App\Models\Adminalumnos\ImagenesalumnoModel::where('alumno_id', $alumno->id)
                            ->where('tipo', 1)
                            ->get();
                    @endphp
                    @if (count($imagen_find))
                        <div class="img-container">
                            <div class="imageOne image">
                                <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_find[0]->imagen)) }}"
                                    height="100" alt="Logo" class="logo img-fluid">
                            </div>
                        </div>
                    @endif
                    <div class="ms-3">
                        <h3><strong>{{ $alumno->nombre }} {{ $alumno->apellidos }}</strong></h3>
                        <h4>{{ $alumno->noexpediente }}<br>{{ $alumno->correo_institucional }}</h4>
                        <h4>Teléfono del alumno</h4>
                        <h3><strong>{{ $alumno->telefono ?? $alumno->celular }}</strong></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <h3>Datos de contacto</h3>
                @if (!empty($alumno->tutor_nombre))
                    <h4>Nombre Tutor: {{ $alumno->tutor_nombre }} {{ $alumno->tutor_apellido1 }}
                        {{ $alumno->tutor_apellido2 }}</h4>
                    <h4>{{ $alumno->tutor_telefono }}</h4>
                    @if (!empty($alumno->tutor_email))
                        <h4>Correo electrónico: {{ $alumno->tutor_email }}</h4>
                    @endif
                @elseif (!empty($alumno->familiar_nombre))
                    <h4>Nombre Familiar: {{ $alumno->familiar_nombre }} {{ $alumno->familiar_apellido1 }}
                        {{ $alumno->familiar_apellido2 }}</h4>
                    <h4>{{ $alumno->familiar_celular }}</h4>
                @endif
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                @if ($datos)
                    <h4>Último Ciclo Esc: <strong>({{ $datos->id_ciclo }}) {{ $datos->nombre }}</strong></h4>
                    <h4>Plantel: <strong>{{ $datos->plantel }}</strong></h4>
                    <h4>Turno: <strong>{{ $datos->turno }}</strong></h4>
                @else
                    <h4>No se encontraron datos.</h4>
                @endif
            </div>
        </div>
    </div>

    @if ($datos)
        <div class="col-md-12">

            <button class="btn btn-info btn-sm"
                @if ($datos) onclick="window.open('{{ route('adminalumnos.generarboletas', ['alumno_id' => $alumno->id, 'ciclo_esc' => $datos->id_ciclo]) }}', '_blank')" @else disabled @endif>
                Boleta
            </button>

            @if ($alumno->id_estatus != 0)
                <button class="btn btn-info btn-sm"
                    onclick="cargando(); window.open('{{ route('adminalumnos.generarConstancias', $alumno->id) }}', '_blank')">
                    Constancia
                </button>
                <button class="btn btn-info btn-sm"
                    onclick="cargando(); window.open('{{ route('adminalumnos.generarConstancias', ['user_id' => $alumno->id, 'grupo_id' => 0, 'promedio' => 1]) }}', '_blank')">
                    Constancia con promedio
                </button>
            @endif

            @can('kardex-imprimir')
                <input type="hidden" name="id_alumno_change" id="id_alumno_change" value="{{ $alumno->id }}">
                <button class="btn btn-light btn-sm" onclick="generandorep(); ">
                    Imprimir Kardex
                </button>

                <button class="btn btn-light btn-sm" onclick="generandohistorialacademico(); ">
                    Imprimir Historial Académico
                </button>

            @endcan

            @hasallroles('control_escolar')
                <button class="btn btn-info btn-sm"
                    onclick="cargando(); window.open('{{ route('movimientos', $alumno->id) }}', '_self')">
                    Traslado
                </button>
            @endhasallroles

            <button class="btn btn-info btn-sm" onclick="cargando(); window.open('{{ route('adminalumnos.alumnos.editar', $alumno->id) }}', '_self')">Datos del alumno</button>



        </div>
    @endif


    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">GRUPOS INSCRITOS:</h3>

            <div class="card-tools">

            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" style="height: 300px;">
            @livewire('cursos.omitidos.alumno-boton-component')
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>Ciclo</th>
                        <th>Grupo Turno</th>
                        <th>Plantel</th>
                        <th>Asignaturas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $dat)
                        <tr>
                            <td>
                                @php
                                    $cic = App\Models\Catalogos\CicloEscModel::find($dat->id_ciclo);
                                @endphp
                                {{ $cic->abreviatura }} - (id:{{ $cic->id }})
                            </td>
                            <td>
                                @php
                                    $gru = App\Models\Grupos\GruposModel::find($dat->id);
                                @endphp
                                {{ $gru->nombre }} - {{ $gru->abreviatura }} - {{ $gru->turno->nombre }} -
                                (id:{{ $gru->id }})
                            </td>
                            <td>
                                @php
                                    $pla = App\Models\Catalogos\PlantelesModel::find($dat->plantel_id);
                                @endphp
                                {{ $pla->nombre }} - (id:{{ $pla->id }})
                            </td>
                            <td>
                                @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
                                    @livewire('adminalumnos.alumnos.datos-contar-cursos-component', ['alumno_id' => $alumno->id, 'grupo_id' => $gru->id])
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-9">

        </div>


    @endsection

    @section('js_post')
        <script>
            function generandorep() {
                let url = "{{ route('adminalumnos.kardex.reporte', [':id_alumno_change']) }}";
                var alumno = document.getElementById("id_alumno_change");
                var valuealumno = alumno.value;
                url = url.replace(":id_alumno_change", valuealumno);
                let swalAlert = Swal; // cache your swal

                swalAlert.fire({
                    title: 'Generando reporte...',
                    html: 'Por favor espere.',
                    showConfirmButton: false
                });
                Swal.showLoading();

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        window.open(url, "_blank");
                        swalAlert.close(); // this is what actually allows the close() to work
                        //console.log(result);
                    },
                });
            }

            function generandohistorialacademico(){
                let url = "{{ route('adminalumnos.kardex.reporte_hist_academ', [':id_alumno_change']) }}";
                var alumno = document.getElementById("id_alumno_change");
                var valuealumno = alumno.value;
                url = url.replace(":id_alumno_change", valuealumno);
                let swalAlert = Swal; // cache your swal

                swalAlert.fire({
                    title: 'Generando Historial Académico...',
                    html: 'Por favor espere.',
                    showConfirmButton: false
                });
                Swal.showLoading();

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        window.open(url, "_blank");
                        swalAlert.close(); // this is what actually allows the close() to work
                        //console.log(result);
                    },
                });
            }

            function cargando(alumno_id) {

                let timerInterval
                Swal.fire({
                    title: 'Cargando...',
                    html: 'Por favor espere.',
                    timer: 10000,
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
