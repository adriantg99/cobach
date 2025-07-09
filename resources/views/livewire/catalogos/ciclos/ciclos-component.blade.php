<div>
    {{-- Be like water. --}}

    <section class="bg-light app-filters">
        <h3>
            @if ($ciclo)
                Editar ciclo escolar ID: {{ $ciclo->id }}
            @else
                Guardar nuevo ciclo escolar
            @endif
        </h3>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <div class="col-12 col-sm-8">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input class="form-control" placeholder="Nombre del ciclo escolar" name="nombre" type="text"
                        wire:model="datos_ciclo.nombre">
                </div>

                <div class="col-12 col-sm-8">
                    <label for="abreviatura" class="form-label">Abreviatura:</label>
                    <input class="form-control" placeholder="Abreviatura del ciclo escolar" name="abreviatura"
                        type="text" wire:model="datos_ciclo.abreviatura">
                </div>

                <div class="col-12 col-sm-8">
                    <label for="per_inicio" class="form-label">Inicio del periodo:</label>
                    <input class="form-control" name="per_inicio" type="date" wire:model="datos_ciclo.per_inicio">
                </div>

                <div class="col-12 col-sm-8">
                    <label for="per_final" class="form-label">Final del periodo:</label>
                    <input class="form-control" name="per_final" type="date" wire:model="datos_ciclo.per_final">
                </div>
                <hr>                
                @if ($datos_ciclo['tipo'] === "N" && $datos_ciclo['activo'])
                <div class="col-12 col-sm-12">
                    <label for="inicio_inscripcion" class="form-label">Inicio Inscripcion:</label>
                    <input class="form-control" name="inicio_inscripcion" type="datetime-local"
                        wire:model="datos_ciclo.inicio_inscripcion">
                </div>
                @endif
                @if ($datos_ciclo['tipo'] === "P" && $datos_ciclo['activo'])                    
                <div class="col-12 col-sm-12">
                    <label for="inicio_reinscripcion" class="form-label">Inicio Reinscripcion:</label>
                    <input class="form-control" name="inicio_reinscripcion" type="datetime-local"
                        wire:model="datos_ciclo.inicio_reinscripcion">
                </div>
                @endif
                <div class="col-12 col-sm-8"><br><br>

                    <input type="hidden" name="activo" value="0">
                    <input class="checkbox" type="checkbox" id="activo" name="activo" value="1" wire:model="datos_ciclo.activo">
                    <label class="form-label"><strong>ACTIVO</strong></label>

                </div>

                <div class="col-12 col-sm-8"><br><br>
                    <select name="tipo" id="tipo" class="form-control" wire:model="datos_ciclo.tipo">
                        <option value="0">Seleccionar alguno</option>
                        <option value="N">Semestre NON</option>
                        <option value="P">Semestre PAR</option>
                        <option value="V">VERANO</option>
                    </select> 
                </div>

            </div>

            @if ($datos_ciclo['activo'] && $datos_ciclo["tipo"] != "V")
                <div class="col-12 col-sm-6">

                    <div class="row">
                        <!-- P1 Inicio -->
                        <div class="col-12 col-sm-6">
                            <label for="p1_inicio" class="form-label">P1 Inicio:</label>
                            <input class="form-control" name="p1_inicio" type="datetime-local"
                                wire:model="datos_ciclo.p1">
                        </div>

                        <!-- P1 Fin -->
                        <div class="col-12 col-sm-6">
                            <label for="fin_p1" class="form-label">P1 Fin:</label>
                            <input class="form-control" name="fin_p1" type="datetime-local"
                                wire:model="datos_ciclo.fin_p1">
                        </div>
                    </div>
                    <div class="row">
                        <!-- P2 Inicio -->
                        <div class="col-12 col-sm-6">
                            <label for="p2_inicio" class="form-label">P2 Inicio:</label>
                            <input class="form-control" name="p2_inicio" type="datetime-local"
                                wire:model="datos_ciclo.p2">
                        </div>

                        <!-- P2 Fin -->
                        <div class="col-12 col-sm-6">
                            <label for="fin_p2" class="form-label">P2 Fin:</label>
                            <input class="form-control" name="fin_p2" type="datetime-local"
                                wire:model="datos_ciclo.fin_p2">
                        </div>
                    </div>

                    <div class="row">
                        <!-- P3 Inicio -->
                        <div class="col-12 col-sm-6">
                            <label for="p3_inicio" class="form-label">P3 Inicio:</label>
                            <input class="form-control" name="p3_inicio" type="datetime-local"
                                wire:model="datos_ciclo.p3">
                        </div>

                        <!-- P2 Fin -->
                        <div class="col-12 col-sm-6">
                            <label for="fin_p3" class="form-label">P3 Fin:</label>
                            <input class="form-control" name="fin_p3" type="datetime-local"
                                wire:model="datos_ciclo.fin_p3">
                        </div>
                    </div>

                    <div class="row">
                        <!-- inicio_repeticion -->
                        <div class="col-12 col-sm-6">
                            <label for="inicio_repeticion" class="form-label">Inicio Repetición:</label>
                            <input class="form-control" name="inicio_repeticion" type="datetime-local"
                                wire:model="datos_ciclo.inicio_repeticion">
                        </div>

                        <!-- Fin Repeticion -->
                        <div class="col-12 col-sm-6">
                            <label for="fin_repeticion" class="form-label">Fin Repetición:</label>
                            <input class="form-control" name="fin_repeticion" type="datetime-local"
                                wire:model="datos_ciclo.fin_repeticion">
                        </div>
                    </div>
                    <hr>
                    @if ($datos_ciclo['tipo'] === "N" && $datos_ciclo['activo'])                    

                    <div class="col-12 col-sm-12">
                        <label for="fin_inscripcion" class="form-label">Fin Inscripcion:</label>
                        <input class="form-control" name="fin_inscripcion" type="datetime-local"
                            wire:model="datos_ciclo.fin_inscripcion">
                    </div>
                    @endif
                    @if ($datos_ciclo['tipo'] === "P" && $datos_ciclo['activo'])                    

                    <div class="col-12 col-sm-12">
                        <label for="fin_reinscripcion" class="form-label">Fin Reinscripcion:</label>
                        <input class="form-control" name="fin_reinscripcion" type="datetime-local"
                            wire:model="datos_ciclo.fin_reinscripcion">
                    </div>
                    @endif
                </div>
            @endif
        </div>
        <div class="row g-3 mt-3">
            <div class="col-sm-8">
                <button class="btn btn-primary" wire:click="guardar">Guardar</button>
            </div>
           </div>
    </section>
</div>
