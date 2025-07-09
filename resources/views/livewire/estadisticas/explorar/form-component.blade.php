{{-- ANA MOLINA 04/09/2024 --}}
<section class="py-4">
    {{-- Be like water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header">
            <label class="card-title"><strong>Explorar Datos</strong></label><br>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ciclo Escolar</label>
                        <select class="form-select" name="ciclo_seleccionado" id='ciclo_seleccionado'
                            wire:model="ciclo_seleccionado">
                            @if ($ciclo)
                            @foreach ($ciclo as $ciclos)
                            <option value="{{ $ciclos->id }}" {{-- @unlessrole('control_escolar')
                                @unlessrole('control_escolar_'.$ciclos->nombre) disabled @endunlessrole @endunlessrole --}}>
                                {{ $ciclos->nombre }} - {{ $ciclos->per_inicio }}
                            </option>
                            @endforeach
                            @endif

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Plantel</label>
                        <select class="form-select" name="plantel_seleccionado" id='plantel_seleccionado'
                            wire:model="plantel_seleccionado">
                            <option value="|" selected>Seleccionar plantel</option>
                            @if ($plantel)
                            @foreach ($plantel as $planteles)
                            <option value="{{ $planteles->id }}" @unlessrole('control_escolar')
                                @unlessrole('control_escolar_'.$planteles->abreviatura) disabled @endunlessrole
                                @endunlessrole>
                                {{ $planteles->nombre }}
                            </option>
                            @endforeach
                            @endif

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Semestre</label>
                        <select class="form-select" name="periodo_seleccionado" id='periodo_seleccionado'
                            wire:model="periodo_seleccionado">
                            <option value="0" selected>Todos los semestres</option>
                            @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->periodo }}">{{ $periodo->periodo }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Turno</label>
                        <select class="form-select" name="turno_seleccionado" id='turno_seleccionado'
                            wire:model="turno_seleccionado">
                            <option value="0" selected>Ambos turnos</option>
                            <option value="1">Matutino</option>
                            <option value="2">Vespertino</option>

                        </select>
                    </div>
                </div>
                @if (!empty($grupos))
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Grupo</label>
                        <select class="form-select" name="grupo_seleccionar" id='grupo_seleccionar'
                            wire:model="grupo_seleccionar">
                            <option value="0" selected>Todos los grupos</option>
                            @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                @endif
                @if (!empty($cursos))
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Curso</label>
                        <select class="form-select" name="curso_seleccionado" id='curso_seleccionado'
                            wire:model="curso_seleccionado">
                            <option value="0" selected>Todos los cursos</option>
                            @foreach ($cursos as $curso)
                            <option value="{{  $curso->nombre }}">{{ $curso->nombre }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                @endif
                @if (!empty($docentes))
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Docente</label>
                        <select class="form-select" name="docente_seleccionado" id='docente_seleccionado'
                            wire:model="docente_seleccionado">
                            <option value="0" selected>Todos los docentes</option>
                            @foreach ($docentes as $docente)
                            <option value="{{  $docente->id }}">{{ $docente->apellido1 }} {{ $docente->apellido2 }} {{ $docente->nombre }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                @endif
            </div>

        </div>
        <div class="row mt-3">
            <div class="col-12">

                <button wire:loading.attr="disabled"  onclick="exportar( {{$ciclo_seleccionado}},'{{$plantel_seleccionado}}',{{$periodo_seleccionado}},{{$grupo_seleccionar}},{{$turno_seleccionado}},'{{$curso_seleccionado}}',{{$docente_seleccionado}});"
                    wire:loading.class="bg-secondary" wire:loading.remove class="btn btn-primary float-end">Exportar a Excel</button>

            </div>
        </div>
    </div>


</section>
@section('js_post')
<script>


    function exportar(ciclo_id,plantel_id,periodo,grupo_id,turno,curso_id,docente_id)
    {

        let url="{{route('estadisticas.explorar.excel',['ciclo_id'=>":ciclo_id",'plantel_id'=>":plantel_id",'periodo'=>":periodo",'grupo_id'=>":grupo_id",'turno'=>":turno",'curso_id'=>":curso_id",'docente_id'=>":docente_id"])}}";

        //codificar
        var encodedciclo_id = btoa(ciclo_id);
        var encodedplantel_id = btoa(plantel_id);
        var encodedperiodo = btoa(periodo);
        var encodedgrupo_id= btoa(grupo_id);
        var encodedturno= btoa(turno);
        var encodedcurso_id= btoa(curso_id);
        var encodeddocente_id= btoa(docente_id);
         url = url.replace(":ciclo_id", encodedciclo_id);
        url = url.replace(":plantel_id", encodedplantel_id);
         url = url.replace(":periodo", encodedperiodo);
         url = url.replace(":grupo_id", encodedgrupo_id);
         url = url.replace(":turno", encodedturno);
        url = url.replace(":curso_id", encodedcurso_id);
          url = url.replace(":docente_id", encodeddocente_id);
        Swal.fire({
        title: 'Exportando a Excel...',
        html: 'Por favor espere.',

            showConfirmButton: false
        });
        Swal.showLoading();


        $.ajax({
    url: url,
    type: "GET",
    success: function (result) {
        window.open(url);
        Swal.close(); // this is what actually allows the close() to work
        //console.log(result);
    },
    });

    }

</script>
@endsection
