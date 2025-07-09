{{-- ANA MOLINA 06/07/2023 --}}
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
          <label for="nombre" class="form-control-plaintext">{{$nombre}}</label>

        </div>
    </div>

    <div class="row g-1">
        <div class="col-12 col-sm-12">
            <label for="descripcion" class="form-label">Descripción:</label>
            <label for="descripcion" class="form-control-plaintext">{{$descripcion}}</label>

        </div>
    </div>
    <div class="row g-2">
        <div class="col-12 col-sm-6">
            <label for="id_areaformacion" class="form-label">Area de Formación:</label>
            <label for="id_areaformacion" class="form-control-plaintext">{{$areaformacion}}</label>

          </div>
          <div class="col-12 col-sm-6">
            <label for="id_variabletipo" class="form-label">Tipo de variable:</label>
            <label for="id_variabletipo" class="form-control-plaintext">{{$variabletipo}}</label>
          </div>
    </div>
    <div class="row g-1">
        <div class="col-12 col-sm-12">
            <label for="formula" class="form-label">Fórmula:</label>
            <textarea class="form-control" wire:model="formula" placeholder="Fórmula"
                rows="2" @error("formula") is-invalid @enderror></textarea>
            @error('formula')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-2 mt-2">
          <div class="col-12 col-sm-4">
            <label for="calificacionminima" class="form-label">Calificación mínima aprobatoria:</label>
            <input class="form-control @error("calificacionminima") is-invalid @enderror"
              placeholder="Calificación mínima aprobatoria"
              name="calificacionminima"
              type="text"
              wire:model="calificacionminima" >
              @error('calificacionminima')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
    </div>

    <div class="card">

            <div class="card-header">
                Variables
            </div>
            <div class="card-body">

                <table class="table" id="variables_table">
                    <thead>

                    </thead>
                    <tbody>
                        @foreach ($variablesPolitica as  $variablePolitica)
                            <tr>
                                <td>
                                     {{ $variablePolitica['nombre'] }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <div class="row g-2">
                <div class="col-12 col-sm-6">
                    <label for="esregularizacion" class="form-label">Límite baja del curso:</label>
                    <select class="form-control @error("esregularizacion") is-invalid @enderror"
                      name="esregularizacion"
                      wire:model="esregularizacion">
                      <option  selected>Selecciona una variable que limita que un alumno se dé de baja de un curso</option>
                      @foreach($variablesPolitica as $variablePolitica)
                          <option value="{{$variablePolitica['id_variableperiodo']}}">{{$variablePolitica['nombre']}}</option>
                      @endforeach
                    </select>
                    @error('esregularizacion')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
                  </div>
                  <div class="col-12 col-sm-6">
                    <label for="eslimite" class="form-label">Variable de regularización:</label>
                    <select class="form-control @error("eslimite") is-invalid @enderror"
                      name="eslimite"
                      wire:model="eslimite">
                      <option  selected>Selecciona una variable que contiene la calificación de regularización</option>
                      @foreach($variablesPolitica as $variablePolitica)
                          <option value="{{$variablePolitica['id_variableperiodo']}}">{{$variablePolitica['nombre']}}</option>
                      @endforeach
                    </select>
                    @error('eslimite')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
                  </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-sm-8">
                <button class="btn btn-primary" wire:click="guardar">Guardar</button>
                </div>
            </div>
    </div>
</section>
