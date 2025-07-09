<div>
    {{-- Success is as dangerous as failure. --}}


    <div class="card shadow" id="principal" wire:ignore>
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Configuración de Grupos</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-xl-11 text-nowrap">
                    <table>
                        <tr>
                            <td><label class="form-label">Ciclo</label></td>
                            <td style="width: 100%">
                                <select class="form-select picker select2"
                                    style="display: inline-block; margin-left: 10px" name="select_ciclo_"
                                    id='select_ciclo_' wire:model="ciclos_escolares">
                                    <option value="" selected>Seleccionar ciclo escolar</option>
                                    @foreach ($Ciclos as $select_ciclos_escolares)
                                        <option value="{{ $select_ciclos_escolares->id }}">
                                            {{ $select_ciclos_escolares->nombre }}
                                            {{ $select_ciclos_escolares->abreviatura }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">Plantel</label>
                            </td>
                            <td>
                                <section class="py-3">
                                    <select class="form-select" name="select_plantel" id="select_plantel"
                                        wire:model="select_plantel">
                                        <option value="" selected>Seleccionar plantel</option>
                                        @foreach ($plantel as $Planteles)
                                            <option value="{{ $Planteles->id }}"
                                                @unlessrole('control_escolar') @unlessrole('control_escolar_' . $Planteles->abreviatura) disabled @endunlessrole
                                            @endunlessrole>
                                            {{ $Planteles->nombre }}</option>
                                    @endforeach
                                </select>
                            </section>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <section class="py-3">
            <button wire:click="realizarBusqueda" class="btn btn-primary btn-success float-end">Realizar
                Búsqueda</button>
        </section>
    </div>
</div>

@if ($actas->count() > 0)
    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Actas especiales por plantel</strong></label>
        </div>

        <div class="card-body table-responsive table-sm">
            <table class="table dataTable" id="dataTable" wire:ignore>
                <thead>
                    <tr>
                        <th>
                            Folio
                        </th>
                        <th>
                            Nombre docente
                        </th>
                        <th>
                            Alumno
                        </th>
                        <th>
                            Grupo
                        </th>
                        <th>
                            Asignatura
                        </th>
                        <th>
                            Parcial
                        </th>
                        <th>
                            Calificacion Anterior
                        </th>
                        <th>
                            Calificación Nueva
                        </th>
                        <th>
                            Faltas anterior
                        </th>
                        <th>
                            Faltas nuevas
                        </th>
                        <th>
                            Motivo
                        </th>
                        <th>
                            Estado
                        </th>
                    </tr>
                </thead>
                @foreach ($actas as $acta)
                    <tr id="fila{{ $acta->id }}"
                        style="background-color: 
                    @if ($acta->estado == 1) #fbfb5f;
                    @elseif($acta->estado == 2) #0bd70b;
                    @elseif($acta->estado == 3) #f11919; @endif
                ">
                        <td>
                            {{ $acta->id }}
                        </td>
                        <td>
                            {{ $acta->docente }}
                        </td>
                        <td>
                            {{ $acta->alumno }}
                        </td>
                        <td>
                            {{ $acta->grupo }}
                        </td>
                        <td>
                            {{ $acta->asignatura }}
                        </td>
                        <td>
                            {{ $acta->parcial_nombre }}
                        </td>
                        <td>
                            @if ($acta->calificacion_anterior == null)
                            {{ $acta->calificacion }}    
                            @else
                            {{ $acta->calificacion_anterior }}    
                            @endif
                            
                        </td>
                        <td>
                            {{ $acta->nueva_calif }}
                        </td>
                        <td>
                            {{ $acta->faltas }}
                        </td>
                        <td>
                            {{ $acta->nueva_falta }}
                        </td>
                        <td>
                            {{ $acta->motivo }}
                        </td>
                        <td>
                            
                            @if ($acta->estado == 2)
                                <button class="btn btn-primary"
                                    onclick="cargando(); window.open('{{ route('cursos.generar_acta', $acta->id) }}', '_blank')">
                                    Generar documento
                                </button>
                            @else                                
                                @hasallroles('control_escolar_'.$plantel_abrev->abreviatura)
                                    <select class="form-control" name="estado" id="estado_{{ $acta->id }}"
                                        onchange="guardar_cambio({{ $acta->id }})">
                                        <option value="1" {{ $acta->estado == 1 ? 'selected' : '' }}>En revisión
                                        </option>
                                        <option value="2" {{ $acta->estado == 2 ? 'selected' : '' }}>Aceptado
                                        </option>
                                        <option value="3" {{ $acta->estado == 3 ? 'selected' : '' }}>Rechazado
                                        </option>
                                    </select>
                                @endhasallroles
                                <button class="btn btn-primary" id="generar_doc{{ $acta->id }}"
                                    onclick="cargando(); window.open('{{ route('cursos.generar_acta', $acta->id) }}', '_blank')"
                                    hidden>
                                    Generar documento
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>
@endif
<script>
    document.addEventListener('livewire:load', function() {
        $('.select2').select2();
        $('.select2').on('change', function() {
            @this.set('ciclos_escolares', this.value);
        });
    })
</script>
<script>
    function guardar_cambio(id_cambio) {
        var cambio_color = document.getElementById("fila" + id_cambio);
        var cambio = document.getElementById("estado_" + id_cambio);
        console.log(cambio_color);

        // Emitir el evento de Livewire y esperar la respuesta
        @this.emit('cambioacta', id_cambio, cambio.value)

        Livewire.on('cambio_acta_completo', (estado, id_cambio) => {
            if (estado == 2) {
                if (cambio) {
                    cambio.style.display = 'none';
                    document.getElementById("fila" + id_cambio).style.backgroundColor = "#0bd70b";
                    document.getElementById("generar_doc" + id_cambio).removeAttribute("hidden");
                }
            }
            if (estado == 3) {
                document.getElementById("fila" + id_cambio).style.backgroundColor = "#f11919";
                console.log(cambio_color);
            }
            if (estado == 1) {
                document.getElementById("fila" + id_cambio).style.backgroundColor = "#fbfb5f";
            }
            cambio_color = "";
            cambio = "";
        });

    }
</script>
<script>
    function cargando() {

        let timerInterval
        Swal.fire({
            title: 'Cargando Documento',
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
</script>


</div>
