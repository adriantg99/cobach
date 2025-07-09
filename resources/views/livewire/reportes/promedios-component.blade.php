<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Promedio</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ciclos</label>
                        <select class="form-select" name="ciclo_seleccionado" id='ciclo_seleccionado'
                            wire:model="ciclo_seleccionado">
                            <option value="0" selected>Seleccionar ciclo</option>
                            @if ($ciclos)
                                @foreach ($ciclos as $ciclo)
                                    <option value="{{ $ciclo['id'] }}">
                                        {{ $ciclo['nombre'] }}
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
                        @if ($peridos)
                            <option value="0" selected>Sin semestre</option>
                            @foreach ($peridos as $periodo)
                                <option value="{{ $periodo['0'] }}">{{ $periodo['0'] }}</option>
                            @endforeach
                        @endif




                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button wire:click="generar_documento" wire:loading.attr="disabled"
                    wire:loading.class="bg-secondary" wire:loading.remove class="btn btn-primary float-end">Imprimir
                    documento</button>
                <span wire:loading wire:target="generar_documento">Descargando...</span>

            </div>
        </div>
    </div>
</div>


</div>
