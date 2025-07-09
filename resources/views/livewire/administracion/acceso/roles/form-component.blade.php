<section class="bg-light app-filters">
    <div class="row g-3">
        <div class="col-12 col-sm-3">
          <label for="name" class="form-label">Nombre:</label>
          <input class="form-control @error("name") is-invalid @enderror" 
            placeholder="Nombre del rol" 
            name="name" 
            type="text" 
            wire:model="name">
            @error('name')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-3 mt-3" >
        <div class="col-12 col-sm-3">
            <label for="permissions" class="form-label">Permisos:</label>
            <br>
            <button class="btn btn-primary" wire:click="guardar">Guardar</button>
        </div>
    </div>
    <div class="row g-3 mt-3" >
        @foreach($permissions as $permission)
            <div class="col-6 col-sm-2">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault{{$permission->id}}" wire:model="permission.{{$permission->id}}">
                  <label class="form-check-label" for="flexCheckDefault{{$permission->id}}">
                    {{$permission->name}}
                  </label>
                </div>
            </div>
        @endforeach

    </div>
</div>
