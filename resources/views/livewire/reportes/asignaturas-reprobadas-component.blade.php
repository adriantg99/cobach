<div>
    {{-- Be like water. --}}



    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Asignaturas reprobadas por alumnos</p>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Plantel</label>
                        <select class="form-select" name="plantel_seleccionado" id='plantel_seleccionado'
                            wire:model="plantel_seleccionado">
                            <option value="" selected>Seleccionar plantel</option>
                            @if ($plantel)
                                @foreach ($plantel as $planteles)
                                    @if ($planteles->id == 0)
                                        @continue
                                    @endif
                                    <option value="{{ $planteles->id }}">
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
                            @if ($periodos)
                                @foreach ($periodos as $periodo)
                                    <option value="{{ $periodo->periodo }}">{{ $periodo->periodo }}</option>
                                @endforeach
                            @endif


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
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Alumnos: </label>
                            <div class="">
                                <select class="form-select" name="alumno_seleccionar" id="alumno_seleccionar"
                                    wire:model="alumno_seleccionar">
                                    <option value="0" selected> Todos los alumnos del grupo</option>
                                    @foreach ($alumnos_grupo as $alumno)
                                        <option value="{{ $alumno->id }}">{{ $alumno->noexpediente }} --
                                            {{ $alumno->apellidos . ' ' . $alumno->nombre }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                    </div>
                @endif

            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button wire:click="generar_documento" wire:loading.attr="disabled"
                        wire:loading.class="bg-secondary" wire:loading.remove class="btn btn-primary float-end">Imprimir
                        documento</button>
                    <span wire:loading wire:target="generar_documento">Generando documento...</span>

                </div>
            </div>
        </div>
    </div>
</div>
