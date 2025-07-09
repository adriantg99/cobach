<div>
    {{-- Care about people's approval and you will be their prisoner. --}}

    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Lista de asistencia</p>
        </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Plantel</label>
                            <select class="form-select" name="plantel_seleccionado" id='plantel_seleccionado'
                                wire:model="plantel_seleccionado">
                                <option value="" selected>Seleccionar plantel</option>
                                @foreach ($plantel as $planteles)
                                    <option value="{{ $planteles->id }}">{{ $planteles->nombre }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Semestre</label>
                            <select class="form-select" name="periodo_seleccionado" id='periodo_seleccionado'
                                wire:model="periodo_seleccionado">
                                <option value="0" selected>Seleccionar Semestre</option>
                                @foreach ($periodos as $periodo)
                                    <option value="{{ $periodo->periodo }}">{{ $periodo->periodo }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    @if (!empty($grupos))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Grupo</label>
                                <select class="form-select" name="grupo_seleccionar" id='grupo_seleccionar'
                                wire:model="grupo_seleccionar">
                                <option value="0" selected>Seleccionar grupo</option>
                                @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                @endforeach
                            </select>
                            
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Curso </label>
                            <select class="form-select" name="curso_origen" id='curso_origen' wire:model="curso_origen">
                                <option value="0" selected>Seleccionar grupo</option>
                                @if ($grupo_seleccionar)
                                    @foreach ($buscar_cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>



                </div>
                @if (!empty($curso_origen))
                <div class="d-flex justify-content-end">
                    <form method="POST" action="/docentes/lista_asistencia/{{ $curso_origen }}/0">
                        @csrf
                        <button class="btn btn-primary float-end" type="submit">Imprimir Lista de asistencia</button>
                    </form>
                </div>    
            @endif
            

        </div>
    </div>

</div>
