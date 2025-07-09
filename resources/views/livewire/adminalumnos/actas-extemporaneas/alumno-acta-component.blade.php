<div class="col-md-12 col-xl-11 text-nowrap">
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
    <table width="100%">
        <tr>
            <td>
                @php
                    //$imagen_find = App\Models\Adminalumnos\ImagenesalumnoModel::where('alumno_id',$alumno['id'])->where('tipo',1)->get();
                @endphp
                {{--
          @if (count($imagen_find))
          <div class="img-container">
            <div class="imageOne image">
                <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_find[0]->imagen)) }}" height="100"  alt="Logo" class="logo">
            </div>
          </div>
          @endif
          --}}
                <h3><strong>{{ $alumno['nombre'] }} {{ $alumno['apellidos'] }}</strong></h3>
                <h4>{{ $alumno['noexpediente'] }}<br>{{ $alumno['correo_institucional'] }}</h4>
                @if (empty($alumno['telefono']))
                    <h3><strong>{{ $alumno['celular'] }}</strong></h3>
                @else
                    <h3><strong>{{ $alumno['telefono'] }}</strong></h3>
                @endif

            </td>
            <td>

            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group-lg">
                    <label class="form-label">Asignaturas Reprobadas del alumno: (seleccione la asignatura en la que se
                        creará el acta)</label>

                    <select class="form-select" wire:model="asignatura_id">
                        <option>Seleccione la asignatura en la que se creará el acta</option>

                        @if ($kardex)
                        @php                            
                            $cuenta_pasantias = 0;
                        @endphp
                            @foreach ($kardex as $cal)
                                @php
                               
                                    if (is_object($cal)) {
                                        $asignatura = App\Models\Catalogos\AsignaturaModel::find($cal->asignatura_id);
                                    } else {
                                        $asignatura = App\Models\Catalogos\AsignaturaModel::find($cal['asignatura_id']);
                                    }

                                    if ($asignatura->periodo > $semestre) {
                                        $semestre = $asignatura->periodo;
                                    }
                                    if (is_object($cal)) {
                                        //dd($cal);
                                        $cal = (array) $cal;
                                    }
                                    $reprobado = false;
                                     if($cal["tipo"] == 3 || $cal["tipo1"] == 3 || $cal["tipo2"] == 3 || $cal["tipo3"] == 3){
                                    $cuenta_pasantias++;
                                }
                                    if (is_null($cal['calificacion']) == false or is_null($cal['calif']) == false) {
                                        if ($cal['calificacion'] >= 60 or $cal['calif'] == 'AC' or $cal['calif'] == 'REV') {
                                            $reprobado = false;
                                        } else {
                                            $reprobado = true;
                                        }
                                    } elseif (
                                        is_null($cal['calificacion3']) == false or
                                        is_null($cal['calif3']) == false
                                    ) {
                                        if ($cal['calificacion3'] >= 60 or $cal['calif3'] == 'AC' or $cal['calif3'] == 'REV') {
                                            $reprobado = false;
                                        } else {
                                            $reprobado = true;
                                        }
                                    } elseif (
                                        is_null($cal['calificacion2']) == false or
                                        is_null($cal['calif2']) == false
                                    ) {
                                        if ($cal['calificacion2'] >= 60 or $cal['calif2'] == 'AC' or $cal['calif2'] == 'REV') {
                                            $reprobado = false;
                                        } else {
                                            $reprobado = true;
                                        }
                                    } elseif (
                                        is_null($cal['calificacion1']) == false or
                                        is_null($cal['calif1']) == false
                                    ) {
                                        if ($cal['calificacion1'] >= 60 or $cal['calif1'] == 'AC' or $cal['calif1'] == 'REV') {
                                            $reprobado = false;
                                        } else {
                                            $reprobado = true;
                                        }
                                    }

                                @endphp

                                @if ($reprobado)
                                    @php
                                        $cuenta_reprobados++;
                                    @endphp
                                    @if (is_object($cal))
                                        <option value="{{ $cal->asignatura_id }}">[{{ $cal->clave }}] -
                                            {{ $cal->materia }}</option>
                                    @else
                                        <option value="{{ $cal['asignatura_id'] }}">[{{ $cal['clave'] }}] -
                                            {{ $cal['materia'] }}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                    </select>
                </div>
            </td>
            @php
                $encuentra_ciclo_activo_sql =
                    "SELECT cat_ciclos_esc.activo
