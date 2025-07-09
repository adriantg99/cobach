<!DOCTYPE html>
<html>
<head>
    <title>Alumnos Nuevos del Plantel {{ $plantel_nombre }}</title>
    <style>
        /* Añade aquí tus estilos CSS personalizados para el PDF */
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 140px;
        }
        .first-page {
            width: 100%;
            text-align: center;
        }
        .first-page h3 {
            margin-bottom: 5mm; /* Ajusta este valor según sea necesario */
        }
        /* Estilos para el resto del contenido */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20mm; /* Ajusta este valor para separar del título */
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>

</head>
<body>
    <!-- Contenido visible solo en la primera página -->
     <!-- Logo -->
     <img src="{{ $logo }}" alt="Logo" class="logo">
     <!-- Contenido visible solo en la primera página -->
     <div class="first-page">
         <h3>Lista de Alumnos Nuevos del Plantel <br> {{ $plantel_nombre }}</h3>
     </div>
 
    <!-- Contenido que se mostrará en todas las páginas -->
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No Expediente</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Documentos cargados</th>
                <!-- Añade más columnas si es necesario -->
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos_nuevos_plantel as $alumno)
                <tr>
                    <td>{{ $alumno->noexpediente }}</td>
                    <td>{{ $alumno->nombre }}</td>
                    <td>{{ $alumno->apellidos }}</td>
                    <td>
                        @if ($alumno->tipos_unicos >= 4)
                            Si
                        @endif
                    </td>
                    <!-- Añade más columnas si es necesario -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

