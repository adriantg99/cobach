<div>
    {{-- Care about people's approval and you will be their prisoner. --}}

    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Replicación de calificaciones</p>
        </div>

        @hasallroles('super_admin')
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
                                        <option value="{{ $planteles->id }}"
                                            @unlessrole('control_escolar') @unlessrole('control_escolar_' . $planteles->abreviatura) disabled @endunlessrole
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
                        <label class="form-label">Calificacion curso origen</label>
                        <select class="form-select" name="curso_origen" id='curso_origen' wire:model="curso_origen">
                            <option value="0" selected>Seleccionar grupo</option>
                            @if ($grupo_seleccionar)
                                @foreach ($buscar_cursos as $curso)
                                    @if ($curso->docente_id != '')
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @else
                                        @continue
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Calificacion curso destino</label>
                        <select class="form-select" name="curso_destino" id='curso_destino' wire:model="curso_destino">
                            <option value="0" selected>Seleccionar grupo</option>
                            @if ($grupo_seleccionar)
                                @foreach ($buscar_cursos as $curso)
                                    @if ($curso->docente_id == 0 || is_null($curso->docente_id))
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @else
                                        @continue
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Parcial</label>
                        <select name="parcial" id="parcial" wire:model="parcial" class="form-select">
                            <option value="P1">P1</option>
                            <option value="P2">P2</option>
                            <option value="P3">P3</option>
                            <option value="Final">Final</option>
                        </select>

                    </div>

                </div>


            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button wire:click="replicar_calificaciones" wire:loading.attr="disabled"
                        wire:loading.class="bg-secondary" wire:loading.remove
                        class="btn btn-primary float-end">Replicacion
                        de calificaciones</button>
                    <span wire:loading wire:target="replicar_calificaciones">Replicando calificaciones...</span>

                </div>
            </div>
        @endhasallroles
    </div>
</div>

</div>
