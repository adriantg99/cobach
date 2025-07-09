<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="keywords" content="" />
    <meta name="csrf_token" value="{{ csrf_token() }}" />
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400,600;700;800&family=Roboto:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <!-- ================== END BASE CSS STYLE ================== -->

    <style>
        .no-display {
            display: none;
        }
    </style>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="auth">

    <div id="app" class="app auth-wrapp">
        <div class="auth-pattern"></div>
        <div class="auth-bg auth-bg-scroll" style="background-image: url({{ asset('images/login-bg.jpg') }});">
            <div class="auth-mask"></div>
        </div>

        <div class="auth-content">
            <div class="container-fluid">
                <div class="row g-0">
                    <div class="col-sm-7 auth-border">
                        <div class="auth-info">
                            <header class="mb-5">
                                <div class="row justify-content-center align-items-center m-0 mb-sm-5">
                                    <div class="col-sm-3 d-none d-sm-inline-block">
                                        <a class="auth-logo logo-1" href="https://www.sonora.gob.mx" title="">
                                            <img src="{{ asset('images/logo-sonora-white.svg') }}" class=""
                                                alt="Gobierno del Estado de Sonora">
                                        </a>
                                    </div>
                                    <div class="col-6 col-sm-3 offset-0 offset-sm-1 ">
                                        <a class="auth-logo logo-2" href="https://cobachsonora.edu.mx/"
                                            title="COBACH-SONORA">
                                            <img src="{{ asset('images/cobach_vertical.png') }}" class=""
                                                alt="COBACH SONORA">
                                        </a>
                                    </div>
                                    <div class="col-6 col-sm-3 offset-0 offset-sm-1 ">
                                        <a class="auth-logo logo-3" href="https://om.sonora.gob.mx" title="">
                                            <img src="{{ asset('images/logo-gobierno-digital-white.svg') }}"
                                                class="" alt="Subsecretaría de Gobierno Digital">
                                        </a>
                                    </div>
                                </div>
                            </header>

                            <div class="row my-auto justify-content-center mb-0 mb-sm-5 ">
                                <div class="col-12 col-sm-8 text-center">
                                    <h1 class="text-uppercase">COBACH</h1>
                                    <h2 class="mb-3">Sistema de Control Escolar</h2>
                                    <p class=""><!-- Leyenda --></p>
                                </div>
                            </div>

                            <footer>
                                <div class="auth-footer d-none d-sm-block">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-8 border-start border-light ps-3">
                                            <p class=" text-light-400 mb-0">
                                                Colegio de Bachilleres del Estado de Sonora. Blvd. Agustín Vildósola
                                                Final Sector Sur S/N, Col. Villa de Seris. Hermosillo, Sonora. C.P.
                                                83280
                                                Teléfonos: (662) 259 2900 y (662) 259 2910.
                                                https://cobach.sonora.gob.mx/
                                                <a
                                                    href="https://www.sonora.gob.mx/gobierno/politicas-de-uso-y-privacidad">Politicas
                                                    de uso y privacidad</a>
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p class="text-center mt-3"></p>
                                        </div>
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
 
                    <div class="col-md-5">
                        @yield('content')
                    </div>
 
                    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="phoneModalLabel">Teléfonos de Atención</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Atención Servidores Públicos</strong></p>
                                    <p>A fin de poder asesorarte sobre la presentación de tu declaración patrimonial y
                                        de intereses, la
                                        Secretaría de la Contrarloría General a través de la Coordinación Ejecutiva de
                                        Sustanciación y
                                        Resolución de Responsabilidades y Situación Patrimonial
                                        pone a su servicio de lunes a viernes de 08:00 a 16:00 horas los siguientes
                                        canales de atención:
                                    </p>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p>Los números teléfonicos</p>
                                            <ul class="list-group">
                                                <li class="list-group-item"><span class="mdi mdi-phone"></span> 662
                                                    2172168</li>
                                                <li class="list-group-item"><span class="mdi mdi-phone"></span> 662
                                                    2136207</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <p>En las siguientes extensiones:</p>
                                            <ul class="list-group">
                                                <li class="list-group-item">1299, 1297, 1300, 1301, 1303, 1304, 1305,
                                                    1306, 1328, 1330.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @yield('modal')
                </div>
            </div>
        </div>
    </div>

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <!-- ================== END BASE JS ================== -->

    <script defer src="{{ asset('js/plugins/telerik-ui/js/kendo.all.min.js') }}"></script>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">¿Eres de nuevo ingreso?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="nuevos_alumnos" name="nuevos_alumnos">
                        @csrf
                        <div class="mb-3">
                            <label for="">Inicia sesión con tu folio PREPASON y tu fecha de nacimiento</label>
                            <label for="folio_prepason" class="form-label">Folio Prepason</label>
                            <input type="text" class="form-control" id="folio_prepason" name="folio_prepason"
                                required placeholder='Sin los "0" del inicio ' autofocus>
                            <label for="">En caso de no recordar el folio validarlo en la siguiente liga con el
                                CURP: <a href="https://prepason.sonora.edu.mx/sistema/solicitud.aspx"
                                    target="_blank">https://prepason.sonora.edu.mx/sistema/solicitud.aspx</a></label>
                            @error('folio_prepason')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Fecha de nacimiento</label>
                            <input type="text" class="form-control" id="password" name="password"
                                placeholder="Sin espacios con el formato día mes año ejemplo: 06061999" required>
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @php
                            $status = DB::select("show status where `variable_name` = 'Threads_connected';");
                            //dd($status[0]->Value);
                            if ($status[0]->Value >= 120) {
                                $habilitado = false;
                            } else {
                                $habilitado = true;
                            }
                        @endphp

                        @if ($habilitado)
                            <button type="submit" class="btn btn-primary"
                                onclick="this.form.action = '{{ route('nuevo_ingreso.iniciar_inscripcion') }}'">Iniciar
                                Sesión</button>
                        @else
                            <button type="submit" class="btn btn-primary" disabled>Iniciar
                                Sesión (Deshabilitado por sobrepasar la cantidad de usuarios simultaneos, por favor
                                intente mas tarde)</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var currentDate = new Date();

        //Cambiar fecha antes de subirlo al servidor
        var startDate = new Date('2024-07-29T08:01:00');
        var endDate = new Date('2024-08-05T23:59:59');

        if (currentDate >= startDate && currentDate <= endDate) {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'), {
                backdrop: true, // Esta opción permite cerrar el modal al hacer clic fuera de él
                keyboard: true // Permite cerrar el modal con la tecla ESC
            });
            loginModal.show();
        }
    });
</script>

</html>
