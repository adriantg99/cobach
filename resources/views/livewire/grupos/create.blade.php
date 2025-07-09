<div>  
  <section class="bg-light app-filters">
      <div class="row g-3">
          <div class="col-12 col-sm-4">
          @if ($mensaje == 1)
            <script>
                Swal.fire({
                icon: "error",
                title: "No tiene permiso para agregar Grupos",
                text: "Solicitar a su administrador los permisos correspondientes",
            });               
            </script>
          @endif
              <label for="cve_turno" class="form-label">Turno:</label>
              <select class="form-control @error('cve_turno') is-invalid @enderror " id="cve_turno" name="cve_turno" wire:model="cve_turno">
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
              <select class="form-control @error('cve_plantel') is-invalid @enderror " id="cve_plantel" name="cve_plantel" wire:model="cve_plantel">
                  <option selected></option>
                    @foreach ($Plantel as $planteles)
                        <option value="{{$planteles->id}}" @unlessrole('control_escolar') @unlessrole('control_escolar_'.$planteles->abreviatura) disabled @endunlessrole @endunlessrole>{{$planteles->nombre}}</option>
                    @endforeach
              </select>
              @error('cve_plantel')
                  <div class="error-message"><span style="color:red;"></span></div>
              @enderror
          </div>
          <div class="col-12 col-sm-4">
              <label for="cve_ciclo" class="form-label">Ciclo escolar:</label>
              <select class="form-control @error('cve_ciclo') is-invalid @enderror " id="cve_ciclo" name="cve_ciclo" wire:model="cve_ciclo">
                <option selected></option>
                @foreach ($Ciclos as $ciclos_escolares)
                        <option value="{{$ciclos_escolares->id}}">{{$ciclos_escolares->nombre}}</option>
                  @endforeach
              </select>
              @error('cve_ciclo')
                  <div class="error-message"><span style="color:red;"></span></div>
              @enderror
          </div>
          <div class="col-12 col-sm-4">
              <label for="cve_periodo" class="form-label">Periodo:</label>
              <select class="form-control @error('cve_periodo') is-invalid @enderror " id="cve_periodo" name="cve_periodo" wire:model="cve_periodo">
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
            <input type="text" id="capacidad_grupo" name="capacidad_grupo" class="form-control" placeholder="Capacidad del grupo:"
                wire:model="capacidad_grupo" @error('capacidad_grupo') is-invalid @enderror
                pattern="[0-9]+" title="Ingresa solo números positivos" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            @error('capacidad_grupo')
                <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
            @enderror
        </div>
          <div class="col-12 col-sm-4">
            <label for="cantidad_grupos" class="form-label">Cantidad de grupos a crear:</label>
            <select class="form-control @error('cantidad_grupos') is-invalid @enderror " id="cantidad_grupos" name="cantidad_grupos" wire:model="cantidad_grupos">
                <option value="0" selected>0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
          </select>
            @error('cantidad_grupos')
                <div class="error-message"><span style="color:red;"></span></div>
            @enderror
        </div>
        <div class="col-12 col-sm-4">
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="customCheckbox2" wire:model="grupos_base">
            <label for="customCheckbox2" class="custom-control-label"><strong>GRUPO(s) BASE</strong></label>
          </div>
        </div>
  
          <div class="row g-3 mt-3">
              <div class="col-sm-8">
                  <button class="btn btn-primary" wire:click="guardar">Guardar</button>
              </div>
          </div>

          <div>
          </div>
  </section>

  
</div>
