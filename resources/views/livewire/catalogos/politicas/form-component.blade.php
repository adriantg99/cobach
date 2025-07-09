{{-- ANA MOLINA 29/06/2023 --}}
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
    <div class="row g-1">

        <div class="col-12 col-sm-12">
          <label for="nombre" class="form-label">Nombre:</label>
          <input class="form-control @error("nombre") is-invalid @enderror"
            placeholder="Nombre de la política de evaluación"
            name="nombre"
            type="text"
            wire:model="nombre">
            @error('nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>

    <div class="row g-1">
        <div class="col-12 col-sm-12">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" wire:model="descripcion" placeholder="Descripción"
                rows="2" @error("descripcion") is-invalid @enderror></textarea>
            @error('descripcion')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-2">
        <div class="col-12 col-sm-6">
            <label for="id_areaformacion" class="form-label">Area de Formación:</label>
            <select class="form-control @error("id_areaformacion") is-invalid @enderror"
              name="id_areaformacion"
              wire:model="id_areaformacion">
              <option  hidden selected>Selecciona un área de formación</option>
              @foreach($areasformacion as $areaformacion)
                  <option value="{{$areaformacion->id}}">{{$areaformacion->id}} - {{$areaformacion->nombre}}</option>
              @endforeach
            </select>
            @error('id_areaformacion')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-6">
            <label for="id_variabletipo" class="form-label">Tipo de variable:</label>
            <select class="form-control @error("id_variabletipo") is-invalid @enderror"
              name="id_variabletipo"
              wire:model="id_variabletipo"
              wire:change="changeEventLetra($event.target.value)">
              <option  hidden selected>Selecciona un tipo de variable</option>
              @foreach($variablestipo as $variabletipo)
                  <option value="{{$variabletipo->id}}">{{$variabletipo->id}} - {{$variabletipo->nombre}}</option>
              @endforeach
            </select>
            @error('id_variabletipo')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-1">
        <div class="col-12 col-sm-12">
            <label for="formula" class="form-label">Fórmula:</label>
            <label for="formula" class="form-control-plaintext">{{$formula}}</label>

        </div>
    </div>
    <div class="row g-2 mt-2">
          <div class="col-12 col-sm-4">
            <label for="calificacionminima" class="form-label">Calificación mínima aprobatoria:</label>
            <label for="calificacionminima" class="form-control-plaintext">{{$calificacionminima}}</label>

          </div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-sm-8">
        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
        </div>
    </div>
    <div class="card">
        @if ($politica_id>0)

            <div class="card-header">
                Variables
            </div>
            <div class="card-body">

                <table class="table" id="variables_table">
                    <thead>
                    <tr>
                        <th>Periodo</th>
                        <th>Es Regularización?</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($variablesPolitica as $index => $variablePolitica)
                            <tr>
                                <td>
                                    @if  ($habvar [$index])
                                    <select name="variablesPolitica[{{$index}}][id_variableperiodo]"
                                                wire:model="variablesPolitica.{{$index}}.id_variableperiodo"
                                                class="form-control"
                                                wire:change="changeEvent($event.target.value,{{$index}},'variablesPolitica[{{$index}}][id_variableperiodo]')"
                                                >
                                            <option value="">Selecciona una variable</option>
                                            @foreach ($variablesperiodo as $variableperiodo)
                                                <option value="{{ $variableperiodo->id }}">
                                                    {{ $variableperiodo->nombre }} - {{ $variableperiodo->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                @else
                                    {{$varsel[ $index]}}
                                @endif
                                </td>
                                <td>
                                    <input type="checkbox"  name="variablesPolitica[{{$index}}][esregularizacion]"
                                    wire:model="variablesPolitica.{{$index}}.esregularizacion"
                                    wire:change="changeEvent_seleccionado({{$index}},{{$this->variablesPolitica[$index]['id_variableperiodo']}})"
                                    @if  ($this->variablesPolitica[$index]['esregularizacion']) checked="checked" @endif  >
                                </td>
                                <td>
                                    {{-- @can('politica-borrar') --}}
                                    <button class="btn btn-warning btn-sm"
                                    wire:click.prevent="eliminarVariable({{$index}});"

                                        >Eliminar Variable</button>
                                    {{-- @endcan --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                 <div class="error-message"><span style="color:red;">
                    @if (session()->has('message'))
                            {{ session('message') }}

                    @endif
                </span></div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-secondary"
                            wire:click.prevent="agregarVariable">Agregar Variable</button>
                    </div>
                </div>

                @if ($variable_esletra)
                    <table class="table" id="variables_letra">
                        <thead>
                        <tr>
                            <th>Valor</th>
                            <th>Descripción</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($variablesletra as   $variableletra)
                                <tr>
                                    <td>
                                        {{ $variableletra->valor }}

                                    </td>
                                    <td>
                                        {{ $variableletra->descripcion }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif

    </div>
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
