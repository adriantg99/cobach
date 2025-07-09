<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('title', config('app.name', 'SCE-COBACH'))
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="keywords" content="" />


    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <!-- Agrega estos enlaces a Bootstrap CSS y JS en la sección head de tu HTML -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireStyles

    <link rel="stylesheet" href="{{ asset('css/alumno.css') }}">


    <!-- ================== BEGIN BASE CSS STYLE ================== -->

    <!--
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400,600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
-->

    <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">

    <!-- ================== END BASE CSS STYLE ================== -->
    @yield('css_pre')
</head>

<body class="">


    <!-- BEGIN #app -->
    <div id="app" class="app app-content-full-height app-footer-fixed ">


        <div id="header" class="app-header">
            <table width="100%">
                <tr>
                    <td>
                        <img src="/images/cobach_vertical.png" height="70">
                    </td>
                    <td>
                        <h2>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA<br><span style="font-size:70%">Portal del
                                alumno</span></h2>
                    </td>
                    <td class="py-0">
                        <button class="d-flex align-items-center" onclick="location.href='{{ url('/logout') }}';">
                            <span class="mdi mdi-exit-to-app fs-4 me-2 text-pink">

                            </span>
                            Cerrar sesión
                        </button>
                    </td>
                </tr>
            </table>
        </div>



        <div id="content" class="app-content">
            <!--Boton temporal de cierre de sesion -->

           



            <div class="container-fluid ">



                <section class="py-3">
                    @yield('content')
                    @livewireScripts

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
-->

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />


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


    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- clockwork LS2 --}}
    <script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/toolbar.js"></script>

</body>

</html>
