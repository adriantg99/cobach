<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}

    <div class="row g-3">
        <div class="card shadow" id="principal">
            <div class="card-header">
                <label class="card-title">Seleccionar alumno:</label>
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($alumno_id == null)
                        <section class="py-3">
                            <select class="form-control select2BuscaAlumn" autocomplete="off">
                                <option value=""></option>
                            </select>
                        </section>
                    @else
                        <div class="col-12 col-sm-12">
                            <label for="alumno" class="form-label">Expediente:</label>
                            <input class="form-control" type="text" value="{{ $alumno->noexpediente }}" disabled>
                        </div>
                        <div class="col-12 col-sm-12">
                            <label for="alumno" class="form-label">Alumno:</label>
                            <input class="form-control" type="text"
                                value="{{ $alumno->apellidos }} {{ $alumno->nombre }}" disabled>
                        </div>
                        {{-- <p>
                            <br>No EXPEDIENTE: <strong>{{$dat_alumno->noexpediente}}</strong>
                            <br>Alumno: <strong>{{$dat_alumno->apellidos}} {{$dat_alumno->nombre}}</strong>
                        </p> --}}
                    @endif
                    <div wire:loading>
                        @if ($alumno == null)
                            <h4><span style="color: red;">Buscando Alumno por favor espere...</span></h4>
                        @endif
                    </div>
                </div>
            </div><!--card body -->

        </div>
        @if ($alumno_id)

            <div>
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <label class="card-title">Asignaturas</label>
                        </div>
                        <div class="text-end">
                            <label for="duplicado">¿Es duplicado?</label>
                            <input type="checkbox" name="duplicado" wire:change="cambia_duplicado()" id="duplicado"
                                @if ($alumno->carta_compromiso) checked @endif>
                            @if ($validacion)
                                <button class="btn btn-info" onclick="generando('{{ $alumno_id }}')">Imprimir
                                    certificado</button>
                            @endif
                            <button class="btn btn-primary">Guardar</button>
                        </div>

                        <div class="card-body">
                            <table style="width: 100%">
                                <tr style="border-bottom: 1px solid #ddd;">

                                    <th>Clave</th>
                                    <th>Asignatura</th>
                                    <th>Calificación</th>
                                </tr>
                                @foreach ($asignaturas_sibal as $asignatura)
                                    <tr>

                                        <td>
                                            {{ $asignatura->clave }}
                                        </td>
                                        <td>
                                            {{ $asignatura->nombre }}

                                        </td>
                                        <td style="align-content: center">
                                            <input style="width: 30%" class="form-control form-control-lg"
                                                type="number" max="100" oninput="formatInput(this);"
                                                wire:model.defer="calificaciones.{{ $asignatura->id }}"
                                                wire:blur="actualizarCalificacion({{ $asignatura->id }}, $event.target.value)"
                                                maxlength="3">
                                        </td>
                                    </tr>
                                @endforeach

                            </table>

                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>

@section('js_post')
    <script type="text/javascript">
        $(document).ready(function() {
            // Inicializar el select2 de alumno
            $('.select2BuscaAlumn').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                language: 'es',
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
                        };
                    },
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

            // Manejar evento de selección para alumno
            $('.select2BuscaAlumn').on('select2:select', function(e) {
                var data = e.params.data;
                @this.set('alumno_id', data.id);
            });
        });


        function formatInput(input) {
            // Elimina cualquier caracter que no sea un número
            input.value = input.value.replace(/\D/g, '');

            // Limita la longitud del valor a 3 dígitos
            if (input.value.length > 3) {
                input.value = input.value.slice(0, 3);
            }
            if (parseInt(input.value) > 100) {
                input.value = '100';
            }
        }
    </script>

    <script>
        function generando(alumno_id) {
            let url =
                "{{ route('sibal.certificado', ['alumno_id' => ':alumno_id']) }}";

            //codificar
            var encode_alumno_id = btoa(alumno_id);


            url = url.replace(":alumno_id", encode_alumno_id);


            Swal.fire({
                title: 'Generando impresión...',
                html: 'Por favor espere.',

                showConfirmButton: false
            });
            Swal.showLoading();


            $.ajax({
                url: url,
                type: "GET",
                success: function(result) {
                    window.open(url, "_blank");
                    Swal.close(); // this is what actually allows the close() to work
                    //console.log(result);
                },
            });
        }
    </script>
@endsection
