<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alumnos</title>
    @php
    use App\Models\Adminalumnos\AlumnoModel;
    use App\Models\Grupos\GruposModel;
    
    // Inicializamos un array para contar los alumnos por grupo y turno
    $conteoPorGrupo = [];
    
    foreach ($alumnos_nuevos_plantel as $alumno) {
        $grupo_id = $alumno['grupo_id'];
        $grupo = GruposModel::find($grupo_id);
    
        if (!isset($conteoPorGrupo[$grupo->nombre])) {
            $conteoPorGrupo[$grupo->nombre] = [
                'matutino' => 0,
                'vespertino' => 0,
            ];
        }
    
        if ($grupo->turno_id == 1) {
            $conteoPorGrupo[$grupo->nombre]['matutino']++;
        } else {
            $conteoPorGrupo[$grupo->nombre]['vespertino']++;
        }
    }
    
    @endphp
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Grupo</th>
                <th>Matutino</th>
                <th>Vespertino</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($conteoPorGrupo as $grupo_nombre => $conteo)
                <tr>
                    <td>{{ $grupo_nombre }}</td>
                    <td>{{ $conteo['matutino'] }}</td>
                    <td>{{ $conteo['vespertino'] }}</td>
                </tr>
            @endforeach
            <tr>
                
            </tr>
        </tbody>
    </table>
    <table>
        <tr>
           
            <td>
                <table>
                    <thead>
                        <tr>
                            <th>No Expediente</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Grupo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alumnos_nuevos_plantel as $alumnos_grupo)
                            @php
                                $alumno = AlumnoModel::find($alumnos_grupo['alumno_id']);
                                $grupo = GruposModel::find($alumnos_grupo['grupo_id']);
                            @endphp
                            <tr>
                                <td>{{ $alumno->noexpediente }}</td>
                                <td>{{ $alumno->nombre }}</td>
                                <td>{{ $alumno->apellidos }}</td>
                                <td>{{ $grupo->nombre }}
                                    @if ($grupo->turno_id == 1)
                                        Mat
                                    @else
                                        Ves
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
