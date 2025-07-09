{{-- ANA MOLINA 28/06/2023 --}}
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
            placeholder="Nombre de la asignatura"
            name="nombre"
            type="text"
            wire:model="nombre">
            @error('nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-2">
        <div class="col-12 col-sm-6">
            <label for="id_areaformacion" class="form-label">Area de Formación:</label>
            <select class="form-control @error("id_areaformacion") is-invalid @enderror"
              name="id_areaformacion"
              wire:model="id_areaformacion"
              wire:change="changeEventareaformacion($event.target.value)"
              >
              <option  hidden selected>Selecciona un área de formación</option>
              @foreach($areasformacion as $areaformacion)
                  <option value="{{$areaformacion->id}}">{{$areaformacion->id}} - {{$areaformacion->nombre}}</option>
              @endforeach
            </select>
            @error('id_areaformacion')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-6">
            <label for="id_nucleo" class="form-label">Núcleo:</label>
            <select class="form-control @error("id_nucleo") is-invalid @enderror"
              name="id_nucleo"
              wire:model="id_nucleo"
              wire:change="changeEventnucleo($event.target.value)"
              >
              <option  hidden selected>Selecciona un núcleo</option>
              @foreach($nucleos as $nucleo)
                  <option value="{{$nucleo->clave_consecutivo}}">{{$nucleo->clave_consecutivo}} - {{$nucleo->nombre}}</option>
              @endforeach
            </select>
            @error('id_nucleo')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>

    </div>
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-4">
          <label for="periodo" class="form-label">Periodo:</label>
          <select class="form-control @error("periodo") is-invalid @enderror"
              name="periodo"
              wire:model="periodo"
              wire:change="changeEventperiodo($event.target.value)"
              >
              <option  hidden selected>Selecciona un periodo</option>
              <option value="1" >1</option>
              <option value="2" >2</option>
              <option value="3" >3</option>
              <option value="4" >4</option>
              <option value="5" >5</option>
              <option value="6" >6</option>

            </select>
            @error('periodo')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>

        <div class="col-12 col-sm-4">
            <label for="consecutivo" class="form-label">Consecutivo:</label>
            <input class="form-control @error("consecutivo") is-invalid @enderror"
              placeholder="Consecutivo"
              name="consecutivo"
              type="text"
              wire:model="consecutivo"
              wire:change="changeEventconsecutivo($event.target.value)"
              >
              @error('cct')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>

          <div class="col-12 col-sm-4">
            <label for="clave" class="form-label">Clave:</label>
            <label class="form-label">{{$clave}}</label>
            <input class="form-control @error("clave") is-invalid @enderror"
              placeholder="Clave"
              name="clave"
              type="text"
              wire:model="clave">
              @error('clave')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>


    </div>
    <div class="row g-2 mt-2">
        <div class="col-12 col-sm-6">
          <label for="creditos" class="form-label">Créditos:</label>
          <input class="form-control @error("creditos") is-invalid @enderror"
            placeholder="Créditos"
            name="creditos"
            type="text"
            wire:model="creditos">
            @error('creditos')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>


          <div class="col-12 col-sm-4">
            <label for="horas_semana" class="form-label">Horas a la semana:</label>
            <input class="form-control @error("horas_semana") is-invalid @enderror"
              placeholder="Horas a la semana"
              name="horas_semana"
              type="text"
              wire:model="horas_semana">
              @error('horas_semana')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>


    </div>
    <div class="row g-1">
        <div class="col-12 col-sm-12">
          <label for="nombre_completo" class="form-label">Nombre completo:</label>
          <input class="form-control @error("nombre_completo") is-invalid @enderror"
            placeholder="Nombre Completo"
            name="nombre_completo"
            type="text"
            wire:model="nombre_completo">
            @error('nombre_completo')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>


    </div>

    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-2">
            <label for="boleta" class="form-label  @error("boleta") is-invalid @enderror" > <input type="checkbox"  name="boleta"  wire:model="boleta"> Boleta</label>
              @error('boleta')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
        <div class="col-12 col-sm-2">
          <label for="kardex" class="form-label  @error("kardex") is-invalid @enderror" > <input type="checkbox"  name="kardex"  wire:model="kardex"> Kardex</label>
            @error('kardex')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-2">
            <label for="expediente" class="form-label  @error("expediente") is-invalid @enderror" > <input type="checkbox"  name="expediente"  wire:model="expediente"> Expediente</label>
              @error('expediente')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-2">
            <label for="certificado" class="form-label  @error("certificado") is-invalid @enderror" > <input type="checkbox"  name="certificado"  wire:model="certificado"> Certificado</label>
              @error('certificado')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-2">
            <label for="optativa" class="form-label  @error("optativa") is-invalid @enderror" > <input type="checkbox"  name="optativa"  wire:model="optativa"> Optativa</label>
              @error('optativa')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-2">
            <label for="activa" class="form-label  @error("activa") is-invalid @enderror" > <input type="checkbox"  name="activa"  wire:model="activa" checked> Activa</label>
              @error('activa')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-2">
            <label for="afecta_promedio" class="form-label  @error("afecta_promedio") is-invalid @enderror" > <input type="checkbox"  name="afecta_promedio"  wire:model="afecta_promedio" checked> afecta_promedio</label>
              @error('afecta_promedio')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>

    </div>

    <div class="row g-3 mt-3">
        <div class="col-sm-8">
        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
        </div>
    </div>
</section>
