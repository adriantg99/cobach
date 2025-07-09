<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
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

    <section class="bg-light app-filters">
        <h3></h3>

        <div class="content">
            <div class="card card-body">
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Número de expediente:</label>
                        <input class="form-control" placeholder="Número de expediente" name="noexpediente"
                            type="text" @role('control_escolar') 
                            @else
                            readonly
                            @endrole value="" wire:model="datos_personales.noexpediente"
                            @error('datos_personales.noexpediente') is-invalid @enderror>
                        @error('datos_personales.noexpediente')
                            <div class="error-message"><span style="color:red;"></span></div>
                        @enderror
                        <label for="apellidopaterno" class="form-label">Apellido Paterno:</label>*
                        <input class="form-control" placeholder="Apellido Paterno del alumno" wire:change="apellidos()"
                            name="apellidopaterno" type="text" value=""
                            wire:model="datos_personales.apellidopaterno"
                            @error('datos_personales.apellidopaterno') is-invalid @enderror>
                    </div>
                    @php
                        /*
                             @if (!empty($this->alumno['noexpediente']))
<div class="col-12 col-sm-6" style="margin-bottom: 1%">
                            <div class="img-container d-flex justify-content-center align-items-center">
                                   
                                </div>
                                @hasallroles('super_admin')
<div class="d-flex justify-content-center align-items-center mt-2">
                                    <fieldset>
                                        <input type="radio" name="tipo_foto" id="radioCertificado" value="2"
                                            wire:model="seleccionado" />
                                        <label for="radioCertificado">Certificado</label>

                                        <input type="radio" name="tipo_foto" id="radioBoleta" value="1"
                                            wire:model="seleccionado" />
                                        <label for="radioBoleta">Boleta</label>
                                    </fieldset>

                                </div>
@endhasallroles
                        </div>
                        <br>
@endif
                        */
                    @endphp


                </div>
                <div class="row g-3">

                    <div class="col-12 col-sm-6">
                        <label for="apellidomaterno" class="form-label">Apellido Materno:</label>*
                        <input class="form-control" placeholder="Apellido Materno del alumno" name="apellidomaterno"
                            type="text" value="" wire:model="datos_personales.apellidomaterno"
                            wire:change="apellidos()">
                    </div>
                    @php
                        /*
                              @hasallroles('super_admin')
<div class="col-12 col-sm-6">
                        <label for="apellidomaterno" class="form-label">Foto:</label>
                        <input type="file" wire:model="imagen">
                    </div>
@endhasallroles
                        */
                    @endphp

                </div>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label for="nombre" class="form-label">Nombre:</label>*
                        <input class="form-control" placeholder="Nombre del alumno"
                            @error('datos_personales.nombre') is-invalid @enderror name="nombre" type="text"
                            value="" wire:model="datos_personales.nombre">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="apellidos" class="form-label">Apellidos:</label>
                        <input class="form-control" placeholder="Apellidos" name="apellidos" type="text" readonly
                            value="" wire:model="datos_personales.apellidos" onchange="">
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">

                    </div>
                </div>
            </div>

            <div id="tabs" class="row g-1">

                <div class="tabs">
                    <a wire:click="datospersonales()">Datos
                        personales</a>
                    <a wire:click="direccion()">Dirección</a>
                    <a wire:click="datostutor()">Datos
                        Tutor</a>
                    <a wire:click="datosescolares()">Datos
                        Escolares</a>

                    <a wire:click="detallesgrupo()">No Expediente @hasallroles('control_escolar')
                            Y asignación de grupo
                        @endhasallroles
                    </a>

                    @hasallroles('control_escolar')
                        @if ($alumno)
                            @if ($alumno->created_at >= '2024-07-01')
                            @endif
                        @endif

                    @endhasallroles
                    @if (!empty($alumno))
                        <a wire:click="visualizar_documentos()">Documentos cargados</a>
                    @endif


                </div>

                <div class="content">
                    @if ($modulo_activo == 1)
                        <div class="tabcontent">
                            <div class="row g-3">
                                <div class="col-12 col-sm-3">
                                    <label for="fechanacimiento" class="form-label">Fecha de nacimiento:</label> *
                                    <input class="form-control" placeholder="Fecha de nacimiento" name="fechanacimiento"
                                        type="date" wire:model="datos_personales.fechanacimiento">

                                </div>


                                <div class="col-12 col-sm-1">
                                    <label for="edad" class="form-label">Edad (años):</label>
                                    <input class="form-control" placeholder="Edad" name="edad" type="number"
                                        min="1" max="100" wire:model="datos_personales.edad">

                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="sexo" class="form-label">Sexo (acta de nacimiento):</label>

                                    <select class="form-control" name="sexo">
                                        <option value="F"
                                            @if (!empty($alumno)) @if ($alumno['sexo'] == 'F') selected @endif
                                            @endif>Femenino</option>
                                        <option value="M"
                                            @if (!empty($alumno)) @if ($alumno['sexo'] == 'M') selected @endif
                                            @endif>Masculino</option>
                                    </select>



                                </div>
                                <div class="col-12 col-sm-6">
                                    <label for="curp" class="form-label">CURP:</label>*
                                    <input class="form-control" placeholder="CURP" maxlength="18" name="curp" required type="text"
                                        wire:model="datos_personales.curp">

                                </div>
                            </div>

                            <div class="card card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <label for="role" class="form-label">Nacionalidad:</label>
                                        <select class="form-control" name="id_nacionalidad"
                                            wire:model="datos_personales.id_nacionalidad">
                                            <option value="0">Selecciona nacionalidad</option>
                                            @foreach ($paises as $pais)
                                                <option value="{{ $pais->id }}">{{ $pais->pais }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="role" class="form-label">País:</label>
                                        <select class="form-control" name="id_paisnacimiento">
                                            <option value="0">Selecciona país</option>
                                            @foreach ($paises as $pais)
                                                <option value="{{ $pais->id }}">{{ $pais->pais }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>

                                <div class="row g-3">

                                    <div class="col-12 col-sm-6">
                                        <label for="role" class="form-label">Estado:</label>
                                        <select class="form-control" name="id_estadonacimiento">
                                            <option value="0">Selecciona estado</option>
                                            {{-- 
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}">{{ $estado->nom_ent }}</option>
                                            @endforeach
 --}}
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="role" class="form-label">Municipio:</label>
                                        <select class="form-control" name="id_municipionacimiento">
                                            <option value="0">Selecciona municipio</option>
                                            <option value=""></option>

                                        </select>
                                    </div>

                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <label for="role" class="form-label">Localidad:</label>
                                        <select class="form-control" name="id_localidadnacimiento">
                                            <option value="0">Selecciona localidad</option>


                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="role" class="form-label">Lugar de nacimiento:</label>
                                        <select class="form-control" name="id_lugarnacimiento">
                                            <option value="0">Selecciona lugar de nacimiento</option>


                                        </select>
                                    </div>


                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-sm-1">
                                    <label for="peso" class="form-label">Peso:</label>
                                    <input class="form-control" placeholder="Peso" name="peso" type="number"
                                        min="1" max="300" wire:model="datos_personales.peso">
                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="estatura" class="form-label">Estatura (en centímetros):</label>
                                    <input class="form-control" placeholder="Estatura" name="estatura"
                                        type="number" min="1" max="220"
                                        wire:model="datos_personales.estatura">

                                </div>

                            </div>
                            <div class="row g-3">

                                <div class="col-12 col-sm-2">
                                    <label for="alergias" class="form-label">Alergias:</label>
                                    <input name="alergias" type="checkbox" wire:model="datos_personales.alergias">
                                </div>
                                <div class="col-12 col-sm-8">
                                    <label for="alergias_describe" class="form-label">Describe:</label>
                                    <input class="form-control" placeholder="Describe" name="alergias_describe"
                                        type="text" wire:model="datos_personales.alergias_describe">

                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="tiposangre" class="form-label">Tipo de Sangre:</label>
                                    <input class="form-control" placeholder="Tipo de Sangre" name="tiposangre"
                                        type="text" wire:model="datos_personales.tiposangre">
                                </div>
                            </div>

                            <div class="row g-3">

                                <div class="col-12 col-sm-4">
                                    <label for="id_discapacidad" class="form-label">Discapacidad:</label>
                                    <select class="form-control" name="id_discapacidad">
                                        <option value="0" selected>Selecciona discapacidad</option>


                                    </select>
                                </div>

                                <div class="col-12 col-sm-8">
                                    <label for="enfermedad" class="form-label">Enfermedad:</label>
                                    <input class="form-control" placeholder="Enfermedad" name="enfermedad"
                                        type="text" wire:model="datos_personales.enfermedad">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-sm-2">
                                    <label for="id_etnia" class="form-label">Etnia:</label>
                                    <select class="form-control" name="id_etnia">
                                        <option value=0>Selecciona etnia</option>


                                    </select>
                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="lengua_indigena" class="form-label">¿Habla alguna etnia
                                        indígena?:</label>
                                    <input name="lengua_indigena" type="checkbox"
                                        wire:model="datos_personales.lengua_indigena">
                                </div>

                                <div class="col-12 col-sm-2">
                                    <label for="lengua_indigena_desc" class="form-label">¿Cuál?:</label>
                                    <input class="form-control" placeholder="¿Cuál lengua indígena?"
                                        name="lengua_indigena_desc" type="text"
                                        wire:model="datos_personales.lengua_indigena_desc">
                                </div>
                            </div>
                            <div class="card card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <strong>Empresa donde labora el alumno:</strong>

                                    </div>

                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <label for="empresa_nombre" class="form-label">Nombre:</label>
                                        <input class="form-control" placeholder="Nombre" name="empresa_nombre"
                                            type="text" wire:model="datos_personales.empresa_nombre">

                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <label for="empresa_colonia" class="form-label">Colonia:</label>
                                        <input class="form-control" placeholder="Colonia" name="empresa_colonia"
                                            type="text" wire:model="datos_personales.empresa_colonia">

                                    </div>

                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <label for="empresa_domicilio" class="form-label">Domicilio:</label>
                                        <input class="form-control" placeholder="Domicilio" name="empresa_domicilio"
                                            type="text" wire:model="datos_personales.empresa_domicilio">

                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <label for="empresa_telefono" class="form-label">Teléfono:</label>
                                        <input class="form-control" placeholder="Teléfono" name="empresa_telefono"
                                            type="text" wire:model="datos_personales.empresa_telefono">

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($modulo_activo == 2)
                        <div class="tabcontent">
                            <div class="row g-3">

                                <div class="col-12 col-sm-12">
                                    <label for="domicilio" class="form-label">Domicilio:</label>*
                                    <input class="form-control" placeholder="Domicilio del alumno" name="domicilio"
                                        type="text" wire:model="datos_personales.domicilio">

                                </div>
                            </div>
                            <div class="row g-3">

                                <div class="col-12 col-sm-8">
                                    <label for="domicilio_entrecalle" class="form-label">Entre calles:</label>*
                                    <input class="form-control" placeholder="Entre calles"
                                        name="domicilio_entrecalle" type="text"
                                        wire:model="datos_personales.domicilio_entrecalle">

                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="domicilio_noexterior" class="form-label">Número exterior:</label>*
                                    <input class="form-control" placeholder="Número exterior"
                                        name="domicilio_noexterior" type="text"
                                        wire:model="datos_personales.domicilio_noexterior">

                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="domicilio_nointerior" class="form-label">Número interior:</label>
                                    <input class="form-control" placeholder="Número interior"
                                        name="domicilio_nointerior" type="text"
                                        wire:model="datos_personales.domicilio_nointerior">

                                </div>
                            </div>
                            <div class="row g-3">

                                <div class="col-12 col-sm-10">
                                    <label for="colonia" class="form-label">Colonia:</label>*
                                    <input class="form-control" placeholder="Colonia" name="colonia" type="text"
                                        wire:model="datos_personales.colonia">

                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="codigopostal" class="form-label">Código postal:</label>*
                                    <input class="form-control" placeholder="Código postal" name="codigopostal"
                                        type="text" wire:model="datos_personales.codigopostal">

                                </div>

                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-sm-4">
                                    <label for="role" class="form-label">Estado:</label>
                                    <select class="form-control" name="id_estadodomicilio"
                                        wire:model="datos_personales.">

                                        <option value="0">Selecciona estado</option>


                                    </select>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label for="role" class="form-label">Municipio:</label>
                                    <select class="form-control" name="id_municipiodomicilio">

                                        <option value="0">Selecciona municipio</option>


                                    </select>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label for="role" class="form-label">Localidad:</label>
                                    <select class="form-control" name="id_localidaddomicilio">
                                        <option value="0">Selecciona localidad</option>


                                    </select>
                                </div>


                            </div>
                            <div class="row g-3">

                                <div class="col-12 col-sm-3">
                                    <label for="telefono" class="form-label">Número de teléfono:</label>*
                                    <input class="form-control" placeholder="Número de teléfono" name="telefono"
                                        type="text" wire:model="datos_personales.telefono">

                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="celular" class="form-label">Número de celular:</label>
                                    <input class="form-control" placeholder="Número de Celular" name="celular"
                                        type="text" wire:model="datos_personales.celular">

                                </div>
                                <div class="col-12 col-sm-6">
                                    <label for="email" class="form-label">Correo electrónico:</label>*
                                    <input class="form-control" placeholder="Correo electrónico" name="email"
                                        type="text" wire:model="datos_personales.email">

                                </div>
                            </div>

                        </div>
                    @endif
                    @if ($modulo_activo == 3)

                        <div class="tabcontent">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <label for="tutor_nombre" class="form-label">Nombre del tutor:</label>
                                    <input class="form-control" placeholder="Nombre del tutor" name="tutor_nombre"
                                        type="text" wire:model="datos_personales.tutor_nombre">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label for="tutor_nombre" class="form-label">Apellido paterno del tutor:</label>
                                    <input class="form-control" placeholder="Nombre del tutor" name="tutor_nombre"
                                        type="text" wire:model="datos_personales.tutor_apellido1">
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="tutor_nombre" class="form-label">Apellido materno del tutor:</label>
                                    <input class="form-control" placeholder="Nombre del tutor" name="tutor_nombre"
                                        type="text" wire:model="datos_personales.tutor_apellido2">
                                </div>

                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-sm-12">
                                    <label for="tutor_domicilio" class="form-label">Domicilio:</label>
                                    <input class="form-control" placeholder="Domicilio" name="tutor_domicilio"
                                        type="text" wire:model="datos_personales.tutor_domicilio">

                                </div>

                            </div>
                            <div class="row g-3" style="margin-top: 2%;">
                                <div class="col-12 col-sm-6">
                                    <label for="tutor_colonia" class="form-label">Colonia:</label>
                                    <input class="form-control" placeholder="Colonia" name="tutor_colonia"
                                        type="text" wire:model="datos_personales.tutor_colonia">

                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="tutor_ocupacion" class="form-label">Ocupacion:</label>
                                    <input class="form-control" placeholder="Ocupacion" name="tutor_ocupacion"
                                        type="text" wire:model="datos_personales.tutor_ocupacion">

                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-sm-3">
                                    <label for="tutor_telefono" class="form-label">Teléfono:</label>
                                    <input class="form-control" placeholder="Teléfono" name="tutor_telefono"
                                        wire:model="datos_personales.tutor_telefono" type="text">

                                </div>

                                <div class="col-12 col-sm-3">
                                    <label for="tutor_celular" class="form-label">Celular:</label>
                                    <input class="form-control" placeholder="Celular" name="tutor_celular"
                                        wire:model="datos_personales.tutor_celular" type="text">

                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="tutor_email" class="form-label">Correo tutor:</label>
                                    <input class="form-control" placeholder="Celular" name="tutor_email"
                                        wire:model="datos_personales.tutor_email" type="text">

                                </div>
                            </div>
                            <div class="row g-3" style="margin-top: 2%;">
                                <div class="col-12 col-sm-6">
                                    <label for="familiar_nombre" class="form-label">Nombre del familiar:</label>
                                    <input class="form-control" placeholder="Nombre de la madre" name="familiar_nombre"
                                        type="text" wire:model="datos_personales.familiar_nombre">

                                </div>
                                <div class="col-12 col-sm-6">
                                    <label for="familiar_apellido1" class="form-label">Apellido paterno del familiar:</label>
                                    <input class="form-control" placeholder="Nombre de la madre" name="familiar_apellido1"
                                        type="text" wire:model="datos_personales.familiar_apellido1">

                                </div>
                                <div class="col-12 col-sm-6">
                                    <label for="familiar_apellido2" class="form-label">Apellido materno del familiar:</label>
                                    <input class="form-control" placeholder="Nombre de la madre" name="familiar_apellido2"
                                        type="text" wire:model="datos_personales.familiar_apellido2">

                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="madre_celular" class="form-label">Celular del familiar:</label>
                                    <input class="form-control" placeholder="Celular" name="madre_celular"
                                        type="text" wire:model="datos_personales.familiar_celular">

                                </div>

                            </div>
                            <div class="card card-body" style="margin-top: 2%;">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <strong>Nacido en el extranjero:</strong>

                                    </div>

                                </div>

                                <div class="row g-3">

                                    <div class="col-12 col-sm-2">
                                        <label for="extranjero_padre_mexicano" class="form-label">¿El alumno cuenta
                                            con al
                                            menos padre o madre mexicano?:</label>
                                        <input name="extranjero_padre_mexicano" type="checkbox">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="id_extranjero_paisnacimiento" class="form-label">Lugar de
                                            nacimiento:</label>
                                        <select class="form-control" name="id_extranjero_paisnacimiento">
                                            <option value=0>Selecciona lugar de nacimiento</option>

                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="card card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <strong>Empresa donde labora el del tutor:</strong>

                                    </div>

                                </div>

                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <label for="tutor_empresa_nombre" class="form-label">Nombre:</label>
                                        <input class="form-control" wire:model="datos_personales.tutor_empresa_nombre"
                                            placeholder="Nombre" name="tutor_empresa_nombre" type="text">

                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <label for="tutor_empresa_colonia" class="form-label">Colonia:</label>
                                        <input class="form-control" placeholder="Colonia"
                                            wire:model="datos_personales.tutor_empresa_colonia"
                                            name="tutor_empresa_colonia" type="text">

                                    </div>

                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <label for="tutor_empresa_domicilio" class="form-label">Domicilio:</label>
                                        <input class="form-control" placeholder="Domicilio"
                                            wire:model="datos_personales.tutor_empresa_domicilio"
                                            name="tutor_empresa_domicilio" type="text">

                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <label for="tutor_empresa_telefono" class="form-label">Teléfono:</label>
                                        <input class="form-control" placeholder="Teléfono"
                                            wire:model="datos_personales.tutor_empresa_telefono"
                                            name="tutor_empresa_telefono" type="text">

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($modulo_activo == 4)

                        <div class="tabcontent">

                            <div class="row g-3">

                                <div class="col-12 col-sm-12">
                                    <label for="secundaria_nombre" class="form-label">Nombre de escuela de
                                        procedencia:</label>*
                                    <input class="form-control" placeholder="Nombre de escuela"
                                        name="secundaria_nombre" type="text"
                                        wire:model="datos_personales.secundaria_nombre">

                                </div>

                            </div>
                            <div class="row g-3">

                                <div class="col-12 col-sm-3">
                                    <label for="secundaria_clave" class="form-label">Clave de la escuela de
                                        procedencia:</label>*
                                    <input class="form-control" placeholder="Clave" name="secundaria_clave"
                                        type="text" wire:model="datos_personales.secundaria_clave">

                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="secundaria_promedio" class="form-label">Calificación
                                        Promedio:</label>*
                                    <input class="form-control" placeholder="Calificación Promedio"
                                        name="secundaria_promedio" type="number" min="6" max="10"
                                        wire:model="datos_personales.secundaria_promedio">

                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="secundaria_fechaegreso" class="form-label">Fecha de egreso:</label>*
                                    <input class="form-control" placeholder="Fecha de egreso"
                                        name="secundaria_fechaegreso" type="date"
                                        wire:model="datos_personales.secundaria_fechaegreso">

                                </div>
                            </div>

                            <div class="card card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <strong>Estudios en el extranjero:</strong>

                                    </div>

                                </div>

                                <div class="row g-3">

                                    <div class="col-12 col-sm-2">
                                        <label for="extranjero_grado_ems" class="form-label">¿Estudió algún grado de
                                            preparatoria en el extranjero?:</label>
                                        <input name="extranjero_grado_ems" type="checkbox"
                                            wire:model="datos_personales.extranjero_grado_ems">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="id_extranjero_paisestudio" class="form-label">Lugar de
                                            estudio:</label>
                                        <select class="form-control" name="id_extranjero_paisestudio"
                                            wire:model="datos_personales.id_extranjero_paisestudio">
                                            <option value="0" selected>Selecciona país de estudio</option>
                                            @foreach ($paises as $pais)
                                                <option value="{{ $pais->id }}">{{ $pais->pais }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-12">
                                        <strong>¿Cuál es su dominio del idioma español?:</strong>

                                    </div>

                                </div>

                                <div class="row g-3">

                                    <div class="col-12 col-sm-2">
                                        <label for="extranjero_habla_espanol" class="form-label">Habla:</label>
                                        <input name="extranjero_habla_espanol" type="checkbox"
                                            wire:mode="datos_personales.extranjero_habla_espanol">
                                    </div>

                                    <div class="col-12 col-sm-2">
                                        <label for="extranjero_lee_espanol" class="form-label">Lee:</label>
                                        <input name="extranjero_lee_espanol" type="checkbox"
                                            wire:model="datos_personales.extranjero_lee_espanol">
                                    </div>

                                    <div class="col-12 col-sm-2">
                                        <label for="extranjero_escribe_espanol" class="form-label">Escribe:</label>
                                        <input name="extranjero_escribe_espanol" type="checkbox"
                                            wire:model="datos_personales.extranjero_escribe_espanol">
                                    </div>


                                </div>


                            </div>
                            <div class="row g-3">

                                <div class="col-12 col-sm-2">
                                    <label for="id_beca" class="form-label">Beca:</label>
                                    <select class="form-control" name="id_beca"
                                        wire:model="datos_personales.id_beca">
                                        <option value=0>Selecciona beca</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="beca_otra" class="form-label">Otra beca:</label>
                                    <input class="form-control" placeholder="Otra beca" name="beca_otra"
                                        type="text" wire:model="datos_personales.beca_otra">

                                </div>


                            </div>

                            <div class="row g-3">

                                <div class="col-12 col-sm-4">
                                    <label for="id_servicio_medico" class="form-label">Servicio médico:</label>
                                    <select class="form-control" name="id_servicio_medico"
                                        wire:model="datos_personales.id_servicio_medico">
                                        <option value=0>Selecciona servicio médico</option>
                                        <option> </option>

                                    </select>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="servicio_medico_otro" class="form-label">Otro servicio médico:</label>
                                    <input class="form-control" placeholder="Otro servicio médico"
                                        name="servicio_medico_otro" type="text"
                                        wire:model="datos_personales.servicio_medico_otro">

                                </div>
                                <div class="col-12 col-sm-2">
                                    <label for="servicio_medico_afiliacion" class="form-label">Afiliación</label>
                                    <input class="form-control" placeholder="Afiliación"
                                        name="servicio_medico_afiliacion" type="text"
                                        wire:model="datos_personales.servicio_medico_afiliacion">

                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($modulo_activo == 5)
                        @if (empty($this->alumno['noexpediente']))
                            <div class="tabcontent">
                                <div class="col-12 col-sm-12">
                                    <label class="form-label">Plantel:</label>*
                                    @if (empty($this->alumno['noexpediente']))
                                        <select name="" id="" class="form-control"
                                            wire:model="idplantel" wire:change="asignacion()">
                                            <option value="">Selecciona un plantel</option>
                                            @foreach ($planteles as $plantel)
                                                <option value="{{ $plantel->id }}"
                                                    @unlessrole('control_escolar') @unlessrole('control_escolar_' . $plantel->abreviatura) disabled @endunlessrole
                                                @endunlessrole>{{ $plantel->nombre }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input class="form-control" name="plantel" type="text" hidden
                                        wire:model="datos_personales.plantel_id" />
                                    @foreach ($planteles as $plantel)
                                        <input class="form-control" type="text"
                                            value="{{ $plantel->nombre }}" readonly />
                                    @endforeach
                                @endif

                            </div>
                            <div class="row g-3">

                                <div class="col-12 col-sm-6">
                                    <label for="role" class="form-label">Ciclo escolar:</label>*
                                    @if (empty($this->alumno['noexpediente']))

                                        <select class="form-control" wire:model="idCicloEscolar"
                                            wire:change="asignacion()">
                                            <option value="0">Selecciona ciclo escolar</option>
                                            @foreach ($ciclo_escolares as $ciclos)
                                                <option value="{{ $ciclos->id }}">{{ $ciclos->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input class="form-control" name="plantel" type="text" hidden
                                            wire:model="datos_personales.cicloesc_id" />
                                        @foreach ($grupo_del_alumno as $ciclo_inscrito)
                                            <input class="form-control" type="text"
                                                value="{{ $ciclo_inscrito->nombre_ciclo }}" readonly />
                                        @endforeach

                                    @endif
                                </div>


                            </div>
                    @endif


                    @if (empty($this->alumno['noexpediente']))
                        @hasallroles('control_escolar')
                            <div class="col-12 col-sm-6">

                                <label for="role" class="form-label">Grupo a inscribir:</label>*
                                <select class="form-control" name="id_estatus" wire:model="grupo_inscribir">
                                    <option value="">Selecciona grupo</option>
                                    @if (!empty($grupos_posibles))
                                        @foreach ($grupos_posibles as $grupos)
                                            <option value="{{ $grupos->id }}">
                                                {{ $grupos->nombre }}-----{{ $grupos->descripcion }}
                                                @if ($grupos->turno_id == '1')
                                                    Matutino
                                                @else
                                                    Vespertino
                                                @endif
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>

                            @php
                                /*
                            <div class="col-12 col-sm-2">

                            <label for="role" class="form-label">Plan de estudio:</label>
                            <select class="form-control" name="Plan"
                                wire:model="datos_personales.id_cicloesc">
                                <option value="0">Selecciona estatus</option>

                                @foreach ($plan_estudio as $planes)
<option value="{{ $planes->id }}">{{ $planes->nombre }}</option>
@endforeach


                            </select>

                        </div>
                            */
                            @endphp
                        @endhasallroles
                    @else
                        <div class="col-12 col-sm-6">

                            <label for="role" class="form-label">Grupo a inscribir:</label>*
                            <select class="form-control" name="id_estatus" wire:model="grupo_inscribir">
                                <option value="">Selecciona grupo</option>
                                @if (!empty($grupos_posibles))
                                    @foreach ($grupos_posibles as $grupos)
                                        <option value="{{ $grupos->id }}">
                                            {{ $grupos->nombre }}-----{{ $grupos->descripcion }}
                                            @if ($grupos->turno_id == '1')
                                                Matutino
                                            @else
                                                Vespertino
                                            @endif
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    @endif



            </div>
            @if (empty($this->alumno['noexpediente']))
            @else
                <p>Nota: En este modulo solo puede cambiar a un alumno de grupo dentro del mismo plantel y
                    que
                    no
                    tenga calificaciones registradas.
                </p>
            @endif
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                }

                img,
                embed {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    /* o usa 'cover' según lo que necesites */
                }
            </style>
            @endif
            @if (!empty($this->alumno['noexpediente']))
                @if ($modulo_activo == 6)
                    <div class="tabcontent container-fluid">
                        <div class="row">
                            <div class="col-12 d-flex flex-row align-items-center">
                                <button class="btn btn-primary mb-3" wire:click="cambiar_doc(4)">Mostrar
                                    acta</button>
                                <button class="btn btn-primary mb-3" wire:click="cambiar_doc(5)">Mostrar
                                    Certificado</button>
                                <button class="btn btn-primary mb-3" wire:click="cambiar_doc(6)">Mostrar
                                    CURP</button>
                                <button class="btn btn-primary mb-3" wire:click="cambiar_doc(1)">Mostrar
                                    Foto</button>
                                    <button class="btn btn-primary mb-3" wire:click="cambiar_doc(2)">Mostrar
                                        Foto certificado</button>
                            </div>
                            <div class="text-center">
                                
                                @if ($documento)

                                    @if ($documento == 1 || $documento == 2)
                                        <div class="container-fluid">
                                            <img
                                                src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $alumno->id]) }}" />
                                        </div>
                                    @else
                                        <div style="width: 100%">
                                            <iframe style="width: 100%; height: 800px; display: block; margin: 0 auto;"
                                                src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $alumno->id]) }}" />
                                        </div>

                                    @endif

                                    <div class="mt-3">
                                        <a class="btn btn-success"
                                            href="{{ route('archivo.descargar', ['tipo_archivo' => $documento, 'alumno_id' => $alumno->id]) }}"
                                            download>
                                            Descargar Archivo
                                        </a>
                                    </div>
                                @endif
                                {{-- 
                                    @if ($documento)
                                        <iframe
                                            src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $alumno->id]) }}"
                                            frameborder="0"
                                            style="width: 100%; height: 500px; display: block; margin: 0 auto;"></iframe>
                                    @endif
                                     --}}
                            </div>
                        </div>
                    </div>





                @endif
            @endif


            <div class="row g-3">

                <div class="col-12 col-sm-12">
                    <label for="observaciones" class="form-label">Observaciones:</label>
                    <input class="form-control" placeholder="Observaciones" name="observaciones" type="text"
                        wire:model="datos_personales.observaciones">

                </div>

            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label for="fecharegistro" class="form-label">Fecha de registro:</label>
                    <input class="form-control" placeholder="Fecha de registro" name="fecharegistro"
                        type="date" wire:model="datos_personales.fecharegistro">

                </div>

                <div class="col-12 col-sm-3">
                    <label for="fechabaja" class="form-label">Fecha de baja:</label>
                    <input class="form-control" placeholder="Fecha de baja" name="fechabaja" type="date"
                        wire:model="datos_personales.fechabaja">


                </div>
            </div>
        </div>

    </div>
    @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
        <div class="row g-3 mt-3">
            <div class="col-sm-8">
                <button class="btn btn-primary" onclick="cargando()" wire:click="guardarDatos()">Guardar</button>
            </div>
        </div>
    @endif

