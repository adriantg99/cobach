{{-- ANA MOLINA 12/07/2024 --}}
<section class="py-4">
    <div class="card">
        <div class="card-header">
            <label class="card-title">Visor de Certificado:</label>
            {{-- {{$alumnos->links()}} --}}
        </div>
        <div class="card-body">
            <h5>Especifica:</h5>
           <div class="col-6 col-sm-6">
            <label class="form-label">Curp:</label>
            <input class="form-control" placeholder="Curp" id="curp" name="curp" wire:model="curp" type="text">
           <label class="form-label">Folio:</label>
                <input class="form-control" placeholder="Folio" id="folio" name="folio" wire:model="folio" type="text">
            </div>
        </div>
        <div class="col-sm-8">
            <button class="btn btn-info" wire:click="buscar();">Validar</button>
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
    @if ($busca)
    <div class="card card-body">
        <div class="row g-3">
            <div class="col-6 col-sm-6">
                <label for="fecha_solicitud" class="form-label">Fecha Solicitud:</label>
                <input class="form-control" placeholder="Fecha de solicitud"  name="fecha_solicitud"   type="date"  wire:model="fecha_solicitud" >
                <label class="form-label">Número de oficio interno:</label>
                <input class="form-control" placeholder="Número de oficio interno" id="oficio" name="oficio" wire:model="oficio" type="text">
                @if (!$genoficio)
                    <label for="alumno" class="form-label">Nombre Alumno:</label>
                    <input class="form-control" placeholder="Nombre Alumno" name="alumno"  type="text"  wire:model="alumno" >
                    <label for="sexo" class="form-label">el joven / la joven</label>
                    <input class="form-control" placeholder="el / la" name="sexo"  id="sexo" type="text"  wire:model="sexo" >
                @endif
                <label for="entidad" class="form-label">Entidad solicitante:</label>
                <input class="form-control" placeholder="Entidad solicitante" name="entidad"  type="text"  wire:model="entidad" >
                <label for="solicitante" class="form-label">Dirigido a:</label>
                <input class="form-control" placeholder="Dirigido a" name="solicitante" type="text" wire:model="solicitante" >
                <label for="puesto" class="form-label">Puesto:</label>
                <input class="form-control" placeholder="Puesto" name="puesto" type="text" wire:model="puesto" >
                <label class="form-label">Oficio:</label>
                <input class="form-control" placeholder="Oficio" name="numoficio" wire:model="numoficio" type="text">
               <label class="form-label">Correo electrónico:</label>
                <input class="form-control" placeholder="Email" name="email" wire:model="email" type="text">
                  @if ($genoficio)
                    <button class="btn btn-info" wire:click="oficio_autenticidad();" >Oficio de autenticidad</button>
                    <button class="btn btn-info" wire:click="enviar_validacion();">Enviar</button>
                @else
                    <button class="btn btn-info" wire:click="oficio_apocrifo();" >Oficio apócrifo</button>
                @endif
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
