<section class="bg-light app-filters">
  {{--@if ($errors->any())
      <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif --}}
  <div class="row g-3">

      <div class="col-12 col-sm-3">
        <label for="name" class="form-label">Nombre:</label>
        <input class="form-control @error("name") is-invalid @enderror" 
          placeholder="Nombre del usuario" 
          name="name" 
          type="text" 
          wire:model="name">
          @error('name')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
      </div>

      <div class="col-12 col-sm-3">
        <label for="role" class="form-label">Correo electrónico:</label>
        <input class="form-control @error("email") is-invalid @enderror" 
          placeholder="Correo electrónico" 
          name="email" 
          type="email" 
          wire:model="email">
          @error('email')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
      </div>
  </div>
  <div class="row g-3 mt-3">
      <div class="col-12 col-sm-3 ">
        <label for="role" class="form-label">Contraseña:</label>
        <input class="form-control @error("password") is-invalid @enderror" 
          placeholder="Contraseña" 
          name="password" 
          type="password" 
          wire:model="password">
          @error('password')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
      </div>

      <div class="col-12 col-sm-3">
        <label for="role" class="form-label">Confirmar Contraseña:</label>
        <input class="form-control @error("password") is-invalid @enderror" 
          placeholder="Confirmar Contraseña" 
          name="password_confirmation" 
          type="password"
          wire:model="password_confirmation">
      </div>
  </div>
  <div class="row g-3 mt-3">
    <div class="col-12 col-sm-6 ">
        <label for="role" class="form-label">Roles:</label>
          <select class="form-control" placeholder="Seleccione los roles del usuario..." wire:model="rols" multiple>
            @foreach($roles as $rol)
              <option value="{{$rol->id}}">{{$rol->name}}</option>
            @endforeach
          </select>

          @error('cont_rols')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
      </div>
  </div>
  <div class="row g-3 mt-3">
      <div class="col-sm-8">
      <button class="btn btn-primary" wire:click="guardar">Guardar</button>
      </div>
  </div>
</section>
