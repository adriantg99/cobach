{{-- ANA MOLINA 04/09/2024 --}}

<section class="py-4">
    {{-- Be like water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header">
            <label class="card-title"><strong>Tablero</strong></label><br>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ciclo Escolar</label>
                        <select class="form-select" name="ciclo_seleccionado" id='ciclo_seleccionado'
                            wire:model="ciclo_seleccionado">
                            @if ($ciclo)
                                @foreach ($ciclo as $ciclos)
                                    <option value="{{ $ciclos->id }}"
                                        {{-- @unlessrole('control_escolar')
                                @unlessrole('control_escolar_' . $ciclos->nombre) disabled @endunlessrole
                                    @endunlessrole --}}>
                                    {{ $ciclos->nombre }} - {{ $ciclos->per_inicio }}
                                </option>
                            @endforeach
                        @endif

                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Plantel</label>
                    <select class="form-select" name="plantel_seleccionado" id='plantel_seleccionado'
                        wire:model="plantel_seleccionado">
                        <option value="|" selected>Seleccionar plantel</option>
                        @if ($plantel)
                            @foreach ($plantel as $planteles)
                                <option value="{{ $planteles->id }}"
                                    @unlessrole('control_escolar')
                                @unlessrole('control_escolar_' . $planteles->abreviatura) disabled @endunlessrole
                                @endunlessrole>
                                {{ $planteles->nombre }}
                            </option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Semestre</label>
                <select class="form-select" name="periodo_seleccionado" id='periodo_seleccionado'
                    wire:model="periodo_seleccionado">
                    <option value="0" selected>Todos los semestres</option>
                    @foreach ($periodos as $periodo)
                        <option value="{{ $periodo->periodo }}">{{ $periodo->periodo }}</option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Turno</label>
                <select class="form-select" name="turno_seleccionado" id='turno_seleccionado'
                    wire:model="turno_seleccionado">
                    <option value="0" selected>Ambos turnos</option>
                    <option value="1">Matutino</option>
                    <option value="2">Vespertino</option>

                </select>
            </div>
        </div>

        @if (!empty($grupos))
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Grupo</label>
                    <select class="form-select" name="grupo_seleccionar" id='grupo_seleccionar'
                        wire:model="grupo_seleccionar">
                        <option value="0" selected>Todos los grupos</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                        @endforeach

                    </select>
                </div>
            </div>

        @endif
        @if (!empty($cursos))
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Curso</label>
                    <select class="form-select" name="curso_seleccionado" id='curso_seleccionado'
                        wire:model="curso_seleccionado">
                        <option value="0" selected>Todos los cursos</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso->nombre }}">{{ $curso->nombre }}</option>
                        @endforeach

                    </select>
                </div>
            </div>

        @endif
        @if (!empty($docentes))
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Docente</label>
                    <select class="form-select" name="docente_seleccionado" id='docente_seleccionado'
                        wire:model="docente_seleccionado">
                        @if (!$es_docente)
                            <option value="0" selected>Todos los docentes</option>
                        @endif
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->id }}">{{ $docente->apellido1 }}
                                {{ $docente->apellido2 }} {{ $docente->nombre }}</option>
                        @endforeach

                    </select>
                </div>
            </div>

        @endif
    </div>

</div>
</div>

<div class="card shadow" id="dash">
<div class="card-header py-3">
    <p class="text-primary m-0 fw-bold">Variables</p>
