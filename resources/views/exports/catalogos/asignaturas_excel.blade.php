<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Asignatura</th>
            <th>Núcleo</th>
            <th>Area de Formación</th>
            <th>Periodo</th>
            <th>Clave</th>
            <th>Activa</th>
            <th>Fecha Hr</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($asignaturas as $asignatura)
            <tr>
                <td>{{$asignatura->id}}</td>
                <td>{{$asignatura->nombre}}</td>
                <td>{{$asignatura->nucleo}}</td>
                <td>{{$asignatura->area_formacion}}</td>
                <td>{{$asignatura->periodo}}</td>
                <td>{{$asignatura->clave}}</td>
                <td>{{$asignatura->activa}}</td>
                <td>{{$asignatura->fecha}}</td>
            </tr>
        @endforeach
    </tbody>
</table>