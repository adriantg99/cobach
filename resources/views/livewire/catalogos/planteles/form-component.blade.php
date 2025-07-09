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
          <input class="form-control @error("nombre") is-invalid @enderror " 
            placeholder="Nombre del plantel" 
            name="nombre" 
            type="text" 
            wire:model="nombre">
            @error('nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>

        <div class="col-12 col-sm-4">
          <label for="abreviatura" class="form-label">Abreviatura:</label>
          <input class="form-control @error("abreviatura") is-invalid @enderror " 
            placeholder="Abreviatura" 
            name="abreviatura" 
            type="text" 
            wire:model="abreviatura">
            @error('abreviatura')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-4">
          <label for="cct" class="form-label">CCT:</label>
          <input class="form-control @error("cct") is-invalid @enderror " 
            placeholder="Clave de Centro de Trabajo" 
            name="cct" 
            type="text" 
            wire:model="cct">
            @error('cct')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-4">
          <label for="cve_mun" class="form-label">Municipio:</label>
          <select class="form-control @error("cve_mun") is-invalid @enderror " 
            name="cve_mun" 
            wire:model="cve_mun">
            <option></option>
            @foreach($municipios as $municipio)
                <option value="{{$municipio->cve_mun}}">{{$municipio->cve_mun}} - {{$municipio->nom_mun}}</option>
            @endforeach
          </select>
          @error('cve_mun')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-4">
          <label for="cve_loc" class="form-label">Localidade:</label>
          <select class="form-control @error("cve_loc") is-invalid @enderror " 
            name="cve_loc" 
            wire:model="cve_loc"{{$localidades!=null? '':' disabled'}}>
            <option></option>
            @if($localidades)
            @foreach($localidades as $localidad)
                <option value="{{$localidad->cve_loc}}">{{$localidad->cve_loc}} - {{$localidad->nom_loc}}</option>
            @endforeach
            @endif
          </select>
          @error('cve_loc')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>

    </div>
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-8">
          <label for="cct" class="form-label">Domicilio:</label>
          <input class="form-control @error("domicilio") is-invalid @enderror " 
            placeholder="Clave de Centro de Trabajo" 
            name="domicilio" 
            type="text" 
            wire:model="domicilio">
            @error('domicilio')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-2">
            <label for="zona" class="form-label">Zona:</label>
            <select class="form-control @error("zona") is-invalid @enderror "
            name="zona" 
            wire:model="zona">
                <option value=""></option>
                <option value="ZNO">ZNO</option>
                <option value="ZNE">ZNE</option>
                <option value="ZC">ZC</option>
                <option value="ZNE">ZNE</option>
                <option value="ZS">ZS</option>
            </select>
            @error('zona')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-2">
            <label for="zona" class="form-label">Coordenadas:</label>
            <input type="text" name="coordenadas" class="form-control"
            placeholder="Coordenadas"  wire:model="coordenadas" @error("coordenadas") is-invalid @enderror>
            @error('coordenadas')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-6">
            <label for="plan_domicilio" class="form-label">Calle(s) y Número:</label>
            <input type="text" name="plan_domicilio" class="form-control"
            placeholder="Calle(s) y Número"  wire:model="plan_domicilio" @error("plan_domicilio") is-invalid @enderror>
            @error('plan_domicilio')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-2">
            <label for="plan_codigopostal" class="form-label">Código Postal:</label>
            <input type="text" name="plan_codigopostal" class="form-control"
            placeholder="Código Postal"  wire:model="plan_codigopostal" @error("plan_codigopostal") is-invalid @enderror>
            @error('plan_codigopostal')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-4">
            <label for="plan_codigopostal" class="form-label">Colonia:</label>
            <input type="text" name="plan_colonia" class="form-control"
            placeholder="Colonia" wire:model="plan_colonia" @error("plan_colonia") is-invalid @enderror>
            @error('plan_colonia')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-6">
            <label for="plan_acceso" class="form-label">Descripción de Acceso:</label>
            <textarea class="form-control" wire:model="plan_acceso" placeholder="Descripción de Acceso"
                rows="2" @error("plan_acceso") is-invalid @enderror></textarea>
            @error('plan_acceso')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-3">
            <label for="plan_email" class="form-label">Correo electrónico del plantel:</label>
            <input type="email" name="plan_email" class="form-control"
            placeholder="Correo electrónico del plantel"  wire:model="plan_email" @error("plan_email") is-invalid @enderror>
            @error('plan_email')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-3">
            <label for="plan_telefono" class="form-label">Teléfono del plantel:</label>
            <input type="text" name="plan_telefono" class="form-control"
            placeholder="Teléfono del plantel:" wire:model="plan_telefono" @error("plan_telefono") is-invalid @enderror>
            @error('plan_telefono')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <hr>
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-12">
            <label for="director" class="form-label">Datos del Director(a):</label>
        </div>

        <div class="col-12 col-sm-4">
          
          <input class="form-control @error("director") is-invalid @enderror " 
            placeholder="Nombre (Apellido Paterno Apellido Materno Nombre(s)" 
            name="director" 
            type="text" 
            wire:model="director">
            @error('director')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-4">
          <input class="form-control @error("correo_director") is-invalid @enderror " 
            placeholder="Correo Institucional del Director" 
            name="correo_director" 
            type="text" 
            wire:model="correo_director">
            @error('correo_director')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-4">
          
          <input class="form-control @error("telefono_director") is-invalid @enderror " 
            placeholder="Teléfono del Director" 
            name="telefono_director" 
            type="text" 
            wire:model="telefono_director">
            @error('telefono_director')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-12">
            <label for="director" class="form-label">Datos del Subdirector(a):</label>
        </div>

        <div class="col-12 col-sm-4">
          
          <input class="form-control @error("subdirector") is-invalid @enderror " 
            placeholder="Nombre (Apellido Paterno Apellido Materno Nombre(s)" 
            name="subdirector" 
            type="text" 
            wire:model="subdirector">
            @error('subdirector')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-4">
          <input class="form-control @error("correo_subdirector") is-invalid @enderror " 
            placeholder="Correo Institucional del Subdirector" 
            name="correo_subdirector" 
            type="text" 
            wire:model="correo_subdirector">
            @error('correo_subdirector')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
        <div class="col-12 col-sm-4">
          
          <input class="form-control @error("telefono_subdirector") is-invalid @enderror " 
            placeholder="Teléfono del Director" 
            name="telefono_subdirector" 
            type="text" 
            wire:model="telefono_subdirector">
            @error('telefono_subdirector')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-sm-8">
        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
        </div>
    </div>
</section>
