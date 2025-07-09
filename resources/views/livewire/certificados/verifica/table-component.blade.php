{{-- ANA MOLINA 02/05/2024 --}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Verificación de Certificados</title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="keywords" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    <!-- Incluye jQuery (Select2 depende de ello) -->
    <!-- Incluye el JS de Select2 -->

    <!-- Incluye los DataTable -->


    <!-- ================== BEGIN BASE CSS STYLE ================== -->

    <!--
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400,600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
-->

    <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/usuarios.css') }}" rel="stylesheet">
    <!-- ================== END BASE CSS STYLE ================== -->

    @livewireStyles
</head>

<body class="">
    <!-- BEGIN #app -->
    <div id="app" class="app app-content-full-height app-footer-fixed ">


        <div id="content" class="app-content">

            <div class="container-fluid ">

                <!-- BEGIN #header -->
                <div id="header" class="app-header">


                    <!-- BEGIN mobile-toggler -->
                    <div class="mobile-toggler">
                        <button type="button" class="menu-toggler" data-toggle="sidebar-mobile">
                            <span class="mdi mdi-menu"></span>
                        </button>
                    </div>
                    <!-- END mobile-toggler -->

                    <table cellspacing="0" cellpadding="0" style="width:100%; ">
                        <tr>
                            <td rowspan="4" width="10%" style="vertical-align:text-top; " >
                                <!-- BEGIN brand -->
                                <div class="brand">


                                    <a class="brand-logo" href="/" title="SCE-COBACH">
                                        <img src="{{ asset('images/cobach_vertical.png') }}" class="logo" alt="Declaranet" style="width:100px">
                                        {{-- <h1>SCE</h1> --}}
                                    </a>

                                </div>
                                <!-- END brand -->
                            </td>
                            <td  width="90%" style="text-align: center; font-size:16px; ">
                                <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong>
                            </td>

                        </tr>
                    </table>

                </div>
                <!-- END #header -->

                    <section class="py-4">

                        <div class="card">
                            <div class="card-header">
                                <label class="card-title">Verificación de Certificado:</label>
                                {{-- {{$alumnos->links()}} --}}
                            </div>

                            <div class="card-body">
                                <h5>Especifica:</h5>
                                 <div class="col-6 col-sm-6">
                                    <label class="form-label">CURP:</label>
                                    <input class="form-control" readonly placeholder="Curp" name="curp" wire:model="curp" type="text">
                                    <label class="form-label">Folio:</label>
                                    <input class="form-control" readonly placeholder="Folio" name="folio" wire:model="folio" type="text">

                                               </div>
                            </div>
                            <div class="col-sm-8">
                                <button class="btn btn-info" wire:click="buscar();">Verificar</button>
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

                                    </section>

                                </div>
                            </div>


                        </div>

            </div>
        </div>


    </div>
    <!-- END #app -->
    <!-- BEGIN btn-scroll-top -->
    <a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
    <!-- END btn-scroll-top -->
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <!-- ================== END BASE JS ================== -->
    <!--
<script src="{{ asset('js/plugins/apexcharts/dist/apexcharts.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/demo/dashboard.demo.js') }}" type="text/javascript"></script>


    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
-->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.select2Agreements').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                //multiple: true,
                language: 'es',
                //templateResult: formatList,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                },
                placeholder: '',
                //minimumInputLength: 1,
                ajax: {
                    url: '/acuerdos/ajax',
                    dataType: 'json',
                    method: 'POST',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            type: 'agreements'
                        }
                    },
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    },
                }

            });


            $('.select2Agreements').on('select2:select', function(e) {
                var data = e.params.data;
                window.location = "/acuerdos/" + data.id + "/seguimiento/";
            });

            //Swal.fire('Hello world!');

        });


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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: '{{ session()->get('success') }}',
                showConfirmButton: false,
                timer: 10000
            })
        </script>
    @endif

    @if (session()->has('warning'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: '{{ session()->get('warning') }}',
                showConfirmButton: false,
                timer: 10000
            })
        </script>
    @endif

    @if (session()->has('danger'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '{{ session()->get('danger') }}',
                showConfirmButton: false,
                timer: 10000
            })
        </script>
    @endif

    @if (session()->has('info'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '{{ session()->get('info') }}',
                showConfirmButton: false,
                timer: 10000
            })
        </script>
    @endif
    @livewireScripts

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- https://fontawesome.com/ --}}
    <script src="https://kit.fontawesome.com/36104518ad.js" crossorigin="anonymous"></script>
    {{-- clockwork LS2 --}}
    <script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/toolbar.js"></script>

</body>

</html>




