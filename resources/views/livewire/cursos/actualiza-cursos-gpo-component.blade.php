<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <div class="card shadow">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Selección de Grupo</p>
        </div>
        <div class="card-body">
            <div class="row">
                @if ($grupos == null)
                    <div class="form-group pb-3" wire:ignore>
                        <label class="form-label">Plantel:</label>
                        <select class="form-control select-planteles" onchange="this.disabled = true;contar();">
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
                    <select class="form-control select-ciclos_esc" onchange="this.disabled = true;contar();">>
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
            @if ($grupos)
                <div class="form-group pb-3 {{ $grupos != null ? '' : ' d-none' }}" wire:ignore>
                    <label class="form-label">Grupos encontrados:
                        {{ $grupos != null ? count($grupos) : '#NA' }}</label>
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
            @endif
            @if ($grupo_seleccionado)
                <div class="form-group pb-3">
                    @if ($gpo_id)
                        <button class="btn btn-info" wire:click="$toggle('bool_mostrar_coment')"
                            @if (auth()->user()->can('cursos-crear') == false) disabled @endif>Agregar curso</button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@if ($bool_mostrar_coment)
    <div class="card shadow">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Datos de Curso: {{$datos_curso!=null? $datos_curso->nombre:''}}</p>
        </div>
        <div class="card-body">
            <div class="row">
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
                <div class="form-group pb-3">
                    <label class="form-label">Plan de estudios:</label>
                    <select class="form-control"  id="nombreCampo" wire:model="plan_est_id" @if($datos_curso) {{$datos_curso->tiene_calificaciones()? ' disabled':''}} @endif>
                        @if ($planes_estudio)
                            <option></option>
                            @foreach ($planes_estudio as $plan_est_select)
                                <option value="{{ $plan_est_select->id }}">{{ $plan_est_select->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group pb-3">
                    <label class="form-label">Asignatura:</label>
                    <select class="form-control" wire:model="asignatura_id" @if($datos_curso) {{$datos_curso->tiene_calificaciones()? ' disabled':''}} @endif>
                        <option></option>
                        @if ($plan_est_asignaturas)
                        
                            @foreach ($plan_est_asignaturas as $pe_asignatura)
                                @php
                                $asigna_p_select = App\Models\Catalogos\AsignaturaModel::find($pe_asignatura->id_asignatura);

                                @endphp
                                @if($asigna_p_select)
                                @if (substr($asigna_p_select->clave, 0, 1) == $grupo_seleccionado->periodo)
                                    <option value="{{ $pe_asignatura->id_asignatura }}">
                                        {{ $asigna_p_select->clave }} -
                                        {{ $asigna_p_select->nombre }}</option>
                                @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group pb-3">
                    <label class="form-label">Docente:</label>
                    <select wire:model="docente_id" class="form-control"
                        @if ($activa_docente = false) disabled @else enabled @endif>
                        <option></option>
                        @if ($activa_docente = true)
                            @if ($docentes_porPlantel)
                                @foreach ($docentes_porPlantel as $docente)
                                    <option value="{{ $docente->id }}">{{ $docente->apellido1 }}
                                        {{ $docente->apellido2 }} {{ $docente->nombre }}</option>
                                @endforeach
                            @endif

                        @endif
                    </select>
                </div>
                <div class="form-group pb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" class="form-control" wire:model="nombre">
                </div>
                <div class="form-group pb-3">
                    <button class="btn btn-primary" wire:click="guardarcurso">Guardar Curso</button>
                </div>
            </div>
        </div>
    </div>
@endif
@if ($grupo_seleccionado)
    @php
        $cursos = $grupo_seleccionado->cursos;
    @endphp
    <div class="card shadow">
        <div class="card-header">
            <p class="text-primary m-0 fw-bold">Cursos: {{ count($cursos) }} - del Grupo:
                {{ $grupo_seleccionado->nombre }} - {{ $grupo_seleccionado->descripcion }}</p>
        </div>
        <!-- Livewire para mostrar modal de horarios -->
        @livewire('cursos.horarios.curso-botton-horario-component')

        <div class="card-body p-0">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 150px">#</th>
                        {{-- <th>Nombre</th> --}}
                        <th>Cve Asign</th>
                        <th>Asignatura</th>
                        <th>Docente</th>
                        <th>Fecha Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($cursos)
                        @foreach ($cursos as $curso)
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td>{{ $curso->id }}</td>
                                            <td>
                                                @can('cursos-editar')
                                                    <button class="btn btn-warning"
                                                        wire:click="editar_curso({{ $curso->id }})">
                                                        <img src="{{ asset('images/editar.png') }}" style="height: 30px" alt="">
                                                        {{-- <i class="fa-solid fa-pen-to-square"></i> --}}
                                                    </button>
                                                @endcan
                                                <button class="btn btn-danger btn-xs"
                                                    onclick="confirmardeletecurso({{ $curso->id }})"
                                                    @if (auth()->user()->can('cursos-borrar') == false) disabled @endif>
                                                    <img src="{{ asset('images/trash_can.png') }}" style="height: 30px" alt="">
                                                </button>

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{{ $curso->asignatura->clave }}</td>
                                <td>{{ $curso->asignatura->nombre }}</td>
                                <td>
                                    @php
                                        $perfil = App\Models\Administracion\PerfilModel::where('id', $curso->docente_id)->get();

                                    @endphp
                                    @if($curso->docente_id != null)
                                    @foreach ($perfil as $perfil)
                                        {{ $perfil->apellido1 }} {{ $perfil->apellido2 }} {{ $perfil->nombre }}
                                    @endforeach
                                    <!-- Boton para mostrar modal de horarios -->
                                @can('horario-ver')    
                                    <br>
                                    @php
                                        $horario = Illuminate\Support\Facades\DB::select("SELECT esc_curso_hora.id FROM esc_curso_hora WHERE esc_curso_hora.curso_id=".$curso->id);
                                    @endphp
                                    <button class="{{$horario!=null? 'btn btn-success btn-sm':'btn btn-info btn-sm'}}" onclick="inicializa_script();"
                                        wire:click="$emitTo('cursos.horarios.curso-botton-horario-component','muestra-modal','{{ $curso->id }}')"
                                    >{{$horario!=null? 'CONSULTAR ':'CREAR '}}Horario</button>
                                    @endif
                                @endcan
                                </td>
                                   

                        <td>{{ $curso->updated_at }}</td>
                        </tr>
                    @endforeach
@endif
</tbody>
</table>
</div>
</div>
@endif
</div>
{{-- SECCION SCRIPTS --}}
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
    });
