{{-- ANA MOLINA 2/09/2024 --}}
<table>
    <tr><td>Datos y calificaciones parciales</td></tr>
    <tr><td>Ciclo escolar</td><td>{{$ciclonom}}</td></tr>
    @if ($plantelnom!='')
        <tr><td>Plantel</td><td>{{$plantelnom}}</td></tr>
    @endif
    @if ($periodonom!='')
    <tr><td>Semestre</td>{{$periodonom}}<td></td></tr>
    @endif
    @if ($turnonom!='')
    <tr><td>Turno</td><td>{{$turnonom}}</td></tr>
    @endif
    @if ($gruponom!='')
    <tr><td>Grupo</td><td>{{$gruponom}}</td></tr>
    @endif
    @if ($cursonom!='')
    <tr><td>Curso</td><td>{{$cursonom}}</td></tr>
    @endif
    @if ($docentenom!='')
    <tr><td>Docente</td><td>{{$docentenom}}</td></tr>
    @endif
</table>
<table >
    <tr style="color:white;background-color:grey">
        <th width="80">Plantel</th>
        <th width="10">NoExpediente</th>
        <th width="80">Nombre</th>
        <th width="80">Apellidos</th>
        <th width="100">Curso</th>
        <th width="10">Grupo</th>
        <th width="40">Descripcion</th>
        <th width="10">Grado</th>
        <th width="10">Turno</th>
        <th width="15">P1</th>
        <th width="8">Faltas 1</th>
        <th width="15">P2</th>
        <th width="8">Faltas 2</th>
        <th width="15">P3</th>
        <th width="8">Faltas 3</th>
        <th width="15">Regularización</th>
        <th width="8">Calificacion tipo</th>
        <th width="15">Final</th>
        <th width="40">Docente</th>
        <th width="40">Apellido Paterno</th>
        <th width="40">Apellido Materno</th>
        <th width="15">Aprobado</th>
        <th width="15">Reprobado</th>

    </tr>

    @if (isset($dashb) )
    @foreach ($dashb as $da)
    <tr>
        <td >{{$da->plantel}} </td>
        <td >{{$da->noexpediente}} </td>
        <td >{{$da->nombre}} </td>
        <td >{{$da->apellidos}} </td>
        <td >{{$da->curso}} </td>
        <td >{{$da->grupo}} </td>
        <td >{{$da->descripcion}} </td>
        <td >{{$da->grado}} </td>
        <td >{{$da->turno}} </td>
        <td >{{$da->p1}} </td>
        <td >{{$da->faltas_1}} </td>
        <td >{{$da->p2}} </td>
        <td >{{$da->faltas_2}} </td>
        <td >{{$da->p3}} </td>
        <td >{{$da->faltas_3}} </td>
        <td >{{$da->r}} </td>
        <td >{{$da->calificacion_tipo}} </td>
        <td >{{$da->final}} </td>
        <td >{{$da->docente}} </td>
        <td >{{$da->apellidopat}} </td>
        <td >{{$da->apellidomat}} </td>
        <td >{{$da->aprobado}} </td>
        <td >{{$da->reprobado}} </td>
    </tr>
    @endforeach
    @endif
</table>
