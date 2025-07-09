<div>
    {{-- Be like water. --}}
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
            <p class="text-primary m-0 fw-bold">Buscar alumno para cambio de plan:</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-xl-11 text-nowrap">
                    <table>

                        <tr>
                            <td>
                                @if ($alumno_id == null)
                                    <label class="form-label">Alumno: </label>
                                @endif

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
                                    @if ($plan_mas_comun)
                                        <p>
                                            {{ $plan_mas_comun }}
                                        </p>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </table>


                </div>




            </div>




        </div><!--card body -->
        @php
            use App\Models\Cursos\CursosModel;

        @endphp
        @if ($buscar_materias)
            <div class="card-body">
                <div class="row">
                    <table>
                        <tr>
                            <td>
                                Materias
                            </td>
                            <td>
                                Aprobada
                            </td>
                            <td>
                                Plan de estudio
                            </td>
                        </tr>
                        @foreach ($buscar_materias as $materias_kardex)
                            @php
                                $curso = CursosModel::find($materias_kardex->esc_curso_id);
                                $periodo_anterior = 0;
                            @endphp
                            <tr @if ($plan_mas_comun != $curso->plan->nombre) style='background-color: #f8d7da;' @endif>

                                <td>
                                    {{ $materias_kardex->materia }}
                                </td>
                                <td>
                                    {{ $materias_kardex->calif }}
                                </td>
                                <td>
                                    {{ $curso->plan->nombre }}

                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif

    </div><!-- Card shadow  -->
    @if ($alumno)
        <div class="card shadow">

        </div>
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
@endsection
