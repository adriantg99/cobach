<div>
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
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">SUBIR DOCUMENTACIÓN DE ALUMNOS</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-xl-11 text-nowrap">
                    <table>

                        <tr>
                            <td>
                                <label class="form-label">Alumno: </label>
                            </td>
                            <td style="width: 100%">
                                @if ($alumno_id == null)
                                    <section class="py-3">
                                        <select class="form-control select2BuscaAlumn" autocomplete="off">
                                            <option value=""></option>
                                        </select>
                                    </section>
                                @else
                                    <p><strong>Alumno EXP: {{ $alumno->noexpediente }}</strong></p>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div wire:loading>
                @if ($alumno == null)
                    <h4><span style="color: red;">Buscando Alumno por favor espere...</span></h4>
                @endif
            </div>
            @if ($alumno_id)


                <div class="row">
                    <h3><strong>{{ $alumno->nombre }} {{ $alumno->apellidos }}</strong></h3>
                    <h4>{{ $alumno->noexpediente }}<br>{{ $alumno->correo_institucional }}</h4>
                    @if (empty($alumno->telefono))
                        <h3><strong>{{ $alumno->celular }}</strong></h3>
                    @else
                        <h3><strong>{{ $alumno->telefono }}</strong></h3>
                    @endif
                    <table>
                        <tr>

                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <td><label class="form-label">Tipo de documento: </label></td>
                            <td style="width: 80%">
                                <select class="form-control" wire:model="tipo" {!! $tipo != null ? 'disabled' : '' !!}>
                                    <option></option>
                                    <option value="1">1: Fotografia de alumno para kardex, boleta y credenciales
                                    </option>
                                    <option value="2">2: Fotografia de alumno para certificado</option>
                                    <option value="4">3: Acta de nacimiento</option>
                                    <option value="5">4: Certificado de secundaria</option>
                                    <option value="6">5: CURP</option>
                                    <option value="7">7: Revalidacion</option>
                                    <option value="8">8: Equivalencia</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            @endif


            @if ($tipo and $alumno_id)

                <div class="form-group">
                    <input type="file" accept="image/jpeg, image/png, image/jpg, application/pdf" class="custom-file-input"
                        id="customFile" wire:model="file" hidden="true">

                    <button type="button" class="btn btn-large btn-warning" accept="image/jpeg, image/png, image/jpg, application/pdf"
                        onclick="document.getElementById('customFile').click();"><i
                            class="fa-sharp fa-solid fa-upload"></i> Seleccione un Archivo</button>

                    <label class="custom-file-label">Selecciona un archivo</label>
                </div>

                <div wire:loading wire:target="file">
                    <span style="color:red;">CARGANDO... ESPERE POR FAVOR...</span>
                </div>

                <section class="py-3">
                    @if ($file)
                        <button class="btn btn-primary btn-success float-end" wire:click="subir_archivo"
                            onclick="cargando();">Subir Archivo</button>
                    @endif
                </section>
            @endif

        </div>
        <!--<input class="form-control" placeholder="0" name="cantidad_grupos_extra" type="text" id="cantidad_grupos_extra" wire:model="cantidad_grupos_extra">-->

        <!-- Elemento de separación con margen inferior -->
        {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    </div>

    <div></div>

    @if ($alumno_id)
        <div class="card shadow" id="principal">
            <div class="card-header py-3">
                <p class="text-primary m-0 fw-bold">Visualización de documentos</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-xl-11 text-nowrap">
                        <div class="mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-xl-11 text-nowrap">
                                        <div>
                                            <div class="row">
                                                <div class="col-12 d-flex flex-row align-items-center">
                                                    <button class="btn btn-primary mb-3"
                                                        wire:click="cambiar_doc(4, {{ $alumno_id }})">Mostrar
                                                        acta</button>
                                                    <button class="btn btn-primary mb-3"
                                                        wire:click="cambiar_doc(5, {{ $alumno_id }})">Mostrar
                                                        Certificado de secundaria</button>
                                                    <button class="btn btn-primary mb-3"
                                                        wire:click="cambiar_doc(6, {{ $alumno_id }})">Mostrar
                                                        CURP</button>
                                                    <button class="btn btn-primary mb-3"
                                                        wire:click="cambiar_doc(1, {{ $alumno_id }})">Mostrar
                                                        Foto</button>
                                                    <button class="btn btn-primary mb-3"
                                                        wire:click="cambiar_doc(2, {{ $alumno_id }})">Mostrar
                                                        Foto certificado</button>
                                                     <button class="btn btn-primary mb-3"
                                                        wire:click="cambiar_doc(8, {{ $alumno_id }})">Mostrar
                                                        Doc Equivalencia</button>
                                                </div>
                                                <div class="text-center">


                                                    @if ($documento)
                                                    @if ($tipo_archivo)
                                                        @if (in_array($tipo_archivo, ['image/jpeg', 'image/png', 'image/gif']))
                                                            <div class="container-fluid">
                                                                <img
                                                                    src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $alumno_id]) }}" />
                                                            </div>
                                                        @else
                                                            <div style="width: 100%">
                                                                <iframe style="width: 100%; height: 800px; display: block; margin: 0 auto;"
                                                                    src="{{ route('archivo.mostrar', ['tipo_archivo' => $documento, 'alumno_id' => $alumno_id]) }}">
                                                                </iframe>
                                                            </div>
                                                        @endif
                    
                                                        <div class="mt-3">
                                                            <a class="btn btn-success"
                                                                href="{{ route('archivo.descargar', ['tipo_archivo' => $documento, 'alumno_id' => $alumno_id]) }}"
                                                                download>
                                                                Descargar Archivo
                                                            </a>
                                                            @can('fotos-borrar')
                                                            <button class="btn btn-secondary"
                                                                onclick="confirmar_borrado('{{ $alumno->id }}', '{{ $alumno->noexpediente }}', '{{ $documento }}')">
                                                                @if ($documento == 1 || $documento == 2)
                                                                Eliminar Foto
                                                                @else
                                                                Eliminar archivo
                                                                @endif
                                                            </button>
                                                    @endcan
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

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @section('js_post')
        <script type="text/javascript">
            $(document).ready(function() {

                $('.select2BuscaAlumn').select2({
                    theme: 'bootstrap-5',
                    allowClear: true,
                    //multiple: true,
                    language: 'es',
                    //templateResult: formatList,
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    //templateResult: function(data) {
                    //    return data.html;
                    //},
                    //templateSelection: function(data) {
                    //    return data.text;
                    //},
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
                            }
                        },
                        /*
                            processResults: function (data, page) {
                              return {
                                results: data
                              };
                            },
                            */
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


                $('.select2BuscaAlumn').on('select2:select', function(e) {
                    var data = e.params.data;
                    //alert(data.id);
                    @this.set('alumno_id', data.id);
                    //window.location = "/alumno/" + data.id + "/datos/";
                });

                //Swal.fire('Hello world!');

            });
        </script>
        <script>
            function cargando(plantel_id) {

                let timerInterval
                Swal.fire({
                    title: 'Cargando...',
                    html: 'Por favor espere.',
                    timer: 10000,
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
        </script>
        <script>
            function confirmar_borrado(alumno_id, expediente, documento) {
                Swal.fire({
                    title: 'Eliminación de la foto',
                    text: "Confirme que desea eliminar la foto del alumno: " + expediente +
                        ". Tenga en cuenta que esta acción es irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, borrarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('borrar_foto', alumno_id, documento);
                    }
                });
            }


            document.addEventListener('DOMContentLoaded', function() {
                // Escucha el evento 'foto_borrada' emitido desde Livewire
                Livewire.on('foto_borrada', () => {
                    Swal.fire({
                        title: '¡Borrado exitoso!',
                        text: 'La foto del alumno ha sido eliminada correctamente.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                });
            });
        </script>
    @endsection
