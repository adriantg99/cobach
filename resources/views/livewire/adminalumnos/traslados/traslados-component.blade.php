<div class="col-md-12">
    {{-- Stop trying to control. --}}
@if($file == null)
    <h5>Subir archivo: <br>
    Seleccione el archivo formato Excel (xlsx)</h5>
    <div class="form-control">
        <input type="file" class="custom-file-input" id="customFile" wire:model="file" hidden="true">

        <button type="button" class="btn btn-large btn-info" onclick="document.getElementById('customFile').click();"><i class="fa-sharp fa-solid fa-upload"></i> Subir Archivo</button>
        
        <label class="custom-file-label">Selecciona un archivo</label>
    </div>
          
    <div wire:loading wire:target="file">
        <span style="color:red;">CARGANDO... ESPERE POR FAVOR A QUE APAREZA LA CONFIRMACIÓN</span>
    </div> 
@else
    <h5>Se ha subido el archivo correctamente:</h5>
    <div>
       <button class="btn btn-danger" wire:click="cargar_otro"><i class="fa-solid fa-x fa-lg"></i> Cargar otro archivo </button>
    </div>
    <hr>
    <h3>Alumno:</h3>
    <h4><strong>{{$alumno->nombre}} {{$alumno->apellidos}}</strong></h4>
    <h4>{{$alumno->noexpediente}}<br>{{$alumno->correo_institucional}}</h4>
    <br>
    <div class="form-control">
        <label>Seleccione el plantel a donde quiere ingresar las calificaciones:</label>
        <select class="form-control" wire:model="plantel_id" {{$plantel_id? 'disabled':''}}>
            <option></option>
            @foreach($planteles as $plantel)
                <option value="{{$plantel->id}}">{{$plantel->id}} - {{$plantel->nombre}}</option>
            @endforeach
        </select>
    </div>
    @if($plantel_id)
        <div class="form-control">
            <label>Seleccione el semestre:</label>
            <select class="form-control" wire:model="semestre" {!! $editando_sem? 'disabled':'' !!}>
                <option></option>
                @foreach($semestres as $sem)
                    <option value="{{$sem->semestre}}">{{$sem->semestre}}</option>
                @endforeach
            </select>
        </div>
    @endif
    @if($semestre)
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Calificaciones a ingresar</h3>      
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Clave</th>
                  <th>Asignatura</th>
                  <th>Asignatura</th>
                  <th>Ciclo</th>
                  <th>Ciclo Seleccion</th>
                  <th>Calificacion</th>
                </tr>
              </thead>
              <tbody>
              @foreach($lista as $ls)
                <tr>
                    
                    <td>{{$ls->clave}}</td>
                    <td>{{$ls->asignatura}}</td>
                    <td>
                        @php
                            $as_coincide = null;
                            $as_coincide = App\Models\Catalogos\AsignaturaModel::where('clave',$ls->clave)->first();
                            //dd($as_coincide);
                        @endphp
                        <div class="form-control">
                            <select class="form-control" wire:model="asignatura.{{$ls->id}}">
                                <option>Seleccione una asign...</option>
                                @if($as_coincide)
                                    <option value={{$as_coincide->id}}>{{$as_coincide->id}}: {{$as_coincide->clave}} - {{$as_coincide->nombre}}</option>
                                @endif
                                @foreach($asignaturas as $as)
                                    <option value={{$as->id}}>{{$as->id}}: {{$as->clave}} - {{$as->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>{{$ls->ciclo}}</td>
                    <td>
                        <div class="form-control">
                            <select class="form-control" wire:model="ciclo.{{$ls->id}}">
                                <option>Seleccione un ciclo esc...</option>
                                @foreach($ciclos as $ci)
                                    <option value="{{$ci->id}}">{{$ci->id}}: {{$ci->abreviatura}} - {{$ci->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>{{$ls->calificacion}}{{$ls->calif}}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
            <button class="btn btn-success" wire:click="guardar_seleccion">Guardar Seleccion</button>
            @php
                $cuenta_asign = App\Models\Adminalumnos\Formato_importarModel::get();
                $c_asi =  $c_cic = 0;
                foreach($cuenta_asign as $cu)
                {
                    if($cu->asignatura_id!=null)
                    {
                        $c_asi++;
                    }
                    if($cu->ciclo_esc_id!=null)
                    {
                        $c_cic++;
                    }
                }
            @endphp
            <p>asign:{{$c_asi}} - cic:{{$c_cic}}</p>
            @if(($c_asi == $c_cic) AND ($c_asi == $registros))
                <button class="btn btn-warning" wire:click="importar">importar informacion</button>
            @endif
                
           </div>
        </div>
    @endif

@endif



</div>

@section('js')


@endsection