</section>
<section>
    <script>
        function cargando(alumno_id) {

            let timerInterval
            Swal.fire({
                title: 'Guardando alumno...',
                html: 'Por favor espere.',
                timer: 100000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            })

        }

        //alert('Name updated to: ');
    </script>
    <script>
        function validarImagen(input) {
            if (input.files && input.files[0]) {
                var fileSize = input.files[0].size; // Tamaño del archivo en bytes
                var maxSize = 2 * 1024 * 1024; // Tamaño máximo permitido en bytes (2 MB en este ejemplo)
                var img = new Image();
                img.src = window.URL.createObjectURL(input.files[0]);
                var seleccionado; // Declara la variable seleccionado

                img.onload = function() {
                    var width = img.width;
                    var height = img.height;

                    var seleccionado = document.querySelector('input[name="tipo_foto"]:checked').value;

                    if (seleccionado == 2 && (width != 240 || height != 192)) {
                        alert('Las dimensiones de la imagen deben ser 240x192.');
                        input.value = ''; // Limpiar el campo de archivo
                        return;
                    }

                    if (fileSize > maxSize) {
                        alert('El tamaño de la imagen excede el límite permitido (2 MB).');
                        input.value = ''; // Limpiar el campo de archivo
                        return;
                    }
                };
            }
        }
    </script>
</section>
</div>