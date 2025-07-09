<div class="card shadow" id="principal">
    <div class="card-header py-3">
        <p class="text-primary m-0 fw-bold">Seleccione el alumno: </p>
    </div>
    <div class="card-body">
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
        <div class="row" wire:ignore>
            <div class="col-md-6 col-xl-11 text-nowrap">
                <table>
                    <tr>
                        <td>
                            <label class="form-label">Alumnos ({{count($alumnos_vista)}}):</label>
                        </td>
                        <td>
                            <div wire:ignore> 
                            <select class="form-select select2_alumnos">
                                <option>Seleccione al alumno que desea consultar</option>

                                @foreach($alumnos_vista as $alu)
                                    @if(is_object($alu))
                                        <option value="{{$alu->alumno_id}}">{{$alu->noexpediente}} - {{$alu->apellidos}} {{$alu->nombre}}</option>
                                    @else
                                        <option value="{{$alu['alumno_id']}}">{{$alu['noexpediente']}} - {{$alu['apellidos']}} {{$alu['nombre']}}</option>
                                    @endif
                                @endforeach
                            </select>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
        <hr>
        <div wire:loading>
            <h4><span style="color: red;">Buscando Alumno por favor espere...</span></h4>
        </div>
        
        @if($alumno_id)
        <div class="row">
            <br>
            
<table>
  <tr>
    <td>
      @php
        $imagen_find = App\Models\Adminalumnos\ImagenesalumnoModel::where('alumno_id',$alumno['id'])->where('tipo',1)->get();
      @endphp
      @if(count($imagen_find))
      <div class="img-container">
        <div class="imageOne image">
            <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_find[0]->imagen)) }}" height="100"  alt="Logo" class="logo">
            </div>
        </div>
      @endif
    </td>
    <td>
      <h3><strong>{{$alumno['nombre']}} {{$alumno['apellidos']}}</strong></h3>
      <h4>{{$alumno['noexpediente']}}<br>{{$alumno['correo_institucional']}}</h4>
      @if (empty($alumno['telefono']))
      <h3><strong>{{ $alumno['celular'] }}</strong></h3>  
      @else
      <h3><strong>{{ $alumno['telefono'] }}</strong></h3>
      @endif
    </td>
  </tr>
</table>
        </div>
        @endif
        <br>
        <hr>
        @if($alumno_id)
        <div class="row">
            <div class="input-group-lg">
                <label class="form-label">Asignaturas Reprobadas del alumno: (seleccione la asignatura que el alumno desea recursar)</label>
                <select class="form-select" wire:model="asignatura_clave">
                    <option>Seleccione la asignatura que el alumno desea recursar</option>
                    @if($kardex)
                    @foreach($kardex as $cal)
                        @php
                            
                                $reprobado = false;
                                if(( is_null($cal->calificacion) == false) OR ( is_null($cal->calif)== false))
                                {
                                    if(($cal->calificacion>=60) OR ($cal->calif=="AC"))
                                    {
                                        $reprobado = false; 
                                    } else { $reprobado = true; }
                                }
                                elseif(( is_null($cal->calificacion3) == false) OR ( is_null($cal->calif3)== false))
                                {
                                    if(($cal->calificacion3>=60) OR ($cal->calif3=="AC"))
                                    {
                                        $reprobado = false; 
                                    } else { $reprobado = true; }
                                }
                                elseif(( is_null($cal->calificacion2) == false) OR ( is_null($cal->calif2)== false))
                                {
                                    if(($cal->calificacion2>=60) OR ($cal->calif2=="AC"))
                                    {
                                        $reprobado = false; 
                                    } else { $reprobado = true; }
                                }
                                elseif(( is_null($cal->calificacion1) == false) OR ( is_null($cal->calif1)== false))
                                {
                                    if(($cal->calificacion1>=60) OR ($cal->calif1=="AC"))
                                    {
                                        $reprobado = false; 
                                    } else { $reprobado = true; }
                                }
                            
                        @endphp

                        @if($reprobado)
                            @if(is_object($cal))                            
                                <option value="{{$cal->clave}}">[{{$cal->clave}}] - {{$cal->materia}}</option>
                            @else
                                <option value="{{$cal['clave']}}">[{{$cal['clave']}}] - {{$cal['materia']}}</option>
                            @endif
                        @endif
                    @endforeach
                    @endif
                </select>
            </div>
            <hr>
            @if($estatus_asignatura)
            <div class="row">
                <h4><strong>El alunmo ya se encuentra cursando la asignatura en el grupo: {{$estatus_asignatura[0]->nombre}}</strong></h4>
            </div>
            @else
            <div class="row">
                <div class="input-group-lg">
                <label class="form-label">Seleccione el grupo donde el alumno recursará la asignatura:</label>
                @if($cursos_del_plantel)
                    <select class="form-select" wire:model="curso_plantel_id">
                        <option>Seleccione el grupo donde el alumno recursará la asignatura</option>   
                        @foreach($cursos_del_plantel as $cdp)
                            @php
                                $cso = App\Models\Cursos\CursosModel::find($cdp->curso_id);
                            @endphp
                            <option value="{{$cdp->curso_id}}">{{$cdp->nombre}} - {{$cdp->descripcion}} - {{$cdp->turno}} ({{$cso->cantidad_alumnos()}})</option>
                        @endforeach 
                    </select>
                @else
                    @if($asignatura_clave!=null)
                    <h4><strong>El plantel no se encuentra impartiendo la asignatura: {{$asignatura_clave}}</strong></h4>
                    @endif
                @endif
                </div>
            </div>
                @if($curso_plantel_id)
                <br><hr>
                <button class="btn btn-warning" wire:click="ingresa_alumno">Ingresar al alumno al Curso</button>
                @endif
            @endif

        </div>
        @endif
    </div>
</div>

@section('js_post')
<script>
        window.addEventListener('carga_sel2', event => {
            $('.select2_alumnos').select2();
            $('.select2_alumnos').on('select2:select', function(e) {
                var data = e.params.data;
                
                @this.set('alumno_id',data.id);
            });
        });
            
        $(document).ready(function() {
            $('.select2_alumnos').select2();

            $('.select2_alumnos').on('select2:select', function(e) {
                var data = e.params.data;
                
                @this.set('alumno_id',data.id);
            });
        });
        
        window.addEventListener('name-updated', event => {
            Swal.fire({
  position: "top-end",
  icon: "success",
  title: "Se ingresó correctamente al alumno.",
  showConfirmButton: false,
  timer: 1500
});
        });

</script>
@endsection