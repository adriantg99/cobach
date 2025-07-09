{{-- ANA MOLINA 23/05/2024 --}}


<section class="py-4">
    @if ($flagautoridad == true)
        <div>
            <p style="color:red;">Falta configurar la efirma de la autoridad educativa</p>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de selección:</label>
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
            <div class="col-6 col-sm-6">
                <label for="id_ciclo" class="form-label">Ciclo Escolar:</label>
                <select class="form-control" name="id_ciclo" id="id_ciclo" wire:model.lazy="id_ciclo">
                    <option value="" selected>por ciclo escolar</option>
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">{{ $ciclo->id }} - {{ $ciclo->nombre }} -
                            {{ $ciclo->per_inicio }} </option>
                    @endforeach
                </select>
                <label for="id_plantel" class="form-label">Plantel:</label>
                <select class="form-control" name="id_plantel" wire:model.lazy="id_plantel">
                    <option value="" selected>por plantel</option>
                    @foreach ($planteles as $plantel)
                        <option value="{{ $plantel->id }}">{{ $plantel->nombre }} </option>
                    @endforeach
                </select>
                <label for="id_grupo" class="form-label">Grupo:</label>
                <select class="form-control" name="id_grupo" wire:model.lazy="id_grupo" id="id_grupo"
                    wire:change="changeEvent($event.target.value)">
                    <option value="" selected>por grupo</option>
                    @foreach ($grupos as $grupo)
                        @php$turno = '';
                                                        if ($grupo->turno_id == 1) {
                                                            $turno = 'M';
                                                        } else {
                                                            $turno = 'V';
                                                        }
                                                @endphp ?>
                        {{-- <option value="{{$grupo->id}}">{{$grupo->nombre}} {{$turno}}</option> --}}
                        <option value="{{ $grupo->id }}">
                            {{ $grupo->nombre }}---{{ $grupo->turno->abreviatura }}---{{ $grupo->descripcion }} --- ID
                            ({{ $grupo->id }})
                        </option>
                    @endforeach
                </select>
                <div>
                    <label>
                        <input type="checkbox" name="chkplantel" wire:change="processMark()">Asignar plantel
                    </label>
                    <select class="form-control" name="id_plantelasigna" id="id_plantelasigna"
                        wire:model.lazy="id_plantelasigna" wire:change="changeEventPlantel($event.target.value)">
                        <option value="" selected>Seleccionar plantel</option>
                        @foreach ($planteles as $plantel)
                            <option value="{{ $plantel->id }}">{{ $plantel->nombre }} </option>
                        @endforeach
                    </select>
                </div>
                {{-- {{$id_grupo}} --}}
                <label class="form-label">Alumnos:</label>
                {{-- <select multiple class="form-control" name="id_grupo" wire:model.lazy="id_grupo">
                    <option value="" selected>por grupo</option>
                    @foreach ($grupos as $grupo)
                    <option value="{{$grupo->id}}">{{$grupo->nombre}} </option>
                    @endforeach
                    </select> --}}

            </div>

            <div class="col-8 col-sm-12">
                @if (!empty($getalumnos))
                <div class="row g-3">
                    <div class="col-12 col-sm-7">
                        <label>Alumno</label>
                    </div>
                    <div class="col-12 col-sm-2">
                        <label>Asignaturas AC</label>
                    </div>
                    <div class="col-12 col-sm-1">
                        <label>Foto</label>
                    </div>
                    <div class="col-12 col-sm-2">
                        <label>Estatus</label>
                    </div>
                </div>
                <div style="height:200px; overflow: auto;">
                    @foreach($getalumnos as $alumno)
                    <div class="row g-3">
                       <div class="col-12 col-sm-7">
                            @php
                                $fase='';
                                $read='';
                                if ($alumno->estatus_cert!=null)
                                {
                                    if ($alumno->digital==null)
                                        $fase='Generado';
                                    else
                                        $fase='Digital';
                                    $read='readonly';
                                }
                            @endphp
                           <label>
                            @if (substr($alumno->curp,10,1) != 'H' && substr($alumno->curp,10,1) != 'M')
                                <span style="color: red; font-weight: bold; background: yellow; padding: 2px 6px; border-radius: 4px;">Formato de CURP incorrecto</span>
                            @else
                                <input type="checkbox" name="chkalumno" {{$read}}
                                    value="{{$alumno->id}}"
                                    @if ($alumno->estatus=="T" || $fase !='Digital')
                                        checked="checked"
                                    @endif
                                    >
                                    @endif {{$alumno->noexpediente}} {{$alumno->apellidos}} {{$alumno->nombre}}
                            </label>
                        </div>
                        <div class="col-12 col-sm-2">
                            <label>
                                {{$alumno->asignaturas}}
                            </label>
                        </div>
                        <div class="col-12 col-sm-1">
                            <label>
                                @php
                                    $ima=self::revisaimagen($alumno->id);

                                            if ($ima->count() > 0) {
                                                echo 'SI';
                                            }
                                        @endphp
                                    </label>
                                </div>
                                <div class="col-12 col-sm-2">
                                    <label>
                                        {{ $fase }}
                                    </label>
                                </div>
                                {{-- 
                                <div class="col-12 col-sm-2 d-flex justify-content-center align-items-center">
                                    <label>
                                        @if ($fase == 'Digital')
                                        <input type="checkbox" name="chkforzar_{{ $alumno->id }}" value="1">
                                        @endif

                                    </label>
                                </div> --}}
                            </div>
                        @endforeach
                    </div>

                    <div class="col-6 col-sm-12">
                        <button class="btn btn-light btn-sm" onclick="selall();">Seleccionar todo</button>
                        <button class="btn btn-light btn-sm" onclick="deselall();">Invertir selección</button>
                        @if ($flagautoridad == false)
                            <button class="btn btn-primary" type="button"
                                onclick="grupo({{ $flagautoridad }});">Generar Certificado</button>
                        @endif
                        <button class="btn btn-info" wire:click="unificar_planes" onclick="showLoading()">Unificar
                            planes</button>
                        <button class="btn btn-info" onclick="imprimir();">Imprimir</button>
                    </div>
                @endif
                @if ($message != '')
                    <div>
                        <p style="color:red; height:5em; overflow-y: scroll;">{{ $message }}</p>
                        {{-- <label>{{$message}}</label>
                    Render
                    <label>{{$fecrender}}</label>
                    Ini
                    <label>{{$fec}}</label>
                    Cons
                    <label>{{$feci}}</label>
                    <label>{{$fecf}}</label>
                    <label>{{$finicio}}</label>
                    <label>{{$ffinal}}</label> --}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <label class="card-title">Criterios de búsqueda:</label>
            {{-- {{$alumnos->links()}} --}}
        </div>
        <div class="card-body">
            <h5>Filtrar por:</h5>
            <div class="col-6 col-sm-6">
                <label class="form-label">Apellidos:</label>
                <input class="form-control" wire:model.lazy="apellidos">
                <label class="form-label">Expediente:</label>
                <input class="form-control" wire:model.lazy="noexpediente">

                <button class="btn btn-info" onclick="cargando();">Buscar</button>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <label class="card-title"><strong>Alumnos:</strong> {{ $count_alumnos }}</label><br>
            </div>

            <div class="col-6 col-sm-6">
                <label class="form-label">Alumno:</label>
                <select class="form-control" name="id_alumno_change" id="id_alumno_change"
                    wire:model.lazy="id_alumno_change" wire:change="changeEventAlumno($event.target.value)">
                    <option value="0">por alumno</option>
                    @foreach ($alumnos as $alumno)
                        <option value="{{ $alumno->id }}">{{ $alumno->noexpediente }} - {{ $alumno->apellidos }}
                            {{ $alumno->nombre }} </option>
                    @endforeach
                </select>
                <label class="form-label">Estatus: {{ $estatus }}</label>
            </div>

            <div class="col-6 col-sm-6">
                {{-- <button class="btn btn-light btn-sm"
                    onclick="generando(); window.open('{{route('adminalumnos.certificado.reporte',$id_alumno_change)}}','_blank');">Imprimir</button>
                --}}
                @if ($flagautoridad == false)
                    @if ($id_alumno_change != 0 && $estatus == '')
                        <button class="btn btn-primary" type="button" onclick="alumno();">Generar</button>
                    @endif
                @endif
                @if ($message_alumno != '')
                    <div>
                        <p style="color:red; overflow-y: scroll; ">{{ $message_alumno }}</p>
                    </div>
                @endif
            </div>

            <div class="card-body table-responsive table-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Orden</td>
                            <td>Periodo</td>
                            <td>Ciclo</td>
                            <td>Clave</td>
                            <td>Materia</td>
                            <td>Calificacion</td>

                        </tr>
                    </thead>
                    @if (!empty($calificaciones))
                        <tbody>
                            @foreach ($calificaciones as $calif)
                                @if (!empty($calif->orden))
                                    <tr>
                                        <td>{{ $calif->orden }}</td>
                                        <td>{{ $calif->periodo }}</td>
                                        <td>{{ $calif->ciclo }} </td>
                                        <td>{{ $calif->clave }} </td>
                                        <td>{{ $calif->materia }} </td>
                                        <td>{{ $calif->calificacion }} </td>

                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>

        </div>
    </div>
    @section('js_post')
        <script>
            $(document).on('change', '#id_alumno_change', function() {
                cargando(0);

            });

            document.addEventListener('DOMContentLoaded', () => {
                // Iterar sobre todos los checkboxes de "Forzar Generado"
                document.querySelectorAll("input[name^='chkforzar_']").forEach((forzarCheckbox) => {
                    forzarCheckbox.addEventListener('change', (e) => {
                        const alumnoId = forzarCheckbox.name.split('_')[1]; // Obtener el ID del alumno
                        const alumnoCheckbox = document.getElementById(`chkalumno_${alumnoId}`);

                        if (forzarCheckbox.checked) {
                            // Si se marca "Forzar Generado", también marcar "Seleccionar Alumno"
                            alumnoCheckbox.checked = true;
                        } else {
                            // Si se desmarca "Forzar Generado", desmarcar "Seleccionar Alumno"
                            alumnoCheckbox.checked = false;
                        }
                    });
                });
            });

            window.addEventListener('efirmaautoridadeducativa', event => {
                Swal.close();
                Swal.fire({
                    title: 'Configure la Autoridad Educativa con Efirma',
                    icon: "warning",
                    html: 'NO podrá emitir certificados sin Efirma',
                    showConfirmButton: false,
                    timer: 10000,
                    didOpen: () => {
                        Swal.showLoading();

                    }
                });
            })

            window.addEventListener('finish_gen', event => {
                Swal.close();
                var cad = '';
                if (event.detail.results == true) {
                    cad = 'Proceso realizado correctamente.';
                    icon = "success";
                } else {
                    cad = 'NO se generó el proceso completamente.';
                    icon = "info";
                }
                Swal.fire({
                    title: 'Generación de certificados',
                    icon: icon,
                    html: cad,
                    showConfirmButton: false,
                    timer: 10000,
                    didOpen: () => {
                        Swal.showLoading();

                    }
                });
            })

            window.addEventListener('finish_unificacion', event => {
                Swal.close();

                Swal.fire({
                    title: 'Unificación concluida',
                    icon: icon,
                    html: cad,
                    showConfirmButton: false,
                    timer: 10000,
                    didOpen: () => {
                        Swal.showLoading();

                    }
                });
            })

            function cargando(id_alumno) {
                $("input[name='chkalumno']").each(function(index, item) {
                    item.checked = false;
                });
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
                Livewire.emit('genbuscar');
            }

            function showLoading() {
                Swal.fire({
                    title: 'Cargando...',
                    text: 'Por favor, espera.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            function showLoadingAndUnificar() {
                showLoading(); // Muestra el loading
                Livewire.emit('unificar_planes'); // Llama a la función Livewire
            }

            // Escucha el evento despachado por Livewire
            window.addEventListener('finish_unificacion', event => {
                Swal.close(); // Cierra el loading
                Swal.fire('Éxito!', 'Los planes han sido unificados.', 'success');
            });


            function grupo(flagautoridad) {
                if (flagautoridad) {
                    let timerInterval
                    Swal.fire({
                        title: 'Cargando...',
                        html: 'Falta configurar la efirma de la autoridad educativa.',
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
                    });
                }
                var alumnos_sel = '';
                $("input[name='chkalumno']").each(function(index, item) {
                    if (item.checked == true) {
                        if (alumnos_sel != "")
                            alumnos_sel = alumnos_sel + ",";
                        alumnos_sel = alumnos_sel + item.value;


                    }
                });

                var grupo = document.getElementById("id_grupo");
                var valuegrupo = grupo.value;

                //codificar
                var encodedgrupo = btoa(valuegrupo);
                //console.log(encodedgrupo); // Outputs: "SGVsbG8gV29ybGQh"
                //codificar
                var encodedalumnos_sel = btoa(alumnos_sel);
                //console.log(encodedalumnos_sel); // Outputs: "SGVsbG8gV29ybGQh"


                //decodificar atob()

                //alert(url);

                Swal.fire({
                    title: 'Generando certificados...',
                    html: 'Por favor espere.',
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        Livewire.emit('gengrupo', encodedalumnos_sel, encodedgrupo);
                    }
                });

                // Swal.fire({
                //     title: 'Generando certificados...',
                //     html: 'Por favor espere.'
                //     .then((result) => {
                //  //if user clicks on delete
                //         if (result.value) {
                //      // calling destroy method to delete
                //             @this.call('gengrupo',encodedalumnos_sel,encodedgrupo)
                //      // success response
                //             Swal.fire({title: 'Contact deleted successfully!', icon: 'success'});
                //         } else {
                //             Swal.fire({
                //                 title: 'Operation finally!',
                //                 icon: 'success'
                //             });
                //         }
                //     });
                // });

                //     Swal.fire({
                //     title: 'Generando certificados...',
                //     html: 'Por favor espere.',
                //     showConfirmButton: false,
                //     didOpen: () => {
                //       Swal.showLoading()
                //     }

                //   }).then((result) => {
                //     /* Read more about handling dismissals below */
                //     Livewire.emit('gengrupo', encodedalumnos_sel,encodedgrupo);

                //   });
                //event.preventDefault();
            }

            function imprimir() {
                var grupo = document.getElementById("id_grupo");
                var valuegrupo = grupo.value;
                //codificar
                var encodedgrupo = btoa(valuegrupo);

                let url = "{{ route('certificados.revisa.revisalistado', ['grupo_id' => ':grupo_id']) }}";
                url = url.replace(":grupo_id", encodedgrupo);


                Swal.fire({
                    title: 'Generando reporte...',
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

            function alumno() {
                var alumno = document.getElementById("id_alumno_change");
                var valuealumno = alumno.value;
                //codificar
                var encodedalumno = btoa(valuealumno);
                console.log(encodedalumno); // Outputs: "SGVsbG8gV29ybGQh"


                //alert(url);

                Swal.fire({
                    title: 'Generando certificado...',
                    html: 'Por favor espere.',
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        Livewire.emit('genalumno', encodedalumno);
                    }
                });


            }

            function selall() {
                $("input[name='chkalumno']").each(function(index, item) {
                    item.checked = true;
                });
            }

            function deselall() {
                $("input[name='chkalumno']").each(function(index, item) {
                    item.checked = !(item.checked);
                });
            }
        </script>
    @endsection
