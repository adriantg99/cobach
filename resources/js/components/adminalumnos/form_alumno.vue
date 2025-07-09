
<!-- ANA MOLINA 25/08/2023 -->
<template>
	<section class="bg-light app-filters">
		<h3>{{ titulo }}</h3>
		<div v-if="errors">
        <div v-for="(v, k) in errors" :key="k">
          <p class="text-danger" v-for="error in v" :key="error">
              {{ error }}
          </p>
        </div>
    	</div>
        <div class="col-12 col-sm-12">
            <label class="form-label">Plantel:</label>

            <input class="form-control"
            name="plantel"
            type="text"
            v-model="datos.plantel"
            readonly>


        </div>

        <div class="content">
            <div  class="card card-body">
                <div class="col-12 col-sm-6">
                    <label   class="form-label">Número de expediente:</label>
                    <input class="form-control"
                        placeholder="Número de expediente"
                        name="noexpediente"
                        type="text"
                        v-model="alumno.noexpediente" readonly >

                    </div>


                <div class="row g-3">

                    <div class="col-12 col-sm-6">
                    <label for="apellidopaterno" class="form-label">Apellido Paterno:</label>
                    <input class="form-control"
                        placeholder="Apellido Paterno del alumno"
                        name="apellidopaterno"
                        type="text"
                        v-model="alumno.apellidopaterno"  >

                    </div>
                </div>
                <div class="row g-3">

                    <div class="col-12 col-sm-6">
                    <label for="apellidomaterno" class="form-label">Apellido Materno:</label>
                    <input class="form-control"
                        placeholder="Apellido Materno del alumno"
                        name="apellidomaterno"
                        type="text"
                        v-model="alumno.apellidomaterno"  >

                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input class="form-control"
                        placeholder="Nombre del alumno"
                        name="nombre"
                        type="text"
                        v-model="alumno.nombre"  >

                    </div>
                    <div class="col-12 col-sm-6">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input class="form-control"
                        placeholder="Apellidos"
                        name="apellidos"
                        type="text"
                        v-model="alumno.apellidos" readonly >

                    </div>
                </div>
            </div>
            <div class="row g-3">

                <div class="col-12 col-sm-6">
                   <label for="role" class="form-label">Ciclo escolar:</label>
                    <select class="form-control"
                    name="id_cicloesc"
                    v-model="alumno.id_cicloesc">
                    <option value="" >Selecciona ciclo escolar</option>
                   <option v-for="cicloesc in datos.cicloArray"
                    v-bind:key="cicloesc.id" v-bind:value="cicloesc.id">{{ cicloesc.nombre }}
                    </option>

                  </select>
                </div>
                <div class="col-12 col-sm-2">

                    <label for="role" class="form-label">Tipo Periodo:</label>
                    <select class="form-control"
                        name="tipoperiodo"
                        v-model="alumno.id_tipoperiodo"
                        @change="onChangeTipoperiodo($event)">
                     <option value="" >Selecciona tipo periodo</option>
                    <option v-for="tipoperiodo in datos.tipoperiodoArray"
                        v-bind:key="tipoperiodo.id" v-bind:value="tipoperiodo.id">{{ tipoperiodo.nombre }}
                        </option>
                    </select>

                </div>
                <div class="col-12 col-sm-2">

                    <label for="role" class="form-label">Periodo:</label>
                    <select class="form-control"
                        name="id_periodo"
                        v-model="alumno.id_periodo">
                        <option value="" >Selecciona periodo</option>
                   <option v-for="periodo in datos.periodoArray"
                        v-bind:key="periodo.id" v-bind:value="periodo.id">{{ periodo.nombre }}
                        </option>
                    </select>

                </div>
            </div>
            <div class="row g-3">

                <div class="col-12 col-sm-6">
                   <label for="role" class="form-label">Plan de estudio:</label>
                    <select class="form-control"
                    name="id_planestudio"
                    v-model="alumno.id_planestudio">
                    <option value="" >Selecciona plan de estudio</option>
                    <option v-for="plan in datos.planArray"
                    v-bind:key="plan.id" v-bind:value="plan.id">{{ plan.nombre }}
                    </option>

                  </select>
                </div>
            </div>
            <div class="col-12 col-sm-2">

                <label for="role" class="form-label">Estatus:</label>
                <select class="form-control"
                    name="id_estatus"
                    v-model="alumno.id_estatus">
                    <option value="" >Selecciona estatus</option>
                <option v-for="estatus in datos.estatusArray"
                    v-bind:key="estatus.id" v-bind:value="estatus.id">{{ estatus.nombre }}
                    </option>
                </select>

            </div>
        </div>
        <div id="tabs" class="row g-1">

            <div class="tabs">
                <a v-on:click="datos.activetab=1" v-bind:class="[ datos.activetab === 1 ? 'active' : '' ]">Datos personales</a>
                <a v-on:click="datos.activetab=2" v-bind:class="[ datos.activetab === 2 ? 'active' : '' ]">Dirección</a>
                <a v-on:click="datos.activetab=3" v-bind:class="[ datos.activetab === 3 ? 'active' : '' ]">Datos Tutor</a>
                <a v-on:click="datos.activetab=4" v-bind:class="[ datos.activetab === 4 ? 'active' : '' ]">Datos Escolares</a>
            </div>

            <div class="content">
                <div v-if="datos.activetab === 1" class="tabcontent">
                    <div class="row g-3">
                        <div class="col-12 col-sm-3">
                        <label for="fechanacimiento" class="form-label">Fecha de nacimiento:</label>
                        <input class="form-control"
                            placeholder="Fecha de nacimiento"
                            name="fechanacimiento"
                            type="date"
                            v-model="alumno.fechanacimiento"  >

                        </div>
                        <div class="col-12 col-sm-1">
                            <label for="edad" class="form-label">Edad (años):</label>
                            <input class="form-control"
                                placeholder="Edad"
                                name="edad"
                                type="number" min="1" max="100"
                                v-model="alumno.edad"  >

                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="sexo" class="form-label">Sexo (acta de nacimiento):</label>

                                <select class="form-control"
                                    name="sexo"
                                    v-model="alumno.sexo">
                                    <option v-for="sexo in datos.sexoArray"
                                    v-bind:key="sexo.id" v-bind:value="sexo.id">{{ sexo.nombre }}
                                    </option>

                                </select>



                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="curp" class="form-label">CURP:</label>
                            <input class="form-control"
                                placeholder="CURP"
                                name="curp"
                                type="text"
                                v-model="alumno.curp"  >

                        </div>
                        </div>

                    <div  class="card card-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                            <label for="role" class="form-label">Nacionalidad:</label>
                                <select class="form-control"
                                name="id_nacionalidad"
                                v-model="alumno.id_nacionalidad">
                                <option value="" >Selecciona nacionalidad</option>
                            <option v-for="nacional in datos.nacionalArray"
                                v-bind:key="nacional.id" v-bind:value="nacional.id">{{ nacional.nombre }}
                                </option>

                            </select>
                             </div>
                            <div class="col-12 col-sm-6">
                            <label for="role" class="form-label">País:</label>
                                <select class="form-control"
                                name="id_paisnacimiento"
                                v-model="alumno.id_paisnacimiento"
                                @change="onChangePais($event)">
                                <option value="" >Selecciona país</option>
                            <option v-for="pais in datos.paisArray"
                                v-bind:key="pais.id" v-bind:value="pais.id">{{ pais.nombre }}
                                </option>

                            </select>
                             </div>

                        </div>

                        <div class="row g-3">

                            <div class="col-12 col-sm-6">
                            <label for="role" class="form-label">Estado:</label>
                                <select class="form-control"
                                name="id_estadonacimiento"
                                v-model="alumno.id_estadonacimiento"
                                @change="onChangeEstado($event)">

                                <option value="" >Selecciona estado</option>
                            <option v-for="estado in datos.estadoArray"
                                v-bind:key="estado.id" v-bind:value="estado.id">{{ estado.nombre }}
                                </option>

                            </select>
                             </div>
                            <div class="col-12 col-sm-6">
                            <label for="role" class="form-label">Municipio:</label>
                                <select class="form-control"
                                name="id_municipionacimiento"
                                v-model="alumno.id_municipionacimiento"
                                @change="onChangeMunicipio($event)">
                                <option value="" >Selecciona municipio</option>
                            <option v-for="municipio in datos.municipioArray"
                                v-bind:key="municipio.id" v-bind:value="municipio.id">{{ municipio.nombre }}
                                </option>

                            </select>
                             </div>

                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                            <label for="role" class="form-label">Localidad:</label>
                                <select class="form-control"
                                name="id_localidadnacimiento"
                                v-model="alumno.id_localidadnacimiento">
                                <option value="" >Selecciona localidad</option>
                            <option v-for="localidad in datos.localidadArray"
                                v-bind:key="localidad.id" v-bind:value="localidad.id">{{ localidad.nombre }}
                                </option>

                            </select>
                             </div>
                            <div class="col-12 col-sm-6">
                            <label for="role" class="form-label">Lugar de nacimiento:</label>
                                <select class="form-control"
                                name="id_lugarnacimiento"
                                v-model="alumno.id_lugarnacimiento">
                                <option value="" >Selecciona lugar de nacimiento</option>
                            <option v-for="lugar in datos.lugarArray"
                                v-bind:key="lugar.id" v-bind:value="lugar.id">{{ lugar.nombre }}
                                </option>

                            </select>
                              </div>


                        </div>
                    </div>
                      <div class="row g-3">
                        <div class="col-12 col-sm-1">
                        <label for="peso" class="form-label">Peso:</label>
                        <input class="form-control"
                            placeholder="Peso"
                            name="peso"
                            type="number" min="1" max="300"
                            v-model="alumno.peso">
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="estatura" class="form-label">Estatura (en centímetros):</label>
                            <input class="form-control"
                                placeholder="Estatura"
                                name="estatura"
                                type="number" min="1" max="220"
                                v-model="alumno.estatura"  >

                        </div>

                    </div>
                    <div class="row g-3">

                        <div class="col-12 col-sm-2">
                        <label for="alergias" class="form-label">Alergias:</label>
                        <input name="alergias"
                            type="checkbox"
                            v-model="alumno.alergias"  >
                        </div>
                        <div class="col-12 col-sm-8">
                            <label for="alergias_describe" class="form-label">Describe:</label>
                            <input class="form-control"
                                placeholder="Describe"
                                name="alergias_describe"
                                type="text"
                                v-model="alumno.alergias_describe"  >

                        </div>
                        <div class="col-12 col-sm-2">
                        <label for="tiposangre" class="form-label">Tipo de Sangre:</label>
                        <input class="form-control"
                                placeholder="Tipo de Sangre"
                                name="tiposangre"
                                type="text"
                                v-model="alumno.tiposangre"  >
                        </div>
                    </div>

                    <div class="row g-3">

                        <div class="col-12 col-sm-4">
                        <label for="id_discapacidad" class="form-label">Discapacidad:</label>
                        <select class="form-control"
                            name="id_discapacidad"
                            v-model="alumno.id_discapacidad">
                            <option value=0 >Selecciona discapacidad</option>
                            <option v-for="discapacidad in datos.discapacidadArray"
                            v-bind:key="discapacidad.id" v-bind:value="discapacidad.id">{{ discapacidad.nombre }}
                            </option>

                        </select>
                        </div>

                        <div class="col-12 col-sm-8">
                        <label for="enfermedad" class="form-label">Enfermedad:</label>
                        <input class="form-control"
                                placeholder="Enfermedad"
                                name="enfermedad"
                                type="text"
                                v-model="alumno.enfermedad"  >
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-sm-2">
                        <label for="id_etnia" class="form-label">Etnia:</label>
                        <select class="form-control"
                            name="id_etnia"
                            v-model="alumno.id_etnia">
                            <option value=0 >Selecciona etnia</option>
                            <option v-for="etnia in datos.etniaArray"
                            v-bind:key="etnia.id" v-bind:value="etnia.id">{{ etnia.nombre }}
                            </option>

                        </select>
                        </div>
                        <div class="col-12 col-sm-2">
                        <label for="lengua_indigena" class="form-label">¿Habla alguna etnia indígena?:</label>
                        <input name="lengua_indigena"
                            type="checkbox"
                            v-model="alumno.lengua_indigena"  >
                        </div>

                        <div class="col-12 col-sm-2">
                        <label for="lengua_indigena_desc" class="form-label">¿Cuál?:</label>
                        <input class="form-control"
                                placeholder="¿Cuál lengua indígena?"
                                name="lengua_indigena_desc"
                                type="text"
                                v-model="alumno.lengua_indigena_desc"  >
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
                        <input class="form-control"
                            placeholder="Nombre"
                            name="empresa_nombre"
                            type="text"
                            v-model="alumno.empresa_nombre"  >

                        </div>

                        <div class="col-12 col-sm-6">
                        <label for="empresa_colonia" class="form-label">Colonia:</label>
                        <input class="form-control"
                            placeholder="Colonia"
                            name="empresa_colonia"
                            type="text"
                            v-model="alumno.empresa_colonia"  >

                        </div>

                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-12">
                        <label for="empresa_domicilio" class="form-label">Domicilio:</label>
                        <input class="form-control"
                            placeholder="Domicilio"
                            name="empresa_domicilio"
                            type="text"
                            v-model="alumno.empresa_domicilio"  >

                        </div>

                        <div class="col-12 col-sm-6">
                        <label for="empresa_telefono" class="form-label">Teléfono:</label>
                        <input class="form-control"
                            placeholder="Teléfono"
                            name="empresa_telefono"
                            type="text"
                            v-model="alumno.empresa_telefono"  >

                        </div>

                    </div>
                </div>
                </div>
                <div v-if="datos.activetab === 2" class="tabcontent">
                    <div class="row g-3">

                        <div class="col-12 col-sm-12">
                        <label for="domicilio" class="form-label">Domicilio:</label>
                        <input class="form-control"
                            placeholder="Domicilio del alumno"
                            name="domicilio"
                            type="text"
                            v-model="alumno.domicilio"  >

                        </div>
                    </div>
                     <div class="row g-3">

                        <div class="col-12 col-sm-8">
                        <label for="domicilio_entrecalle" class="form-label">Entre calles:</label>
                        <input class="form-control"
                            placeholder="Entre calles"
                            name="domicilio_entrecalle"
                            type="text"
                            v-model="alumno.domicilio_entrecalle"  >

                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="domicilio_noexterior" class="form-label">Número exterior:</label>
                            <input class="form-control"
                                placeholder="Número exterior"
                                name="domicilio_noexterior"
                                type="text"
                                v-model="alumno.domicilio_noexterior"  >

                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="domicilio_nointerior" class="form-label">Número interior:</label>
                            <input class="form-control"
                                placeholder="Número interior"
                                name="domicilio_nointerior"
                                type="text"
                                v-model="alumno.domicilio_nointerior"  >

                        </div>
                    </div>
                    <div class="row g-3">

                        <div class="col-12 col-sm-10">
                        <label for="colonia" class="form-label">Colonia:</label>
                        <input class="form-control"
                            placeholder="Colonia"
                            name="colonia"
                            type="text"
                            v-model="alumno.colonia"  >

                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="codigopostal" class="form-label">Código postal:</label>
                            <input class="form-control"
                                placeholder="Código postal"
                                name="codigopostal"
                                type="text"
                                v-model="alumno.codigopostal"  >

                        </div>

                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                        <label for="role" class="form-label">Estado:</label>
                            <select class="form-control"
                            name="id_estadodomicilio"
                            v-model="alumno.id_estadodomicilio"
                            @change="onChangeEstadodom($event)">

                            <option value="" >Selecciona estado</option>
                        <option v-for="estado in datos.estadodomicilioArray"
                            v-bind:key="estado.id" v-bind:value="estado.id">{{ estado.nombre }}
                            </option>

                        </select>
                        </div>
                        <div class="col-12 col-sm-4">
                        <label for="role" class="form-label">Municipio:</label>
                            <select class="form-control"
                            name="id_municipiodomicilio"
                            v-model="alumno.id_municipiodomicilio"
                            @change="onChangeMunicipiodom($event)">

                            <option value="" >Selecciona municipio</option>
                        <option v-for="municipio in datos.municipiodomicilioArray"
                            v-bind:key="municipio.id" v-bind:value="municipio.id">{{ municipio.nombre }}
                            </option>

                        </select>
                        </div>
                        <div class="col-12 col-sm-4">
                        <label for="role" class="form-label">Localidad:</label>
                            <select class="form-control"
                            name="id_localidaddomicilio"
                            v-model="alumno.id_localidaddomicilio">
                            <option value="" >Selecciona localidad</option>
                        <option v-for="localidad in datos.localidaddomicilioArray"
                            v-bind:key="localidad.id" v-bind:value="localidad.id">{{ localidad.nombre }}
                            </option>

                        </select>
                        </div>


                    </div>
                    <div class="row g-3">

                        <div class="col-12 col-sm-3">
                        <label for="telefono" class="form-label">Número de teléfono:</label>
                        <input class="form-control"
                            placeholder="Número de teléfono"
                            name="telefono"
                            type="text"
                            v-model="alumno.telefono"  >

                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="celular" class="form-label">Número de celular:</label>
                            <input class="form-control"
                                placeholder="Número de Celular"
                                name="celular"
                                type="text"
                                v-model="alumno.celular"  >

                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="email" class="form-label">Correo electrónico:</label>
                            <input class="form-control"
                                placeholder="Correo electrónico"
                                name="email"
                                type="text"
                                v-model="alumno.email"  >

                        </div>
                    </div>

                </div>
                <div v-if="datos.activetab === 3" class="tabcontent">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                        <label for="tutor_nombre" class="form-label">Nombre del tutor:</label>
                        <input class="form-control"
                            placeholder="Nombre del tutor"
                            name="tutor_nombre"
                            type="text"
                            v-model="alumno.tutor_nombre"  >

                        </div>

                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-12">
                        <label for="tutor_domicilio" class="form-label">Domicilio:</label>
                        <input class="form-control"
                            placeholder="Domicilio"
                            name="tutor_domicilio"
                            type="text"
                            v-model="alumno.tutor_domicilio"  >

                        </div>

                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                        <label for="tutor_colonia" class="form-label">Colonia:</label>
                        <input class="form-control"
                            placeholder="Colonia"
                            name="tutor_colonia"
                            type="text"
                            v-model="alumno.tutor_colonia"  >

                        </div>

                        <div class="col-12 col-sm-6">
                        <label for="tutor_ocupacion" class="form-label">Ocupacion:</label>
                        <input class="form-control"
                            placeholder="Ocupacion"
                            name="tutor_ocupacion"
                            type="text"
                            v-model="alumno.tutor_ocupacion"  >

                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-3">
                        <label for="tutor_telefono" class="form-label">Teléfono:</label>
                        <input class="form-control"
                            placeholder="Teléfono"
                            name="tutor_telefono"
                            type="text"
                            v-model="alumno.tutor_telefono"  >

                        </div>

                        <div class="col-12 col-sm-3">
                        <label for="tutor_celular" class="form-label">Celular:</label>
                        <input class="form-control"
                            placeholder="Celular"
                            name="tutor_celular"
                            type="text"
                            v-model="alumno.tutor_celular"  >

                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                        <label for="madre_nombre" class="form-label">Nombre de la madre:</label>
                        <input class="form-control"
                            placeholder="Nombre de la madre"
                            name="madre_nombre"
                            type="text"
                            v-model="alumno.madre_nombre"  >

                        </div>
                        <div class="col-12 col-sm-3">
                        <label for="madre_celular" class="form-label">Celular:</label>
                        <input class="form-control"
                            placeholder="Celular"
                            name="madre_celular"
                            type="text"
                            v-model="alumno.madre_celular"  >

                        </div>

                    </div>
                    <div class="card card-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-12">
                            <strong>Nacido en el extranjero:</strong>

                            </div>

                        </div>

                        <div class="row g-3">

                            <div class="col-12 col-sm-2">
                            <label for="extranjero_padre_mexicano" class="form-label">¿El alumno cuenta con al menos padre o madre mexicano?:</label>
                            <input name="extranjero_padre_mexicano"
                                type="checkbox"
                                v-model="alumno.extranjero_padre_mexicano"  >
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="id_extranjero_paisnacimiento" class="form-label">Lugar de nacimiento:</label>
                                <select class="form-control"
                                name="id_extranjero_paisnacimiento"
                                v-model="alumno.id_extranjero_paisnacimiento">
                                <option value=0 >Selecciona lugar de nacimiento</option>
                                <option v-for="pais in datos.paisArray"
                                v-bind:key="pais.id" v-bind:value="pais.id">{{ pais.nombre }}
                                </option>

                            </select>
                            </div>

                        </div>
                    </div>
                    <div  class="card card-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-12">
                            <strong>Empresa donde labora el del tutor:</strong>

                            </div>

                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-sm-12">
                            <label for="tutor_empresa_nombre" class="form-label">Nombre:</label>
                            <input class="form-control"
                                placeholder="Nombre"
                                name="tutor_empresa_nombre"
                                type="text"
                                v-model="alumno.tutor_empresa_nombre"  >

                            </div>

                            <div class="col-12 col-sm-6">
                            <label for="tutor_empresa_colonia" class="form-label">Colonia:</label>
                            <input class="form-control"
                                placeholder="Colonia"
                                name="tutor_empresa_colonia"
                                type="text"
                                v-model="alumno.tutor_empresa_colonia"  >

                            </div>

                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-sm-12">
                            <label for="tutor_empresa_domicilio" class="form-label">Domicilio:</label>
                            <input class="form-control"
                                placeholder="Domicilio"
                                name="tutor_empresa_domicilio"
                                type="text"
                                v-model="alumno.tutor_empresa_domicilio"  >

                            </div>

                            <div class="col-12 col-sm-6">
                            <label for="tutor_empresa_telefono" class="form-label">Teléfono:</label>
                            <input class="form-control"
                                placeholder="Teléfono"
                                name="tutor_empresa_telefono"
                                type="text"
                                v-model="alumno.tutor_empresa_telefono"  >

                            </div>

                        </div>
                     </div>
                </div>
                <div v-if="datos.activetab === 4" class="tabcontent">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                        <label for="role" class="form-label">Secundaria de procedencia:</label>
                            <select class="form-control"
                            name="id_secundaria_procedencia"
                            v-model="alumno.id_secundaria_procedencia">
                            <option value="" >Selecciona secundaria de procedencia</option>
                        <option v-for="secundaria in datos.secundariaArray"
                            v-bind:key="secundaria.id" v-bind:value="secundaria.id">{{ secundaria.nombre }}
                            </option>

                        </select>
                        </div>


                    </div>
                    <div class="row g-3">

                        <div class="col-12 col-sm-12">
                        <label for="secundaria_nombre" class="form-label">Nombre de escuela:</label>
                        <input class="form-control"
                            placeholder="Nombre de escuela"
                            name="secundaria_nombre"
                            type="text"
                            v-model="alumno.secundaria_nombre"  >

                        </div>

                    </div>
                    <div class="row g-3">

                        <div class="col-12 col-sm-3">
                        <label for="secundaria_clave" class="form-label">Clave:</label>
                        <input class="form-control"
                            placeholder="Clave"
                            name="secundaria_clave"
                            type="text"
                            v-model="alumno.secundaria_clave"  >

                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="secundaria_promedio" class="form-label">Calificación Promedio:</label>
                            <input class="form-control"
                                placeholder="Calificación Promedio"
                                name="secundaria_promedio"
                                type="number" min="6" max="10"
                                v-model="alumno.secundaria_promedio"  >

                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="secundaria_fechaegreso" class="form-label">Fecha de egreso:</label>
                            <input class="form-control"
                                placeholder="Fecha de egreso"
                                name="secundaria_fechaegreso"
                                type="date"
                                v-model="alumno.secundaria_fechaegreso"  >

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
                            <label for="extranjero_grado_ems" class="form-label">¿Estudió algún grado de preparatoria en el extranjero?:</label>
                            <input name="extranjero_grado_ems"
                                type="checkbox"
                                v-model="alumno.extranjero_grado_ems"  >
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="id_extranjero_paisestudio" class="form-label">Lugar de estudio:</label>
                                <select class="form-control"
                                name="id_extranjero_paisestudio"
                                v-model="alumno.id_extranjero_paisestudio">
                                <option value=0 >Selecciona país de estudio</option>
                                <option v-for="pais in datos.paisArray"
                                v-bind:key="pais.id" v-bind:value="pais.id">{{ pais.nombre }}
                                </option>

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
                            <input name="extranjero_habla_espanol"
                                type="checkbox"
                                v-model="alumno.extranjero_habla_espanol"  >
                            </div>

                            <div class="col-12 col-sm-2">
                            <label for="extranjero_lee_espanol" class="form-label">Lee:</label>
                            <input name="extranjero_lee_espanol"
                                type="checkbox"
                                v-model="alumno.extranjero_lee_espanol"  >
                            </div>

                            <div class="col-12 col-sm-2">
                            <label for="extranjero_escribe_espanol" class="form-label">Escribe:</label>
                            <input name="extranjero_escribe_espanol"
                                type="checkbox"
                                v-model="alumno.extranjero_escribe_espanol"  >
                            </div>


                        </div>


                    </div>
                    <div class="row g-3">

                        <div class="col-12 col-sm-2">
                            <label for="id_beca" class="form-label">Beca:</label>
                            <select class="form-control"
                                name="id_beca"
                                v-model="alumno.id_beca">
                            <option value=0 >Selecciona beca</option>
                            <option v-for="beca in datos.becaArray"
                                v-bind:key="beca.id" v-bind:value="beca.id">{{ beca.nombre }}
                                </option>

                            </select>
                        </div>
                        <div class="col-12 col-sm-3">
                        <label for="beca_otra" class="form-label">Otra beca:</label>
                        <input class="form-control"
                            placeholder="Otra beca"
                            name="beca_otra"
                            type="text"
                            v-model="alumno.beca_otra"  >

                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="turno_especial" class="form-label">Turno especial:</label>
                            <input name="turno_especial"
                                type="checkbox"
                                v-model="alumno.turno_especial"  >
                        </div>

                    </div>

                    <div class="row g-3">

                        <div class="col-12 col-sm-4">
                            <label for="id_servicio_medico" class="form-label">Servicio médico:</label>
                            <select class="form-control"
                                name="id_servicio_medico"
                                v-model="alumno.id_servicio_medico">
                            <option value=0 >Selecciona servicio médico</option>
                            <option v-for="serviciomedico in datos.serviciomedicoArray"
                                v-bind:key="serviciomedico.id" v-bind:value="serviciomedico.id">{{ serviciomedico.nombre }}
                                </option>

                            </select>
                        </div>
                        <div class="col-12 col-sm-3">
                        <label for="servicio_medico_otro" class="form-label">Otro servicio médico:</label>
                        <input class="form-control"
                            placeholder="Otro servicio médico"
                            name="servicio_medico_otro"
                            type="text"
                            v-model="alumno.servicio_medico_otro"  >

                        </div>
                        <div class="col-12 col-sm-2">
                        <label for="servicio_medico_afiliacion" class="form-label">Afiliación</label>
                        <input class="form-control"
                            placeholder="Afiliación"
                            name="servicio_medico_afiliacion"
                            type="text"
                            v-model="alumno.servicio_medico_afiliacion"  >

                        </div>
                    </div>
                </div>
                <div class="row g-3">

                    <div class="col-12 col-sm-12">
                    <label for="observaciones" class="form-label">Observaciones:</label>
                    <input class="form-control"
                        placeholder="Observaciones"
                        name="observaciones"
                        type="text"
                        v-model="alumno.observaciones"  >

                    </div>

                </div>

                <div class="row g-3">
                    <div class="col-12 col-sm-3">
                            <label for="fecharegistro" class="form-label">Fecha de registro:</label>
                            <input class="form-control"
                                placeholder="Fecha de registro"
                                name="fecharegistro"
                                type="date"
                                v-model="alumno.fecharegistro"  >

                        </div>

                        <div class="col-12 col-sm-3"   v-if="alumno_id !== undefined" >
                            <label for="fechabaja" class="form-label">Fecha de baja:</label>
                            <input class="form-control"
                                placeholder="Fecha de baja"
                                name="fechabaja"
                                type="date"
                                v-model="alumno.fechabaja"  >


                        </div>
                </div>
            </div>

        </div>

	        <div class="row g-3 mt-3">
		        <div class="col-sm-8">
		        	<button class="btn btn-primary" v-on:click="Guardar">Guardar</button>
		        </div>
	   		</div>
	</section>
