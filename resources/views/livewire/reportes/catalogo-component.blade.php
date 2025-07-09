<div>
    {{-- Be like water. --}}
        <div class="card shadow" id="principal">
            <div class="card-header py-3">
                <p class="text-primary m-0 fw-bold">Catalogo de alumnos</p>
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
                                    <option value="{{ $planteles->id }}" @unlessrole('control_escolar') @unlessrole('control_escolar_'.$planteles->abreviatura) disabled @endunlessrole @endunlessrole>
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
                    <div class="col-md-6 d-flex">
                        <div class="mb-3" style="padding-right: 3%">
                            <label class="form-label">Foto Certificado</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" wire:model="foto" wire:change="checkbox('foto')" style="width: 2.5em; height: 1.5em;" type="checkbox" id="flexSwitchCheckChecked">
                              </div>    
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto credenciales</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" wire:model="foto_credencial" wire:change="checkbox('foto_credencial')" style="width: 2.5em; height: 1.5em;" type="checkbox" id="flexSwitchCheckChecked_2">
                              </div>    
                        </div>
                        
                    </div>
                    @endif
                  
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button wire:click="generar_documento" wire:loading.attr="disabled" wire:loading.class="bg-secondary" wire:loading.remove class="btn btn-primary float-end">Imprimir documento</button>
                        <span wire:loading wire:target="generar_documento" >Generando documento...</span>
    
                    </div>
                </div>
            </div>
        </div>
    
        <script>
            document.addEventListener('livewire:load', function () {
                Livewire.on('logMessage', function (message) {
                    console.log(message); // Aquí se imprime el mensaje en la consola del navegador
                });
            });
        </script>
</div>
