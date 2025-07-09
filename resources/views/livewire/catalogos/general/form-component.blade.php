{{-- ANA MOLINA 12/05/2024 --}}
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

    <div class="card card-body">
        <div class="row g-3">
        <div class="col-12 col-sm-3">
            <label for="efirma_file_certificate" class="form-label">Certificado:</label>
            <input class="form-control @error("efirma_file_certificate") is-invalid @enderror"
              accept=".cer"
              placeholder="Certificado"
              name="efirma_file_certificate"
              type="text"
              wire:model="efirma_file_certificate" readonly>
              @error('efirma_file_certificate')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-8">
            <label class="form-label">Archivo de Certificado:</label>
            <input class="form-control @error("efirma_file_certificate1") is-invalid @enderror"
              placeholder="Archivo de Certificado"
              name="efirma_file_certificate1"
              type="file"
              accept=".cer"
              wire:model="efirma_file_certificate1" >
              @error('efirma_file_certificate1')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-3">
            <label for="efirma_file_key" class="form-label">Llave:</label>
            <input class="form-control @error("efirma_file_key") is-invalid @enderror"
              placeholder="Llave"
              name="efirma_file_key"
              type="text"
              wire:model="efirma_file_key" readonly>
              @error('efirma_file_key')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-8">
            <label class="form-label">Archivo Llave:</label>
            <input class="form-control @error("efirma_file_key1") is-invalid @enderror"
              placeholder="Archivo Llave"
              name="efirma_file_key1"
              type="file"
              accept=".key"
              wire:model="efirma_file_key1">
              @error('efirma_file_key1')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-2">
            <label for="efirma_password" class="form-label">Contraseña:</label>
            <input class="form-control @error("efirma_password") is-invalid @enderror"
              placeholder="Contraseña"
              name="efirma_password"
              type="password"
              wire:model="efirma_password">
              @error('efirma_password')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
             <div class="col-sm-8">
                <button class="btn btn-primary" wire:click="validar">Validar</button>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-12 col-sm-4">
            <label for="titulo" class="form-label">Título:</label>
            <input class="form-control @error("titulo") is-invalid @enderror"
              placeholder="Título"
              name="titulo"
              type="text"
              wire:model="titulo">
              @error('titulo')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
        <div class="col-12 col-sm-8">
          <label for="nombre" class="form-label">Nombre Autoridad:</label>
          <input class="form-control @error("nombre") is-invalid @enderror"
            placeholder="Nombre Autoridad"
            name="nombre"
            type="text"
            wire:model="nombre">
            @error('nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-6">
            <label for="rfc" class="form-label">RFC:</label>
            <input class="form-control @error("rfc") is-invalid @enderror"
              placeholder="RFC"
              name="rfc"
              type="text"
              wire:model="rfc">
              @error('rfc')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>

          <div class="col-12 col-sm-6">
            <label for="ciudad" class="form-label">Ciudad:</label>
            <input class="form-control @error("ciudad") is-invalid @enderror"
              placeholder="Ciudad"
              name="ciudad"
              type="text"
              wire:model="ciudad">
              @error('ciudad')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>

          <div>
            <div class="col-12 col-sm-8">
                <label for="efirma_nombre" class="form-label">Nombre:</label>
                <input class="form-control @error("efirma_nombre") is-invalid @enderror"
                  placeholder="Nombre"
                  name="efirma_nombre"
                  type="text"
                  wire:model="efirma_nombre" readonly>
                  @error('efirma_nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
              </div>
          <div class="col-12 col-sm-2">
            <label for="numcertificado" class="form-label">Número de Certificado:</label>
            <input class="form-control @error("numcertificado") is-invalid @enderror"
              placeholder="Número de Certificado"
              name="numcertificado"
              type="text"
              wire:model="numcertificado" readonly>
              @error('numcertificado')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-10">
            <label  for="sellodigital"  class="form-label">Sello Digital:</label>
            <input class="form-control @error("sellodigital") is-invalid @enderror"
              placeholder="Sello Digital"
              name="sellodigital"
              type="text"
              wire:model="sellodigital" readonly>
              @error('sellodigital')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
        </div>
        <div class="row g-3">
            <div class="col-12 col-sm-4">
                <label for="desde" class="form-label">Desde:</label>
                <input class="form-control @error("desde") is-invalid @enderror"
                  placeholder="Desde"
                  name="desde"
                  type="text"
                  wire:model="desde" readonly>
                  @error('desde')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
              </div>
              <div class="col-12 col-sm-4">
                <label for="hasta" class="form-label">Hasta:</label>
                <input class="form-control @error("hasta") is-invalid @enderror"
                  placeholder="Hasta"
                  name="hasta"
                  type="text"
                  wire:model="hasta" readonly>
                  @error('hasta')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
              </div>
            </div>
          <div class="col-12 col-sm-4">
            <label for="fechainicio" class="form-label">Fecha Inicio:</label>
            <input class="form-control @error("fechainicio") is-invalid @enderror"
              placeholder="Fecha Inicio"
              name="fechainicio"
              type="date"
              wire:model="fechainicio">
              @error('fechainicio')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
          <div class="col-12 col-sm-4">
            <label for="fechafinal" class="form-label">Fecha Final:</label>
            <input class="form-control @error("fechafinal") is-invalid @enderror"
              placeholder="Fecha Final"
              name="fechafinal"
              type="date"
              wire:model="fechafinal">
              @error('fechafinal')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
          </div>
    </div>
    <hr>
    <div class="row g-3 mt-3">
        <div class="col-sm-8">
        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
        </div>
    </div>
</section>