</script>
<script>
    window.addEventListener('carga_sweet_guardar', event => {
        //alert('Name updated to: ');
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Curso guardado correctamente',
            showConfirmButton: false,
            timer: 10000
        })
    });
</script>
<script>
    function confirmardeletecurso(curso_id) {
        //alert('curso_id'+curso_id);
        Swal.fire({
            title: 'CONFIRMAR',
            text: "Confirme que desea eliminar el curso ID:" + curso_id,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                //location.href="/catalogos/planteles/eliminar/"+plantel_id;
                Livewire.emit('borra_curso', curso_id);
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
            title: 'Curso borrado correctamente',
            showConfirmButton: false,
            timer: 10000
        })
    });
</script>
<script>
    window.addEventListener('carga_sweet_no_borrado', event => {
        //alert('Name updated to: ');
        Swal.fire({
            position: 'top-end',
            icon: 'danger',
            title: 'NO es posible borrar el curso por que tiene calificaciones asociadas',
            showConfirmButton: false,
            timer: 10000
        })
    });
</script>

<script>
    window.addEventListener('carga_sweet_no_editado', event => {
        //alert('Name updated to: ');
        Swal.fire({
            position: 'top-end',
            icon: 'danger',
            title: 'NO es posible modificar el curso por que tiene calificaciones asociadas',
            showConfirmButton: false,
            timer: 10000
        })
    });
</script>
<script>
    var selects = 0;

    function contar() {
        selects++;
        console.log('contador: ' + selects);
        if (selects === 2) {
            console.log('contador: ' + selects + ' es igual a 2 alert');
            alert('Por favor espere a que se realize la busqueda de grupos...');
        } else {
            console.log('contador: ' + selects + ' es dif a 2');
        }
    };
</script>
<script>
    window.addEventListener('carga_sweet_alerta_asign_repetida', event => {
        //alert('Name updated to: ');
        Swal.fire({
            position: 'top-end',
            icon: 'danger',
            title: 'NO es posible crear el curso por que la asignatura ya existe en el grupo',
            showConfirmButton: false,
            timer: 10000
        })
    });
</script>
<script>
    var selects = 0;

    function contar() {
        selects++;
        console.log('contador: ' + selects);
        if (selects === 2) {
            console.log('contador: ' + selects + ' es igual a 2 alert');
           // alert('Por favor espere a que se realize la busqueda de grupos...');
        } else {
            console.log('contador: ' + selects + ' es dif a 2');
        }
    };
</script>
<script>
    document.addEventListener('livewire:load', function () {
        // Escuchar el evento que viene desde Livewire
        window.addEventListener('focusField', event => {
            // Hacer foco en el campo con el ID recibido
            document.getElementById(event.detail.fieldId).focus();
        });
    });