FROM esc_grupo_alumno 
INNER JOIN esc_grupo ON esc_grupo_alumno.grupo_id = esc_grupo.id
INNER JOIN cat_ciclos_esc ON esc_grupo.ciclo_esc_id = cat_ciclos_esc.id
WHERE esc_grupo.descripcion != 'ActasExtemporaneas' AND esc_grupo_alumno.alumno_id = " .
                    $alumno_id .
                    ' AND cat_ciclos_esc.activo = 1';
                $ciclo_activo = Illuminate\Support\Facades\DB::select($encuentra_ciclo_activo_sql);
                if ($ciclo_activo) {
                    $tiene_ciclo_acivo = 1;
                } else {
                    $tiene_ciclo_acivo = 0;
                }
                
                if (/*$tiene_ciclo_acivo == 0 and*/ $semestre == 6 and $cuenta_reprobados <= 2 && $cuenta_pasantias < 2) {
                    $disabled_pasantia = '';
                } else {
                    $disabled_pasantia = 'disabled';
                }
            @endphp
            <td align="right">
                {{ $cuenta_reprobados }}-{{ $semestre }} - {{ $tiene_ciclo_acivo }}</td>
        </tr>
        @if ($asignatura_id)
            <tr>
                <td colspan="2">
                    <hr>
                    <div class="input-group-lg">
                        <label class="form-label">Seleccione el tipo de acta:</label>

                        <select class="form-select" wire:model="tipo_acta">
                            <option>Seleccione el tipo de acta.</option>
                            @if ($disabled_pasantia != 'disabled')
                                <option value="PASANTIA" {{ $disabled_pasantia }}>PASANTIA</option>    
                            @endif
                            
                            <option value="ESTRATEGIA DE RECUPERECIÓN ACADÉMICA">ESTRATEGIA DE RECUPERECIÓN ACADÉMICA
                                (ERAPEL)</option>
                            <option value="ACTA ESPECIAL SERVICIO SOCIAL">ACTA ESPECIAL SERVICIO SOCIAL</option>
                            <option value="ACTA ESPECIAL PRACTICAS PREPROFESIONALES">ACTA ESPECIAL PRACTICAS
                                PREPROFESIONALES</option>
                            <option value="OTRO">OTRO</option>
                        </select>
                        <br>
                        @if ($tipo_acta == 'OTRO')
                            <input type="text" class="form-control" wire:model="otro"
                                placeholder="Especifique el motivo...">
                        @endif
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div wire:ignore>
                        <label class="form-label">Seleccione al docente comisionado:</label><br>
                        <select class="form-control seleccion_docente_acta">
                            <option>Docente comisionado...</option>
                            @foreach ($docentes as $doc)
                                @php
                                    $d = (array) $doc;
                                @endphp
                                <option value="{{ $d['email'] }}">{{ $d['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <label class="form-label">Capture la calificación:</label><br>
                    @if ($numerica)
                        <input type="number" class="form-control" max="100" wire:model="calificacion">
                    @else
                        <select class="form-select" wire:model="calif">
                            <option></option>
                            <option value="AC">AC</option>
                            <option value="NA">NA</option>
                        </select>
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <br><br>
                    <button class="btn btn-primary btn-lg btn-block" onclick="confirmar()">CREAR ACTA</button>
                </td>
            </tr>
        @endif
    </table>
</div>

@section('js_post')
    <script>
        window.addEventListener('select2_docente', event => {
            $('.seleccion_docente_acta').select2();
            $('.seleccion_docente_acta').on('change', function(e) {
                var data = $('.seleccion_docente_acta').select2("val");
                @this.set('email_docente', data);
            });
        })

        function confirmar() {
            Swal.fire({
                title: "CONFIRMACIÓN",
                text: "CONFIRME QUE LOS DATOS CAPTURADOS SON CORRECTOS. ESTA OPERACIÓN NO SE PUEDE REVERTIR",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ES CORRECTO. CREAR EL ACTA"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('crear_acta');
                }
            });
        }
    </script>
@endsection
