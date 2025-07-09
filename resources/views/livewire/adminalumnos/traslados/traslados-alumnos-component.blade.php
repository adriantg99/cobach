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
            <p class="text-primary m-0 fw-bold">CARGAR - EDITAR HISTORIAL DE CALIFICACIONES DEL ALUMNO:</p>
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
                                @if($alumno_id == null)
                                <section class="py-3">
                                    <select class="form-control select2BuscaAlumn" autocomplete="off">
                                        <option value=""></option>
                                    </select>
                                </section>
                                @else
                                    <p>
                                        <br>Alumno EXP: <strong>{{$alumno->noexpediente}}</strong>
                                        <br>Alumno Nombre: <strong>{{$alumno->apellidos}} {{$alumno->nombre}}</strong>
                                    </p>
                                @endif
                            </td>
                        </tr>
                    </table>                        
                </div>

                <div wire:loading>
                    @if($alumno == null)
                            <h4><span style="color: red;">Buscando Alumno por favor espere...</span></h4>
                    @endif
                </div>
                @if($alumno_id != null)
                    <div class="form-control">
                        <label>Seleccione el plantel a donde quiere ingresar las calificaciones:</label>
                        <select class="form-control" wire:model="plantel_id" {{$plantel_id? 'disabled':''}}>
                            <option></option>
                            @foreach($planteles as $plantel)
                                <option value="{{$plantel->id}}">{{$plantel->id}} - {{$plantel->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12" >
                        @if($mostrar_selector_de_asignaturas == false)
                            <button class="btn btn-warning" wire:click="$toggle('agregar_ciclo_esc')">Agregar Ciclo Escolar</button>
                        @endif
                    
                        @if($agregar_ciclo_esc)
                            <div class="form-control" wire:ignore>
                            <label class="form-label">Seleccione el ciclo escolar:</label>
                            <select class="form-control select2_ciclos_esc" autocomplete="off">
                                @foreach($ciclos_esc as $ce)
                                <option><option value="{{$ce->id}}">{{$ce->id}} - ({{$ce->abreviatura}}) - {{$ce->nombre}}</option></option>
                                @endforeach
                            </select>
                            </div>
                        @endif
                    </div>
                @endif
                @if($ciclo_esc_alumn)
                <div class="col-md-12">
                    <label class="form-label">Ciclos Escolares donde el alumno tiene calificaciones capturadas:</label>
                    <select class="form-control">
                        <option disabled>Seleccione el ciclo escolar que desee consultar</option>
                        @foreach($ciclo_esc_alumn as $ciclo_esc_alu)
                            <option value="{{$ciclo_esc_alu->ciclo_esc_id}}">{{$ciclo_esc_alu->ciclo_esc_id}} - ({{$ciclo_esc_alu->abreviatura}}) - {{$ciclo_esc_alu->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-success">Consultar</button>
                </div>
                @endif
            </div>
            
            @if($mostrar_selector_de_asignaturas)
                <div class="row">
                    @php
                    $ciclo_esc_mostrar = App\Models\Catalogos\CicloEscModel::find($ciclo_esc);
                    @endphp
                    <p>Ciclo Escolar Seleccionado: <strong>{{$ciclo_esc}} - ({{$ciclo_esc_mostrar->abreviatura}}) - {{$ciclo_esc_mostrar->nombre}}</strong></p><hr>
                    <div class="input-group">
                        <label class="form-label">Selecciona el Plan de Estudios:</label>
                        <select class=*"form-control" wire:model="plan_estudio_id">
                            <option></option>
                            @foreach($plantes_estudio as $pe)
                                <option value={{$pe->id}}>{{$pe->id}} - {{$pe->nombre}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                @if($plan_estudio_id)
                <br>
                <div class="row" >
                    <!--<p>Seleccione la asignatura:</p><br> -->
                    @php
                        if($asignatura_id)
                        {
                            $asignatura = App\Models\Catalogos\AsignaturaModel::find($asignatura_id);
                        }
                    @endphp
                    <div class="input-group">
                        <label class="form-label">Seleccione la asignatura: @if($asignatura_id) <strong>{{$asignatura->clave}} - {{$asignatura->nombre}}</strong> @endif </label>
                    @if($plan_est_asignaturas)
                    <select class="form-control select2_asignaturas" autocomplete="off">
                        <option></option>
                        @foreach($plan_est_asignaturas as $pea)
                            <option value="{{$pea->asignatura->id}}">{{$pea->asignatura->clave}} - {{$pea->asignatura->nombre}}</option>
                        @endforeach
                    </select>
                    @endif
                    </div>
                </div>
                @endif
                @if($asignatura_id)
                <br>
                <div class="row">
                    <label class="form-label">Indique la calificación:</label>
                    <div class="col-3">
                    Numerica: <input type="number" class="form-control" wire:model="calificacion">
                    Alfabética: <select class="form-control" wire:model="calif">
                        <option></option>
                        <option value="AC">AC</option>
                        <option value="NA">NA</option>
                        <option value="REV">REV</option>
                    </select>
                    </div>
                </div>
                <div class="input-group">
                    <br>
                    <button class="btn btn-primary" wire:click="guardar_calificacion">Guardar Calificación</button>
                </div>
                @endif
            @endif



        </div><!--card body --> 
    </div><!-- Card shadow  -->

    
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
                 
                  <th>Ciclo</th>
                  
                  <th>Calificacion</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
            @if($lista)
              @foreach($lista as $ls)
                <tr>
                    
                    <td>{{$ls->clave}}</td>
                    <td>{{$ls->asignatura}}</td>
                    
                    <td>{{$ls->ciclo}}</td>
                    
                    <td>{{$ls->calificacion}}{{$ls->calif}}</td>
                    <td><button class="btn btn-danger" onclick="borrar_asign({{$ls->id}})"><i class="fa-solid fa-delete-left"></i></button></td>
                </tr>
              @endforeach
            @endif
              </tbody>
            </table>
            @if($lista)
            @if(count($lista)>0)
                <button class="btn btn-warning" wire:click="ingresar_alumno">Ingresar calificaciones a sistema</button>
            @endif
            @endif
           </div>
        </div>
    


</div>

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
                @this.set('alumno_id',data.id);
                //window.location = "/alumno/" + data.id + "/datos/";
            });

            //Swal.fire('Hello world!');

        });
    </script>
    <script>
    function cargando(plantel_id)
    {

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
        /* Read more abou   t handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log('I was closed by the timer')
        }
      })

    }
    </script>
    <script>
        function borrar_asign(formato_id)
        {
            
            Swal.fire({
              title: 'CONFIRMAR',
              text: "Confirme que desea eliminar la asignatura ID:"+formato_id,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Si, borrarlo'
            }).then((result) => {
              if (result.isConfirmed) {
                //location.href="/catalogos/planteles/eliminar/"+plantel_id;
                Livewire.emit('emitBorraAsi',formato_id)
              }
            })


        }
    </script>
    <script>
    window.addEventListener('activa_select2_ciclos', event => {
        $('.select2_ciclos_esc').select2();

        $('.select2_ciclos_esc').on('select2:select', function(e) {
                var data = e.params.data;
                //alert(data.id);
                @this.set('ciclo_esc',data.id);
                //window.location = "/alumno/" + data.id + "/datos/";
        });
    })
    </script>
    <script>
        
    window.addEventListener('activa_select2_asignaturas', event => {
        $('.select2_asignaturas').select2();

        $('.select2_asignaturas').on('select2:select', function(e) {
                var data = e.params.data;
                //alert(data.id);
                @this.set('asignatura_id',data.id);
                //window.location = "/alumno/" + data.id + "/datos/";
        });
    })
    
    </script>
@endsection