</script>
<script>
        var curso_id_ant = 0;
        var cont = 0;
        var datos  = [];
        var objeto = {};


        function cambia_color(horas_marcadas,horas_semana,curso_id,hora_id,dia,elemento) 
        {
            if(elemento.style.backgroundColor=="green")
            {
                
                if(curso_id_ant == curso_id)
                {
                    //Cuando selecciona una celda verde para eliminarla
                    cont--;
                    console.log('cont: '+ cont);  

                    //datos.pop();
                    let datos_buscar = { 
                            "curso_id"  : curso_id,
                            "hora_id"   : hora_id,
                            "dia"       : dia 
                        };
                    //alert(Object.values(datos_buscar));
                    //alert(Object.values(datos[0]));
                    //alert(datos.length);
                    for(var i = 0; i < datos.length; i++)
                    {
                        if(JSON.stringify(datos[i]) === JSON.stringify(datos_buscar))
                        {
                            //alert(Object.values(datos[i]));
                            delete(datos[i]);
                            break;
                        }
                    }

                    //alert(Object.values(datos_buscar));
                    //index = datos.indexOf(datos_buscar);
                    if(cont == horas_semana)//habilita o deshabilita el guardado
                    {
                        document.getElementById('guardar_script').removeAttribute('disabled');
                    }
                    else {
                        document.getElementById('guardar_script').setAttribute('disabled','true');
                    }
                    //alert('fin');
                }
                else 
                {
                    //reinicia contador cuando es un curso diferente
                    curso_id_ant = curso_id;
                    cont = 0;
                    console.log('cont: '+ cont);    
                }
                elemento.style.backgroundColor ="" ;
                
            }
            else if(elemento.style.backgroundColor =="")
            {
                if(curso_id_ant == curso_id)
                {
                    if(cont == horas_semana)
                    {
                        //mensaje que no puede exceder horas semana
                        //alert('No puede exceder las horas a la semana de la asignatura.');
                        Swal.fire("No puede exceder las horas a la semana de la asignatura!");
                    }
                    else 
                    {
                        cont++;
                        console.log('cont: '+ cont); 
                        elemento.style.backgroundColor ="green" ;

                        datos.push({ 
                            "curso_id"  : curso_id,
                            "hora_id"   : hora_id,
                            "dia"       : dia 
                        });

                        //alert(Object.values(datos));
                        if(cont == horas_semana)//habilita o deshabilita el guardado
                        {
                            document.getElementById('guardar_script').removeAttribute('disabled');
                        }
                        else {
                            document.getElementById('guardar_script').setAttribute('disabled','true');
                        }

                    }
                }
                else 
                {
                    if(horas_semana > 0)
                    {
                        if(horas_marcadas==1)
                        {
                            Swal.fire("No puede exceder las horas a la semana de la asignatura!");
                        }
                        else {
                            curso_id_ant = curso_id;
                            cont = 1;
                            console.log('cont: '+ cont);    
                            elemento.style.backgroundColor ="green" ;
                            
                            datos.push({ 
                                "curso_id"  : curso_id,
                                "hora_id"   : hora_id,
                                "dia"       : dia 
                            });
                        }
                    }
                    else 
                    {
                        Swal.fire("Las horas a la semana de la asignatura debe ser mayor de 0!");
                    }
                }
            }
            //console.log('curso_id: ' + curso_id);
            //console.log('hora_id: ' + hora_id);
            //console.log('dia: ' + dia);
            //console.log('horas_semana: ' + horas_semana);
            objeto.datos = datos;
            console.log(JSON.stringify(objeto));
            //Livewire.emit('guarda_hora', hora_id, dia);
        }

        function inicializa_script()
        {
            curso_id_ant = 0;
            cont = 0;
            datos  = [];
            objeto = {};
        }

        function guardar_script()
        {
            console.log("guardar");
            console.log(JSON.stringify(objeto));
            Livewire.emit('guarda_hora',JSON.stringify(objeto));
        }
        
        function alert_cambio_horas_semana()
        {
            Swal.fire({
              title: "CONFIRMAR",
              text: "Al relaizar un cambio en la cantidad de horas en el curso se eliminara el horario capturado (celdas verdes).",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Confirmo, hacer el cambio"
            }).then((result) => {
              if (result.isConfirmed) {
                Livewire.emitTo('cursos.horarios.curso-botton-horario-component','cambio_horas_semana');
                /*
                Swal.fire({
                  title: "Deleted!",
                  text: "Your file has been deleted.",
                  icon: "success"
                });
                */
              }
            });
        }
</script>
<script>
window.addEventListener('event_limpiar_alert', event => {
    //alert('Name updated to: ' + event.detail.grupo);
    Swal.fire({
      title: "Horario Limpiado",
      text: "Se limpió correctamente el horario para el grupo: "+ event.detail.grupo +" de la asignatura: "+event.detail.asignatura,
      icon: "success",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Crear nuevo horario para el grupo"
    }).then((result) => {
      if (result.isConfirmed) {
        //alert('Hacer aqui un emit al horario del curso');
        inicializa_script();
        Livewire.emitTo('cursos.horarios.curso-botton-horario-component','muestra-modal',event.detail.curso_id);
        /*
        Swal.fire({
          title: "Deleted!",
          text: "Your file has been deleted.",
          icon: "success"
        });
        */
      }
    });
})
</script>
<script>
window.addEventListener('event_guardar_alert', event => {
    //alert('Name updated to: ' + event.detail.grupo);
    Swal.fire({
      position: "top-end",
      icon: "success",
      title: "Se GUARDÓ correctamente el horario para el grupo: "+ event.detail.grupo +" de la asignatura: "+event.detail.asignatura,
      showConfirmButton: false,
      timer: 3000
    });
})
</script>


@endsection
