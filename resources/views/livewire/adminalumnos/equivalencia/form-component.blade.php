{{-- ANA MOLINA 10/07/2024 --}}
<section class="bg-light app-filters">
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
    @php
        use App\Models\Catalogos\PoliticaModel;
        use App\Models\Catalogos\AsignaturaModel;
    @endphp
    <div class="row g-3">

        <div class="col-12 col-sm-12">
            <div class="card shadow" id="principal">
                <div class="card-header">
                    <label class="card-title">Seleccionar alumno:</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($alumno_id == null)
                            <section class="py-3">
                                <select class="form-control select2BuscaAlumn" autocomplete="off">
                                    <option value=""></option>
                                </select>
                            </section>
                        @else
                            <div class="col-12 col-sm-12">
                                <label for="alumno" class="form-label">Expediente:</label>
                                <input class="form-control" type="text" value="{{ $dat_alumno->noexpediente }}"
                                    disabled>
                            </div>
                            <div class="col-12 col-sm-12">
                                <label for="alumno" class="form-label">Alumno:</label>
                                <input class="form-control" type="text"
                                    value="{{ $dat_alumno->apellidos }} {{ $dat_alumno->nombre }}" disabled>
                            </div>
                            {{-- <p>
                                <br>No EXPEDIENTE: <strong>{{$dat_alumno->noexpediente}}</strong>
                                <br>Alumno: <strong>{{$dat_alumno->apellidos}} {{$dat_alumno->nombre}}</strong>
                            </p> --}}
                        @endif
                        <div wire:loading>
                            @if ($alumno == null)
                                <h4><span style="color: red;">Buscando Alumno por favor espere...</span></h4>
                            @endif
                        </div>
                    </div>
                </div><!--card body -->
            </div><!-- Card shadow  -->



        </div>

        @if ($dat_alumno)
            <div class="error-message">
                @if (isset($tipo))
                    @if ($tipoe)
                        <span style="color:red;">EQUIVALENCIA </span>
                    @endif
                    @if ($tipor)
                        <span style="color:red;">REVALIDACION </span>
                    @endif
                    @hasrole(['control_escolar', 'super_admin'])
                        @if ($tipo == 'E' && is_null($expediente))
                            <span style="color:red">REVISIÓN</span>
                        @endif
                    @endhasrole
                @endif
                @if (isset($fecha_aut))
                    <span style="color:red;  "> * AUTORIZADO</span>
                @endif
            </div>

            @if (!isset($tipo))
                <div class="col-12 col-sm-4">
                    <label for="tipoe" class="form-label">Equivalencia:</label>
                    <input name="tipoe" type="checkbox" wire:model="tipoe" wire:change="processer('E')"
                        @if (isset($tipo)) disabled @endif>
                    @error('tipoe')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
                <div class="col-12 col-sm-4">
                    <label for="tipor" class="form-label">Revalidación:</label>
                    <input name="tipor" type="checkbox" wire:model="tipor" wire:change="processer('R')"
                        @if (isset($tipo)) disabled @endif>
                    @error('tipor')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
                @hasrole(['super_admin', 'control_escolar'])
                    <div class="col-12 col-sm-4">
                        <label for="tipor" class="form-label">REVISIÓN :</label>
                        <input name="tipor" type="checkbox" wire:model="tipos" wire:change="processer('S')"
                            @if (isset($tipo)) disabled @endif>
                        @error('tipor')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </div>
                @endhasrole
            @endif
            <div class="col-12 col-sm-12">
                <label for="alumno" class="form-label">Nombre del alumno:</label>
                <input class="form-control @error('alumno') is-invalid @enderror" placeholder="Nombre del alumno"
                    name="alumno" type="text" wire:model="alumno" disabled>
                @error('alumno')
                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                @enderror
            </div>



            @if ($tipor)
                <div class="col-12 col-sm-12">
                    <label for="emitidopor" class="form-label">Emitido por:</label>
                    <input class="form-control @error('emitidopor') is-invalid @enderror" placeholder="Emitido por"
                        name="emitidopor" type="text" wire:model="emitidopor">
                    @error('emitidopor')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>

                @if ($tipor || $tipoe)
                    <div class="col-12 col-sm-2">
                        <label for="folio" class="form-label">Folio:</label>
                        <input class="form-control @error('folio') is-invalid @enderror" placeholder="Folio"
                            name="folio" type="text" wire:model="folio">
                        @error('folio')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </div>
                @endif

            @endif
            @if ($tipor || $tipoe)
                <div class="col-12 col-sm-2">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input class="form-control @error('fecha') is-invalid @enderror" placeholder="Fecha" name="fecha"
                        type="date" wire:model="fecha">
                    @error('fecha')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
            @endif

            @if ($tipor)
                <div class="col-12 col-sm-4">
                    <label for="semestres" class="form-label">Semestres:</label>
                    <input class="form-control @error('semestres') is-invalid @enderror" placeholder="Semestres"
                        name="semestres" type="text" wire:model="semestres">
                    @error('semestres')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
            @endif

            @if ($tipoe || $tipos)
                <div>
                    <div>
                        <label for="cct" class="form-label">CCT:</label>
                        @if (!$cct)
                            <div wire:ignore.self> <!-- Ignora Livewire aquí -->
                                <section class="py-3">
                                    <select class="form-control select2buscact" autocomplete="off">
                                        <option value=""></option>
                                    </select>
                                </section>
                            </div>
                        @else
                            <input class="form-control @error('cct') is-invalid @enderror" disabled placeholder="CCT"
                                name="cct" type="text" wire:model="cct">
                        @endif

                    </div>

                </div>

            @endif
            <div class="col-12 col-sm-12">
                <label for="institucion" class="form-label">Institución educativa:</label>
                <input class="form-control @error('institucion') is-invalid @enderror"
                    placeholder="Institución educativa" name="institucion" type="text"
                    @if ($tipoe || $tipos) disabled @endif wire:model="institucion">
                @error('institucion')
                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                @enderror
            </div>
            @if ($tipor)
                <div class="col-12 col-sm-4">
                    <label for="grados" class="form-label">Grados:</label>
                    <input class="form-control @error('grados') is-invalid @enderror" placeholder="Grados"
                        name="grados" type="text" wire:model="grados">
                    @error('grados')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
                <div class="col-12 col-sm-4">
                    <label for="periodo_escolar" class="form-label">Periodo escolar:</label>
                    <input class="form-control @error('periodo_escolar') is-invalid @enderror"
                        placeholder="Periodo escolar" name="periodo_escolar" type="text"
                        wire:model="periodo_escolar">
                    @error('periodo_escolar')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
            @endif
            @unless (Auth::user()->hasRole('autorizar_rev'))
                @if (!$tipos)
                    <div class="col-12 col-sm-2">
                        <label for="expediente" class="form-label">Número de expediente de procedencia:</label>
                        <input class="form-control @error('expediente') is-invalid @enderror"
                            placeholder="Número de expediente" name="expediente" type="text" wire:model="expediente">
                        @error('expediente')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </div>
                @endif
            @endunless


            @if ($tipor)
                <div class="col-12 col-sm-12">
                    <label for="lugar" class="form-label">Lugar:</label>
                    <input class="form-control @error('lugar') is-invalid @enderror" placeholder="Lugar"
                        name="lugar" type="text" wire:model="lugar">
                    @error('lugar')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
            @endif

            @unless (Auth::user()->hasRole('autorizar_rev'))
                @if (!$tipos)
                    <div class="col-12 col-sm-12">
                        <label for="firmadcto" class="form-label">Firma documento de procedencia:</label>
                        <input class="form-control @error('firmadcto') is-invalid @enderror"
                            placeholder="Firma documento" name="firmadcto" type="text" wire:model="firmadcto">
                        @error('firmadcto')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </div>
                @endif
            @endunless
            <hr>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <label class="form-label">Seleccione el plantel a donde quiere ingresar las calificaciones:</label>
                    <select class="form-control" wire:model="plantel_id">
                        <option value="" selected>seleccionar plantel</option>
                        @foreach ($planteles as $plantel)
                            <option value="{{ $plantel->id }}">{{ $plantel->id }} - {{ $plantel->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <label class="form-label">Plan de estudios:</label>
                    <select class="form-control" wire:model="planestudio_id" wire:change="changeEventPlan(true)">
                        <option value="" selected>seleccionar plan de estudios</option>
                        @foreach ($planes_estudio as $pe)
                            <option value={{ $pe->id }}>{{ $pe->id }} - {{ $pe->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if ($equivalencia_id && Auth::user()->can('autorizar_rev'))

                <div class="tabcontent container-fluid">
                    <div class="row">
                        <div class="col-12 d-flex flex-row align-items-center">
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(4,{{ $dat_alumno->id }})">Mostrar
                                acta</button>
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(5,{{ $dat_alumno->id }})">Mostrar
                                Certificado</button>
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(6,{{ $dat_alumno->id }})">Mostrar
                                CURP</button>
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(1,{{ $dat_alumno->id }})">Mostrar
                                Foto</button>
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(8,{{ $dat_alumno->id }})">Mostrar
                                Documento Equivalencia</button>
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(7,{{ $dat_alumno->id }})">Mostrar
                                Documento Revalidación</button>
                        </div>


                        <div class="text-center">

                            @if ($documento)
                                @if ($tipo_archivo)
                                    @if (in_array($tipo_archivo, ['image/jpeg', 'image/png', 'image/gif']))
                                        <div class="container-fluid">
                                            <img
                                                src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $dat_alumno->id]) }}" />
                                        </div>
                                    @else
                                        <div style="width: 100%">
                                            <iframe style="width: 100%; height: 800px; display: block; margin: 0 auto;"
                                                src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $dat_alumno->id]) }}">
                                            </iframe>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <a class="btn btn-success"
                                            href="{{ route('archivo.descargar', ['tipo_archivo' => $documento, 'alumno_id' => $dat_alumno->id]) }}"
                                            download>
                                            Descargar Archivo
                                        </a>
                                    </div>
                                @else
                                    <div>
                                        <p>Archivo no encontrado</p>
                                    </div>
                                @endif
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
            @else
                <hr />
                @if ($equivalencia_id)

                    <div>


                        @if ($tipor)
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(7,{{ $dat_alumno->id }})">Mostrar
                                Documento Revalidación</button>
                        @else
                            <button class="btn btn-primary mb-3"
                                wire:click="cambiar_doc(8,{{ $dat_alumno->id }})">Mostrar
                                Documento Equivalencia</button>
                        @endif
                        @if ($documento)
                            @if ($tipo_archivo)
                                @if (in_array($tipo_archivo, ['image/jpeg', 'image/png', 'image/gif']))
                                    <div class="container-fluid">
                                        <img
                                            src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $dat_alumno->id]) }}" />
                                    </div>
                                @else
                                    <div style="width: 100%">
                                        <iframe style="width: 100%; height: 800px; display: block; margin: 0 auto;"
                                            src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $dat_alumno->id]) }}">d
                                        </iframe>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <a class="btn btn-success"
                                        href="{{ route('archivo.descargar', ['tipo_archivo' => $documento, 'alumno_id' => $dat_alumno->id]) }}"
                                        download>
                                        Descargar Archivo
                                    </a>
                                </div>
                            @else
                                <div>
                                    <p>Archivo no encontrado</p>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                @if (!$tipos)


                    <div>
                        Cargar documento de @if ($tipoe)
                            la Equivalencia
                        @else
                            la Revalidación
                        @endif

                        <div class="form-group">
                            @if (!$file)
                                <input type="file" accept="image/jpeg, image/png, image/jpg, application/pdf"
                                    class="custom-file-input" id="customFile" wire:model="file" hidden="true">

                                <button type="button" class="btn btn-large btn-warning"
                                    onclick="document.getElementById('customFile').click();">
                                    <i class="fa-sharp fa-solid fa-upload"></i> Seleccione un Archivo
                                </button>

                                <label class="custom-file-label">Selecciona un archivo</label>
                            @else
                                Documento de @if ($tipoe)
                                    Equivalencia
                                @else
                                    Revalidación
                                @endif cargado
                            @endif

                        </div>

                        <div wire:loading wire:target="file">
                            <span style="color:red;">CARGANDO... ESPERE POR FAVOR...</span>
                        </div>
                        {{-- 
                <section class="py-3">
                    @if ($file)
                        <button class="btn btn-primary btn-success float-end" wire:click="subir_archivo"
                            onclick="cargando();">Subir Archivo</button>
                    @endif
                </section>
                 --}}
                    </div>
                    <hr />
                @endif
            @endif

            @if ($tipoe || $tipos)
                <div class="col-12 col-sm-4">
                    <label for="tipop" class="form-label">Calificación por promedio:</label>
                    <input name="tipop" type="checkbox" wire:model="tipop" wire:change="processpa('P')">
                    @error('tipop')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
                <div class="col-12 col-sm-4">
                    <label for="tipoa" class="form-label">Calificación por asignatura:</label>
                    <input name="tipoa" type="checkbox" wire:model="tipoa" wire:change="processpa('A')">
                    @error('tipoa')
                        <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                    @enderror
                </div>
            @endif
            <table>
                <tr>
                    <th>Semestre</th>
                    <th>Selección</th>
                    <th>Ciclo Escolar</th>
                    @if ($tipoe)
                        <th>Calificación</th>
                        <th>Calif</th>
                    @endif
                </tr>
                <tr>
                    <td>
                        <label for="s1" class="form-label">Primero</label>
                    </td>
                    <td>
                        <input name="s1" type="checkbox" wire:model="s1">
                        @error('s1')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </td>
                    <td>
                        @if ($s1)
                            <select class="form-control select2_ciclos_esc" autocomplete="off"
                                wire:model.lazy="ciclo1">
                                <option value="" selected>seleccionar ciclo escolar</option>
                                @foreach ($ciclos_esc as $ce)
                                    <option value="{{ $ce->id }}">{{ $ce->id }} -
                                        ({{ $ce->abreviatura }})
                                        -
                                        {{ $ce->nombre }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    @if ($tipoe || $tipos)
                        <td>
                            @if ($tipop && $s1)
                                <input class="form-control @error('prom1') is-invalid @enderror"
                                    placeholder="Promedio" name="prom1" type="text" wire:model="prom1">
                                @error('prom1')
                                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                                @enderror
                            @endif
                        </td>
                        <td>
                            @if ($tipop && $s1)
                                <select class="form-control" wire:model="calif1">
                                    <option></option>
                                    <option value="AC">AC</option>
                                    <option value="NA">NA</option>
                                    <option value="REV">REV</option>
                                </select>
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>
                        <label for="s1" class="form-label">Segundo</label>
                    </td>
                    <td>
                        <input name="s2" type="checkbox" wire:model="s2">
                        @error('s2')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </td>
                    <td>
                        @if ($s2)
                            <select class="form-control select2_ciclos_esc" autocomplete="off"
                                wire:model.lazy="ciclo2">
                                <option value="" selected>seleccionar ciclo escolar</option>
                                @foreach ($ciclos_esc as $ce)
                                    <option value="{{ $ce->id }}">{{ $ce->id }} -
                                        ({{ $ce->abreviatura }})
                                        -
                                        {{ $ce->nombre }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    @if ($tipoe)
                        <td>
                            @if ($tipop && $s2)
                                <input class="form-control @error('prom2') is-invalid @enderror"
                                    placeholder="Promedio" name="prom2" type="text" wire:model="prom2">
                                @error('prom2')
                                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                                @enderror
                            @endif
                        </td>
                        <td>
                            @if ($tipop && $s2)
                                <select class="form-control" wire:model="calif2">
                                    <option></option>
                                    <option value="AC">AC</option>
                                    <option value="NA">NA</option>
                                    <option value="REV">REV</option>
                                </select>
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>
                        <label for="s1" class="form-label">Tercero</label>
                    </td>
                    <td>
                        <input name="s3" type="checkbox" wire:model="s3">
                        @error('s3')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </td>
                    <td>
                        @if ($s3)
                            <select class="form-control select2_ciclos_esc" autocomplete="off"
                                wire:model.lazy="ciclo3">
                                <option value="" selected>seleccionar ciclo escolar</option>
                                @foreach ($ciclos_esc as $ce)
                                    <option value="{{ $ce->id }}">{{ $ce->id }} -
                                        ({{ $ce->abreviatura }})
                                        -
                                        {{ $ce->nombre }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    @if ($tipoe)
                        <td>
                            @if ($tipop && $s3)
                                <input class="form-control @error('prom3') is-invalid @enderror"
                                    placeholder="Promedio" name="prom3" type="text" wire:model="prom3">
                                @error('prom3')
                                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                                @enderror
                            @endif
                        </td>
                        <td>
                            @if ($tipop && $s3)
                                <select class="form-control" wire:model="calif3">
                                    <option></option>
                                    <option value="AC">AC</option>
                                    <option value="NA">NA</option>
                                    <option value="REV">REV</option>
                                </select>
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>
                        <label for="s1" class="form-label">Cuarto</label>
                    </td>
                    <td>
                        <input name="s4" type="checkbox" wire:model="s4">
                        @error('s4')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </td>
                    <td>
                        @if ($s4)
                            <select class="form-control select2_ciclos_esc" autocomplete="off"
                                wire:model.lazy="ciclo4">
                                <option value="" selected>seleccionar ciclo escolar</option>
                                @foreach ($ciclos_esc as $ce)
                                    <option value="{{ $ce->id }}">{{ $ce->id }} -
                                        ({{ $ce->abreviatura }})
                                        -
                                        {{ $ce->nombre }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    @if ($tipoe)
                        <td>
                            @if ($tipop && $s4)
                                <input class="form-control @error('prom4') is-invalid @enderror"
                                    placeholder="Promedio" name="prom4" type="text" wire:model="prom4">
                                @error('prom4')
                                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                                @enderror
                            @endif
                        </td>
                        <td>
                            @if ($tipop && $s4)
                                <select class="form-control" wire:model="calif4">
                                    <option></option>
                                    <option value="AC">AC</option>
                                    <option value="NA">NA</option>
                                    <option value="REV">REV</option>
                                </select>
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>
                        <label for="s1" class="form-label">Quinto</label>
                    </td>
                    <td>
                        <input name="s5" type="checkbox" wire:model="s5">
                        @error('s5')
                            <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                        @enderror
                    </td>
                    <td>
                        @if ($s5)
                            <select class="form-control select2_ciclos_esc" autocomplete="off"
                                wire:model.lazy="ciclo5">
                                <option value="" selected>seleccionar ciclo escolar</option>
                                @foreach ($ciclos_esc as $ce)
                                    <option value="{{ $ce->id }}">{{ $ce->id }} -
                                        ({{ $ce->abreviatura }})
                                        -
                                        {{ $ce->nombre }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    @if ($tipoe)
                        <td style="width:8%">
                            @if ($tipop && $s5)
                                <input class="form-control @error('prom5') is-invalid @enderror"
                                    placeholder="Promedio" name="prom5" type="text" wire:model="prom5">
                                @error('prom5')
                                    <div class="error-message"><span style="color:red;">{{ $message }}</span></div>
                                @enderror
                            @endif
                        </td>
                        <td style="width:8%">
                            @if ($tipop && $s5)
                                <select class="form-control" wire:model="calif5">
                                    <option></option>
                                    <option value="AC">AC</option>
                                    <option value="NA">NA</option>
                                    <option value="REV">REV</option>
                                </select>
                            @endif
                        </td>
                    @endif
                </tr>
            </table>
            <div class="row g-3 mt-3">
                <div class="col-sm-8">
                    @if (!isset($fecha_aut))
                        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
                        @if ($equivalencia_id)
                            @php
                                $autorizar = 0;
                            @endphp
                            @hasallroles('autorizar_rev')
                                @php
                                    $autorizar = 1;
                                @endphp
                                <button class="btn btn-primary" onclick="autorizando()">Autorizar</button>
                            @endhasallroles
                        @endif

                        @if ($equivalencia_id && $tipos && $autorizar != 1)
                            @hasrole(['control_escolar', 'super_admin'])
                                <button class="btn btn-primary" onclick="autorizando()">Autorizar</button>
                            @endhasrole
                        @endif

                    @endif
                    {{-- @if (isset($fecha_aut))  --}}
                    @if ($equivalencia_id)
                        <button class="btn btn-info" type="button"
                            onclick="generando('{{ $equivalencia_id }}','{{ $tipo }}');">Imprimir</button>
                    @endif

                    {{-- @endif --}}

                </div>
            </div>
            <div class="col-12 col-sm-12">
                <div class="card ">
                    <div class="card-header">
                        <label class="card-title">Asignaturas</label>
                    </div>
                    <div class="card-body">
                        <table style="width:100%">
                            <tr style="border-bottom: 1px solid #ddd;">
                                <th></th>
                                <th style="width:5%">ID</th>
                                <th style="width:8%">Semestre</th>
                                <th style="width:8%">Clave</th>
                                <th>Asignatura</th>
                                @if ($tipoe)
                                    <th style="width:8%">Calificación</th>
                                    <th style="width:8%">Calif</th>
                                @endif
                            </tr>
                            @php
                                $periodo = 0;
                            @endphp
                            @foreach ($lista_asignaturas as $index => $pea)
                                @php
                                    $buscar_asi = AsignaturaModel::find($pea['id_asignatura']);

                                    $politica = PoliticaModel::where(
                                        'id_areaformacion',
                                        $buscar_asi->id_areaformacion,
                                    )->first();

                                    switch ($politica->id_variabletipo) {
                                        case '1':
                                            $numerica = true;
                                            break;
                                        case '2':
                                            $numerica = false;
                                            break;
                                        default:
                                            $numerica = null;
                                            break;
                                    }
                                @endphp
                                <tr>
                                    @if (
                                        ($pea['periodo'] == 1 && $s1) ||
                                            ($pea['periodo'] == 2 && $s2) ||
                                            ($pea['periodo'] == 3 && $s3) ||
                                            ($pea['periodo'] == 4 && $s4) ||
                                            ($pea['periodo'] == 5 && $s5))
                                        @if ($periodo != $pea['periodo'])
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td axis="periodo" colspan=4>Semestre: {{ $pea['periodo'] }}</td>
                                </tr>
                            @endif
                            <td>
                                <input type="checkbox" name="lista_asignaturas[{{ $index }}][sel]"
                                    wire:model="lista_asignaturas.{{ $index }}.sel"
                                    @if ($this->lista_asignaturas[$index]['sel']) checked="checked" @endif>
                            </td>
                            {{-- <td>{{$pea->asignatura->id}}</td>
                                                                <td>{{$pea->asignatura->periodo}}</td>
                                                                <td>{{$pea->asignatura->clave}}</td>
                                                                <td>{{$pea->asignatura->nombre}}</td> --}}
                            <td>{{ $pea['id_asignatura'] }}</td>
                            <td>{{ $pea['periodo'] }}</td>
                            <td>{{ $pea['clave'] }}</td>
                            <td>{{ $pea['nombre'] }}</td>
                            @if ($tipoe || $tipos)
                                <td>
                                    @if ($tipoa && $numerica)
                                        <input class="form-control" placeholder="Calificación"
                                            name="lista_asignaturas[{{ $index }}][calificacion]"
                                            oninput="formatInput(this)"
                                            type="text"
                                            wire:model="lista_asignaturas.{{ $index }}.calificacion">
                                    @endif

                                    @if ($tipop)
                                        @if ($pea['periodo'] == 1 && $s1 && $pea['sel'] == true && $numerica)
                                            {{ $prom1 }}
                                        @endif
                                        @if ($pea['periodo'] == 2 && $s2 && $pea['sel'] == true && $numerica)
                                            {{ $prom2 }}
                                        @endif
                                        @if ($pea['periodo'] == 3 && $s3 && $pea['sel'] == true && $numerica)
                                            {{ $prom3 }}
                                        @endif
                                        @if ($pea['periodo'] == 4 && $s4 && $pea['sel'] == true && $numerica)
                                            {{ $prom4 }}
                                        @endif
                                        @if ($pea['periodo'] == 5 && $s5 && $pea['sel'] == true && $numerica)
                                            {{ $prom5 }}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($tipoa && !$numerica)
                                        <select class="form-control"
                                            name="lista_asignaturas[{{ $index }}][calif]"
                                            wire:model="lista_asignaturas.{{ $index }}.calif">
                                            <option></option>
                                            <option value="AC">AC</option>
                                            <option value="NA">NA</option>
                                            <option value="REV">REV</option>
                                        </select>
                                    @endif
                                    @if ($tipop)
                                        @if ($pea['periodo'] == 1 && $s1)
                                            {{ $calif1 }}
                                        @endif
                                        @if ($pea['periodo'] == 2 && $s2)
                                            {{ $calif2 }}
                                        @endif
                                        @if ($pea['periodo'] == 3 && $s3)
                                            {{ $calif3 }}
                                        @endif
                                        @if ($pea['periodo'] == 4 && $s4)
                                            {{ $calif4 }}
                                        @endif
                                        @if ($pea['periodo'] == 5 && $s5)
                                            {{ $calif5 }}
                                        @endif
                                    @endif
                                </td>
                            @endif
        @endif

        @php $periodo=$pea['periodo']; @endphp
        </tr>
        @endforeach
        </table>
    </div>
    </div>
    </div>
    @endif


    </div>

</section>




@section('js_post')
    <script type="text/javascript">
        $(document).ready(function() {
            // Inicializar el select2 de alumno
            $('.select2BuscaAlumn').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                language: 'es',
                placeholder: 'Buscar por expediente, nombre o apellidos',
                minimumInputLength: 5,
                ajax: {
                    url: '/api/alumno/buscar',
                    dataType: 'json',
                    method: 'GET',
                    delay: 250,
                    data: function(params) {
                        var termBase64 = btoa(unescape(encodeURIComponent(params.term)));
                        var typeBase64 = btoa('correos');
                        return {
                            term: termBase64,
                            type: typeBase64
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.noexpediente + ' - ' + obj.apellidos + ' ' + obj
                                        .nombre
                                };
                            })
                        };
                    }
                }
            });

            // Manejar evento de selección para alumno
            $('.select2BuscaAlumn').on('select2:select', function(e) {
                var data = e.params.data;
                @this.set('alumno_id', data.id);
            });

            // Inicializar el select2 de Centro de Trabajo (CCT)
            function initSelect2CT() {
                $('.select2buscact').select2({
                    theme: 'bootstrap-5',
                    allowClear: true,
                    language: 'es',
                    placeholder: 'Buscar por nombre o clave de Centro de Trabajo (CT)',
                    minimumInputLength: 5,
                    ajax: {
                        url: '/api/ct/buscar',
                        dataType: 'json',
                        method: 'GET',
                        delay: 250,
                        data: function(params) {
                            var termBase64 = btoa(unescape(encodeURIComponent(params.term)));
                            return {
                                term: termBase64
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: `${obj.ct} - ${obj.nombre_ct}`,
                                        ct: obj.ct,
                                        nombre: obj.nombre_ct
                                    };
                                })
                            };
                        }
                    }
                });

                // Manejar evento de selección para CCT
                $('.select2buscact').on('select2:select', function(e) {
                    var data = e.params.data;
                    @this.set('cct', data.ct);
                    @this.set('institucion', data.nombre);
                    //console.log(data);
                });
            }

            // Inicializar select2 de Plan de Estudio
            $('.planestudio_id').select2({
                theme: 'bootstrap-5'
            });

            $('.planestudio_id').on('select2:select', function(e) {
                var data = e.params.data;
                @this.set('alumno_id', data.id);
            });

            // Inicializar el select2 de CCT al cargar la página
            initSelect2CT();

            // Escuchar el evento de Livewire después de cada renderizado
            Livewire.hook('message.processed', (message, component) => {
                // Reinicializar solo el select2 de CCT
                initSelect2CT();
            });
        });
    </script>

    <script>
        function generando(equivalencia_id, tipo) {
            let url =
                "{{ route('adminalumnos.equivalencia.reporte', ['equivalencia_id' => ':equivalencia', 'tipo' => ':tipo']) }}";

            //codificar
            var encodedequi = btoa(equivalencia_id);
            var encodedtipo = btoa(tipo);

            url = url.replace(":equivalencia", encodedequi);

            url = url.replace(":tipo", encodedtipo);


            Swal.fire({
                title: 'Generando impresión...',
                html: 'Por favor espere.',

                showConfirmButton: false
            });
            Swal.showLoading();


            $.ajax({
                url: url,
                type: "GET",
                success: function(result) {
                    window.open(url, "_blank");
                    Swal.close(); // this is what actually allows the close() to work
                    //console.log(result);
                },
            });
        }
    </script>

    <script>
        function autorizando() {
            Swal.fire({
                title: 'Carga de calificaciones al alumno',
                text: "Confirme que desea cargar las calificaciones al historial del alumno",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, continuar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('autorizar');
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Escucha el evento 'foto_borrada' emitido desde Livewire
            Livewire.on('autorizado', () => {
                Swal.fire({
                    title: '¡Autorizado!',
                    text: 'Se han cargado las calificaciones en el historial del alumno.',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            });
        });

        function formatInput(input) {
            // Elimina cualquier caracter que no sea un número
            input.value = input.value.replace(/\D/g, '');

            // Limita la longitud del valor a 3 dígitos
            if (input.value.length > 3) {
                input.value = input.value.slice(0, 3);
            }
            if (parseInt(input.value) > 100) {
                input.value = '100';
            }
        }
    </script>
@endsection
