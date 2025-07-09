<!DOCTYPE html>
<html>

<head>

    <title>Validación de Identificación del alumno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 200px;
            /* Tamaño personalizado para el logo */
        }

        .texto-encima-ciclo {
            position: absolute;
            /* Establecer la posición absoluta */
            top: 11%;
            /* Alinear verticalmente al 50% del contenedor padre */
            left: 70%;
            /* Alinear horizontalmente al 50% del contenedor padre */
            /*transform: translate(-50%, -50%);*/
            /* Centrar el texto */
            color: white;
            /* Alinear el texto al centro */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Sombra del texto */
            z-index: 999;
            font-size: 20px;

        }

        .contenido {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .tabla th,
        .tabla td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .tabla th {
            background-color: #f2f2f2;
        }

        .imagen-container {
            position: relative;
            /* Establecer la posición relativa */
            /*width: 100%;*/
            width: 1000px;
            /* Ajustar al ancho de la página */
            margin-bottom: 20px;
            /* Espacio entre las imágenes */
        }

        .imagen {
            width: 100%;
            /* Ancho al 100% del contenedor */
            height: auto;
            /* Altura automática para mantener la proporción */
            display: block;

            border-radius: 1%;
            /* Mostrar como bloque para evitar desbordamiento */
        }

        .texto-encima {
            position: absolute;
            /* Establecer la posición absoluta */
            top: 50%;
            /* Alinear verticalmente al 50% del contenedor padre */
            left: 23%;
            /* Alinear horizontalmente al 50% del contenedor padre */
            transform: translate(-50%, -50%);
            /* Centrar el texto */
            color: black;
            text-align: center;
            /* Alinear el texto al centro */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Sombra del texto */
            z-index: 999;

        }

        .codigo-qr {
            position: absolute;
            /* Establecer la posición absoluta */
            top: 28%;
            /* Alinear verticalmente al 50% del contenedor padre */
            right: 10%;
            /* Alinear horizontalmente al 50% del contenedor padre */
            transform: translate(-50%, -50%);
            /* Centrar el texto */
            color: black;
            text-align: center;
            /* Alinear el texto al centro */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Sombra del texto */
            z-index: 999;

        }

        .imagen-superior img {
            position: absolute;
            /* Establecer la posición absoluta */
            top: 10%;
            /* Alinear al borde superior del contenedor padre */
            right: 10%;
            /* Alinear al borde derecho del contenedor padre */
            max-width: 300px;
            max-height: 360px;

            min-width: 300px;
            min-height: 300px;

            border-radius: 3%;

            /* Limitar el ancho al 30% del contenedor padre */
            height: auto;
            /* Altura automática para mantener la proporción */
        }

        .firma {
            position: absolute;
            right: 40px;
            bottom: 200px;
            max-width: 150px;
            max-height: 180px;
            min-width: 90px;
            min-height: 90px;
            z-index: 999;

        }



        .imagen-superior-2 img {
            position: absolute;
            /* Establecer la posición absoluta */
            top: 65%;
            /* Alinear al borde superior del contenedor padre */
            right: 15%;
            /* Alinear al borde derecho del contenedor padre */
            max-width: 300px;
            max-height: 360px;

            border-radius: 3%;

            /* Limitar el ancho al 30% del contenedor padre */
            height: auto;
            /* Altura automática para mantener la proporción */
        }

        .texto-encima-2 {
            position: absolute;
            /* Establecer la posición absoluta */
            top: 90%;
            /* Alinear verticalmente al 50% del contenedor padre */
            left: 75%;
            /* Alinear horizontalmente al 50% del contenedor padre */
            transform: translate(-50%, -50%);
            /* Centrar el texto */
            color: black;
            /* Alinear el texto al centro */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Sombra del texto */
            z-index: 999;

        }

        .texto-encima-periodo {
            position: absolute;
            /* Establecer la posición absoluta */
            top: 5%;
            /* Alinear verticalmente al 50% del contenedor padre */
            left: 75%;
            /* Alinear horizontalmente al 50% del contenedor padre */
            transform: translate(-50%, -50%);
            /* Centrar el texto */
            color: black;
            /* Alinear el texto al centro */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Sombra del texto */
            z-index: 999;

        }
    </style>
</head>

<body>
    @if (isset($dato))
        <div class="imagen-container">
            <img src="{{ $imagen_frente }}" class="imagen"> <!-- Mostrar la primera imagen -->
            <div class="texto-encima">
                <br>
                <table style="">
                    <tr>
                        <td></td>
                        <td align="center" style="font-weight: bold; font-size: 24px;  width: 100px; text-align: center;">
                            ALUMNO:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center" style="font-weight: bold; font-size: 22px;">{{ $dato->nombre }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center"
                            style="font-weight: bold; font-size: 22px;  width: 100px; text-align: center;">
                            {{ $dato->apellidos }}</td>
                    </tr>
                    <tr>
                        <td style="color: white;">.</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center"
                            style="font-weight: bold; font-size: 24px; text-align:center;  width: 100px; text-align: center;">
                            EXPEDIENTE:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center" style="  font-size: 20px; ">{{ $dato->noexpediente }}</td>
                    </tr>
                    <tr>
                        <td style="color: white;">.</td>
                    </tr>

                    <tr>
                        <td></td>
                        <td align="center"
                            style="font-weight: bold; font-size: 24px; width: 100px; text-align: center;">PLANTEL:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="left"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center;  font-size: 20px;">
                            {{ $dato->plantel }}</td>
                    </tr>
                </table>
            </div> <!-- Texto encima de la primera imagen -->
            <div class="imagen-superior">

                {{-- @php

                $imagen_alumnos_2 = ImagenesalumnoModel::where(
                    'alumno_id',
                    $dato->id
                    //$imagen_alumnos_2 = FotosModel::where('expediente', $dato->noexpediente)->get();
                )
                    ->where('tipo', '1')
                    ->get();

            @endphp --}}


                @if (isset($imagen_alumnos))
                    <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_alumnos->imagen)) }}"
                        class="imagen">
                @endif

                <!-- Mostrar la segunda imagen -->
            </div>
            {{-- <div class="codigo-qr">
                <img src="data:image/png;base64,.{{$codigoqr}}." alt="qrcode"  height="100"  >

            </div> --}}
        </div>
        <div class="imagen-container">
            <img src="{{ $imagen_atras }}" class="imagen">

            <div class="imagen-superior-2">
                <img src="{{ asset('firmas/' . $firma_director) }}" class="firma">

                <div class="texto-encima-2">
                    <p style="font-weight: bold; font-size: 20px; white-space: nowrap;">{{ $dato->director }}</p>
                </div>
                <div class="texto-encima-periodo">
                    <p>SEMESTRE: {{ $dato->periodo }}</p>
                </div>
                <div class="texto-encima-ciclo" style="padding-top: 12%">
                    <p>Agosto {{ $annio }}</p>
                    <p>Agosto {{ $anniom1 }}</p>
                </div>
            </div>

        </div>
    @else
        <div id="app" class="app app-content-full-height app-footer-fixed ">


            <div id="content" class="app-content">

                <div class="container-fluid ">

                    <!-- BEGIN #header -->
                    <div id="header" class="app-header">


                        <!-- BEGIN mobile-toggler -->

                        <!-- END mobile-toggler -->

                        <table cellspacing="0" cellpadding="0" style="width:100%; ">
                            <tr>
                                <td rowspan="4" width="10%" style="vertical-align:text-top; ">
                                    <!-- BEGIN brand -->
                                    <div class="brand">


                                        <a class="brand-logo" href="/" title="SCE-COBACH">
                                            <img src="{{ asset('images/cobach_vertical.png') }}" class="logo"
                                                alt="Declaranet" style="width:100px">
                                            {{-- <h1>SCE</h1> --}}
                                        </a>

                                    </div>
                                    <!-- END brand -->
                                </td>
                                <td width="90%" style="text-align: center; font-size:16px; ">
                                    <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong>
                                </td>

                            </tr>
                        </table>

                    </div>
                    <!-- END #header -->

                    <section class="py-4">

                        <div class="card">
                            <div class="card-header">
                                <label class="card-title">Verificación de identificación del alumno:</label>
                                {{-- {{$alumnos->links()}} --}}
                            </div>

                            <div class="card-body">
                                <div class="col-6 col-sm-6">
                                    <label class="form-label">APELLIDOS:</label>
                                    <label>{{ $apellidos }}</label>

                                </div>
                                <div class="col-6 col-sm-6">
                                    <label class="form-label">NO. EXPEDIENTE:</label>
                                    <label>{{ $noexpediente }}</label>

                                </div>
                            </div>

                            @if ($message != '')
                                <div>
                                    <p style="color:red; height:5em; overflow-y: scroll;">{{ $message }}</p>

                                </div>
                            @endif
                        </div>


                    </section>

                </div>
            </div>


        </div>

        </div>
        </div>


        </div>
    @endif
</body>

</html>
