<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

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
            <p class="text-primary m-0 fw-bold">Buscar alumno para cambio de ciclo</p>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-xl-11 text-nowrap">
                        <table>
                            <tr>
                                <td>
                                    <label for="form-label">Alumno:</label>
                                </td>
                                <td style="width: 100%">
                                    @if ($alumno_id == null)
                                        <section class="py-3">
                                            <select class="form-control select2BuscaAlumn" autocomplete="off">
                                                <option value=""></option>
                                            </select>
                                        </section>
                                    @else
                                        <p>
                                            <br>Alumno EXP: <strong>{{ $alumno->noexpediente }}</strong>
                                            <br>Alumno Nombre: <strong>{{ $alumno->apellidos }}
                                                {{ $alumno->nombre }}</strong>
                                        </p>
                                    @endif
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if ($alumno_id)
        @if ($alumno_id)
        <div class="row" style="padding-top: 5%">
            <div class="col-md-6 mb-3">
                <p>Semestre a reemplazar del certificado</p>

                <select  class="form-select" name="semestre" id="semestre" wire:model = "semestre">
                    <option value="0" selected>Seleccionar semestre</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <p>Ciclos cursados del alumno</p>
            <select  class="form-select" name="ciclos" id="ciclos" wire:model="ciclo_seleccionado">
                <option value="0">Seleccionar semestre</option>
                @foreach ($ciclos_alumno as $ciclo)
                <option value={{ $ciclo->ciclo_esc_id }}>{{ $ciclo->nombre }}</option>
            @endforeach

            </select>
        </div>
        @endif
        <div class="card shadow">
            
        </div>
    @endif


    @if ($alumno_id && $semestre && $ciclo_seleccionado)
    <button class="btn btn-primary float-end" wire:click="guardar()">Guardar</button>

    @endif

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
                        var termBase64 = btoa(params.term);
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
                @this.set('alumno_id', data.id);
                //window.location = "/alumno/" + data.id + "/datos/";
            });

            //Swal.fire('Hello world!');

        });
    </script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('alert', event => {
        Swal.fire({
            icon: event.detail.type,
            title: 'Listo!',
            text: event.detail.message,
            confirmButtonText: 'Aceptar',
            allowOutsideClick: true // Permite clics fuera del modal
        }).then((result) => {
            if (result.isConfirmed || result.dismiss === Swal.DismissReason.backdrop) {
                // Redirigir si se confirma o si se hace clic fuera del modal
                window.location.href = "/certificados/cambiociclos";
            }
        });
    });
});
</script>
@endsection
