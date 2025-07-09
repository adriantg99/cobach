{{-- ANA MOLINA 27/06/2023 --}}
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

        <div class="col-12 col-sm-8">
          <label for="nombre" class="form-label">Nombre:</label>
          <input class="form-control @error("nombre") is-invalid @enderror"
            placeholder="Nombre del área de formación"
            name="nombre"
            type="text"
            wire:model="nombre">
            @error('nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>


    </div>

    <hr>


    <div class="row g-3 mt-3">
        <div class="col-sm-8">
        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
        </div>
    </div>
</section>
