<div class="row">
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    @if($mensaje)
        <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                   
                        <li>{{ $mensaje }}</li>
                    
                </ul>
            </div>
    @endif

    @if($num_planteles >= 1)
    <hr>
        <div class="form-group row">
            <label for="plantel_1" class="col-sm-2 col-form-label">Seleccione el plantel donde labora 1:</label>
            <div class="col-sm-10">
              <select class="form-control" wire:model="plantel_id_1" >
                <option></option>
                @foreach($planteles as $plantel)
                    <option value="{{$plantel->id}}">{{$plantel->nombre}}</option>
                @endforeach
              </select>
              @error('plantel_id_1')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
            </div>
            
        </div>
        
    @endif

    @if($num_planteles >= 2)
    <hr>
        <div class="form-group row">
            <label for="plantel_2" class="col-sm-2 col-form-label">Seleccione el plantel donde labora 2:</label>
            <div class="col-sm-10">
              <select class="form-control" wire:model="plantel_id_2" >
                <option></option>
                @foreach($planteles as $plantel)
                    <option value="{{$plantel->id}}">{{$plantel->nombre}}</option>
                @endforeach
              </select>
              @error('plantel_id_2')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
            </div>
            
        </div>
        
    @endif

    @if($num_planteles >= 3)
    <hr>
        <div class="form-group row">
            <label for="plantel_3" class="col-sm-2 col-form-label">Seleccione el plantel donde labora 3:</label>
            <div class="col-sm-10">
              <select class="form-control" wire:model="plantel_id_3" >
                <option></option>
                @foreach($planteles as $plantel)
                    <option value="{{$plantel->id}}">{{$plantel->nombre}}</option>
                @endforeach
              </select>
              @error('plantel_id_3')<label class="alert-danger offset-md-2">{{ $message }}</label>@enderror
            </div>
            
        </div>
        
    @endif

    @if($user_id == Auth()->user()->id)
    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <button type="button" class="btn btn-danger" wire:click="guardar">Guardar sección 2</button>
        </div>
    </div>
    @endif


</div>
