{{-- ANA MOLINA 02/05/2024 --}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Verificación de Constancias</title>

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

    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/usuarios.css') }}" rel="stylesheet"> --}}
    <!-- ================== END BASE CSS STYLE ================== -->

    @livewireStyles

    <style>
        .photo-frame {
            width: 2.5in;
            height: 3.5in;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 10px 0;
        }

        .student-photo {
            max-width: 100%;
            max-height: 100%;
        }

        .student-photo-placeholder {
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body class="">
    <!-- BEGIN #app -->
    <div id="app" class="app app-content-full-height app-footer-fixed ">

        <div id="content" class="app-content">

            <div class="container-fluid">

                <!-- BEGIN #header -->
                <div id="header" class="app-header">

                    <!-- BEGIN mobile-toggler -->
                    <div class="mobile-toggler">
                        <button type="button" class="menu-toggler" data-toggle="sidebar-mobile">
                            <span class="mdi mdi-menu"></span>
                        </button>
                    </div>
                    <!-- END mobile-toggler -->

                    <table cellspacing="0" cellpadding="0" style="width:100%;">
                        <tr>
                            <td rowspan="4" width="10%" style="vertical-align:text-top;">
                                <!-- BEGIN brand -->
                                <div class="brand">
                                    <a class="brand-logo" href="/" title="SCE-COBACH">
                                        <img src="{{ asset('images/cobach_vertical.png') }}" class="logo"
                                            alt="Declaranet" style="width:100px">
                                    </a>
                                </div>
                                <!-- END brand -->
                            </td>
                            <td width="90%" style="text-align: center; font-size:16px;">
                                <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong>
                            </td>
                        </tr>
                    </table>

                </div>
                <!-- END #header -->
                @if ($no_encontrada)
                <section class="py-4 text-center">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Constancia no encontrada</h4>
                        <p>Lo sentimos, no pudimos encontrar la constancia que estás buscando.</p>
                        <hr>
                        <p class="mb-0">Si el problema persiste, contacta al administrador del sistema para obtener ayuda.</p>
                    </div>
                </section>
                @else
                <section class="py-4">

                    <div class="card">
                        <div class="card-header">
                            <label class="card-title">Verificación de la constancia:</label>
                        </div>

                        <div class="card-body">
                            <h5>Especifica:</h5>
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-3">
                                    <label class="form-label">CURP:</label>
                                    <input class="form-control" placeholder="Curp" readonly name="curp"
                                        value="{{ $buscar_alumno->curp }}" type="text">
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label class="form-label">NOEXPEDIENTE:</label>
                                    <input class="form-control" placeholder="Folio" readonly name="folio"
                                        value="{{ $buscar_alumno->noexpediente }}" type="text">
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label class="form-label">NOMBRE COMPLETO:</label>
                                    <input class="form-control" placeholder="Nombre Completo" readonly name="nombre"
                                        value="{{ $buscar_alumno->nombre . ' ' . $buscar_alumno->apellidos }}"
                                        type="text">
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label class="form-label">Expedida el ciclo:</label>
                                    <input class="form-control" placeholder="CICLO" readonly name="CICLO"
                                        value="{{ $buscar_ciclo->nombre }}" type="text">
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label class="form-label">Plantel que emite:</label>
                                    <input class="form-control" placeholder="Plantel que emite" readonly name="plantel"
                                        value="{{ $buscar_plantel->nombre}}" type="text">
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label class="form-label">Con el grupo:</label>
                                    <input class="form-control" placeholder="Grupo" readonly name="Grupo"
                                        value="{{ $datos->grupo_nombre }} @if ($datos->turno_id == 1) MATUTINO @else VESPERTINO @endif"
                                        type="text">
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label class="form-label">Con fecha de emisión:</label>
                                    <input class="form-control" placeholder="Fecha emisión" readonly name="fecha"
                                        value="{{ $validar_constancia->updated_at->format('Y-m-d') }}" type="text">
                                </div>
                           
                            </div>
                            <div class="text-center">
                                <h2>Imagen del alumno</h2>
                                <div class="photo-frame mx-auto" style="width: 2.5in; height: 3.5in; border: 2px solid #000; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    @if ($imagen)
                                        <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen->imagen)) }}"
                                            alt="Foto del Estudiante" class="student-photo" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="student-photo-placeholder">
                                            <p>Sin imagen</p>
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>

                </section>
                @endif
            </div>
        </div>

    </div>
</body>

</html>