</template>

<script>
import { ref } from 'vue'
import { reactive } from 'vue';
import get from 'axios';

export default {
    el: '#tabs',
    //por default el tab activo es 1
    activetab:1,
    //data: { activetab: 1 },
    data() {
        return {

        }
    },
    mounted() {},

	props: ['alumno_id','plantel_id'], //Datos pasados al componente
   	setup(props){


		const errors = ref('');
		const alumno_new = ref([]);
        const current = new Date();

        var mesi=current.getMonth()+1;
        var diai=current.getDate();
        var mes;
        var dia;
        if (mesi <10)
            mes= '0'+mesi;
        else
            mes=mesi;

        if (diai <10)
            dia='0'+diai;
        else
            dia=diai;

        //const date = `${current.getDate()}/${current.getMonth()+1}/${current.getFullYear()}`;
        const date = `${current.getFullYear()}-${mes}-${dia}`;
        //console.log(current);
        //console.log(date);
		const alumno = reactive({
            id_plantel:'',
			id_cicloesc:'',
            id_planestudio:'',
            noexpediente:'',
            nombre: '',
            apellidos:'',
            apellidopaterno:'',
            apellidomaterno:'',
            domicilio:'',
            domicilio_entrecalle:'',
            domicilio_nointerior:'',
            domicilio_noexterior:'',
            colonia:'',
            codigopostal:'',
            telefono:'',
            celular:'',
            email:'',
            fechanacimiento:'',
            edad:0,
            sexo:'',
            estatura:0,
            peso:0,
            curp:'',
            tipoperiodo:'',
            id_periodo:'',
            fecharegistro: '',
            fechabaja:null,
            id_secundaria_procedencia:'',
            secundaria_nombre:'',
            secundaria_clave:'',
            secundaria_promedio:0,
            secundaria_fechaegreso:'',
            observaciones:'',
            alergias:false,
            alergias_describe:'',
            tiposangre:'',
            tutor_nombre:'',
            tutor_domicilio:'',
            tutor_colonia:'',
            tutor_telefono:'',
            tutor_ocupacion:'',
            tutor_celular:'',
            id_nacionalidad:'',
            id_lugarnacimiento:'',
            id_paisnacimiento:'',
            id_estadonacimiento:'',
            id_municipionacimiento:'',
            id_localidadnacimiento:'',
            id_estadodomicilio:'',
            id_municipiodomicilio:'',
            id_localidaddomicilio:'',
            id_discapacidad:0,
            enfermedad:'',
            id_etnia:0,
            lengua_indigena:false,
            lengua_indigena_desc:'',
            extranjero_padre_mexicano:false,
            extranjero_grado_ems:false,
            extranjero_habla_espanol:false,
            extranjero_escribe_espanol:false,
            extranjero_lee_espanol:false,
            id_extranjero_paisnacimiento:0,
            id_extranjero_paisestudio:0,
            id_estatus:'',
            turno_especial:false,
            id_beca:0,
            beca_otra:'',
            id_servicio_medico:0,
            servicio_medico_otro:'',
            servicio_medico_afiliacion:''

		});

        const alumnosave = reactive({
            id:0,
            id_plantel:'',
			id_cicloesc:'',
            id_planestudio:'',
            noexpediente:'',
            nombre: '',
            apellidos:'',
            apellidopaterno:'',
            apellidomaterno:'',
            domicilio:'',
            domicilio_entrecalle:'',
            domicilio_nointerior:'',
            domicilio_noexterior:'',
            colonia:'',
            codigopostal:'',
            telefono:'',
            celular:'',
            email:'',
            fechanacimiento:'',
            edad:0,
            sexo:'',
            estatura:0,
            peso:0,
            curp:'',
            id_periodo:'',
            fecharegistro:'',
            fechabaja:null,
            id_secundaria_procedencia:'',
            secundaria_nombre:'',
            secundaria_clave:'',
            secundaria_promedio:0,
            secundaria_fechaegreso:'',
            observaciones:'',
            alergias:false,
            alergias_describe:'',
            tiposangre:'',
            tutor_nombre:'',
            tutor_domicilio:'',
            tutor_colonia:'',
            tutor_telefono:'',
            tutor_ocupacion:'',
            tutor_celular:'',
            id_nacionalidad:'',
            id_paisnacimiento:'',
            id_localidadnacimiento:'',
            id_localidaddomicilio:'',
            id_lugarnacimiento:'',
            id_discapacidad:0,
            enfermedad:'',
            id_etnia:0,
            lengua_indigena:false,
            lengua_indigena_desc:'',
            extranjero_padre_mexicano:false,
            extranjero_grado_ems:false,
            extranjero_habla_espanol:false,
            extranjero_escribe_espanol:false,
            extranjero_lee_espanol:false,
            id_extranjero_paisnacimiento:0,
            id_extranjero_paisestudio:0,
            id_estatus:'',
            turno_especial:false,
            id_beca:0,
            beca_otra:'',
            id_servicio_medico:0,
            servicio_medico_otro:'',
            servicio_medico_afiliacion:''

		});
		const titulo = ref('');
        //const activetab= ref('');
        const datos = reactive({
            //por default el tab activo es 1
			activetab: 1,
            cicloArray: [],
            planArray:[],
            nacionalArray:[],
            paisArray:[],
            lugarArray:[],

            //por default el país del domicilio del tutor debe ser 1 MEXICO
            id_paisdomicilio:1,

            estadodomicilioArray:[],
            tipoperiodoArray:[],

            id_paisnacimiento: '',
            estadoArray:[],

            id_estadonacimiento:'',
            municipioArray:[],

            id_municipionacimiento:'',
            localidadArray:[],

            id_estadodomicilio:'',
            municipiodomicilioArray:[],

            id_municipiodomicilio:'',
            localidaddomicilioArray:[],

            //por default el tpo periodo 1 debe ser SEMESTRE
            id_tipoperiodo:1,
            periodoArray:[],

            sexoArray:[],

            secundariaArray:[],

            discapacidadArray:[],
            etniaArray:[],

            estatusArray:[],
            becaArray:[],
            serviciomedicoArray:[],
            plantel:'',

		});

        alumno.fecharegistro=date;
        alumnosave.fecharegistro=date;

        datos.sexoArray.push({'id':'','nombre':'Selecciona sexo'});
        datos.sexoArray.push({'id':'M','nombre':'MASCULINO'});
        datos.sexoArray.push({'id':'F','nombre':'FEMENINO'});
        datos.sexoArray.push({'id':'N','nombre':'NO BINARIO'});
        /* datos.semestreArray.push({'Id': 0, 'Name': 'Selecciona periodo'});
        datos.semestreArray.push({'Id': 1, 'Name': '1'});
        datos.semestreArray.push({'Id': 2, 'Name': '2'});
        datos.semestreArray.push({'Id': 3, 'Name': '3'});
        datos.semestreArray.push({'Id': 4, 'Name': '4'});
        datos.semestreArray.push({'Id': 5, 'Name': '5'});
        datos.semestreArray.push({'Id': 6, 'Name': '6'}); */

        get('/api/ciclo_esc/getciclos')
				.then(({data}) => {
					//alert (data);
                    //dd(data);
                    datos.cicloArray=data.data;
                   /* ciclo_esc.nombre= data.data.nombre;
					ciclo_esc.abreviatura = data.data.id; */
				});
        get('/api/plan_estudio/getplanes/'+props.plantel_id)
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.planArray=data.data;
            });
        get('/api/ubicacion_geo/getnacionalidades')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.nacionalArray=data.data;

            });
        get('/api/ubicacion_geo/getpaises')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.paisArray=data.data;

            });


        get('/api/ubicacion_geo/getlugares')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.lugarArray=data.data;

            });


        get('/api/escolares/gettipoperiodos')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.tipoperiodoArray=data.data;

            });

        get('/api/ubicacion_geo/getestados/'+datos.id_paisdomicilio)
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.estadodomicilioArray=data.data;

            });

        get('/api/escolares/getsecundarias')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.secundariaArray=data.data;

            });
        get('/api/catalogos/getdiscapacidades')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.discapacidadArray=data.data;

            });

        get('/api/catalogos/getetnias')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.etniaArray=data.data;

            });
        get('/api/catalogos/getserviciosmedicos')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.serviciomedicoArray=data.data;

            });
            get('/api/escolares/getbecas')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.becaArray=data.data;

            });
            get('/api/escolares/getestatus')
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.estatusArray=data.data;

            });

        console.log("Alumno_id: "+props.alumno_id);
        console.log("Plantel_id: "+props.plantel_id);


		if(props.alumno_id === undefined)
		{
			//No se pasaron datos al componente
			titulo.value = 'Guardar nuevo alumno';
            alumno.id_plantel=props.plantel_id;
            get('/api/catalogos/getplantel/'+alumno.id_plantel)
                    .then(({data}) => {
                        //alert (data);
                        //dd(data);
                        datos.plantel=data.data[0].nombre;

                    });

		}
		else
		{
			//Se paso el id del registro a editar al componente
			titulo.value = 'Editar alumno Id: '+props.alumno_id;

			get('/api/alumno/get/'+props.alumno_id)
				.then(({data}) => {
					//alert (data);
                    alumno.id_plantel=data.data[0].id_plantel;
                    alumno.id_cicloesc=data.data[0].id_cicloesc;
                    alumno.id_planestudio=data.data[0].id_planestudio;
                    alumno.noexpediente=data.data[0].noexpediente;
                    alumno.nombre= data.data[0].nombre;
                    alumno.apellidos= data.data[0].apellidos;
                    alumno.apellidopaterno=data.data[0].apellidopaterno;
                    alumno.apellidomaterno=data.data[0].apellidomaterno;
                    alumno.domicilio=data.data[0].domicilio;
                    alumno.domicilio_entrecalle=data.data[0].domicilio_entrecalle;
                    alumno.domicilio_nointerior=data.data[0].domicilio_nointerior;
                    alumno.domicilio_noexterior=data.data[0].domicilio_noexterior;
					alumno.domicilio_noexterior=data.data[0].domicilio_noexterior;
                    alumno.colonia=data.data[0].colonia;
                    alumno.codigopostal=data.data[0].codigopostal;
                    alumno.telefono=data.data[0].telefono;
                    alumno.celular=data.data[0].celular;
                    alumno.email=data.data[0].email;
                    alumno.fechanacimiento=data.data[0].fechanacimiento;
                    alumno.edad=data.data[0].edad;
                    alumno.sexo=data.data[0].sexo;
                    alumno.estatura=data.data[0].estatura;
                    alumno.peso=data.data[0].peso;
                    alumno.curp=data.data[0].curp;
                    alumno.id_tipoperiodo=data.data[0].id_tipoperiodo;
                    alumno.id_periodo=data.data[0].id_periodo;
                    alumno.fecharegistro=data.data[0].fecharegistro;
                    alumno.fechabaja=data.data[0].fechabaja;
                    alumno.id_secundaria_procedencia=data.data[0].id_secundaria_procedencia;
                    alumno.secundaria_nombre=data.data[0].secundaria_nombre;
                    alumno.secundaria_clave=data.data[0].secundaria_clave;
                    alumno.secundaria_promedio=data.data[0].secundaria_promedio;
                    alumno.secundaria_fechaegreso=data.data[0].secundaria_fechaegreso;
                    alumno.observaciones=data.data[0].observaciones;
                    if (data.data[0].alergias==1)
                        alumno.alergias=true;
                    else
                        alumno.alergias=false;
                    alumno.alergias_describe=data.data[0].alergias_describe;
                    alumno.id_discapacidad=data.data[0].id_discapacidad;
                    alumno.enfermedad=data.data[0].enfermedad;
                    alumno.tiposangre=data.data[0].tiposangre;
                    alumno.tutor_nombre=data.data[0].tutor_nombre;
                    alumno.tutor_domicilio=data.data[0].tutor_domicilio;
                    alumno.tutor_colonia=data.data[0].tutor_colonia;
                    alumno.tutor_telefono=data.data[0].tutor_telefono;
                    alumno.tutor_ocupacion=data.data[0].tutor_ocupacion;
                    alumno.tutor_celular=data.data[0].tutor_celular;
                    alumno.madre_nombre=data.data[0].madre_nombre;
                    alumno.madre_celular=data.data[0].madre_celular;
                    alumno.tutor_empresa_nombre=data.data[0].tutor_empresa_nombre;
                    alumno.tutor_empresa_domicilio=data.data[0].tutor_empresa_domicilio;
                    alumno.tutor_empresa_telefono=data.data[0].tutor_empresa_telefono;
                    alumno.tutor_empresa_colonia=data.data[0].tutor_empresa_colonia;
                    alumno.empresa_nombre=data.data[0].empresa_nombre;
                    alumno.empresa_domicilio=data.data[0].empresa_domicilio;
                    alumno.empresa_telefono=data.data[0].empresa_telefono;
                    alumno.empresa_colonia=data.data[0].empresa_colonia;

                    alumno.id_nacionalidad=data.data[0].id_nacionalidad;
                    alumno.id_lugarnacimiento=data.data[0].id_lugarnacimiento;
                    alumno.id_paisnacimiento=data.data[0].id_paisnacimiento;
                    alumno.id_estadonacimiento=data.data[0].id_estadonacimiento;
                    alumno.id_municipionacimiento=data.data[0].id_municipionacimiento;
                    alumno.id_localidadnacimiento=data.data[0].id_localidadnacimiento;
                    alumno.id_estadodomicilio=data.data[0].id_estadodomicilio;
                    alumno.id_municipiodomicilio=data.data[0].id_municipiodomicilio;
                    alumno.id_localidaddomicilio=data.data[0].id_localidaddomicilio;
                    alumno.id_etnia=data.data[0].id_etnia;
                    if (data.data[0].lengua_indigena==1)
                        alumno.lengua_indigena=true;
                    else
                        alumno.lengua_indigena=false;

                    alumno.lengua_indigena_desc=data.data[0].lengua_indigena_desc;
                    if (data.data[0].extranjero_padre_mexicano==1)
                        alumno.extranjero_padre_mexicano=true;
                    else
                        alumno.extranjero_padre_mexicano=false;

                    if (data.data[0].extranjero_grado_ems==1)
                        alumno.extranjero_grado_ems=true;
                    else
                        alumno.extranjero_grado_ems=false;

                    if (data.data[0].extranjero_habla_espanol==1)
                        alumno.extranjero_habla_espanol=true;
                    else
                        alumno.extranjero_habla_espanol=false;

                    if (data.data[0].extranjero_escribe_espanol==1)
                        alumno.extranjero_escribe_espanol=true;
                    else
                        alumno.extranjero_escribe_espanol=false;

                    if (data.data[0].extranjero_lee_espanol==1)
                        alumno.extranjero_lee_espanol=true;
                    else
                        alumno.extranjero_lee_espanol=false;


                    alumno.id_extranjero_paisnacimiento=data.data[0].id_extranjero_paisnacimiento;
                    alumno.id_extranjero_paisestudio=data.data[0].id_extranjero_paisestudio;

                    if (data.data[0].turno_especial==1)
                        alumno.turno_especial=true;
                    else
                        alumno.turno_especial=false;

                    alumno.id_beca=data.data[0].id_beca;
                    alumno.beca_otra=data.data[0].beca_otra;
                    alumno.id_servicio_medico=data.data[0].id_servicio_medico;
                    alumno.servicio_medico_otro=data.data[0].servicio_medico_otro;
                    alumno.servicio_medico_afiliacion=data.data[0].servicio_medico_afiliacion;
                    alumno.id_estatus=data.data[0].id_estatus;

                    datos.id_paisnacimiento =alumno.id_paisnacimiento;
                    get('/api/ubicacion_geo/getestados/'+datos.id_paisnacimiento)
                    .then(({data}) => {
                        //alert (data);
                        datos.estadoArray=data.data;
                    });
                    datos.id_estadonacimiento=alumno.id_estadonacimiento;
                    get('/api/ubicacion_geo/getmunicipios/'+datos.id_estadonacimiento)
                        .then(({data}) => {
                            //alert (data);
                            //dd(data);
                            datos.municipioArray=data.data;

                     });
                     datos.id_municipionacimiento=alumno.id_municipionacimiento;
                    get('/api/ubicacion_geo/getlocalidades/'+datos.id_municipionacimiento)
                    .then(({data}) => {
                        //alert (data);
                        //dd(data);
                        datos.localidadArray=data.data;

                    });

                    datos.id_estadodomicilio=alumno.id_estadodomicilio;
                    get('/api/ubicacion_geo/getmunicipios/'+datos.id_estadodomicilio)
                        .then(({data}) => {

                            datos.municipiodomicilioArray=data.data;

                        });
                    datos.id_municipiodomicilio=alumno.id_municipiodomicilio;
                    get('/api/ubicacion_geo/getlocalidades/'+datos.id_municipiodomicilio)
                        .then(({data}) => {
                            //alert (data);
                            //dd(data);
                            datos.localidaddomicilioArray=data.data;

                        });

                    datos.id_localidaddomicilio=alumno.id_localidaddomicilio;
                    datos.id_periodo=alumno.id_periodo;
                    get('/api/escolares/getperiodos/'+datos.id_tipoperiodo)
                        .then(({data}) => {
                            //alert (data);
                            //dd(data);
                            datos.periodoArray=data.data;

                        });

                    get('/api/catalogos/getplantel/'+alumno.id_plantel)
                    .then(({data}) => {
                        //alert (data);
                        //dd(data);
                         datos.plantel=data.data[0].nombre;

                    });
				});

			console.log("Datos de registro id: "+props.alumno_id+" cargados");
		}

		//funcion para el boton fuardar
		async function Guardar(){

            alumno.apellidos=alumno.apellidopaterno+' '+alumno.apellidomaterno;

            //alumnosave.id=20;
            alumnosave.id_plantel=alumno.id_plantel;
			alumnosave.id_cicloesc=alumno.id_cicloesc;
            alumnosave.id_planestudio=alumno.id_planestudio;
            alumnosave.nombre=alumno.nombre;
            alumnosave.apellidos=alumno.apellidos;
            alumnosave.apellidopaterno=alumno.apellidopaterno;
            alumnosave.apellidomaterno=alumno.apellidomaterno;
            alumnosave.domicilio=alumno.domicilio;
            alumnosave.domicilio_entrecalle=alumno.domicilio_entrecalle;
            alumnosave.domicilio_nointerior=alumno.domicilio_nointerior;
            alumnosave.domicilio_noexterior=alumno.domicilio_noexterior;
            alumnosave.colonia=alumno.colonia;
            alumnosave.codigopostal=alumno.codigopostal;
            alumnosave.telefono=alumno.telefono;
            alumnosave.celular=alumno.celular;
            alumnosave.email=alumno.email;
            alumnosave.fechanacimiento=alumno.fechanacimiento;
            alumnosave.edad=alumno.edad;
            alumnosave.sexo=alumno.sexo;
            alumnosave.estatura=alumno.estatura;
            alumnosave.peso=alumno.peso;
            alumnosave.curp=alumno.curp;
            alumnosave.id_periodo=alumno.id_periodo;
            alumnosave.fecharegistro=alumno.fecharegistro;
            alumnosave.fechabaja=alumno.fechabaja;
            alumnosave.id_secundaria_procedencia=alumno.id_secundaria_procedencia;
            alumnosave.secundaria_nombre=alumno.secundaria_nombre;
            alumnosave.secundaria_clave=alumno.secundaria_clave;
            alumnosave.secundaria_promedio=alumno.secundaria_promedio;
            alumnosave.secundaria_fechaegreso=alumno.secundaria_fechaegreso;
            alumnosave.observaciones=alumno.observaciones;
            alumnosave.alergias=alumno.alergias;
            alumnosave.alergias_describe=alumno.alergias_describe;
            alumnosave.id_discapacidad=alumno.id_discapacidad;
            alumnosave.enfermedad=alumno.enfermedad;
            alumnosave.tiposangre=alumno.tiposangre;
            alumnosave.tutor_nombre=alumno.tutor_nombre;
            alumnosave.tutor_domicilio=alumno.tutor_domicilio;
            alumnosave.tutor_colonia=alumno.tutor_colonia;
            alumnosave.tutor_telefono=alumno.tutor_telefono;
            alumnosave.tutor_ocupacion=alumno.tutor_ocupacion;
            alumnosave.tutor_celular=alumno.tutor_celular;
            alumnosave.madre_nombre=alumno.madre_nombre;
            alumnosave.madre_celular=alumno.madre_celular;
            alumnosave.tutor_empresa_nombre=alumno.tutor_empresa_nombre;
            alumnosave.tutor_empresa_domicilio=alumno.tutor_empresa_domicilio;
            alumnosave.tutor_empresa_telefono=alumno.tutor_empresa_telefono;
            alumnosave.tutor_empresa_colonia=alumno.tutor_empresa_colonia;
            alumnosave.empresa_nombre=alumno.empresa_nombre;
            alumnosave.empresa_domicilio=alumno.empresa_domicilio;
            alumnosave.empresa_telefono=alumno.empresa_telefono;
            alumnosave.empresa_colonia=alumno.empresa_colonia;
            alumnosave.id_nacionalidad=alumno.id_nacionalidad;
            alumnosave.id_paisnacimiento=alumno.id_paisnacimiento;
            alumnosave.id_localidadnacimiento=alumno.id_localidadnacimiento;
            alumnosave.id_localidaddomicilio=alumno.id_localidaddomicilio;
            alumnosave.id_lugarnacimiento=alumno.id_lugarnacimiento;
            alumnosave.id_etnia=alumno.id_etnia;
            alumnosave.lengua_indigena=alumno.lengua_indigena;
            alumnosave.lengua_indigena_desc=alumno.lengua_indigena_desc;
            alumnosave.extranjero_padre_mexicano=alumno.extranjero_padre_mexicano;
            alumnosave.extranjero_grado_ems=alumno.extranjero_grado_ems;
            alumnosave.extranjero_habla_espanol=alumno.extranjero_habla_espanol;
            alumnosave.extranjero_escribe_espanol=alumno.extranjero_escribe_espanol;
            alumnosave.extranjero_lee_espanol=alumno.extranjero_lee_espanol;
            alumnosave.id_extranjero_paisnacimiento=alumno.id_extranjero_paisnacimiento;
            alumnosave.id_extranjero_paisestudio=alumno.id_extranjero_paisestudio;

            alumnosave.turno_especial=alumno.turno_especial;
            alumnosave.id_beca=alumno.id_beca;
            alumnosave.beca_otra=alumno.beca_otra;
            alumnosave.id_servicio_medico=alumno.id_servicio_medico;
            alumnosave.servicio_medico_otro=alumno.servicio_medico_otro;
            alumnosave.servicio_medico_afiliacion=alumno.servicio_medico_afiliacion;
            alumnosave.id_estatus=alumno.id_estatus;


			if(props.alumno_id === undefined)
			{
                alumnosave.id_plantel=props.plantel_id;

                let response = await axios.get('/api/alumno/getmaxexpediente/'+alumno.id_plantel+'/'+alumno.id_cicloesc)
                    /* .then(({data}) => {
                        //alert (data);
                        //dd(data);

                    }); */
                    console.log(response);
                        alumno.noexpediente=response.data;
                        console.log('aui');
                        console.log(alumno.noexpediente);

                alumnosave.noexpediente=alumno.noexpediente;

                console.log(alumnosave);

                //CREAR No se pasaron datos al componente
				console.log("Recibida en funcion Guardar Alumno: ("+alumnosave.nombre+")");
				errors.value = '';

				try{
					let response = await axios.post('/api/alumno/store', alumnosave);
					alumno_new.value = response.data.data;
				}
				catch(e){
					if(e.response.status === 422){
					errors.value = e.response.data.errors
					}
				}
				if(errors.value == '')
				{
					console.log("Registro agregado id:" + alumno_new.value.id);
					location.href = '/adminalumnos/alumnos/agregar/success/' + alumno_new.value.id;
				}
			}
			else
			{
                alumnosave.noexpediente=alumno.noexpediente;

                console.log(alumnosave);
				//EDITAR Se paso el id del registro a editar al componente
				console.log("Recibida en funcion Editar Alumno: ("+alumnosave.nombre+")");
				errors.value = '';

				try{
					let response = await axios.post('/api/alumno/'+props.alumno_id+'/editar', alumnosave);
					alumno_new.value = response.data.data;
				}
				catch(e){
					if(e.response.status === 422){
					errors.value = e.response.data.errors
					}
				}
				if(errors.value == '')
				{
					console.log("Registro editado id:" + alumno_new.value.id);
					location.href = '/adminalumnos/alumnos/agregar/success/' + alumno_new.value.id;
				}
			}
		}

        const onChangePais = (event) => {
            datos.id_paisnacimiento = event.target.value;
            get('/api/ubicacion_geo/getestados/'+datos.id_paisnacimiento)
            .then(({data}) => {
                //alert (data);
                datos.id_estadonacimiento='';
                datos.id_municipionacimiento='';
                datos.id_localidadnacimiento='';

                alumno.id_estadonacimiento='';
                alumno.id_municipionacimiento='';
                alumno.id_localidadnacimiento='';

                datos.estadoArray=data.data;
                datos.municipioArray=[];
                datos.localidadArray=[];


            });
            };
        const  onChangeEstado =(event) =>{
            datos.id_estadonacimiento = event.target.value;
            get('/api/ubicacion_geo/getmunicipios/'+datos.id_estadonacimiento)
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.id_municipionacimiento='';
                datos.id_localidadnacimiento='';
                alumno.id_municipionacimiento='';
                alumno.id_localidadnacimiento='';

                datos.municipioArray=data.data;
                datos.localidadArray=[];


            });
            };
        const onChangeMunicipio=(event)=>{
            datos.id_municipionacimiento= event.target.value;
            get('/api/ubicacion_geo/getlocalidades/'+datos.id_municipionacimiento)
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.id_localidadnacimiento='';
                alumno.id_localidadnacimiento='';
                datos.localidadArray=data.data;

            });
            };
            const onChangeEstadodom =(event) =>{
            datos.id_estadodomicilio= event.target.value;
            get('/api/ubicacion_geo/getmunicipios/'+datos.id_estadodomicilio)
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.id_municipiodomicilio='';
                datos.id_localidaddomicilio='';

                alumno.id_municipiodomicilio='';
                alumno.id_localidaddomicilio='';

                datos.municipiodomicilioArray=data.data;
                datos.localidaddomicilioArray=[];


            });
            };
            const  onChangeMunicipiodom=(event)=>{
                datos.id_municipiodomicilio= event.target.value;
                get('/api/ubicacion_geo/getlocalidades/'+datos.id_municipiodomicilio)
                .then(({data}) => {
                    //alert (data);
                    //dd(data);
                    datos.id_localidaddomicilio='';
                    alumno.id_localidaddomicilio='';
                    datos.localidaddomicilioArray=data.data;

                });
            };
            const  onChangeTipoperiodo=(event)=>{
            datos.id_tipoperiodo= event.target.value;
            get('/api/escolares/getperiodos/'+datos.id_tipoperiodo)
            .then(({data}) => {
                //alert (data);
                //dd(data);
                datos.id_periodo='';
                alumno.id_periodo='';
                datos.periodoArray=data.data;

            });
            };
		return {
			alumno,
			titulo,
			Guardar,
			errors,
			alumno_new,
            datos,
            onChangePais,
            onChangeEstado,
            onChangeMunicipio,
            onChangeEstadodom,onChangeMunicipiodom,onChangeTipoperiodo
		}
	},






}

</script>

