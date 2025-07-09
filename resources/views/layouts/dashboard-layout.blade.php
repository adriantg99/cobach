<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('title', config('app.name', 'SICE-COBACH'))
    </title>

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
    <link href="{{ asset('css/mouse_actions.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/usuarios.css') }}" rel="stylesheet">
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- PACE I------>


    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/red/pace-theme-center-radar.css">


    <!-- PACE F------>

    @yield('css_pre')


    @livewireStyles
</head>

<body class="">
    <!-- BEGIN #app -->
    <div id="app" class="app app-content-full-height app-footer-fixed ">


        <!-- BEGIN #sidebar -->
        <div id="sidebar" class="app-sidebar">

            <div class="auth-bg auth-bg-scroll" style="background-image: url({{ asset('images/login-bg.jpg') }});">
                <div class="auth-mask"></div>
            </div>


            <!-- BEGIN scrollbar -->
            <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">


                <!-- BEGIN mobile-toggler -->
                <div class="mobile-toggler">
                    <button type="button" class="menu-toggler" data-dismiss="sidebar-mobile">
                        <span class="mdi mdi-arrow-left"> </span>
                    </button>
                </div>
                <!-- END mobile-toggler -->



                <div class="desktop-toggler">
                    <button type="button" class="menu-toggler" data-toggle="sidebar-minify">
                        <span class="mdi mdi-arrow-left"> </span>
                    </button>
                </div>


                <!-- BEGIN brand -->
                <div class="brand">


                    <a class="brand-logo" href="/" title="SCE-COBACH">
                        <img src="{{ asset('images/cobach_vertical.png') }}" class="logo" alt="Declaranet">
                        {{-- <h1>SCE</h1> --}}
                    </a>

                </div>
                <!-- END brand -->

                <!-- BEGIN menu -->
                @include('layouts.dashboard-menu-layout')
                <!-- END menu -->

            </div>
            <!-- END scrollbar -->

            <!-- BEGIN mobile-sidebar-backdrop -->
            <button class="app-sidebar-mobile-backdrop" data-dismiss="sidebar-mobile"></button>
            <!-- END mobile-sidebar-backdrop -->
        </div>
        <!-- END #sidebar -->

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

                    <!-- BEGIN menu -->
                    <div class="menu">

                        <h3 class="page-header">
                        </h3>
                        @php
                            if (
                                Auth()->user()->hasRole('super_admin') ||
                                Auth()->user()->hasRole('asistencia_educativa') ||
                                Auth()->user()->hasRole('orientacion_educativa') ||
                                Auth()->user()->hasRole('inclusion_educativa') ||
                                Auth()->user()->hasRole('tutoria_grupal') ||
                                Auth()->user()->hasRole('director_plantel') ||
                                str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar')
                            ) {
                                $nodocente = true;
                            } else {
                                $nodocente = false;
                            }
                        @endphp
                        @if ($nodocente)
                            <form class="menu-search" name="header_search_form">
                                <div class="menu-search-icon"><i class="mdi mdi-magnify"></i></div>
                                <div class="menu-search-input">
                                    @livewire('select2-agreements')
                                </div>

                            </form>
                        @endif



                        <div class="menu-item dropdown ">
                            <a href="#" data-bs-toggle="dropdown" title=" Usuario Verificado "
                                data-bs-display="static" class="menu-link">
                                <div class="menu-img">
                                    <i class="mdi mdi-account-circle-outline"></i>
                                </div>

                                <div class="menu-text lh-1">

                                    {{ Auth()->user()->email }} <span class="mdi mdi-chevron-down"></span>
                                    {{-- <small class="d-block fw-normal">miguel.romero@sonora.gob.mx</small> --}}

                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end me-lg-3 py-0 border">
                                <a class="dropdown-item d-flex align-items-center" href="/perfil">
                                    <span class="mdi mdi-account-circle fs-4 me-2 text-pink"></span> Mi perfil
                                </a>
                                <div class="dropdown-divider my-0"></div>

                                <a class="dropdown-item d-flex align-items-center" href="public/perfil">
                                    <i class="mdi mdi-face-agent fs-4 me-2 text-pink"></i> Soporte
                                </a>
                                <div class="dropdown-divider my-0"></div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item d-flex align-items-center" type="submit">
                                        <span class="mdi mdi-exit-to-app fs-4 me-2 text-pink"></span> Salir
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- END menu -->

                </div>
                <!-- END #header -->

                <section class="py-3">
                    @yield('content')
                </section>

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
                        var rolesBase64 = @json(session('roles'));
                        return {
                            term: termBase64,
                            type: typeBase64,
                            rol: rolesBase64
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


            $('.select2Agreements').on('select2:select', function(e) {
                var data = e.params.data;

                window.location = "/alumno/" + data.id + "/datos/";
            });

            //Swal.fire('Hello world!');

        });
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
    @yield('js_post')
    @livewireScripts

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- https://fontawesome.com/ --}}
    {{-- <script src="https://kit.fontawesome.com/36104518ad.js" crossorigin="anonymous"></script> --}}
    {{-- clockwork LS2 --}}
    <script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/toolbar.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

</body>

</html>
