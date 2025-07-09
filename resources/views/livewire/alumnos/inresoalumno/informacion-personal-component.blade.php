<div>
    <section class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Alumno:
                    {{ $alumno->noexpediente }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Confirma Información Personal</li>
            </ol>
        </nav>
    </section>

    <div class="col-md-9">

        <h3><strong>{{ $alumno->nombre }} {{ $alumno->apellidos }}</strong></h3>
        <h4>{{ $alumno->noexpediente }}<br>{{ $alumno->correo_institucional }}</h4>
        <br>
        <h4>CONFIRMA TU INFORMACIÓN PERSONAL</h4>

        <!-- Tabs navs -->
        <form id="formID" enctype="multipart/form-data" method="POST">
            <input type="hidden" id="seccion_actual" name="seccion_actual" value="">
            <div class="card">
                <div class="card-header p-1">
                    <div style="display: none;">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#primera"
                                    role="tab" wire:click="cambio_ventana('datos_contacto')" aria-controls="home"
                                    aria-selected="true"><strong>1. DATOS DE CONTACTO Y
                                        PERSONALES</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab"
                                    wire:click="cambio_ventana('tutor_familiar')" href="#segunda" role="tab"
                                    aria-controls="profile" aria-selected="false"><strong>2. TUTOR /
                                        FAMILIAR</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="messages-tab" wire:click="cambio_ventana('detalles_medicos')"
                                    data-toggle="tab" href="#tercera" role="tab" aria-controls="messages"
                                    aria-selected="false"><strong>3. DETALLES MEDICOS</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="docs-tab" data-toggle="tab" href="#cuarta" role="tab"
                                    aria-controls="docs" aria-selected="false"><strong>4. CARGA DE
                                        DOCUMENTOS</strong></a>
                            </li>

                        </ul>
                    </div>

                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <input type="hidden" value="{{ $alumno->noexpediente }}" name="no_expendiente" readonly />
                        <div class="tab-pane active" id="primera" role="tabpanel" aria-labelledby="primera-tab">
                            <div class="row">
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Teléfono:*</label>
                                    <input type="text" class="form-control" id="telefono_contacto"
                                        name="telefono_contacto" value="{{ $alumno->telefono }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-12 col-12 p-3">
                                    <label class="form-label">Correo electrónico personal:*</label>
                                    <input type="email" class="form-control" id="correo_electronico_personal"
                                        name="correo_electronico_personal" value="{{ $alumno->email }}"
                                        maxlength = "300">
                                    @error('correo_personal')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Domicilio:*</label>
                                    <input type="text" class="form-control" id="domicilio" name="domicilio"
                                        value="{{ $alumno->domicilio }}" maxlength = "250">
                                    @error('correo_personal')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Número exterior:*</label>
                                    <input type="text" class="form-control" id="domicilio"
                                        name="domicilio_noexterior" value="{{ $alumno->domicilio_noexterior }}"
                                        maxlength = "250">
                                    @error('domicilio_noexterior')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>


                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">¿Perteneces a un etnia?</label>
                                    <input type="checkbox" name="etnia_check" id="etnia_check"
                                        onclick="cambiar_etnia()">
                                </div>
                                <div id="etnia" hidden>

                                    <label class="form-label">¿A que etnia pertenece?</label>

                                    <select class="form-control" name="etnia_nombre" id="etnia_nombre">
                                        <option value="0" selected>Seleccione una etnia</option>
                                        <option value="Mayo">Mayo</option>
                                        <option value="Yaqui">Yaqui</option>
                                        <option value="Migrantes">Migrantes</option>
                                        <option value="Guarijío">Guarijío</option>
                                        <option value="Seri">Seri</option>
                                        <option value="Tohono o'odham (pápago)">Tohono o'odham (pápago)</option>
                                        <option value="Pima">Pima</option>
                                        <option value="Cucapá">Cucapá</option>
                                        <option value="Kikapoo">Kikapoo</option>
                                    </select>
                                    <label for="lengua_indigena_desc" class="form-label">
                                        <strong>¿Es hablante de lenguas indígenas?</strong>
                                        <br>
                                        <small>Describa cuál</small>
                                    </label>
                                    <input type="text" class="form-control" id="lengua_indigena_desc"
                                        name="lengua_indigena_desc" value="{{ $alumno->lengua_indigena_desc }}"
                                        maxlength="100" placeholder="Escriba aquí...">
                                </div>

                            </div>
                            <div class="row">
                                <label class="form-label col-12"><strong>Indique la ubicación donde
                                        reside*:</strong></label>

                                {{-- Estado --}}
                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">Estado/entidad:*</label>
                                    <select class="form-control" id="entidad_id" name="entidad_id"
                                        onchange="select_entidad_id_ajax($('#entidad_id').val());return false;">
                                        <option value='00'>SD</option>
                                        @if (!is_null($estados))

                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->cve_ent }}">
                                                    {{ $estado->cve_ent }} - {{ $estado->nom_ent }}
                                                </option>
                                            @endforeach
                                        @endif


                                    </select>
                                    @error('entidad_id')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                {{-- Municipio --}}
                                <div class="form-group col-lg-12 col-12" id="carga_municipio">
                                    <label class="form-label">Municipio:*</label>
                                    <select class="form-control" id="municipio_id" name="municipio_id"
                                        onchange="select_municipio_id_ajax($('#municipio_id').val(), $('#entidad_id').val());return false;">
                                        <option value='00'>SD</option>
                                        @if (!is_null($municipios))

                                            @foreach ($municipios as $municipio)
                                                <option value="{{ $municipio->cve_mun }}"
                                                    {{ $municipio->cve_mun == $alumno->id_municipiodomicilio ? 'selected' : '' }}>
                                                    {{ $municipio->cve_mun }} - {{ $municipio->nom_mun }}
                                                </option>
                                            @endforeach

                                        @endif
                                    </select>
                                    @error('municipio_id')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                {{-- Localidad --}}
                                <div class="form-group col-lg-12 col-12" id="carga_localidad">
                                    <label
                                        class="form-label">Localidad:*{{ str_pad($alumno->id_localidaddomicilio, 4, '0', STR_PAD_LEFT) }}
                                    </label>
                                    <select class="form-control" id="localidad_id" name="localidad_id">
                                        <option value='00'>SD</option>
                                        @if (!is_null($localidades))
                                            @php
                                                $localidadesUnicas = [];
                                            @endphp
                                            @foreach ($localidades as $localidad)
                                                @if (!in_array($localidad->nom_loc, $localidadesUnicas))
                                                    @php
                                                        $localidadesUnicas[] = $localidad->nom_loc;
                                                    @endphp
                                                    <option value="{{ $localidad->cve_loc }}"
                                                        {{ str_pad($alumno->id_localidaddomicilio, 4, '0', STR_PAD_LEFT) == $localidad->cve_loc ? 'selected' : '' }}>
                                                        {{ $localidad->nom_loc }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>

                                    @error('localidad_id')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="d-grid gap-2 d-md-block">
                                    <button class="btn btn-primary" type="button" name="datos_contacto"
                                        onclick="guardarInformacion('datos_contacto')">Guardar Sección 1. Datos de
                                        Contacto</button>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane" id="segunda" role="tabpanel" aria-labelledby="segunda-tab">
                            <div class="row">
                                <label class="form-label col-12"><strong>Datos del Tutor*:</strong></label>
                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">Nombre(s):*</label>
                                    <input type="text" class="form-control" name="nombre_tutor" id="nombre_tutor"
                                        value="{{ $alumno->tutor_nombre }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Primer Apellido:*</label>
                                    <input type="text" class="form-control" name="apellido_paterno_tutor"
                                        id="apellido_paterno_tutor" value="{{ $alumno->tutor_apellido1 }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Segundo Apellido:*</label>
                                    <input type="text" class="form-control" name="apellido_materno_tutor"
                                        id="apellido_materno_tutor" value="{{ $alumno->tutor_apellido2 }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Correo electrónico:*</label>
                                    <input type="text" class="form-control" name="correo_electronico_tutor"
                                        id="correo_electronico_tutor" value="{{ $alumno->tutor_email }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Telefono:*</label>
                                    <input type="text" class="form-control" name="telefono_tutor"
                                        id="telefono_tutor" value="{{ $alumno->tutor_telefono }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">Colonia:*</label>
                                    <input type="text" class="form-control" name="colonia_tutor"
                                        id="colonia_tutor" value="{{ $alumno->tutor_colonia }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">Domicilio:*</label>
                                    <textarea class="form-control" name="domicilio_tutor" id="domicilio_tutor" rows="2">{{ $alumno->tutor_domicilio }}</textarea>
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                            </div>
                            <hr>
                            <label class="form-label col-12"><strong>Datos de Familiar:</strong></label>
                            <div class="row">

                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">Nombre(s):*</label>
                                    <input type="text" class="form-control" name="nombre_familiar"
                                        id="nombre_familiar" value="{{ $alumno->familiar_nombre }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Primer Apellido:*</label>
                                    <input type="text" class="form-control" name="apellido_paterno_familiar"
                                        id="apellido_paterno_familiar" value="{{ $alumno->familiar_apellido1 }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Segundo Apellido:*</label>
                                    <input type="text" class="form-control" name="apellido_materno_familiar"
                                        id="apellido_materno_familiar" value="{{ $alumno->familiar_apellido2 }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Correo electrónico:*</label>
                                    <input type="text" class="form-control" name="correo_electronico_familiar"
                                        id="correo_electronico_familiar" value="{{ $alumno->familiar_email }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Telefono:*</label>
                                    <input type="text" class="form-control" name="telefono_familiar"
                                        id="telefono_familiar" value="{{ $alumno->celular }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="d-grid gap-2 d-md-block">
                                    <button class="btn btn-primary" name="tutor_familiar" type="button"
                                        onclick="guardarInformacion('tutor_familiar')">Guardar Sección
                                        2.
                                        Tutor /Familiar</button>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="tercera" role="tabpanel" aria-labelledby="tercera-tab">

                            <div class="row">


                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">Alergias:*</label>

                                    <textarea class="form-control" rows="2" name="alergias" wire:model="alergias_describe" id="alergias">{{ $alumno->alergias_describe }}</textarea>
                                    @error('alergias')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">Medicamentos permitidos:*</label>
                                    <textarea class="form-control" rows="2" name="meds_permit" id="meds_permit">
                                        {{ $alumno->meds_permit }}
                                        </textarea>
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                    <br>
                                </div>

                                <div class="form-group col-lg-12 col-12">
                                    <label class="form-label">¿Tiene alguna discapacidad?</label>
                                    <input type="checkbox" name="discapacidad" id="discapacidad"
                                        onclick="cambiar()">


                                    <div id="discapacidades" hidden>
                                        <div class="row">
                                            <div class="column">
                                                <input type="checkbox" id="discapacidad_fisica"
                                                    name="discapacidad_tipo" value="fisica">
                                                <label for="discapacidad_fisica">Discapacidad Física</label><br>

                                                <input type="checkbox" id="discapacidad_intelectual"
                                                    name="discapacidad_tipo" value="intelectual">
                                                <label for="discapacidad_intelectual">Discapacidad
                                                    Intelectual</label><br>

                                                <input type="checkbox" id="discapacidad_multiple"
                                                    name="discapacidad_tipo" value="multiple">
                                                <label for="discapacidad_multiple">Discapacidad Múltiple</label><br>
                                                <input type="checkbox" id="discapacidad_psicosocial"
                                                    name="discapacidad_tipo" value="psicosocial">
                                                <label for="discapacidad_psicosocial">Discapacidad
                                                    Psicosocial</label><br>

                                                <label><strong>Discapacidad Auditiva:</strong></label><br>
                                                <input type="radio" id="discapacidad_auditiva_hipoacusia"
                                                    name="discapacidad_auditiva" value="hipoacusia">
                                                <label for="discapacidad_auditiva_hipoacusia">Hipoacusia</label><br>
                                                <input type="radio" id="discapacidad_auditiva_sordera"
                                                    name="discapacidad_auditiva" value="sordera">
                                                <label for="discapacidad_auditiva_sordera">Sordera</label><br>
                                            </div>

                                            <div class="column">
                                                <label><strong>Discapacidad Visual:</strong></label><br>
                                                <input type="radio" id="discapacidad_visual_baja_vision"
                                                    name="discapacidad_visual" value="baja_vision">
                                                <label for="discapacidad_visual_baja_vision">Baja Visión</label><br>
                                                <input type="radio" id="discapacidad_visual_ceguera"
                                                    name="discapacidad_visual" value="ceguera">
                                                <label for="discapacidad_visual_ceguera">Ceguera</label><br>


                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group col-lg-12 col-12" id="id_servicio_medico">
                                    <label class="form-label">Servicio Medico:*</label>
                                    <select class="form-control" id="id_servicio_medico"
                                        wire:model="id_servicio_medico" name="id_servicio_medico">
                                        <option @if ($alumno->id_servicio_medico == '0') selected @endif value='00'>SD
                                        </option>
                                        <option @if ($alumno->id_servicio_medico == '01') selected @endif value="01">IMSS
                                        </option>
                                        <option @if ($alumno->id_servicio_medico == '02') selected @endif value="02">ISSTE
                                        </option>
                                        <option @if ($alumno->id_servicio_medico == '03') selected @endif value="03">
                                            ISSTESON</option>
                                    </select>
                                    @error('id_servicio_medico')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Otro:*</label>
                                    <input type="text" class="form-control" name="servicio_medico_otro"
                                        id="servicio_medico_otro" value="{{ $alumno->servicio_medico_otro }}"
                                        wire:model ="servicio_medico_otro">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6 col-12">
                                    <label class="form-label">Núm Filiación:*</label>
                                    <input type="text" class="form-control"
                                        wire:model="servicio_medico_afiliacion" name="filiacion"
                                        id="servicio_medico_afiliacion"
                                        value="{{ $alumno->servicio_medico_afiliacion }}">
                                    @error('telefono')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>


                            </div>
                            <br>
                            <div class="row">
                                <div class="d-grid gap-2 d-md-block">
                                    <button class="btn btn-primary" name="detalles_medicos" type="button"
                                        onclick="guardar_info_medico()">Guardar
                                        Sección 3.
                                        Detalles Medicos</button>


                                </div>
                            </div>

                        </div>

                        <div class="tab-pane" id="cuarta" role="tabpanel" aria-labelledby="cuarta-tab">

                            <div class="row">

                                <div class="form-group col-lg-12 col-12">
                                    <label for="file_acta">Acta de nacimiento</label>
                                    <label>En formato PDF, JPG, PNG o JPEG Máximo 2MB</label>
                                    <input type="file" id="file_acta" class="form-control" name="file_acta"
                                        accept=".pdf, .jpg, .png, .jpeg">
                                    <span id="file_acta-error" class="text-danger"></span>
                                    <!-- Asegúrate de que este elemento exista -->
                                    @error('file_acta')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-12 col-12">
                                    <label for="file_certificado">Certificado de secundaria</label>
                                    <label>En formato PDF, JPG, PNG o JPEG Máximo 2MB</label>
                                    <input type="file" id="file_certificado" name="file_certificado"
                                        class="form-control" accept=".pdf, .jpg, .png, .jpeg">
                                    <span id="file_certificado-error" class="text-danger"></span>
                                    <!-- Asegúrate de que este elemento exista -->
                                    @error('file_certificado')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-12 col-12">
                                    <label for="file_foto">Fotografía digital actual a color en formato JPG Máximo
                                        2MB</label>

                                    <input type="file" id="file_foto" name="file_foto" class="form-control"
                                        accept=".jpg">
                                    @error('file_foto')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                    <span id="file_foto-error" class="text-danger"></span>
                                </div>
                                <div class="form-group col-lg-12 col-12">
                                    <label for="file_foto">En caso de tener algún hermano en el plantel favor de subir
                                        alguna evidencia que los compruebe</label>

                                    <input type="file" id="file_familiar" name="file_familiar"
                                        class="form-control" accept=".jpg">
                                    @error('file_familiar')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                    <label>Puede ser boleta, acta de nacimiento, documento de CURP y/o credencial
                                        escolar </label>

                                    <span id="file_familiar-error" class="text-danger"></span>
                                </div>


                                <div class="form-group">
                                    <label for="file_nss">Número de seguridad social</label>

                                    <input type="text" id="file_nss" name="file_nss" class="form-control">
                                    <span id="file_nss-error" class="text-danger"></span>
                                    <label>Para obtenerlo favor de ir a la siguiente página: <a
                                            href="http://imss.gob.mx/derechoH/nss"
                                            target="_blank">http://imss.gob.mx/derechoH/nss</a></label>
                                    @error('file_nss')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror

                                    <!-- Asegúrate de que este elemento exista -->
                                </div>

                                <div class="form-group">
                                    <label for="file_curp">CURP</label>
                                    <p>En formato PDF, JPG, PNG o JPEG Máximo 2MB</p>
                                    <input type="file" id="file_curp" name="file_curp" class="form-control"
                                        accept=".pdf, .jpg, .png, .jpeg">
                                    <span id="file_curp-error" class="text-danger"></span>
                                    @error('file_curp')
                                        <label class="alert-danger">{{ $message }}</label>
                                    @enderror
                                    <!-- Asegúrate de que este elemento exista -->
                                </div>



                            </div>
                            <br>
                            <div class="row">
                                <div class="d-grid gap-2 d-md-block">
                                    <button class="btn btn-primary" name="documentos" type="button"
                                        onclick="guardarInformacion('docs')">Guardar
                                        Sección 4.
                                        Carga de documentos</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Tabs navs -->
            </div>
        </form>

        <!-- /.col -->
        <br>
        <div class="row">
            <div class="d-grid gap-2 d-md-block">

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const estado = '{{ str_pad($alumno->id_estadodomicilio, 3, '0', STR_PAD_LEFT) }}';
            const municipio = '{{ str_pad($alumno->id_municipiodomicilio, 3, '0', STR_PAD_LEFT) }}';
            const localidad = '{{ str_pad($alumno->id_localidaddomicilio, 4, '0', STR_PAD_LEFT) }}';

            if (estado !== '000') {
                $.get(`/api/municipios/${estado}`, function(data) {
                    let options = '<option value="">Seleccione municipio</option>';
                    data.forEach(mun => {
                        const selected = mun.cve_mun === municipio ? 'selected' : '';
                        options +=
                            `<option value="${mun.cve_mun}" ${selected}>${mun.cve_mun} - ${mun.nom_mun}</option>`;
                    });
                    $('#municipio_id').html(options);

                    // Cargar localidades después
                    if (municipio !== '000') {
                        $.get(`/api/localidades/${estado}/${municipio}`, function(data) {
                            let options = '<option value="">Seleccione localidad</option>';
                            const localidadesUnicas = new Set();
                            data.forEach(loc => {
                                if (!localidadesUnicas.has(loc.nom_loc)) {
                                    localidadesUnicas.add(loc.nom_loc);
                                    const selected = loc.cve_loc === localidad ?
                                        'selected' : '';
                                    options +=
                                        `<option value="${loc.cve_loc}" ${selected}>${loc.nom_loc}</option>`;
                                }
                            });
                            $('#localidad_id').html(options);
                        });
                    }
                });
            }
        });

        function select_entidad_id_ajax(entidad_id) {
            $('#municipio_id').html('<option value="">Cargando municipios...</option>');
            $('#localidad_id').html('<option value="">Seleccione municipio</option>');

            $.get(`/api/municipios/${entidad_id}`, function(data) {
                let options = '<option value="">Seleccione municipio</option>';
                data.forEach(mun => {
                    options += `<option value="${mun.cve_mun}">${mun.cve_mun} - ${mun.nom_mun}</option>`;
                });
                $('#municipio_id').html(options);
            });
        }

        function select_municipio_id_ajax(municipio_id, entidad_id) {
            $('#localidad_id').html('<option value="">Cargando localidades...</option>');

            $.get(`/api/localidades/${entidad_id}/${municipio_id}`, function(data) {
                let options = '<option value="">Seleccione localidad</option>';
                const localidadesUnicas = new Set();

                data.forEach(loc => {
                    if (!localidadesUnicas.has(loc.nom_loc)) {
                        localidadesUnicas.add(loc.nom_loc);
                        options += `<option value="${loc.cve_loc}">${loc.nom_loc}</option>`;
                    }
                });

                $('#localidad_id').html(options);
            });
        }


        window.addEventListener('ejecutar-js', event => {
            const ventana_anterior = event.detail.ventana_anterior;
            console.log(ventana_anterior);
            // Aquí puedes llamar la función que necesites
            guardarInformacion(ventana_anterior);
        });

        function guardarInformacion(seccion) {
            $("#seccion_actual").val(seccion);
            if (validarCampos()) {
                var formData = new FormData($('#formID')[0]);
                const path = window.location.pathname;

                // Usar una expresión regular para extraer el número al final de la ruta
                const match = path.match(/\/(\d+)$/);

                let alumnoId = null;
                if (match) {
                    alumnoId = match[1];
                    console.log('Valor de alumno_id:', alumnoId);

                    // Realizar alguna acción si el número existe
                    if (alumnoId) {
                        // alert('El alumno_id extraído de la URL es: ' + alumnoId);
                    }
                } else {
                    console.log('No se encontró un alumno_id en la URL.');
                    // alert('No se encontró un alumno_id en la URL.');
                }

                // Si existe alumnoId, añadirlo al formData
                if (alumnoId) {
                    formData.append('alumno_id', alumnoId);
                }

                // Siempre añadir seccion_actual al formData
                formData.append('seccion_actual', seccion);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('alumno.guardar_datos') }}', // Ruta para manejar la solicitud en Laravel
                    data: formData,
                    processData: false, // No procesar los datos del formulario
                    contentType: false, // No establecer el tipo de contenido
                    success: function(response) {
                        // Puedes manejar la respuesta del servidor aquí si es necesario
                        console.log(response);
                        var seccion_actual = $("#seccion_actual").val();
                        switch (seccion_actual) {
                            case 'datos_contacto':
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Datos de contacto actualizados correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#profile-tab').tab('show'); // Cambia a la pestaña del perfil/tutor
                                break;
                            case 'tutor_familiar':
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Datos de tutor/familiar actualizados correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#messages-tab').tab('show'); // Cambia a la pestaña de detalles médicos
                                break;
                            case 'detalles_medicos':
                                // Puedes manejar la redirección a otra página aquí si es necesario
                                break;
                            case 'docs':
                                Swal.fire({
                                    title: "¿Está seguro/a de que la información es correcta?",
                                    text: "",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Si, continuar"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Aqui cambiar para cuando este la ficha
                                        if (alumnoId) {
                                            window.location.href = `/`;
                                        } else {
                                            window.location.href =
                                                "{{ route('ingreso_alumno.iniciar_reinscripcion') }}";
                                        }

                                        // window.location.href = '{{ route('ingreso_alumno.index') }}';
                                    }
                                });
                                break;
                            default:
                                console.log('estoy en esta zona sin cambiar');
                                break;
                                // Puedes agregar más casos según sea necesario
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        if (error.responseJSON && error.responseJSON.errors) {
                            let errorMessages = '';
                            for (const [key, value] of Object.entries(error.responseJSON.errors)) {
                                errorMessages += value + '<br>';
                            }
                            Swal.fire({
                                icon: 'warning',
                                title: 'Errores de validación',
                                html: errorMessages
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error inesperado. Por favor, intenta nuevamente.'
                            });
                        }
                        console.log('llegue a la parte del error');
                    },
                    contentType: false,
                    processData: false
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Campos obligatorios vacios",
                    text: "Favor de llenar todos los campos solicitados.",
                });
            }
        }

        function validar_correo(correo) {

            // Define our regular expression.
            var validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

            // Using test we can check if the text match the pattern
            if (validEmail.test(correo)) {
                return true;
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "El formato del correo no es correcto",
                });
                return false;
            }
        }


        function validarCampos() {
            // Validar los campos del formulario aquí
            var seccion_actual = $("#seccion_actual").val();
            switch (seccion_actual) {
                case 'datos_contacto':
                    var telefono = $("#telefono_contacto").val();
                    var correo = $("#correo_electronico_personal").val();
                    if (validar_correo(correo) == false) {
                        return false;
                    }
                    var estado = $("#entidad_id").val();
                    var municipio = $("#municipio_id").val();
                    var localidad = $("#localidad_id").val();
                    // Puedes agregar más campos según sea necesario

                    // Verificar si algún campo está vacío
                    if (telefono.trim() === '' || correo.trim() === '' ||
                        estado.trim() === '00' || municipio.trim() === '00' || localidad.trim() === '00') {
                        return false; // Al menos un campo está vacío
                    }

                    var etnia = document.getElementById("etnia_check");
                    if (etnia.checked) {
                        var nombre_etnia = document.getElementById("etnia_nombre").value;
                        if (nombre_etnia == "0") {
                            return false;
                        }
                    }
                    break;
                case 'tutor_familiar':
                    var nombre_tutor = $("#nombre_tutor").val();
                    var apellido_paterno_tutor = $("#apellido_paterno_tutor").val();
                    var apellido_materno_tutor = $("#apellido_materno_tutor").val();
                    var correo_electronico_tutor = $("#correo_electronico_tutor").val();
                    if (validar_correo(correo_electronico_tutor) == false) {
                        return false;
                    }
                    var telefono_tutor = $("#telefono_tutor").val();
                    var colonia_tutor = $("#colonia_tutor").val();
                    var domicilio_tutor = $("#domicilio_tutor").val();
                    var nombre_familiar = $("#nombre_familiar").val();

                    if (nombre_familiar != '') {
                        var apellido_paterno_familiar = $("#apellido_paterno_familiar").val();
                        var apellido_materno_familiar = $("#apellido_materno_familiar").val();
                        var correo_electronico_familiar = $("#correo_electronico_familiar").val();
                        var telefono_familiar = $("#telefono_familiar").val();

                        if (validar_correo(correo_electronico_familiar) == false) {
                            return false;
                        }
                        if (apellido_paterno_familiar.trim() === '' ||
                            apellido_materno_familiar.trim() === '' ||
                            correo_electronico_familiar.trim() === '') {
                            return false;
                        }
                    }

                    if (nombre_tutor.trim() === '' || apellido_paterno_tutor.trim() === '' ||
                        apellido_materno_tutor.trim() === '' || correo_electronico_tutor.trim() === '' ||
                        telefono_tutor.trim() === '' || colonia_tutor.trim() === '' || domicilio_tutor.trim() === '') {
                        return false;
                    }

                    break;

                case 'detalles_medicos':

                    var alergias = $("#alergias").val();
                    var meds_permit = $("#meds_permit").val();
                    var problemas_salud = $("#problemas_salud").val();
                    var otros_medico = $("#otros_medico").val();
                    var filiacion = $("#filiacion").val();

                    if (alergias.trim() === '' || meds_permit.trim() === '' ||
                        problemas_salud.trim() === '' || otros_medico.trim() === '' ||
                        filiacion.trim() === '') {
                        return false; // Al menos un campo está vacío
                    }
                    break;
                default:
                    return true;
                    break;
            }


            // Puedes agregar más validaciones según sea necesario

            return true; // Todos los campos están completos
        }



        $(function() {
            // Inicializar los tabs
            $("#tabs").tabs();
        });

        function guardar_info_medico() {

            // Obtener los valores de los checkboxes seleccionados
            var selectedDiscapacidades = [];
            document.querySelectorAll('input[name="discapacidad_tipo"]:checked').forEach(function(checkbox) {
                selectedDiscapacidades.push(checkbox.value);
            });

            // Obtener los valores de los radios seleccionados
            var selectedAuditiva = document.querySelector('input[name="discapacidad_auditiva"]:checked');
            if (selectedAuditiva) {
                selectedDiscapacidades.push(selectedAuditiva.value);
            }

            var selectedVisual = document.querySelector('input[name="discapacidad_visual"]:checked');
            if (selectedVisual) {
                selectedDiscapacidades.push(selectedVisual.value);
            }


            //var discapacidad_describe = document.getElementById('discapacidad_describe').value;
            var discapacidad_describe = selectedDiscapacidades.join(',');



            var meds_permit = document.getElementById('meds_permit').value;

            var id_servicio_medico = document.getElementById('id_servicio_medico').value;
            var servicio_medico_otro = document.getElementById('servicio_medico_otro').value;
            var servicio_medico_afiliacion = document.getElementById('servicio_medico_afiliacion').value;
            Livewire.emit('guardar_informacion', meds_permit, discapacidad_describe, id_servicio_medico,
                servicio_medico_otro, servicio_medico_afiliacion);
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Detalles medicos actualizados correctamente",
                showConfirmButton: false,
                timer: 1500
            });
            $('#docs-tab').tab('show'); // Cambia a la pestaña de detalles médicos
        }

        function carga_documentos() {


            Livewire.emit('saveFile');

        }


        /*
                function select_municipio_id_ajax(municipio_id, entidad_id) {
                    var parametros = {
                        "municipio_id": municipio_id,
                        "entidad_id": entidad_id,
                    };
                    console.log('Ajax municipio:' + municipio_id + ' entidad: ' + entidad_id);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        data: parametros,
                        url: '{{ Route('ingreso_alumno.carga_localidad') }}',
                        type: 'post',
                        beforeSend: function() {
                            $("#carga_localidad").html(
                                '<div class="form-group col-lg-12 col-12" id="carga_localidad"><label class="form-label">Localidad:*</label><select class="form-control" id="localidad_id" name="localidad_id" > <option value="00">...</option></select></div>'
                            );
                        },
                        success: function(response) {
                            $("#carga_localidad").html(response);
                        }
                    });


                };
        */
        function cambiar() {
            var divDiscapacidad = document.getElementById("discapacidades");

            var marcado = document.getElementById("discapacidad");
            if (marcado.checked) {
                divDiscapacidad.removeAttribute("hidden");
            } else {
                divDiscapacidad.setAttribute("hidden", true);
            }
        }

        function cambiar_etnia() {
            var divDiscapacidad = document.getElementById("etnia");

            var marcado = document.getElementById("etnia_check");
            if (marcado.checked) {
                divDiscapacidad.removeAttribute("hidden");
            } else {
                divDiscapacidad.setAttribute("hidden", true);
            }
        }
    </script>

    @section('css_pre')
        <!-- jQuery antes que bootstrap.js -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
        </script>

        <!-- Bootstrap.js despues de jQuery-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>
    @endsection
</div>
