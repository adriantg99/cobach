<div class="row">
    <div class="col-md-12">
    <form class="form-horizontal">
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
        <div class="form-group row">
            <label for="nombre" class="col-sm-2 col-form-label">Nombre(s):</label>
            <div class="col-sm-10">
              <input type="text" 
                    class="form-control" 
                    placeholder="Nombre(s)" 
                    wire:model="nombre"
                    onkeyup="javascript:this.value=this.value.toUpperCase();">
            </div>
            @error('nombre')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
        </div>

        <div class="form-group row">
            <label for="apellido1" class="col-sm-2 col-form-label">Primer Apellido:</label>
            <div class="col-sm-10">
              <input type="text" 
                    class="form-control" 
                    placeholder="Apellido 1" 
                    wire:model="apellido1"
                    onkeyup="javascript:this.value=this.value.toUpperCase();">
            </div>
            @error('apellido1')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
        </div>

        <div class="form-group row">
            <label for="apellido2" class="col-sm-2 col-form-label">Segundo Apellido:</label>
            <div class="col-sm-10">
              <input type="text" 
                    class="form-control" 
                    placeholder="Apellido 2" 
                    wire:model="apellido2"
                    onkeyup="javascript:this.value=this.value.toUpperCase();">
            </div>
            @error('apellido2')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
        </div>

        <div class="form-group row">
            <label for="fecha_nac" class="col-sm-2 col-form-label">Fecha de nacimiento:</label>
            <div class="col-sm-2">
              <input type="date" 
                    class="form-control"                     
                    wire:model="fecha_nac">
            </div>
            @error('fecha_nac')<label class="alert-danger">{{ $message }}</label>@enderror
        </div>

        <div class="form-group row">
            <label for="expediente" class="col-sm-2 col-form-label">Expediente:</label>
            <div class="col-sm-10">
              <input type="text" 
                    class="form-control" 
                    placeholder="Número de expediente (4 dígitos)" 
                    wire:model="expediente"
                    onkeyup="javascript:this.value=this.value.toUpperCase();">
            </div>
            @error('expediente')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
        </div>

        <div class="form-group row">
            <label for="correo_personal" class="col-sm-2 col-form-label">Correo personal:</label>
            <div class="col-sm-10">
              <input type="text" 
                    class="form-control" 
                    placeholder="Correo electrónico personal" 
                    wire:model="correo_personal"
                    >
            </div>
            @error('correo_personal')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
        </div>

        <div class="form-group row">
            <label for="telefono" class="col-sm-2 col-form-label">Teléfono personal:</label>
            <div class="col-sm-10">
              <input type="text" 
                    class="form-control" 
                    placeholder="Teléfono personal (10 dígitos)" 
                    wire:model="telefono"
                    >
            </div>
            @error('correo_personal')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
        </div>

        <div class="form-group row">
            <label for="num_planteles" class="col-sm-2 col-form-label">Cantidad de planteles en los que labora:</label>
            <div class="col-sm-10">
              <select class="form-control" wire:model="num_planteles">
                <option></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </div>
            @error('num_planteles')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
        </div>

        
        @if($user_id == Auth()->user()->id)
        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <button type="button" class="btn btn-danger" wire:click="guardar">Guardar sección 1</button>
            </div>
        </div>
        @endif
    </form>
    </div>
</div>


