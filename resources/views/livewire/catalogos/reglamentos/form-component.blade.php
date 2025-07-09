{{-- ANA MOLINA 02/08/2023 --}}
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
            placeholder="Nombre del reglamento"
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
     <div class="row g-3 mt-3">

        <div class="col-12 col-sm-6">

            <label for="id_areaformacion_change" class="form-label">Area de Formación:</label>
            <select class="form-control @error("id_areaformacion_change") is-invalid @enderror"
              name="id_areaformacion_change"
              wire:model="id_areaformacion_change"
              wire:change="changeEventareaformacion($event.target.value)">
              <option  hidden selected>Selecciona un área de formación</option>
              @foreach($areasformacion as $areaformacion)
                  <option value="{{$areaformacion->id}}">{{$areaformacion->id}} - {{$areaformacion->nombre}}</option>
              @endforeach
            </select>
            @error('id_areaformacion_change')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror

            <label  class="form-label">Políticas del Área de Formación:</label>
            <select name="sel_idpolitica"
            wire:model="sel_idpolitica"
            class="form-control"
            wire:change="changeEvent($event.target.value)"

            multiple
            >
                @foreach ($politicas as $politica)
                        <option value="{{ $politica->id }}">
                            {{ $politica->nombre }}
                        </option>
                    @endforeach
                </select>
        </div>
        <div class="col-12 col-sm-1 align-items-center justify-content-center">

            <div class="row g-1 align-items-center justify-content-center">
                <div class="col-sm-8">
                    @can('reglamento-editar')
                <button class="btn btn-secondary "
                 wire:click.prevent="asignar();"
                >Asignar</button>
                @endcan
                </div>
                <div class="col-sm-8">
                @can('reglamento-editar')
                <button class="btn btn-secondary  "
                wire:click.prevent="quitar();"
                >Quitar</button>
                @endcan
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5">
            <label  class="form-label">Políticas asignadas:</label>
            <select name="sel_idpoliticaasi"
                            wire:model="sel_idpoliticaasi"
                            class="form-control"
                            wire:change="changeEventasi($event.target.value)"

                            multiple
                            >
                                @foreach ($politicasasi as $politicaa)
                                        <option value="{{ $politicaa['id_politica'] }}">
                                            {{ $politicaa['nombre'] }} ({{$politicaa['areaformacion'] }})

                                            </option>
                                    @endforeach
                                </select>
        </div>

    </div>
    <div class="error-message"><span style="color:red;">
        @if (session()->has('message'))
                {{ session('message') }}

        @endif
    </span></div>
</section>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    window.addEventListener('alert', event => {
                 toastr[event.detail.type](event.detail.message,
                 event.detail.title ?? ''), toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                    }
                });
    </script>
