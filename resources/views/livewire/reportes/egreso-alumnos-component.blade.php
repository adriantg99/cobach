<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}


    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Reporte de alumnos egresados del ciclo escolar anterior</p>
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
                                <option value="{{ $planteles->id }}">
                                        {{ $planteles->nombre }}
                                    </option>
                                @endforeach
                            @endif

                        </select>
                    </div>
                </div>
              

             
              
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button wire:click="generar_documento" wire:loading.attr="disabled" wire:loading.class="bg-secondary" wire:loading.remove class="btn btn-primary float-end">Imprimir documento</button>
                    <span wire:loading wire:target="generar_documento" >Generando documento...</span>

                </div>
            </div>
        </div>
    </div>
</div>
