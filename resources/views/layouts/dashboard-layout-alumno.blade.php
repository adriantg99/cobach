<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('title', config('app.name', 'SCE-COBACH'))
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+AMrEJ3oGpG1Dlv5K4l9e4aFq4ql" crossorigin="anonymous"></script>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/alumno.css') }}">
    <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    @yield('css_pre')
</head>

<body class="">

    <!-- BEGIN #app -->
    <div id="app">

        <!-- Header -->
        <div id="header" class="bg-light p-3">
            <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="/images/cobach_vertical.png" height="70" alt="Logo">
                </div>
                <div class="text-center mt-2 mt-md-0">
                    <h2 class="mb-0">COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</h2>
                    <span style="font-size: 70%">Portal del alumno</span>
                </div>
                <div class="mt-2 mt-md-0">
                    <button class="btn btn-outline-danger d-flex align-items-center" onclick="location.href='{{ url('/logout') }}';">
                        <span class="mdi mdi-exit-to-app fs-4 me-2 text-pink"></span>
                        Cerrar sesión
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{url('alumno')}}">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('alumno/boleta')}}">Boleta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/alumno/credencialdigital')}}">Credencial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/alumnos/kardex')}}">Kardex</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="container-fluid">
            <section class="py-3">
                @yield('content')
                @livewireScripts
            </section>
        </div>

    </div>
    <!-- END #app -->

    <!-- Scroll to Top Button -->
    <a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>

    <!-- Scripts -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
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
    @yield('js_post')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>
