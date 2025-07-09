<div>
    <section class="py-4">
        <div class="col-sm-8">
            @can('grupos-crear')
                <button class="btn btn-success btn-sm"
                    onclick="cargando(); window.location='{{ route('grupos.crear') }}';">Agregar Grupos</button>
            @endcan
            <!--<button class="btn btn-light btn-sm" onclick="generando(); window.location='';">Reporte</button>-->
        </div>

    </section>
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
                                    <select class="form-select" 
                                        name="select_plantel" id="select_plantel" 
                                        wire:model="select_plantel">
                                        <option value="" selected>Seleccionar plantel</option>
                                        @foreach ($Plantel as $Planteles)
                                            <option value="{{ $Planteles->id }}" @unlessrole('control_escolar') @unlessrole('control_escolar_'.$Planteles->abreviatura) disabled @endunlessrole @endunlessrole>
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
    <!--<input class="form-control" placeholder="0" name="cantidad_grupos_extra" type="text" id="cantidad_grupos_extra" wire:model="cantidad_grupos_extra">-->
    @if (!empty($datos_grupos))
        <div class="card">
            <div class="card-header">
                <label class="card-title"><strong>Grupos por plantel</strong></label>
            </div>
            <div class="card-body table-responsive table-sm">
                <table class="table dataTable" id="dataTable">
                    <thead>
                        <tr>
                            <th>Turno</th>
                            <th>Plantel</th>
                            <th>Grupo-Turno</th>
                            <th>Periodo</th>
                            <th>GpoBase</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($datos_grupos))
                            @foreach ($datos_grupos as $datos_db)
                                <tr>
                                    <td>@if ($datos_db->turno_id == 1)
                                        Matutino
                                    @else
                                        Vespertino
                                    @endif
                                    </td>
                                    <td>{{ $datos_db->nombre_plantel }}</td>
                                    @php
                                        $turno = App\Models\Escolares\TurnoModel::find($datos_db->turno_id);
                                    @endphp
                                    <td>{{ $datos_db->descripcion }}@if($turno){{$turno->abreviatura}}@endif</td>
                                    <td>{{ $datos_db->periodo }}</td>
                                    <td>@if($datos_db->gpo_base) 1 @endif</td>
                                    <td>
                                        @can('grupos-editar')
                                            <button id="{{ $datos_db->id_grupo }}" value="{{ $datos_db->id_grupo }}"
                                                class="btn btn-info btn-sm"
                                                onclick="cargando(); location.href='{{ route('grupos.update', $datos_db->id_grupo) }}';">Editar</button>
                                        @endcan
                                        @can('grupos-borrar')
                                            <button class="btn btn-warning btn-sm"
                                                onclick="confirmar_borrado({{ $datos_db->id_grupo }});">Eliminar</button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <div class="mb-4"></div> <!-- Elemento de separación con margen inferior -->
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
</div>
@section('js_post')
    <script>
        let table = new DataTable('#dataTable');
        function confirmar_borrado(grupo_id) {
            Swal.fire({
                title: 'CONFIRMAR',
                text: "Confirme que desea eliminar el Grupo con el ID:" + grupo_id,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, borrarlo'
            }).then((result) => {
                if (result.isConfirmed) {
                    //Livewire.emit('borra_curso',grupo_id);
                    location.href = "/grupos/eliminar/" + grupo_id;
                }
            })
        }

        function cargando(grupo_id) {

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
        window.addEventListener('carga_sweet_borrar', event => {
            //alert('Name updated to: ');
            Swal.fire({
                    position: 'top-end',
                    icon: 'danger',
                    title: 'Grupo eliminado correctamente',
                    showConfirmButton: false,
                    timer: 10000
                })
    
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function(){
            $('.select2').select2();
            $('.select2').on('change', function(){
                @this.set('ciclos_escolares', this.value);
            });
        })
    </script>

    
</div>
@endsection