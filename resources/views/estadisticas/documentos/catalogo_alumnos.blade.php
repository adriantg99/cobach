<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Catalogo</title>
    <style>
        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        .contenido {
            margin-top: 30px;
            /* Ajusta el valor según sea necesario para dejar espacio para el encabezado */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* Esto asegura que las celdas se fusionen y no se superpongan */

        }

        th,
        td {
            border: 1px solid;
        }

        .alumno {
            width: 70%;
            text-align: center;
            padding-bottom: 10px;
            /* Ajusta el valor según sea necesario */
        }

        .exp {
            text-align: center;
            width: 10%;
        }

        .firma {
            width: 70%;
            padding-bottom: 10px;
            /* Ajusta el valor según sea necesario */
        }

        .fecha {
            width: 20%;
        }

        .tabla_abajo {
            padding-bottom: 10px;
            /* Ajusta el valor según sea necesario */
        }

        .fondo {
            padding-bottom: 10px;
        }

        .centrar {
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <!-- Agrega tus datos de encabezado aquí -->
        <div class="centrar">
            <h3>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</h3>
            @if ($foto == true || $foto_credencial == true)
                LISTADO DE ALUMNOS CON FOTOGRAFIA PARA @if ($foto_credencial == true)
                    CREDENCIALES
                @else
                    CERTIFICADOS
                @endif 
            @else
                CATALOGO DE ALUMNOS
            @endif
        </div>

    </header>

    @php
        use App\Models\Adminalumnos\ImagenesalumnoModel;
        $contador = 0;
    @endphp
    @foreach ($alumnos as $alumno)
        @if ($contador == 0)
            <table style="width: 100%;">
                <tr>
                    <td style="align-items: left; border: none;">
                        CICLO ESCOLAR: {{ $ciclo->nombre }}
                    </td>
                    <td style="align-items: right; border: none;">
                        FECHA:
                        {{ $hoy->day < 10 ? '0' . $hoy->day : $hoy->day }}-{{ $hoy->month < 10 ? '0' . $hoy->month : $hoy->month }}-{{ $hoy->year }}
                    </td>
                </tr>
                <tr>
                    <td style="border: none;">
                        PLANTEL: {{ $plantel->nombre }}
                    </td>
                    <td style="border: none;">
                        GRUPO: {{ $alumno->grupo }}
                    </td>
                </tr>

            </table>
            <div class="contenido">

            </div>
        @endif
        <table>
            <tr style="background-color: rgba(190, 190, 190, 0.533);">
                <td class="exp">
                    No EXP
                </td>
                <td class="alumno">
                    ALUMNO
                </td>
                @if ($foto == true || $foto_credencial == true)
                    <td>
                        FOTO
                    </td>
                @endif

            </tr>
            <tr>
                <td>
                    {{ $alumno->noexpediente }}
                </td>
                <td class="alumno">
                    {{ $alumno->apellidos }} {{ $alumno->nombre }}
                </td>
                @if ($foto == true || $foto_credencial == true)
                    <td>
                        <?php
                        if($foto_credencial == true){
                            $imagen_find = ImagenesalumnoModel::where('alumno_id', $alumno->id)
                                        ->where('tipo', 1)
                                        ->get();
                        }
                        else{
                            $imagen_find = ImagenesalumnoModel::where('alumno_id', $alumno->id)
                                        ->where('tipo', 2)
                                        ->get();
                        }
                                    
                                        if ($imagen_find->count()>0)
                        {
                            $row=$imagen_find[0];
                            ?>
                        <div class="img-container">
                            <div class="imageOne image">
                                <img src="data:image/png;base64,{{ chunk_split(base64_encode($row->imagen)) }}"
                                    height="150" width="150" alt="Logo" class="logo">
                            </div>
                        </div>
                        <?php } else { ?>
                        <img src="../public/images/logocobachchico.png" width="100px" alt="Logo" class="logo">
                        <?php
                            }
                            ?>
                    </td>
                @endif
            </tr>
            @if ($foto == true || $foto_credencial == true)
                @if ($imagen_find->count() > 0)
                @else
        </table>

        <table class="tabla_abajo">
    @endif
@else
    </table>

    <table class="tabla_abajo" style="border: none;">
        @endif



        <tr>
            @if ($foto == true || $foto_credencial == true)
                @if ($imagen_find->count() > 0)
                    <td colspan="2"> <!-- colspan="2" asegura que la celda ocupe dos columnas -->
                        Firma de conformidad del estudiante
                    </td>
                    <td class="fecha">
                        FECHA:
                    </td>
    </table>
    <input type="hidden" class="fondo">
@else
    <td colspan="2"> <!-- colspan="2" asegura que la celda ocupe dos columnas -->
        Firma de conformidad del estudiante
    </td>
    <td class="fecha">
        FECHA:
    </td>
    </table>
    <input type="hidden" class="fondo">
    @endif
@else
    <td class="firma"> <!-- colspan="2" asegura que la celda ocupe dos columnas -->
        Firma de conformidad del estudiante
    </td>

    <td class="fecha">
        FECHA:
    </td>
    @endif
    </tr>
    </table>
    @if ($foto == true || $foto_credencial == true)
        @if ($loop->iteration % 4 == 0)
            @php
                $contador = 0;
            @endphp
            <div style="page-break-after: always;">
            </div>
            @continue
        @endif
    @else
        @if ($loop->iteration % 8 == 0)
            @php
                $contador = 0;
            @endphp
            <div style="page-break-after: always;">
            </div>
            @continue
        @endif
    @endif

    @php
        $contador++;
    @endphp
    @endforeach
    </div>
</body>

</html>
