<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos por Grupo</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Listado de Alumnos por Grupo Plantel: {{ $plantel->nombre }}</h1>
    <p>Fecha de generación: {{ $fecha_actual }}</p>
    <table>
        <thead>
            <tr>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Grupo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buscar_alumnos_grupo as $alumno)
                <tr>
                    <td>{{ $alumno->apellidos }}</td>
                    <td>{{ $alumno->nombre }}</td>
                    <td>{{ $alumno->grupo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
