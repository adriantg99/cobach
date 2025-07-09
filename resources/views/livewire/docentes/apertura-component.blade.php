<div>
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Apertura de captura</p>
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
                                <option value="{{ $planteles->id }}">
                                    {{ $planteles->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parcial</label>
                        <select class="form-select" id="parcial_seleccionado" wire:model="parcial_seleccionado"
                            name="parcial_seleccionado">
                            <option value="0" selected>Seleccionar Parcial</option>
                            <option value="P1">P1</option>
                            <option value="P2">P2</option>
                            <option value="P3">P3</option>
                            <option value="Final">Final</option>
                            <option value="R">Repetición</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de cierre</label>
                        <input class="form-select" wire:model="fecha_cierre" type="datetime-local" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Docente</label>
                        <select class="form-select" id="docente_seleccionar" wire:model="docente_seleccionar"
                            name="docente_seleccionar">
                            <option value="0" selected>Sin seleccionar</option>
                            @if ($docente)
                                @foreach ($docente as $docentes)
                                    <option value="{{ $docentes->id }}">{{ $docentes->apellido1 }}
                                        {{ $docentes->apellido2 }} {{ $docentes->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grupo</label>
                        <select class="form-select" id="grupo_seleccionar" wire:model="grupo_seleccionar"
                            name="grupo_seleccionar">
                            <option value="0" selected>Sin seleccionar</option>
                            @if ($grupo)
                                @foreach ($grupo as $grupos_docente)
                                    <option value="{{ $grupos_docente->id }}">{{ $grupos_docente->nombre }}
                                        ---
                                        @if ($grupos_docente->gpo_base == '1')
                                        @else
                                            {{ $grupos_docente->descripcion }}
                                        @endif
                                        @switch($grupos_docente->turno_id)
                                            @case(1)
                                                Matutino
                                            @break

                                            @default
                                                Vespertino
                                        @endswitch
                                        -- {{ $grupos_docente->materia }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button wire:click="guardar_apertura" class="btn btn-primary float-end">Guardar apertura</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('falta_fecha', function(data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'danger',
                    title: 'Favor de capturar fecha',
                    showConfirmButton: false,
                    timer: 10000
                });
                // Aquí puedes realizar cualquier acción con los datos recibidos
            });
        });
        

        document.addEventListener('livewire:load', function() {
            Livewire.on('sin_grupos', function(data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'danger',
                    title: 'El docente no tiene grupos asignados',
                    showConfirmButton: false,
                    timer: 10000
                });
                // Aquí puedes realizar cualquier acción con los datos recibidos
            });
        });

        document.addEventListener('livewire:load', function() {
            Livewire.on('falta_plantel', function(data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'danger',
                    title: 'Seleccionar un plantel.',
                    showConfirmButton: false,
                    timer: 10000
                });
                // Aquí puedes realizar cualquier acción con los datos recibidos
            });
        });

        document.addEventListener('livewire:load', function() {
            Livewire.on('falta_parcial', function(data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'danger',
                    title: 'Favor de capturar Parcial',
                    showConfirmButton: false,
                    timer: 10000
                });
                // Aquí puedes realizar cualquier acción con los datos recibidos
            });
        });


    </script>
</div>
