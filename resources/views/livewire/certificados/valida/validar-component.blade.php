{{-- ANA MOLINA 02/05/2024 --}}
<section class="py-4">
    <div class="card">
        <div class="card-header">
            <label class="card-title">Validación de Certificado:</label>
            {{-- {{$alumnos->links()}} --}}
        </div>
        <div class="card-body">
            <h5>Especifica:</h5>
           <div class="col-6 col-sm-6">
            <label class="form-label">Curp:</label>
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
            <button class="btn btn-info  "
            onclick="cargando(); location.href='{{route('certificados.valida.oficio')}}';"
            >Oficios</button>
        </div>

        @if ($message!='')
        <div>
            <p  style="color:red; height:5em; overflow-y: scroll;">{{$message}}</p>

        </div>
        @endif
    </div>
    @if ($nombrealumno!='')

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


    </script>

    @endsection
