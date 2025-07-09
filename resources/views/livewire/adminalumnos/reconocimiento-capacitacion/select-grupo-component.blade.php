<div>
    {{-- The whole world belongs to you. --}}
    <div class="card shadow">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Selección de Grupo</p>
        </div>
   
        <div class="card-body">
            <div class="row">
                @if ($grupos == null)
                    <div class="form-group pb-3" wire:ignore>
                        <label class="form-label">Plantel:</label>
                        <select class="form-control select-planteles" onchange="this.disabled = true;">
                            <option></option>
                            @if ($planteles)
                                @foreach ($planteles as $plantel_select)
                                    <option value="{{ $plantel_select->id }}"
                                        @unlessrole('control_escolar') @unlessrole('control_escolar_' . $plantel_select->abreviatura) disabled @endunlessrole
                                    @endunlessrole>{{ $plantel_select->id }} - {{ $plantel_select->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group pb-3" wire:ignore>
                    <label class="form-label">Ciclo escolar:</label>
                    <select class="form-control select-ciclos_esc" onchange="this.disabled = true;">>
                        <option></option>
                        @if ($ciclos_esc)
                            @foreach ($ciclos_esc as $ciclo_esc_select)
                                <option value="{{ $ciclo_esc_select->id }}">{{ $ciclo_esc_select->id }} -
                                    {{ $ciclo_esc_select->nombre }} - {{ $ciclo_esc_select->abreviatura }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                <div class="form-group pb-3" wire:ignore>
                    <label class="form-label">Plantel:</label>
                    <input type="text" class="form-control" value="{{ $plantel_id }} - {{ $plantel->nombre }}"
                        disabled>
                </div>
                <div class="form-group pb-3" wire:ignore>
                    <label class="form-label">Ciclo escolar:</label>
                    <input type="text" class="form-control"
                        value="{{ $ciclo_esc->id }} - {{ $ciclo_esc->nombre }} - {{ $ciclo_esc->abreviatura }}"
                        disabled>
                </div>
                <div class="form-group pb-3">
                    <button class="btn btn-primary" wire:click="limpiabusqueda">Limpiar búsqueda</button>
                </div>
            @endif

            {{-- @if ($grupos) --}}
            <div class="form-group pb-3 {{ $grupos != null ? '' : ' d-none' }}" wire:ignore>
                <label class="form-label">Grupos encontrados: {{ $grupos != null ? count($grupos) : '#NA' }}</label>
                <select class="form-control select-grupos">
                    <option></option>
                    @if ($grupos)
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">
                                {{ $grupo->nombre }}{{ $grupo->turno->abreviatura }} - (cursos:
                                {{ $grupo->cursos_count }}) {{-- (alumnos: {{$grupo->alumnos_count}}) --}}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            @if(is_null($gpo_id)==false)

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Alumnos del grupo </h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 400px;">  
                        <button type="submit" class="btn btn-success"  wire:click="descargar"
                          <i class="fa-solid fa-download"></i> Descargar Diplomas
                        </button>
                        <button type="submit" class="btn btn-warning"  wire:click="descargar_sf"
                          <i class="fa-solid fa-download"></i> Descargar Diplomas sin imagen de fondo
                        </button>
                      </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0" >
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>EXP</th>
                                <th>NOMBRE</th>
                                <th>CAP</th>
                                <th>IMP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gpo_alumnos as $alu)
                                <tr>
                                    <td>{{$alu->alumno->noexpediente}}</td>
                                    <td>{{$alu->alumno->apellidos}} {{$alu->alumno->nombre}}</td>
                                    <td>{{$alu->alumno->capacitacion()}}</td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="customCheckbox1" wire:model="alumnos.{{$alu->alumno->id}}">
                          <label for="customCheckbox1" class="custom-control-label">Impr</label>
                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @endif

        </div><!--card-body -->
    </div><!-- card -->


</div>

@section('js_post')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select-planteles').select2();
        $('.select-planteles').on('change', function() {
            @this.set('plantel_id', this.value);
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select-ciclos_esc').select2();
        $('.select-ciclos_esc').on('change', function() {
            @this.set('ciclo_esc_id', this.value);
        });

    });
</script>

<script>
    window.addEventListener('cargar_select2_grupo', event => {
        //alert('Name updated to: ');
        $(document).ready(function() {
            $('.select-grupos').select2();
            $('.select-grupos').on('change', function() {
                @this.set('gpo_id', this.value);
                //@this.@render();
            });

        });
    })
</script>



@endsection