</div>
<div class="card-body">

    <table class="table" id="variables_table">
        <tbody>
            @foreach ($variablesSel as $index => $var)
                <tr>
                    <td>
                        @if ($habvar[$index])
                            <select name="variablesSel[{{ $index }}]"
                                wire:model="variablesSel.{{ $index }}" class="form-control"
                                wire:change="changeEvent($event.target.value,{{ $index }})">
                                <option value="">Selecciona una variable</option>
                                @foreach ($rows as $row)
                                    <option value="{{ $row }}">{{ $row }}</option>
                                @endforeach

                            </select>
                        @else
                            {{ $varsel[$index] }}
                        @endif
                    </td>

                    <td>
                        {{-- @can('politica-borrar') --}}
                        <button class="btn btn-warning btn-sm"
                            wire:click.prevent="eliminarVariable({{ $index }});">Eliminar
                            Variable</button>
                        {{-- @endcan --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="error-message"><span style="color:red;">
            @if (session()->has('message'))
                {{ session('message') }}
            @endif
        </span></div>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-sm btn-secondary" wire:click.prevent="agregarVariable">Agregar
                Variable</button>
        </div>
    </div>
    <div class="row">
        @if ($this->carga)
            <div class="col-2">
                <label>
                    <input type="checkbox" name="chk1" wire:change="processMark()" wire:model="chk1">1er
                    parcial
                </label>
            </div>
            <div class="col-2">
                <label>
                    <input type="checkbox" name="chk2" wire:change="processMark()" wire:model="chk2">2do
                    parcial
                </label>
            </div>
            <div class="col-2">
                <label>
                    <input type="checkbox" name="chk3" wire:change="processMark()" wire:model="chk3">3er
                    parcial
                </label>
            </div>
            <div class="col-2">
                <label>
                    <input type="checkbox" name="chkr" wire:change="processMark()"
                        wire:model="chkr">Regularización
                </label>
            </div>
            <div class="col-2">
                <label>
                    <input type="checkbox" name="chkf" wire:change="processMark()"
                        wire:model="chkf">Final
                </label>
            </div>
        @endif
    </div>
    <div class="row mt-3">
        <div class="col-12">
            {{-- <button wire:click="generar_dashboard" wire:loading.attr="disabled"  onclick="cargando();" --}}
            <button wire:loading.attr="disabled"
                onclick="exportar( {{ $ciclo_seleccionado }},'{{ $plantel_seleccionado }}',  {{ $periodo_seleccionado }},   {{ $grupo_seleccionar }},{{ $turno_seleccionado }} ,'{{ $curso_seleccionado }}' ,{{ $docente_seleccionado }},'{{ $cols }}','{{ $chk1 }}','{{ $chk2 }}','{{ $chk3 }}','{{ $chkr }}','{{ $chkf }}');"
                wire:loading.class="bg-secondary" wire:loading.remove
                class="btn btn-primary float-end">Exportar a Excel</button>

            <button wire:loading.attr="disabled" onclick="cargando();" wire:loading.class="bg-secondary"
                wire:loading.remove class="btn btn-primary float-end">Procesar
                datos</button>
            {{-- <span wire:loading wire:target="generar_dashboard">Procesando datos...</span> --}}



        </div>
    </div>

</div>


<div class="card-body">
    <div class="card-header">
        <label class="card-title"><strong>Indicadores</strong></label><br>

    </div>
    <table class="table" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td>
                <div style="width:100%; overflow-y: scroll;">
                    <table cellspacing="0" cellpadding="1" border="1" width="100%">
                        <tr style="color:white;background-color:grey">
                            @php
                                $cols = count($varsel);
                                $desc = 12;
                                if ($chk1 == true) {
                                    $desc = $desc + 12;
                                }
                                if ($chk2 == true) {
                                    $desc = $desc + 12;
                                }
                                if ($chk3 == true) {
                                    $desc = $desc + 12;
                                }
                                if ($chkr == true) {
                                    $desc = $desc + 12;
                                }
                                if ($chkf == true) {
                                    $desc = $desc + 12;
                                }
                                if ($cols == 0) {
                                    $perc = 100 - $desc;
                                } else {
                                    $perc = (100 - $desc) / $cols;
                                }
                                $per = $perc . '%';
                            @endphp
                            @foreach ($varsel as $var)
                                <th rowspan=2>{{ $var }}</th>
                            @endforeach
                            <th rowspan=2 width="4%">Grupos</th>
                            <th rowspan=2 width="4%">Docentes</th>
                            <th rowspan=2 width="4%">Alumnos</th>
                            @if ($this->chk1 == true)
                                <th colspan=5
                                    style="text-align: center;  border-left: gray solid; border-left-style: grove;">
                                    Parcial 1</th>
                            @endif
                            @if ($this->chk2 == true)
                                <th colspan=5
                                    style="text-align: center;  border-left: gray solid; border-left-style: grove;">
                                    Parcial 2</th>
                            @endif
                            @if ($this->chk3 == true)
                                <th colspan=5
                                    style="text-align: center;  border-left: gray solid; border-left-style: grove;">
                                    Parcial 3</th>
                            @endif
                            @if ($this->chkr == true)
                                <th colspan=5
                                    style="text-align: center;  border-left: gray solid; border-left-style: grove;">
                                    Regularización</th>
                            @endif
                            @if ($this->chkf == true)
                                <th colspan=5
                                    style="text-align: center;  border-left: gray solid; border-left-style: grove;">
                                    Final</th>
                            @endif
                        </tr>
                        <tr>
                            @if ($this->chk1 == true)
                                <th width="4%" style="border-left: gray solid; border-left-style: grove;">
                                    Prom
                                </th>
                                <th width="2%">Ap</th>
                                <th width="2%">% Ap</th>
                                <th width="2%">Rep</th>
                                <th width="2%">% Rep</th>
                            @endif
                            @if ($this->chk2 == true)
                                <th width="4%" style="border-left: gray solid; border-left-style: grove;">
                                    Prom
                                </th>
                                <th width="2%">Ap</th>
                                <th width="2%">% Ap</th>
                                <th width="2%">Rep</th>
                                <th width="2%">% Rep</th>
                            @endif
                            @if ($this->chk3 == true)
                                <th width="4%" style="border-left: gray solid; border-left-style: grove;">
                                    Prom
                                </th>
                                <th width="2%">Ap</th>
                                <th width="2%">% Ap</th>
                                <th width="2%">Rep</th>
                                <th width="2%">% Rep</th>
                            @endif
                            @if ($this->chkr == true)
                                <th width="4%" style="border-left: gray solid; border-left-style: grove;">
                                    Prom
                                </th>
                                <th width="2%">Ap</th>
                                <th width="2%">% Ap</th>
                                <th width="2%">Rep</th>
                                <th width="2%">% Rep</th>
                            @endif
                            @if ($this->chkf == true)
                                <th width="4%" style="border-left: gray solid; border-left-style: grove;">
                                    Prom
                                </th>
                                <th width="2%">Ap</th>
                                <th width="2%">% Ap</th>
                                <th width="2%">Rep</th>
                                <th width="2%">% Rep</th>
                            @endif
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="width:100%; height:600px; overflow-y: scroll;">
                    <table cellspacing="0" cellpadding="1" border="1" width="100%">
                        @if (isset($this->dashb) && $this->add == false)
                            @foreach ($dashb as $da)
                                <tr>
                                    @foreach ($varsel as $var)
                                        <td width={{ $per }}>
                                            @php
                                                $valor = '';
                                                if ($var == 'plantel' && isset($da->plantel)) {
                                                    $valor = $da->plantel;
                                                } elseif ($var == 'grupo' && isset($da->grupo)) {
                                                    $valor = $da->grupo;
                                                } elseif ($var == 'grado' && isset($da->periodo)) {
                                                    $valor = $da->periodo;
                                                } elseif ($var == 'turno' && isset($da->turno)) {
                                                    $valor = $da->turno;
                                                } elseif ($var == 'curso' && isset($da->curso)) {
                                                    $valor = $da->curso;
                                                } elseif ($var == 'docente' && isset($da->docente)) {
                                                    $valor = $da->docente;
                                                } elseif ($var == 'alumno' && isset($da->alumno)) {
                                                    $valor = $da->alumno;
                                                } elseif ($var == 'noexpediente' && isset($da->noexpediente)) {
                                                    $valor = $da->noexpediente;
                                                }
                                            @endphp
                                            {{ $valor }}
                                        </td>
                                    @endforeach
                                    <td width="4%">
                                        @if (isset($da->grupos))
                                            {{ $da->grupos }}
                                        @endif
                                    </td>
                                    <td width="4%">
                                        @if (isset($da->docentes))
                                            {{ $da->docentes }}
                                        @endif
                                    </td>
                                    <td width="4%">
                                        @if (isset($da->alumnos))
                                            {{ $da->alumnos }}
                                        @endif
                                    </td>
                                    @if ($this->chk1 == true)
                                        <td width="4%"
                                            style="border-left: gray solid; border-left-style: grove;">
                                            @if (isset($da->p1))
                                                {{ $da->p1 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->aprobado1))
                                                {{ $da->aprobado1 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->pap1))
                                                {{ $da->pap1 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->reprobado1))
                                                {{ $da->reprobado1 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->prep1))
                                                {{ $da->prep1 }}
                                            @endif
                                        </td>
                                    @endif
                                    @if ($this->chk2 == true)
                                        <td width="4%"
                                            style="border-left: gray solid; border-left-style: grove;">
                                            @if (isset($da->p2))
                                                {{ $da->p2 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->aprobado2))
                                                {{ $da->aprobado2 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->pap2))
                                                {{ $da->pap2 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->reprobado2))
                                                {{ $da->reprobado2 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->prep2))
                                                {{ $da->prep2 }}
                                            @endif
                                        </td>
                                    @endif
                                    @if ($this->chk3 == true)
                                        <td width="4%"
                                            style="border-left: gray solid; border-left-style: grove;">
                                            @if (isset($da->p3))
                                                {{ $da->p3 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->aprobado3))
                                                {{ $da->aprobado3 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->pap3))
                                                {{ $da->pap3 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->reprobado3))
                                                {{ $da->reprobado3 }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->prep3))
                                                {{ $da->prep3 }}
                                            @endif
                                        </td>
                                    @endif
                                    @if ($this->chkr == true)
                                        <td width="4%"
                                            style="border-left: gray solid; border-left-style: grove;">
                                            @if (isset($da->r))
                                                {{ $da->r }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->aprobador))
                                                {{ $da->aprobador }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->papr))
                                                {{ $da->papr }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->reprobador))
                                                {{ $da->reprobador }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->prepr))
                                                {{ $da->prepr }}
                                            @endif
                                        </td>
                                    @endif
                                    @if ($this->chkf == true)
                                        <td width="4%"
                                            style="border-left: gray solid; border-left-style: grove;">
                                            @if (isset($da->final))
                                                {{ $da->final }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->aprobado))
                                                {{ $da->aprobado }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->pap))
                                                {{ $da->pap }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->reprobado))
                                                {{ $da->reprobado }}
                                            @endif
                                        </td>
                                        <td width="2%">
                                            @if (isset($da->prep))
                                                {{ $da->prep }}
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

