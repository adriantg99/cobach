{{-- ANA MOLINA 02/05/2024 --}}
<section class="bg-light app-filters">
    <div class="row g-3">
        <div class="col-6 col-sm-6">
            <label for="fecha_solicitud" class="form-label">Fecha Solicitud:</label>
            <input class="form-control" placeholder="Fecha de solicitud"  name="fecha_solicitud"   type="date"  wire:model="fecha_solicitud"
            @if ($add_alumnos)
                disabled
            @endif>
            <label class="form-label">Número de oficio interno:</label>
            <input class="form-control" placeholder="Número de oficio interno" id="oficio" name="oficio" wire:model="oficio" type="text"
            @if ($add_alumnos)
                disabled
            @endif>
            <label for="entidad" class="form-label">Entidad solicitante:</label>
            <input class="form-control" placeholder="Entidad solicitante" name="entidad"  type="text"  wire:model="entidad"
            @if ($add_alumnos)
                disabled
            @endif>
            <label for="solicitante" class="form-label">Dirigido a:</label>
            <input class="form-control" placeholder="Dirigido a" name="solicitante" type="text" wire:model="solicitante"
            @if ($add_alumnos)
                disabled
            @endif >
            <label for="puesto" class="form-label">Puesto:</label>
            <input class="form-control" placeholder="Puesto" name="puesto" type="text" wire:model="puesto"
            @if ($add_alumnos)
                disabled
            @endif>
            <label class="form-label">Oficio:</label>
            <input class="form-control" placeholder="Oficio" name="numoficio" wire:model="numoficio" type="text"
            @if ($add_alumnos)
                disabled
            @endif>
            <label class="form-label">Correo electrónico:</label>
            <input class="form-control" placeholder="Email" name="email" wire:model="email" type="text"
            @if ($add_alumnos)
                disabled
            @endif>

            <div class="row g-3 mt-3">
                @if (!$add_alumnos)
                    <div class="col-sm-8">
                        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
                        <button class="btn btn-info" wire:click="cerrar">Cerrar</button>
                    </div>
                @endif
                @if ($add_alumnos)
                    <div class="col-sm-8">
                    <button class="btn btn-primary" wire:click="editar">Editar</button>
                    </div>
                @endif


                 <div class="col-sm-8">
              @if ($count_alumnos>0)
                        <button class="btn btn-info"  onclick="oficio_autenticidad('{{$oficio_id}}');">Oficio de autenticidad</button>
              @endif
                @if ($count_alumnosa>0)
                    <button class="btn btn-info" onclick="oficio_apocrifo('{{$oficio_id}}');">Oficio apócrifo</button>
                @endif
                @if ($count_alumnos>0 || $count_alumnosa>0)
                <button class="btn btn-info" wire:click="enviar_validacion();" onclick="enviando()">Enviar</button>
                @endif
                </div>

            </div>
        </div>
    </div>
    @if ($add_alumnos)
        <div class=" card-body">
            <h5>Especifica:</h5>
           <div class="col-6 col-sm-6">
                <label class="form-label">Curp, Nombre o Apellidos:</label>
                <input class="form-control" placeholder="Curp" id="curp" name="curp" wire:model="curp" type="text"
                wire:change="changeParam($event.target.value)">
                <select class="form-control" name="id_alumno_change" id="id_alumno_change"
                    wire:model.lazy="id_alumno_change" wire:change="changeEventAlumno($event.target.value)">
                    @if ($Calumn!=1) <option value="0">por alumno</option> @endif
                    @if (isset($alumn))
                        @foreach($alumn as $getal)
                        <option value="{{$getal->id}}" @if ($Calumn==1) selected @endif >{{$getal->noexpediente}} - {{$getal->apellidos}} {{$getal->nombre}} </option>
                        @endforeach
                    @endif
                </select>
                <label class="form-label">Folio:</label>
                <input class="form-control" placeholder="Folio" id="folio" name="folio" wire:model="folio" type="text">
            </div>
        </div>
        <div class="col-sm-8">
            <button class="btn btn-info" wire:click="buscar();">Validar</button>
            <button class="btn btn-info" wire:click="limpiar">Limpiar</button>
        </div>

        @if ($message!='' && $busca)
            <div>
                <p  style="color:red;  ">{{$message}}</p>
            </div>
        @endif
        @if ($busca)
            @if (!$genoficio)
            <div class="card card-body">
                    <div class="row g-3">
                        <label for="alumno" class="form-label">Nombre Alumno:</label>
                        <input class="form-control" placeholder="Nombre Alumno" name="alumno"  type="text"  wire:model="nombrealumno" >
                        <label for="sexo" class="form-label">el joven / la joven</label>
                        <input class="form-control" placeholder="el / la" name="sexo"  id="sexo" type="text"  wire:model="sexo" >
                    </div>
                </div>
            @endif
            @if ($genoficio)
                <div class="card card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-2">
                            <label for="fecha_certificado" class="form-label">Fecha Certificado:</label>
                            <input class="form-control"
                            name="fecha_certificado"
                            type="text"
                            wire:model="fecha_certificado" readonly>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="plantel" class="form-label">Plantel:</label>
                            <input class="form-control"
                            name="plantel"
                            type="text"
                            wire:model="plantel" readonly>
                        </div>
                        <hr>
                        <div class="col-12 col-sm-6">
                            <label for="nombrealumno" class="form-label">Alumno:</label>
                            <input class="form-control"
                            name="nombrealumno"
                            type="text"
                            wire:model="nombrealumno" readonly>
                            <label for="sexo" class="form-label">el joven / la joven</label>
                            <input class="form-control" placeholder="el / la" name="sexo1" id="sexo1" type="text"  wire:model="sexo" >
                        </div>
                        <hr>
                        <div class="col-12 col-sm-8">
                            <table>
                                <tr >
                                    <td  rowspan="3">
                                            <div class="imageOne image">
                                            <img src="data:image/png;base64,{{ chunk_split(base64_encode($img)) }}" height="200"  alt="foto" class="logo">
                                            </div>
                                    </td>
                                    <td colspan="2">
                                        <label for="digital" class="form-label">Fecha Generación:</label>
                                        <input class="form-control"
                                        name="digital"
                                        type="text"
                                        wire:model="digital" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                            <label for="asignaturas" class="form-label">Asignaturas:</label>
                                            <input class="form-control"
                                            name="asignaturas"
                                            type="text"
                                            wire:model="asignaturas" readonly>
                                    </td>
                                    <td>
                                            <label for="promedio" class="form-label">Promedio:</label>
                                            <input class="form-control"
                                            name="promedio"
                                            type="text"
                                            wire:model="promedio" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                            <label for="estatus" class="form-label">Documento:</label>
                                            <input class="form-control"
                                            name="estatus"
                                            type="text"
                                            wire:model="estatus" readonly>
                                    </td>
                                    <td>
                                            <label for="vigente" class="form-label">Estatus:</label>
                                            <input class="form-control"
                                            name="vigente"
                                            type="text"
                                            wire:model="vigente" readonly>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            @if($agrega)
                <div class="col-sm-8">
                    <button class="btn btn-primary" wire:click="agregara_oficio();">Agregar</button>
                </div>
            @endif
         @endif
    @endif
    @if ($guarda)
        <div class="card">
            <div class="card-header">
                <label class="card-title"><strong>Certificados autenticados:</strong> {{$count_alumnos}}</label><br>
            </div>
            <div class="card-body table-responsive table-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Curp</th>
                            <th>Folio</th>
                            <th>Alumno</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($alumnos as $alumno)
                        <tr>
                            <td>{{$alumno->curp}}</td>
                            <td>{{$alumno->folio}}</td>
                            <td>{{$alumno->nombrealumno}}</td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                onclick="confirmar_borrado('{{$alumno->curp}}','{{$alumno->folio}}','{{$alumno->nombrealumno}}');"
                                >Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <label class="card-title"><strong>Certificados apócrifos:</strong> {{$count_alumnosa}}</label><br>
            </div>
            <div class="card-body table-responsive table-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Curp</th>
                            <th>Folio</th>
                            <th>Alumno</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($alumnosa as $alumnoa)
                        <tr>
                            <td>{{$alumnoa->curp}}</td>
                            <td>{{$alumnoa->folio}}</td>
                            <td>{{$alumnoa->nombrealumno}}</td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    onclick="confirmar_borrado('{{$alumnoa->curp}}','{{$alumnoa->folio}}','{{$alumnoa->nombrealumno}}');"
                                >Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</section>
    @section('js_post')
    <script>
    window.addEventListener('noencuentra', event => {
        Swal.close();
        Swal.fire({
        title: 'Validación de certificados',
        icon: "warning",
        html: 'Certificado NO existe.',
        showConfirmButton: false,
        timer: 10000,
        didOpen: () => {
          Swal.showLoading();

           }
        });
    })
    window.addEventListener('finish_borrar', event => {
        Swal.close();
    })
    window.addEventListener('finish_email', event => {
        Swal.close();
        Swal.fire({
        title: 'Envio de oficio',
        icon: "success",
        html: 'Correo enviado correctamente.',
        showConfirmButton: false,
        timer: 10000,
        didOpen: () => {
          Swal.showLoading();

           }
        });
    })
    function confirmar_borrado(curp,folio,alumno)
    {
        Swal.fire({
          title: 'CONFIRMAR',
          text: "Confirme que desea eliminar el alumno:"+alumno,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Si, borrarlo'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
            title: 'Borrando...',
            html: 'Por favor espere.',
            showConfirmButton: false,
            didOpen: () => {
            Swal.showLoading();
            Livewire.emit('borrarde_oficio', curp,folio);
            }
            });
          }
        });

    }
    function cargando()
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
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer')
            }
        })

    }
    function oficio_autenticidad(oficio)
    {

        var encodedoficio = btoa(oficio);

        let url="{{route('certificados.valida.oficioaut',['oficio_id'=>":oficio"])}}";

        url = url.replace(":oficio", encodedoficio);

         Swal.fire({
        title: 'Generando oficio de autenticidad...',
        html: 'Por favor espere.',

            showConfirmButton: false
        });
        Swal.showLoading();


        $.ajax({
        url: url,
        type: "GET",
        success: function (result) {
        window.open(url, "_blank");
        Swal.close(); // this is what actually allows the close() to work
        //console.log(result);
               },
        });

    }
    function oficio_apocrifo(oficio)
    {

        //codificar
       var encodedoficio = btoa(oficio);
        let url="{{route('certificados.valida.oficioapo',['oficio_id'=>":oficio"])}}";

        url = url.replace(":oficio", encodedoficio);

        Swal.fire({
        title: 'Generando oficio apócrifo...',
        html: 'Por favor espere.',

            showConfirmButton: false
        });
        Swal.showLoading();


        $.ajax({
        url: url,
        type: "GET",
        success: function (result) {
        window.open(url, "_blank");
        Swal.close(); // this is what actually allows the close() to work
        //console.log(result);
               },
        });

    }
    function enviando()
    {
        Swal.fire({
        title: 'Enviando oficio...',
        html: 'Por favor espere.',

            showConfirmButton: false
        });
        Swal.showLoading();
    }

    </script>

    @endsection
