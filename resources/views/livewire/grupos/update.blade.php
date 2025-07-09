<div>
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
    <section class="bg-light app-filters">
        <div class="row g-3">
            <div class="col-12 col-sm-4">
                <label for="cve_turno" class="form-label">Turno:</label>
                <select class="form-control @error('cve_turno') is-invalid @enderror " id="cve_turno" name="cve_turno"
                    wire:model="cve_turno">
                    <option selected></option>
                    <option value="1">Matutino</option>
                    <option value="2">Vespertino</option>
                </select>
                @error('cve_turno')
                    <div class="error-message"><span style="color:red;"></span></div>
                @enderror
            </div>
            <div class="col-12 col-sm-4">
                <label for="cve_plantel" class="form-label">Plantel:</label>
                <select class="form-control @error('cve_plantel') is-invalid @enderror " id="cve_plantel"
                    name="cve_plantel" wire:model="cve_plantel">
                    <option selected></option>
                    @foreach ($Plantel as $planteles)
                        <option value="{{ $planteles->id }}" @unlessrole('control_escolar') @unlessrole('control_escolar_'.$planteles->abreviatura) disabled @endunlessrole @endunlessrole>{{ $planteles->nombre }}</option>
                    @endforeach
                </select>
                @error('cve_plantel')
                    <div class="error-message"><span style="color:red;"></span></div>
                @enderror
            </div>
            <div class="col-12 col-sm-4">
                <label for="cve_ciclo" class="form-label">Ciclo escolar:</label>
                <select class="form-control @error('cve_ciclo') is-invalid @enderror " id="cve_ciclo" name="cve_ciclo"
                    wire:model="cve_ciclo">
                    <option selected></option>
                    @foreach ($Ciclos as $ciclos_escolares)
                        <option value="{{ $ciclos_escolares->id }}">{{ $ciclos_escolares->nombre }}</option>
                    @endforeach
                </select>
                @error('cve_ciclo')
                    <div class="error-message"><span style="color:red;"></span></div>
                @enderror
            </div>
            <div class="col-12 col-sm-4">
                <label for="cve_periodo" class="form-label">Periodo:</label>
                <select class="form-control @error('cve_periodo') is-invalid @enderror " id="cve_periodo"
                    name="cve_periodo" wire:model="cve_periodo">
                    <option selected></option>
                    <option value="1">Primero</option>
                    <option value="2">Segundo</option>
                    <option value="3">Tercero</option>
                    <option value="4">Cuarto</option>
                    <option value="5">Quinto</option>
                    <option value="6">Sexto</option>
                </select>
                @error('cve_periodo')
                    <div class="error-message"><span style="color:red;"></span></div>
                @enderror
            </div>
            <div class="col-12 col-sm-4">
                <label for="capacidad_grupo" class="form-label">Capacidad:</label>
                <input type="text" id="capacidad_grupo" name="capacidad_grupo" class="form-control"
                    placeholder="Capacidad del grupo:" wire:model="capacidad_grupo"
                    @error('capacidad_grupo') is-invalid @enderror pattern="[0-9]+"
                    title="Ingresa solo números positivos" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                @error('capacidad_grupo')
                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                @enderror
            </div>
            <div class="col-12 col-sm-4">
                <label for="aula_grupo" class="form-label">Aula:</label>
                <select class="form-control @error('aula_grupo') is-invalid @enderror " id="aula_grupo" name="aula_grupo"
                    wire:model="aula_grupo">
                    <option selected></option>
                    @foreach ($aula as $datos_aulas)
                        
                        <option value="{{ $datos_aulas->id }}">{{ $datos_aulas->nombre }}</option>
                    @endforeach
                </select>
                @error('aula_grupo')
                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                @enderror
            </div>
            <div class="col-12 col-sm-8">
                <label for="nombre_grupo" class="form-label">Nombre Grupo:</label>
                <input class="form-control @error("nombre_grupo") is-invalid @enderror " 
                  placeholder="Nombre del plantel" 
                  name="nombre_grupo" 
                  type="text" 
                  wire:model="nombre_grupo">
                  @error('nombre_grupo')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
              </div>
              <div class="col-12 col-sm-4"><br><br>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="customCheckbox2" wire:model="gpo_base">
                    <label for="customCheckbox2" class="custom-control-label"><strong>GRUPO BASE</strong></label>
                  </div>
                </div>
              <div class="col-12 col-sm-6">
                <label for="v" class="form-label">Descripción del grupo:</label>
                <textarea class="form-control" wire:model="descripcion_grupo" placeholder="Descripción del grupo"
                    rows="2" @error("descripcion_grupo") is-invalid @enderror></textarea>
                @error('descripcion_grupo')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
            </div>
            <div class="row g-3 mt-3">
                <div class="col-sm-8">
                    <button class="btn btn-primary" wire:click="actualizar">Actualizar grupo</button>
                </div>
            </div>
            <p>
                {{$mensaje}}
            </p>
    </section>

</div>