</div>
</div>


</section>
@section('js_post')
<script>
    window.addEventListener('finish_carga', event => {
        Swal.close();

    })

    function cargando() {

        Swal.fire({
            title: 'Cargando...',
            html: 'Por favor espere.',
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                Livewire.emit('generar_dashboard');
            }
        });

    }

    function exportar(ciclo_id, plantel_id, periodo, grupo_id, turno, curso_id, docente_id, vars, chk1, chk2, chk3,
        chkr, chkf) {


        let url =
            "{{ route('estadisticas.tablero.excel', [
                'ciclo_id' => ':ciclo_id',
                'plantel_id' => ':plantel_id',
                'periodo' => ':periodo',
                'grupo_id' => ':grupo_id',
                'turno' => ':turno',
                'curso_id' => ':curso_id',
                'docente_id' => ':docente_id',
                'vars' => ':vars',
                'chk1' => ':chk1',
                'chk2' => ':chk2',
                'chk3' => ':chk3',
                'chkr' => ':chkr',
                'chkf' => ':chkf',
            ]) }}";
        if (chk1 != 1)
            chk1 = 0;
        if (chk2 != 1)
            chk2 = 0;
        if (chk3 != 1)
            chk3 = 0;
        if (chkr != 1)
            chkr = 0;
        if (chkf != 1)
            chkf = 0;
        //codificar
        var encodedciclo_id = btoa(ciclo_id);
        var encodedplantel_id = btoa(plantel_id);
        var encodedperiodo = btoa(periodo);
        var encodedgrupo_id = btoa(grupo_id);
        var encodedcurso_id = btoa(curso_id);
        var encodedturno = btoa(turno);
        var encodeddocente_id = btoa(docente_id);
        var encodedvars = btoa(vars);
        var encodedchk1 = btoa(chk1);
        var encodedchk2 = btoa(chk2);
        var encodedchk3 = btoa(chk3);
        var encodedchkr = btoa(chkr);
        var encodedchkf = btoa(chkf);

        url = url.replace(":ciclo_id", encodedciclo_id);
        url = url.replace(":plantel_id", encodedplantel_id);
        url = url.replace(":periodo", encodedperiodo);
        url = url.replace(":grupo_id", encodedgrupo_id);
        url = url.replace(":curso_id", encodedcurso_id);
        url = url.replace(":turno", encodedturno);
        url = url.replace(":docente_id", encodeddocente_id);
        url = url.replace(":vars", encodedvars);
        url = url.replace(":chk1", encodedchk1);
        url = url.replace(":chk2", encodedchk2);
        url = url.replace(":chk3", encodedchk3);
        url = url.replace(":chkr", encodedchkr);
        url = url.replace(":chkf", encodedchkf);

        //alert(url);
        Swal.fire({
            title: 'Exportando a Excel...',
            html: 'Por favor espere.',

            showConfirmButton: false
        });
        Swal.showLoading();


        $.ajax({
            url: url,
            type: "GET",
            success: function(result) {
                window.open(url);
                Swal.close(); // this is what actually allows the close() to work
                //console.log(result);
            },
        });

    }
</script>
@endsection
