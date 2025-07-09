<section class="bg-light app-filters">
    {{-- Because she competes with no one, no one can compete with her. --}}
    {{--
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
    --}}
    <div class="row g-3">
        <label>Aula  del plantel:</label>
        <div class="col-12 col-sm-8">
          <label for="nombre" class="form-label">Nombre:</label>
          <input class="form-control @error("plantel_nombre") is-invalid @enderror " 
            placeholder="Nombre del plantel" 
            name="plantel_nombre" 
            type="text" 
            wire:model="plantel_nombre"
            disabled 
            >
           
        </div>

        <div class="col-12 col-sm-4">
          <label for="abreviatura" class="form-label">CCT:</label>
          <input class="form-control @error("plantel_cct") is-invalid @enderror " 
            placeholder="Abreviatura" 
            name="plantel_cct" 
            type="text" 
            wire:model="plantel_cct"
            disabled 
            >
            
        </div>
    </div>
    <hr>
    <div class="row g-3">
        <label>Datos del aula:</label>
        <div class="col-12 col-sm-6">
          <label class="form-label">Tipo de Aula:</label>
          <select class="form-control @error("tipo_aula_id") is-invalid @enderror " 
            name="tipo_aula_id" 
            wire:model="tipo_aula_id"
            >
            <option></option>
            @foreach($aulas_tipo as $at)
                <option value="{{$at->id}}">{{$at->descripcion}}</option>
            @endforeach
          </select>
           @error('tipo_aula_id')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>

        <div class="col-12 col-sm-6">
          <label class="form-label">Condición del Aula:</label>
          <select class="form-control @error("condicion_aula_id") is-invalid @enderror " 
            name="condicion_aula_id" 
            wire:model="condicion_aula_id"
            >
            <option></option>
            @foreach($aulas_condicion as $ac)
                <option value="{{$ac->id}}">{{$ac->descripcion}}</option>
            @endforeach
          </select>
          @error('condicion_aula_id')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-3 pt-2">
        <div class="col-12 col-sm-6">
          <label class="form-label">Nombre del aula:</label>
          <input class="form-control @error("nombre") is-invalid @enderror " 
            placeholder="Nombre del aula" 
            name="nombre" 
            type="text" 
            wire:model="nombre"
             
            >
           @error('nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-6">
        <label class="form-label">Estatus del aula:</label>
          <div class="custom-control custom-checkbox pt-2" >
              <input class="custom-control-input" type="checkbox" id="customCheckbox2" wire:model="aula_activa">
              <label for="customCheckbox2" class="custom-control-label">Aula Activa</label>
          </div>
           
        </div>
    </div>
    <div class="row g-3 pt-2">
        <div class="col-12 col-sm-12">
          <label class="form-label">Descripción (opcional):</label>
          <textarea class="form-control" rows="3" placeholder="Descripción opcional" wire:model="descripcion"></textarea>
        </div>
        @error('descripcion')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
    </div>

    <div class="row g-3 mt-3">
        <div class="col-sm-8">
        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
        </div>
    </div>

</div>
