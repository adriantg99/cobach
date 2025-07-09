<!DOCTYPE html>
<html>
@foreach ($datos as $dato)

    <head>
        @if ($grupo != null)
            <title>Credenciales del grupo: {{ $grupo->nombre }} {{ $grupo->turno_id == 1 ? "Matutino" : "Vespertino" }}</title>
        @else
<title>Credencial {{ $dato->apellidos }} {{ $dato->nombre }}</title>
        @endif
        
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
                width: 100%;
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
                top: 25%;
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
                top: 5%;
                /* Alinear al borde superior del contenedor padre */
                right: 14%;
                /* Alinear al borde derecho del contenedor padre */
                max-width: 150px;
                max-height: 180px;

                min-width: 150px;
                min-height: 150px;

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
                top: 29%;
                /* Alinear al borde superior del contenedor padre */
                right: 14%;
                /* Alinear al borde derecho del contenedor padre */
                max-width: 150px;
                max-height: 180px;

                border-radius: 3%;

                /* Limitar el ancho al 30% del contenedor padre */
                height: auto;
                /* Altura automática para mantener la proporción */
            }

            .texto-encima-2 {
                position: absolute;
                /* Establecer la posición absoluta */
                top: 40%;
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
                font-size: 18px;

            }
        </style>
    </head>

    <body>
        @foreach ($datos as $alumno)
            
        @endforeach
        @if (isset($imagen_alumnos))

        <div class="imagen-container">
            <img src="{{ $imagen_frente }}" class="imagen"> <!-- Mostrar la primera imagen -->
            <div class="texto-encima">
                <br>
                <table style="">
                    <tr>
                        <td></td>
                        <td align="center" style="font-weight: bold; font-size: 20px;  width: 100px; text-align: center;">
                            ALUMNO:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center" style="font-weight: bold; font-size: 18px;">{{ $dato->nombre }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center"
                            style="font-weight: bold; font-size: 18px;  width: 100px; text-align: center;">
                            {{ $dato->apellidos }}</td>
                    </tr>
                    <tr>
                        <td style="color: white;">.</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center"
                            style="font-weight: bold; font-size: 20px; text-align:center;  width: 100px; text-align: center;">
                            EXPEDIENTE:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center">{{ $dato->noexpediente }}</td>
                    </tr>
                    <tr>
                        <td style="color: white;">.</td>
                    </tr>

                    <tr>
                        <td></td>
                        <td align="center"
                            style="font-weight: bold; font-size: 20px; width: 100px; text-align: center;">PLANTEL:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="left" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center;">
                            {{ $dato->plantel }}</td>
                    </tr>
                </table>
            </div> <!-- Texto encima de la primera imagen -->
            <div class="imagen-superior">

                {{-- @php --}}

                {{--  $imagen_alumnos_2 = ImagenesalumnoModel::where(
                     'alumno_id',
                     $dato->id
                     //$imagen_alumnos_2 = FotosModel::where('expediente', $dato->noexpediente)->get();
                 )
                     ->where('tipo', '1')
                     ->get(); --}}

            {{-- @endphp --}}



                    <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_alumnos->imagen)) }}" class="imagen">


                <!-- Mostrar la segunda imagen -->
            </div>
            <div class="codigo-qr">
                <img src="data:image/png;base64,.{{$codigoqr}}." alt="qrcode"  height="100"  >

            </div>
        </div>
        <div class="imagen-container">
             <img src="{{ $imagen_atras }}" class="imagen">

              <div class="imagen-superior-2">
                <img src="{{public_path('firmas/'.$firma_director)}}" class="firma">

                <div class="texto-encima-2">
                    <p style="font-weight: bold; font-size: 12px; white-space: nowrap;">{{$dato->director}}</p>
                </div>
                <div class="texto-encima-periodo">
                    <p>SEMESTRE: {{$dato->periodo}}</p>
                </div>
                <div class="texto-encima-ciclo">
                    <p>Agosto 2024</p>
                    <p>Agosto 2025</p>
                </div>
                <div style="text-align:center; font-size: 10px;">F.{{date('d-m-Y')}}</div>

            </div>

        </div>
        @else
            <div>
                <p>FOTOGRAFÍA INEXISTENTE</p>
            </div>
         @endif

    </body>

@endforeach

</html>
