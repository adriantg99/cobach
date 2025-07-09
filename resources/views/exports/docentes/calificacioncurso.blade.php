<table>
    <thead>
        <tr>
            <td>ASIGNATURA</td>
            <td>GRUPO</td>
            <td>EXPEDIENTE</td>
            <td>NOMBRE</td>
            <td>APELLIDOS</td>
            @if ($calificaciones[0]->calif == null)
                <td>FALTAS PARCIAL I</td>
                <td>CALIFICACION PARCIAL I</td>
                <td>FALTAS PARCIAL II</td>
                <td>CALIFICACION PARCIAL II</td>
                <td>FALTAS PARCIAL III</td>
                <td>CALIFICACION PARCIAL III</td>
                <td>PROMEDIO DE LOS PARCIALES CALIFICADOS</td>
            @else
                <td>CALIFICACIÓN FINAL</td>
            @endif

        </tr>
    </thead>

    @if (!empty($calificaciones))
        <tbody>
            @foreach ($calificaciones as $mat)
                <tr>
                    <td>{{ $mat->curso_nombre }} </td>
                    <td>{{ $curso->grupo->descripcion }}</td>
                    <td>{{ $mat->noexpediente }} </td>
                    <td>{{ $mat->nombre }} </td>
                    <td>{{ $mat->apellidos }} </td>
                    @if ($calificaciones[0]->calif == null)
                        <td>{{ $mat->faltas_p1 }} </td>
                        <td>{{ $mat->parcial1 }}</td>
                        <td>{{ $mat->faltas_p2 }} </td>
                        <td>{{ $mat->parcial2 }}</td>
                        <td>{{ $mat->faltas_p3 }} </td>
                        <td>{{ $mat->parcial3 }}</td>
                        <td>{{ $mat->promedio_parciales }}</td>
                    @else
                        <td>{{ $mat->calif }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    @endif
</table>
